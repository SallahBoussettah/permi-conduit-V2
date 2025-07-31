<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use App\Models\PermitCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:inspector']);
    }

    /**
     * Display a listing of the permit categories.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all permit categories with school-specific course counts
        $permitCategories = PermitCategory::orderBy('name')
            ->withCount(['courses' => function ($query) use ($user) {
                $query->where('school_id', $user->school_id);
            }])
            ->paginate(10);
            
        return view('inspector.permit-categories.index', compact('permitCategories'));
    }

    /**
     * Display the specified permit category.
     */
    public function show(PermitCategory $permitCategory)
    {
        return view('inspector.permit-categories.show', compact('permitCategory'));
    }
} 