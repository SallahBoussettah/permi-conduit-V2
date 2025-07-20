<?php

namespace App\Services;

use App\Models\Course;
use App\Models\PermitCategory;
use App\Models\School;
use App\Models\StandardCourseTemplate;
use Illuminate\Support\Facades\Log;

class CourseSeederService
{
    /**
     * Seed standard courses for a newly created school.
     *
     * @param School $school
     * @return array Array of created courses
     */
    public function seedCoursesForSchool(School $school): array
    {
        $createdCourses = [];
        
        try {
            // Get all standard course templates
            $templates = StandardCourseTemplate::all();
            
            // Group templates by permit category
            $templatesByCategory = $templates->groupBy('permit_category_id');
            
            // For each permit category, create the standard courses
            foreach ($templatesByCategory as $permitCategoryId => $categoryTemplates) {
                $permitCategory = PermitCategory::find($permitCategoryId);
                
                if (!$permitCategory) {
                    Log::warning("Permit category ID {$permitCategoryId} not found when seeding courses for school ID {$school->id}");
                    continue;
                }
                
                // Create courses for this permit category
                foreach ($categoryTemplates as $template) {
                    $course = Course::create([
                        'title' => $template->title,
                        'description' => $template->description,
                        'permit_category_id' => $permitCategory->id,
                        'school_id' => $school->id,
                        'is_auto_seeded' => true, // Mark as auto-seeded
                        'exam_section' => $template->exam_section,
                        'sequence_order' => $template->sequence_order,
                    ]);
                    
                    $createdCourses[] = $course;
                    
                    Log::info("Created auto-seeded course '{$course->title}' (ID: {$course->id}) for school ID {$school->id}, permit category '{$permitCategory->name}'");
                }
            }
            
            Log::info("Successfully seeded " . count($createdCourses) . " courses for school ID {$school->id}");
            
        } catch (\Exception $e) {
            Log::error("Error seeding courses for school ID {$school->id}: " . $e->getMessage());
            throw $e;
        }
        
        return $createdCourses;
    }
}