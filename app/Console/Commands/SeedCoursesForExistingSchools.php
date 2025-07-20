<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Services\CourseSeederService;
use Illuminate\Console\Command;

class SeedCoursesForExistingSchools extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-courses-for-existing-schools';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed standard courses for existing schools that don\'t have auto-seeded courses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding courses for existing schools...');
        
        $schools = School::all();
        $courseSeeder = new CourseSeederService();
        $totalSeeded = 0;
        
        foreach ($schools as $school) {
            $this->info("Processing school: {$school->name} (ID: {$school->id})");
            
            // Check if school already has auto-seeded courses
            $existingAutoSeededCourses = $school->courses()->where('is_auto_seeded', true)->count();
            
            if ($existingAutoSeededCourses > 0) {
                $this->warn("School {$school->name} already has {$existingAutoSeededCourses} auto-seeded courses. Skipping.");
                continue;
            }
            
            try {
                $seededCourses = $courseSeeder->seedCoursesForSchool($school);
                $courseCount = count($seededCourses);
                $totalSeeded += $courseCount;
                
                $this->info("✅ Seeded {$courseCount} courses for {$school->name}");
                
            } catch (\Exception $e) {
                $this->error("❌ Failed to seed courses for {$school->name}: " . $e->getMessage());
            }
        }
        
        $this->info("Completed! Total courses seeded: {$totalSeeded}");
        
        return 0;
    }
}
