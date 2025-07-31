<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QcmPaper;
use App\Models\PermitCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QcmPaperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Modified query to show all papers created by the user or in their school
        // Removed the permit category filter to show all papers
        $papers = QcmPaper::where(function($query) use ($user) {
                $query->where('created_by', $user->id)
                    ->orWhere('school_id', $user->school_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        \Log::info('Retrieved papers count: ' . $papers->count());
        
        return view('inspector.qcm-papers.index', compact('papers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get all active permit categories
        $permitCategories = PermitCategory::where('status', true)
            ->orderBy('name')
            ->get();
        
        return view('inspector.qcm-papers.create', compact('permitCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the request data for debugging
        \Log::info('QCM Paper Store Request Data:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permit_category_id' => 'required|exists:permit_categories,id',
        ]);

        if ($validator->fails()) {
            \Log::error('QCM Paper Validation Errors:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Create the paper directly without try-catch
        $paper = new QcmPaper();
        $paper->title = $request->title;
        $paper->description = $request->description;
        $paper->permit_category_id = $request->permit_category_id;
        $paper->created_by = $user->id;
        $paper->status = $request->has('status') && $request->status == '1';
        $paper->school_id = $user->school_id;
        
        // Log before saving
        \Log::info('About to save QCM Paper:', [
            'title' => $paper->title,
            'permit_category_id' => $paper->permit_category_id,
            'created_by' => $paper->created_by,
            'school_id' => $paper->school_id,
            'status' => $paper->status
        ]);
        
        $paper->save();
        
        \Log::info('QCM Paper Created Successfully:', ['paper_id' => $paper->id]);
        
        // Use the correct route parameter name
        return redirect()->route('inspector.qcm-papers.show', $paper)
            ->with('success', 'QCM paper created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(QcmPaper $qcmPaper)
    {
        $this->authorize('view', $qcmPaper);
        
        $questionsCount = $qcmPaper->questions()->count();
        $examsCount = $qcmPaper->exams()->count();
        
        return view('inspector.qcm-papers.show', compact('qcmPaper', 'questionsCount', 'examsCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QcmPaper $qcmPaper)
    {
        $this->authorize('update', $qcmPaper);
        
        $user = Auth::user();
        
        // Get all active permit categories
        $permitCategories = PermitCategory::where('status', true)
            ->orderBy('name')
            ->get();
        
        return view('inspector.qcm-papers.edit', compact('qcmPaper', 'permitCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QcmPaper $qcmPaper)
    {
        $this->authorize('update', $qcmPaper);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permit_category_id' => 'required|exists:permit_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Update the paper
        $qcmPaper->title = $request->title;
        $qcmPaper->description = $request->description;
        $qcmPaper->permit_category_id = $request->permit_category_id;
        $qcmPaper->status = $request->has('status') && $request->status == '1';
        $qcmPaper->save();

        // Use the correct route parameter name
        return redirect()->route('inspector.qcm-papers.show', $qcmPaper)
            ->with('success', 'QCM paper updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QcmPaper $qcmPaper)
    {
        $this->authorize('delete', $qcmPaper);
        
        try {
            // Start a database transaction
            DB::beginTransaction();
            
            // Get counts for success message
            $examsCount = $qcmPaper->exams()->count();
            $questionsCount = $qcmPaper->questions()->count();
            
            \Log::info('Deleting QCM Paper ID: ' . $qcmPaper->id . ' with ' . $examsCount . ' exams and ' . $questionsCount . ' questions');
            
            // 1. Delete all exam answers for exams related to this paper
            foreach ($qcmPaper->exams as $exam) {
                $exam->examAnswers()->delete();
                \Log::info('Deleted exam answers for exam ID: ' . $exam->id);
            }
            
            // 2. Delete all exams associated with this paper
            $qcmPaper->exams()->delete();
            \Log::info('Deleted ' . $examsCount . ' exams');
            
            // 3. Delete all question images and answers
            foreach ($qcmPaper->questions as $question) {
                // Delete question image if it exists
                if ($question->image_path && \Storage::disk('public')->exists($question->image_path)) {
                    \Storage::disk('public')->delete($question->image_path);
                    \Log::info('Deleted image: ' . $question->image_path);
                }
                
                // Delete all answers for this question
                $question->answers()->delete();
                \Log::info('Deleted answers for question ID: ' . $question->id);
            }
            
            // 4. Delete all questions associated with this paper
            $qcmPaper->questions()->delete();
            \Log::info('Deleted ' . $questionsCount . ' questions');
            
            // 5. Delete all sections associated with this paper (if sections exist)
            if (Schema::hasColumn('qcm_sections', 'qcm_paper_id')) {
                $sectionsCount = $qcmPaper->sections()->count();
                $qcmPaper->sections()->delete();
                \Log::info('Deleted ' . $sectionsCount . ' sections');
            }
            
            // 6. Delete the paper itself
            $qcmPaper->delete();
            \Log::info('Deleted QCM Paper ID: ' . $qcmPaper->id);
            
            // Commit the transaction
            DB::commit();
            
            $successMessage = 'QCM paper deleted successfully.';
            if ($examsCount > 0) {
                $successMessage .= ' ' . $examsCount . ' related exam(s) were also deleted.';
            }
            if ($questionsCount > 0) {
                $successMessage .= ' ' . $questionsCount . ' question(s) and their answers were also deleted.';
            }
            
            return redirect()->route('inspector.qcm-papers.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            // If something goes wrong, rollback the transaction
            DB::rollBack();
            
            \Log::error('Failed to delete QCM Paper ID: ' . $qcmPaper->id . ' - Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete QCM paper: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of a QCM paper.
     */
    public function updateStatus(Request $request, QcmPaper $qcmPaper)
    {
        // Check if the paper belongs to the user's school
        if (!$this->checkSchoolAccess($qcmPaper)) {
            abort(403, 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // If trying to activate, ensure there are exactly 10 questions
        if ($request->status) {
            $questionCount = $qcmPaper->questions()->count();
            
            if ($questionCount !== 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'A QCM paper must have exactly 10 questions to be activated. This paper has ' . $questionCount . ' questions.'
                ], 422);
            }

            // Check if the section_id column exists before making the query
            if (Schema::hasColumn('qcm_questions', 'section_id')) {
                // Make sure each question has at least one correct answer
                $questionsWithNoCorrectAnswer = $qcmPaper->questions()
                    ->whereDoesntHave('answers', function($query) {
                        $query->where('is_correct', true);
                    })
                    ->count();
                    
                if ($questionsWithNoCorrectAnswer > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'All questions must have at least one correct answer. Please check your questions and try again.'
                    ], 422);
                }
            } else {
                // Alternative check without using section_id
                foreach ($qcmPaper->questions as $question) {
                    $hasCorrectAnswer = $question->answers()->where('is_correct', true)->exists();
                    if (!$hasCorrectAnswer) {
                        return response()->json([
                            'success' => false,
                            'message' => 'All questions must have at least one correct answer. Please check your questions and try again.'
                        ], 422);
                    }
                }
            }
        }
        
        $qcmPaper->status = $request->status;
        $qcmPaper->save();
        
        return response()->json([
            'success' => true,
            'message' => $request->status 
                ? 'QCM paper activated successfully.' 
                : 'QCM paper deactivated successfully.'
        ]);
    }
}
