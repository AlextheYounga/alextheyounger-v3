<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\CodingLanguage;
use App\Models\Project;
use App\Models\Book;
use App\Models\Category;
use App\Models\PageContent;

class PagesController extends Controller
{
    public function home()
    {
        $pageContent = PageContent::where('view', '=', 'Home')->get()->keyBy('key');

        return Inertia::render('Home', [
            'content' => $pageContent,
        ]);
    }

    public function readingList()
    {
        $books = Book::with('categories')->active()->orderBy('position', 'asc')->get();

        $categories = Category::active()
            ->where('type', '=', 'Book::class')
            ->orderBy('position', 'asc')
            ->get();

        return Inertia::render('ReadingList', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }

    public function projects()
    {
        $projects = Project::active()->orderBy('position', 'asc')->get()->map(function (Project $project): Project {
            $project->setAttribute('content', $this->applyTechnologyColors($project->content ?? []));

            return $project;
        });

        return Inertia::render('Projects', [
            'projects' => $projects,
        ]);
    }

    public function starfield()
    {
        return Inertia::render('StarField');
    }

    public function setupFooter()
    {
        $pageContent = PageContent::where('view', '=', 'Footer')->get()->keyBy('key');

        return response()->json($pageContent);
    }

    private function applyTechnologyColors(array $content): array
    {
        $content['technology'] = collect($content['technology'] ?? [])
            ->map(function ($technology): ?array {
                $technologyName = is_array($technology) ? ($technology['name'] ?? null) : $technology;

                if (! is_string($technologyName) || $technologyName === '') {
                    return null;
                }

                return [
                    'name' => $technologyName,
                    'color' => CodingLanguage::colorFor($technologyName) ?? '#64748b',
                ];
            })
            ->filter()
            ->values()
            ->all();

        return $content;
    }
}
