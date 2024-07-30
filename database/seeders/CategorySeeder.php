<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();
        
        $categoriesJson = file_get_contents('storage/app/data/categories.json');
        $categories = json_decode($categoriesJson, true);

        foreach($categories as $category) {
            $properties = json_decode($category['properties'], true);

            Category::create([
                'name' => $category['name'],
                'type' => $category['type'] ?? 'Book::class',
                'position' => $category['position'],
                'properties' => [
                    'html_selector' => $properties['html_selector'],
                ],
                'active' => $category['active'] ?? true,
            ]);
        }
    }
}
