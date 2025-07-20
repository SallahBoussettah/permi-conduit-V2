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
                $coursesBySection = [];
                
                foreach ($categoryTemplates as $template) {
                    $course = Course::create([
                        'title' => $template->title,
                        'description' => $template->description,
                        'permit_category_id' => $permitCategory->id,
                        'school_id' => $school->id,
                        'is_auto_seeded' => true, // Mark as auto-seeded
                        'exam_section' => $template->exam_section,
                        'sequence_order' => $template->sequence_order,
                        'requires_sequential_completion' => true, // Enable prerequisites
                    ]);
                    
                    $createdCourses[] = $course;
                    $coursesBySection[$template->exam_section] = $course;
                    
                    Log::info("Created auto-seeded course '{$course->title}' (ID: {$course->id}) for school ID {$school->id}, permit category '{$permitCategory->name}'");
                }
                
                // Set up prerequisites for sequential learning (IE → Socle1 → Theme → IO → Socle2 → Manoeuvres)
                $this->setupPrerequisites($coursesBySection);
            }
            
            Log::info("Successfully seeded " . count($createdCourses) . " courses for school ID {$school->id}");
            
        } catch (\Exception $e) {
            Log::error("Error seeding courses for school ID {$school->id}: " . $e->getMessage());
            throw $e;
        }
        
        return $createdCourses;
    }

    /**
     * Set up prerequisites for the standard courses to enforce sequential learning.
     *
     * @param array $coursesBySection
     * @return void
     */
    private function setupPrerequisites(array $coursesBySection): void
    {
        // Define the learning sequence: IE → Socle1 → Theme → IO → Socle2 → Manoeuvres
        $sequence = [
            'IE' => [], // No prerequisites for the first course
            'Socle1' => ['IE'], // Requires IE completion
            'Theme' => ['IE', 'Socle1'], // Requires IE and Socle1 completion
            'IO' => ['IE', 'Socle1', 'Theme'], // Requires IE, Socle1, and Theme completion
            'Socle2' => ['IE', 'Socle1', 'Theme', 'IO'], // Requires all previous courses
            'Manoeuvres' => ['IE', 'Socle1', 'Theme', 'IO', 'Socle2'], // Requires all previous courses
        ];

        foreach ($sequence as $examSection => $prerequisites) {
            if (isset($coursesBySection[$examSection])) {
                $course = $coursesBySection[$examSection];
                $prerequisiteCourseIds = [];

                // Get the IDs of prerequisite courses
                foreach ($prerequisites as $prerequisiteSection) {
                    if (isset($coursesBySection[$prerequisiteSection])) {
                        $prerequisiteCourseIds[] = $coursesBySection[$prerequisiteSection]->id;
                    }
                }

                // Update the course with prerequisites
                $course->update([
                    'prerequisite_courses' => $prerequisiteCourseIds,
                    'requires_sequential_completion' => true,
                ]);

                Log::info("Set prerequisites for course '{$course->title}' (ID: {$course->id}): " . implode(', ', $prerequisites));
            }
        }
    }
}