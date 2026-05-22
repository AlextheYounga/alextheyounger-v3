<?php

namespace App\Filament\Resources\CoverLetterResource\Pages;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\Concerns\HasSaveHeaderAction;
use App\Filament\Resources\CoverLetterResource;
use App\Models\CoverLetter;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCoverLetter extends EditRecord
{
    use GeneratesUniqueCopyName;
    use HasSaveHeaderAction;

    protected static string $resource = CoverLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->saveHeaderAction('save'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return $this->editFormSaveAction();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['properties'] = $this->normalizeProperties($data['properties'] ?? []);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
