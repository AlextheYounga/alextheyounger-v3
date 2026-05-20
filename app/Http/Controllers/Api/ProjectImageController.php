<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ProjectImageController extends Controller
{
    private const CACHE_DIRECTORY = 'project-images';

    public function show(Project $project): BinaryFileResponse
    {
        $cachedResponse = $this->cachedResponse($project);

        if ($cachedResponse !== null) {
            return $cachedResponse;
        }

        $imageUrl = $project->external_image_link;

        if (! is_string($imageUrl) || $imageUrl === '') {
            abort(404);
        }

        try {
            $response = Http::timeout(15)
                ->retry(2, 250)
                ->get($imageUrl);
        } catch (ConnectionException) {
            abort(502, 'Unable to fetch the project image.');
        }

        if (! $response->successful()) {
            abort(502, 'Unable to fetch the project image.');
        }

        $contentType = $this->contentType($response->header('Content-Type'));

        if (! str_starts_with($contentType, 'image/')) {
            abort(502, 'The project image source did not return an image.');
        }

        $extension = $this->extensionForContentType($contentType);
        $imagePath = $this->imagePath($project->id, $extension);

        Storage::disk('public')->put($imagePath, $response->body());
        Storage::disk('public')->put($this->metadataPath($project->id), json_encode([
            'source_url' => $imageUrl,
            'content_type' => $contentType,
            'image_path' => $imagePath,
        ], JSON_THROW_ON_ERROR));

        return $this->serveCachedImage($imagePath, $contentType);
    }

    private function cachedResponse(Project $project): ?BinaryFileResponse
    {
        $metadataPath = $this->metadataPath($project->id);

        if (! Storage::disk('public')->exists($metadataPath)) {
            return null;
        }

        $metadata = json_decode(Storage::disk('public')->get($metadataPath), true);

        if (! is_array($metadata)) {
            return null;
        }

        $sourceUrl = $metadata['source_url'] ?? null;
        $imagePath = $metadata['image_path'] ?? null;
        $contentType = $metadata['content_type'] ?? null;

        if (! is_string($sourceUrl) || $sourceUrl === '' || $sourceUrl !== $project->external_image_link) {
            return null;
        }

        if (! is_string($imagePath) || $imagePath === '' || ! Storage::disk('public')->exists($imagePath)) {
            return null;
        }

        return $this->serveCachedImage($imagePath, $this->contentType(is_string($contentType) ? $contentType : null));
    }

    private function serveCachedImage(string $imagePath, string $contentType): BinaryFileResponse
    {
        return response()->file(
            Storage::disk('public')->path($imagePath),
            [
                'Content-Type' => $contentType,
                'Cache-Control' => 'public, max-age=86400',
            ],
        );
    }

    private function imagePath(int $projectId, string $extension): string
    {
        return sprintf('%s/%d.%s', self::CACHE_DIRECTORY, $projectId, $extension);
    }

    private function metadataPath(int $projectId): string
    {
        return sprintf('%s/%d.json', self::CACHE_DIRECTORY, $projectId);
    }

    private function contentType(?string $contentType): string
    {
        if (! is_string($contentType) || $contentType === '') {
            return 'image/jpeg';
        }

        return strtolower(trim(explode(';', $contentType, 2)[0]));
    }

    private function extensionForContentType(string $contentType): string
    {
        return match ($contentType) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/avif' => 'avif',
            'image/svg+xml' => 'svg',
            'image/bmp' => 'bmp',
            'image/x-icon', 'image/vnd.microsoft.icon' => 'ico',
            default => 'jpg',
        };
    }
}
