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
        
        $categoriesJson = file_get_contents('storage/app/data/book_categories.json');
        $categories = json_decode($categoriesJson, true);

        foreach($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'properties' => [
                    'html_selector' => $category['html_selector'],
                ],
            ]);
        }
    }
}