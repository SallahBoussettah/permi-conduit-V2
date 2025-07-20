<?php

namespace Database\Seeders;

use App\Models\PermitCategory;
use App\Models\StandardCourseTemplate;
use Illuminate\Database\Seeder;

class StandardCourseTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all permit categories
        $permitCategories = PermitCategory::all();

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

        // Create standard course templates for each permit category
        foreach ($permitCategories as $permitCategory) {
            foreach ($standardCourses as $course) {
                StandardCourseTemplate::create([
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'permit_category_id' => $permitCategory->id,
                    'exam_section' => $course['exam_section'],
                    'sequence_order' => $course['sequence_order'],
                    'default_materials' => null, // No default materials, will be added by inspectors
                ]);
            }
        }
    }
}
