<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\PermitCategory;
use App\Models\School;
use App\Models\StandardCourseTemplate;
use App\Models\User;
use App\Services\CourseSeederService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoCourseSeederTest extends TestCase
{
    // We're not using RefreshDatabase trait to avoid SQLite issues
// use RefreshDatabase;

    public function test_courses_are_auto_seeded_for_new_school()
    {
        // Create permit categories
        $permitCategory = PermitCategory::create([
            'name' => 'Test Permit C',
            'code' => 'TC',
            'description' => 'Test permit category',
        ]);

        // Create standard course templates
        $templates = [
            [
                'title' => 'Interrogation Écrite (QCM)',
                'description' => 'Test description',
                'exam_section' => 'IE',
                'sequence_order' => 1,
            ],
            [
                'title' => 'Socle Minimum 1',
                'description' => 'Test description',
                'exam_section' => 'Socle1',
                'sequence_order' => 2,
            ],
        ];

        foreach ($templates as $template) {
            StandardCourseTemplate::create([
                'title' => $template['title'],
                'description' => $template['description'],
                'permit_category_id' => $permitCategory->id,
                'exam_section' => $template['exam_section'],
                'sequence_order' => $template['sequence_order'],
            ]);
        }

        // Create a new school
        $school = School::create([
            'name' => 'Test School',
            'slug' => 'test-school',
            'candidate_limit' => 100,
        ]);

        // Assert that the school has no courses initially
        $this->assertEquals(0, $school->courses()->count());

        // Use the CourseSeederService to seed courses
        $courseSeeder = new CourseSeederService();
        $seededCourses = $courseSeeder->seedCoursesForSchool($school);

        // Assert that courses were created
        $this->assertEquals(2, count($seededCourses));
        $this->assertEquals(2, $school->courses()->count());

        // Assert that the courses have the correct properties
        $courses = $school->courses()->get();
        
        $this->assertEquals('Interrogation Écrite (QCM)', $courses[0]->title);
        $this->assertEquals('IE', $courses[0]->exam_section);
        $this->assertEquals(1, $courses[0]->sequence_order);
        $this->assertTrue($courses[0]->is_auto_seeded);
        
        $this->assertEquals('Socle Minimum 1', $courses[1]->title);
        $this->assertEquals('Socle1', $courses[1]->exam_section);
        $this->assertEquals(2, $courses[1]->sequence_order);
        $this->assertTrue($courses[1]->is_auto_seeded);
    }
}