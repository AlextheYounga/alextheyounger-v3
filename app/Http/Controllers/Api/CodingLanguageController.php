<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CodingLanguage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CodingLanguageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'languages' => ['required', 'array'],
            'languages.*.language' => ['required', 'string'],
            'languages.*.value' => ['required', 'numeric'],
            'languages.*.display_value' => ['nullable', 'numeric'],
            'languages.*.color' => ['nullable', 'string'],
            'languages.*.active' => ['nullable', 'boolean'],
            'languages.*.project_count' => ['nullable', 'integer'],
            'languages.*.properties' => ['nullable', 'array'],
        ]);

        foreach ($validated['languages'] as $languageData) {
            CodingLanguage::updateOrCreate(
                [
                    'language' => $languageData['language'],
                ],
                [
                    'value' => $languageData['value'],
                    'display_value' => $languageData['display_value'] ?? $languageData['value'],
                    'color' => $languageData['color'] ?? '#000000',
                    'active' => $languageData['active'] ?? true,
                    'project_count' => $languageData['project_count'] ?? null,
                    'properties' => $languageData['properties'] ?? [],
                ],
            );
        }

        return response()->json(['success' => true], 201);
    }

    public function stats(): JsonResponse
    {
        $languages = CodingLanguage::active()->orderByDesc('display_value')->get();
        $this->applyComputedWidths($languages);
        $bytes = (float) CodingLanguage::sum('value');

        [$displaySize, $scale] = $this->formatSize($bytes);

        return response()->json([
            'languages' => $languages,
            'languageStats' => [
                'count' => CodingLanguage::count(),
                'size' => $displaySize,
                'scale' => $scale,
            ],
        ]);
    }

    private function applyComputedWidths(Collection $languages): void
    {
        $total = (float) $languages->sum('display_value');

        foreach ($languages as $language) {
            $width = $total > 0 ? round(($language->display_value / $total) * 100, 2) : 0.0;
            $language->setAttribute('width', $width);
        }
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
