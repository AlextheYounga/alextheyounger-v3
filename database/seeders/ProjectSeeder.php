<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::truncate();

        $projectsJson = file_get_contents('storage/app/data/projects.json');
        $projects  = json_decode($projectsJson, true);

        foreach($projects as $project) {
            Project::create([
                'title' => $project['title'],
                'description' => $project['description'],
                'image_name' => $project['image_name'],
                'external_link' => $project['external_link'],
                'external_image_link' => $project['external_image_link'] ?? null,
                'techstack' => json_decode($project['techstack'], true),
                'excerpt' => $project['excerpt'],
                'position' => $project['position'],
                'scope' => $project['scope'],
                'active' => $project['active'] ?? true,
            ]);
        }
    }
}
