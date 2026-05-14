<?php

namespace App\Filament\Resources\CoverLetterResource\Pages;

use App\Filament\Resources\CoverLetterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoverLetter extends CreateRecord
{
    protected static string $resource = CoverLetterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['properties'] = $this->normalizeProperties($data['properties'] ?? []);

        return $data;
    }

    protected function normalizeProperties(mixed $properties): array
    {
        if (is_array($properties)) {
            return $properties;
        }

        if (is_string($properties)) {
            $decoded = json_decode($properties, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
