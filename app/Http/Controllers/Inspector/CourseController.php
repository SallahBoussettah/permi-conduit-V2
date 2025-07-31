<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamSection;
use App\Models\CourseCategory;
use App\Models\PermitCategory;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * The notification service instance.
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\NotificationService  $notificationService
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Check if the user can access the given course based on school membership.
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\User|null  $user
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function authorizeSchoolAccess(Course $course, $user = null)
    {
        $user = $user ?: Auth::user();
        
        if ($user->school_id) {
            // User has a school, course must belong to the same school
            if ($course->school_id !== $user->school_id) {
                abort(403, 'You do not have permission to access this course.');
            }
        } else {
            // User has no school, can only access courses with no school (legacy courses)
            if ($course->school_id !== null) {
                abort(403, 'You do not have permission to access this course.');
            }
        }
    }

    /**
     * Display a listing of the courses.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Course::with(['permitCategory', 'materials']);
        
        // CRITICAL: Filter by school - only show courses from the inspector's school
        if ($user->school_id) {
            $query->where('school_id', $user->school_id);
        } else {
            // If inspector has no school_id, only show courses with no school_id (legacy courses)
            $query->whereNull('school_id');
        }
        
        // Filter by permit category if specified
        $selectedPermitCategory = $request->input('permit_category');
        if ($selectedPermitCategory === 'null') {
            // Show courses with no permit category
            $query->whereNull('permit_category_id');
        } elseif (!empty($selectedPermitCategory)) {
            // Show courses for specific permit category
            $query->where('permit_category_id', $selectedPermitCategory);
        }
        
        // Filter by course type (auto-seeded vs custom)
        $courseType = $request->input('course_type');
        if ($courseType === 'auto_seeded') {
            $query->where('is_auto_seeded', true);
        } elseif ($courseType === 'custom') {
            $query->where('is_auto_seeded', false);
        }
        
        // Search functionality
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('exam_section', 'like', "%{$search}%");
            });
        }
        
        // Order by sequence for auto-seeded courses, then by title
        $courses = $query->orderBy('is_auto_seeded', 'desc')
                        ->orderBy('sequence_order')
                        ->orderBy('title')
                        ->paginate(12);
        
        // Get permit categories for filter dropdown
        $permitCategories = PermitCategory::where('status', true)->orderBy('name')->get();
        
        return view('inspector.courses.index', compact('courses', 'permitCategories', 'selectedPermitCategory', 'courseType', 'search'));
    }

    /**
     * Show the form for creating a new course.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $examSections = ExamSection::orderBy('name')->pluck('name', 'id');
        $categories = CourseCategory::where('status', true)->orderBy('name')->pluck('name', 'id');
        $permitCategories = PermitCategory::where('status', true)->orderBy('name')->pluck('name', 'id');
        return view('inspector.courses.create', compact('examSections', 'categories', 'permitCategories'));
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_section_id' => 'nullable|exists:exam_sections,id',
            'category_id' => 'nullable|exists:course_categories,id',
            'permit_category_id' => 'nullable|exists:permit_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');
            $validated['thumbnail'] = 'thumbnails/' . $thumbnailName;
        }

        // Add the authenticated inspector as the course creator
        $validated['inspector_id'] = Auth::id();
        $validated['created_by'] = Auth::id();
        $validated['status'] = true;
        
        // CRITICAL: Assign the course to the inspector's school
        $validated['school_id'] = Auth::user()->school_id;

        $course = Course::create($validated);

        // Always send notifications for new courses, even if they don't have a permit category
        // The NotificationService will handle the logic for determining who gets notified
        $this->notificationService->notifyNewCourse($course);

        return redirect()->route('inspector.courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function show(Course $course)
    {
        // CRITICAL: Ensure the course belongs to the inspector's school
        $this->authorizeSchoolAccess($course);
        
        $materials = $course->materials()->orderBy('sequence_order')->get();
        return view('inspector.courses.show', compact('course', 'materials'));
    }

    /**
     * Show the form for editing the specified course.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\View\View
     */
    public function edit(Course $course)
    {
        // CRITICAL: Ensure the course belongs to the inspector's school
        $this->authorizeSchoolAccess($course);
        
        $examSections = ExamSection::orderBy('name')->pluck('name', 'id');
        $categories = CourseCategory::where('status', true)->orderBy('name')->pluck('name', 'id');
        $permitCategories = PermitCategory::where('status', true)->orderBy('name')->pluck('name', 'id');
        return view('inspector.courses.edit', compact('course', 'examSections', 'categories', 'permitCategories'));
    }

    /**
     * Update the specified course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Course $course)
    {
        // CRITICAL: Ensure the course belongs to the inspector's school
        $this->authorizeSchoolAccess($course);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_section_id' => 'nullable|exists:exam_sections,id',
            'category_id' => 'nullable|exists:course_categories,id',
            'permit_category_id' => 'nullable|exists:permit_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->storeAs('thumbnails', $thumbnailName, 'public');
            $validated['thumbnail'] = 'thumbnails/' . $thumbnailName;
        }

        // Add the authenticated inspector as the course updater
        $validated['updated_by'] = Auth::id();

        $course->update($validated);

        return redirect()->route('inspector.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course)
    {
        // CRITICAL: Ensure the course belongs to the inspector's school
        $this->authorizeSchoolAccess($course);
        
        // Check if the course has materials
        if ($course->materials()->count() > 0) {
            return redirect()->route('inspector.courses.show', $course)
                ->with('error', 'Cannot delete course with materials. Please remove all materials first.');
        }

        // Delete thumbnail if exists
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        // Soft delete with deleted_by
        $course->update(['deleted_by' => Auth::id()]);
        $course->delete();

        return redirect()->route('inspector.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
