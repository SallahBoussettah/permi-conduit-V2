<?php

namespace Database\Seeders;

use App\Models\PermitCategory;
use Illuminate\Database\Seeder;

class PermitCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the standard permit categories
        $permitCategories = [
            [
                'name' => 'Permis C',
                'description' => 'Permis de conduire pour les véhicules de plus de 3,5 tonnes.',
                'code' => 'C',
            ],
            [
                'name' => 'Permis CE',
                'description' => 'Permis de conduire pour les véhicules de plus de 3,5 tonnes avec remorque.',
                'code' => 'CE',
            ],
            [
                'name' => 'Permis D',
                'description' => 'Permis de conduire pour les véhicules de transport en commun.',
                'code' => 'D',
            ],
        ];
        
        foreach ($permitCategories as $category) {
            // Check if the permit category already exists
            $existingCategory = PermitCategory::where('code', $category['code'])->first();
            
            if (!$existingCategory) {
                PermitCategory::create($category);
                $this->command->info("Created permit category: {$category['name']}");
            } else {
                $this->command->info("Permit category {$category['name']} already exists. Skipping.");
            }
        }
    }
}
