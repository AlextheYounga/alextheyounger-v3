<?php

namespace App\Filament\Resources\CoverLetterResource\Pages;

use App\Filament\Resources\Concerns\HasSaveHeaderAction;
use App\Filament\Resources\CoverLetterResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCoverLetter extends CreateRecord
{
    use HasSaveHeaderAction;

    protected static string $resource = CoverLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->saveHeaderAction('create')];
    }

    protected function getCreateFormAction(): Action
    {
        return $this->createFormSaveAction();
    }

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
