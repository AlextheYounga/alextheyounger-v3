<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'image' => $project['image_address'],
                'project_link' => $project['project_link'],
                'framework' => $project['framework'],
                'excerpt' => $project['excerpt'],
                'position' => $project['position'],
                'properties' => [
                    'image_alt' => $project['image_alt'],
                ],
            ]);
        }
    }
}
