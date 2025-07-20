<?php

namespace App\Console\Commands;

use App\Models\PermitCategory;
use App\Models\StandardCourseTemplate;
use Illuminate\Console\Command;

class SeedStandardCourseTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-standard-course-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the standard course templates for auto-seeding courses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding standard course templates...');
        
        // Check if permit categories exist
        $permitCategoriesCount = PermitCategory::count();
        if ($permitCategoriesCount === 0) {
            $this->error('No permit categories found. Please create permit categories first.');
            return 1;
        }
        
        $this->info("Found {$permitCategoriesCount} permit categories.");
        
        // Define the standard course templates
        $standardCourses = [
            [
                'title' => 'Interrogation Écrite (QCM)',
                'description' => 'Préparation à l\'examen QCM avec 10 questions à compléter en 6 minutes.',
                'exam_section' => 'IE',
                'sequence_order' => 1,
            ],
            [
                'title' => 'Socle Minimum 1',
                'description' => 'Préparation aux vérifications pré-conduite (immobilisation, feux, documents, cabine, etc.).',
                'exam_section' => 'Socle1',
                'sequence_order' => 2,
            ],
            [
                'title' => 'Thème',
                'description' => 'Préparation aux thèmes techniques oraux (documents de bord, triangle, extincteur, etc.).',
                'exam_section' => 'Theme',
                'sequence_order' => 3,
            ],
            [
                'title' => 'Interrogation Orale (I.O.)',
                'description' => 'Préparation aux questions de sécurité et de connaissances théoriques.',
                'exam_section' => 'IO',
                'sequence_order' => 4,
            ],
            [
                'title' => 'Socle Minimum 2',
                'description' => 'Préparation aux vérifications post-thème (systèmes de freinage, démarrage moteur, posture, etc.).',
                'exam_section' => 'Socle2',
                'sequence_order' => 5,
            ],
            [
                'title' => 'Manœuvres',
                'description' => 'Préparation aux manœuvres pratiques (marche arrière, demi-tour, stationnement, etc.).',
                'exam_section' => 'Manoeuvres',
                'sequence_order' => 6,
            ],
        ];
        
        // Get all permit categories
        $permitCategories = PermitCategory::all();
        $totalCreated = 0;
        
        // Create standard course templates for each permit category
        foreach ($permitCategories as $permitCategory) {
            $this->info("Creating templates for permit category: {$permitCategory->name}");
            
            foreach ($standardCourses as $course) {
                // Check if template already exists
                $existingTemplate = StandardCourseTemplate::where('permit_category_id', $permitCategory->id)
                    ->where('exam_section', $course['exam_section'])
                    ->first();
                
                if ($existingTemplate) {
                    $this->warn("Template for {$course['exam_section']} already exists for {$permitCategory->name}. Skipping.");
                    continue;
                }
                
                StandardCourseTemplate::create([
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'permit_category_id' => $permitCategory->id,
                    'exam_section' => $course['exam_section'],
                    'sequence_order' => $course['sequence_order'],
                    'default_materials' => null, // No default materials, will be added by inspectors
                ]);
                
                $totalCreated++;
                $this->info("Created template: {$course['title']} for {$permitCategory->name}");
            }
        }
        
        $this->info("Seeding completed. Created {$totalCreated} standard course templates.");
        
        return 0;
    }
}
