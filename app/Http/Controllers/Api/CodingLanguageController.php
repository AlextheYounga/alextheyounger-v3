<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CodingLanguage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CodingLanguageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'languages' => $this->getLanguages(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'languages' => ['required', 'array'],
            'languages.*.size' => ['required', 'numeric'],
            'languages.*.percentage' => ['required', 'numeric'],
        ]);

        foreach ($validated['languages'] as $name => $languageData) {
            CodingLanguage::updateOrCreate(
                [
                    'name' => $name,
                ],
                [
                    'value' => $languageData['size'],
                    'percentage' => $languageData['percentage'],
                    'active' => true,
                    'properties' => [
                        'slug' => Str::slug($name),
                    ],
                ],
            );
        }

        return response()->json(['success' => true], 201);
    }

    public function stats(): JsonResponse
    {
        $storedLanguages = CodingLanguage::query()->get();
        $languages = $this->getLanguages();
        $bytes = (float) ($storedLanguages->isNotEmpty() ? $storedLanguages->sum('value') : $languages->sum('value'));

        $languages = $this->applyLanguageMetadata($languages);

        [$displaySize, $scale] = $this->formatSize($bytes);

        return response()->json([
            'languages' => $languages,
            'languageStats' => [
                'count' => $storedLanguages->isNotEmpty() ? $storedLanguages->count() : $languages->count(),
                'size' => $displaySize,
                'scale' => $scale,
            ],
        ]);
    }

    private function getLanguages(): Collection
    {
        $storedLanguages = CodingLanguage::query()->get();

        if ($storedLanguages->isNotEmpty()) {
            return $this->applyLanguageMetadata(
                $storedLanguages
                    ->where('active', true)
                    ->sortByDesc('percentage')
                    ->values(),
            );
        }

        return $this->applyLanguageMetadata(CodingLanguage::defaultLanguages());
    }

    private function applyLanguageMetadata(Collection $languages): Collection
    {
        return $languages->map(function ($language): array {
            $name = data_get($language, 'name', data_get($language, 'language', ''));
            $percentage = (float) data_get($language, 'percentage', 0);
            $properties = (array) data_get($language, 'properties', []);

            $properties['slug'] = $properties['slug'] ?? Str::slug((string) $name);

            return [
                'name' => $name,
                'value' => (float) data_get($language, 'value', 0),
                'percentage' => $percentage,
                'color' => data_get($language, 'color') ?: CodingLanguage::colorFor((string) $name) ?: '#64748b',
                'active' => (bool) data_get($language, 'active', true),
                'properties' => $properties,
            ];
        });
    }

    private function formatSize(float $bytes): array
    {
        if ($bytes >= 1000000000) {
            return [round($bytes / 1000000000, 2), 'GB'];
        }

        if ($bytes >= 1000000) {
            return [round($bytes / 1000000, 2), 'MB'];
        }

        if ($bytes >= 1000) {
            return [round($bytes / 1000, 2), 'KB'];
        }

        return [round($bytes, 2), 'B'];
    }
}
