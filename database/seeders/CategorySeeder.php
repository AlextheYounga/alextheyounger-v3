<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        
        $categoriesJson = file_get_contents('storage/data/categories.json');
        $categories = json_decode($categoriesJson, true);

        foreach($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'type' => $category['type'] ?? 'Book::class',
                'position' => $category['position'],
                'properties' => [
                    'html_selector' => $category['html_selector'],
                ],
                'active' => $category['active'] ?? true,
            ]);
        }
    }
}
