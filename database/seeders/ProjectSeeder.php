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

        $projectsJson = json_decode(file_get_contents('storage/app/public/projects.json'), true);

        foreach ($projectsJson as $project) {
            $content = isset($project['content'])
                ? json_decode($project['content'], true)
                : [
                    'description' => $project['description'] ?? null,
                    'excerpt' => $project['excerpt'] ?? null,
                    'technology' => $this->getArrayField('technology', $project),
                    'bullets' => $this->getArrayField('bullets', $project),
                ];

            $record = [
                'title' => $project['title'],
                'external_link' => $project['external_link'],
                'external_image_link' => $project['external_image_link'] ?? null,
                'content' => $content,
                'properties' => [
                    'image_name' => $project['image_name'] ?? null,
                ],
                'position' => $project['position'],
                'scope' => $project['scope'],
                'active' => $project['active'] ?? true,
            ];

            Project::create($record);
        }
    }

    private function getArrayField($key, $project)
    {
        if (isset($project[$key])) {
            if (is_string($project[$key])) {
                return json_decode($project[$key], true);
            } else {
                return $project[$key];
            }
        } else {
            return [];
        }
    }
}
