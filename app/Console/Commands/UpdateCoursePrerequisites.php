<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\School;
use Illuminate\Console\Command;

class UpdateCoursePrerequisites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-course-prerequisites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing auto-seeded courses with prerequisites for sequential learning';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating course prerequisites for existing auto-seeded courses...');
        
        $schools = School::all();
        $totalUpdated = 0;
        
        foreach ($schools as $school) {
            $this->info("Processing school: {$school->name} (ID: {$school->id})");
            
            // Get auto-seeded courses grouped by permit category
            $coursesByPermitCategory = Course::where('school_id', $school->id)
                ->where('is_auto_seeded', true)
                ->whereNotNull('permit_category_id')
                ->get()
                ->groupBy('permit_category_id');
            
            foreach ($coursesByPermitCategory as $permitCategoryId => $courses) {
                $this->info("  Processing permit category ID: {$permitCategoryId}");
                
                // Group courses by exam section for this permit category
                $coursesBySection = $courses->keyBy('exam_section');
                
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
                        
                        $totalUpdated++;
                        $this->info("    ✅ Updated {$course->title} with " . count($prerequisiteCourseIds) . " prerequisites");
                    }
                }
            }
        }
        
        $this->info("Completed! Updated {$totalUpdated} courses with prerequisites.");
        
        return 0;
    }
}
