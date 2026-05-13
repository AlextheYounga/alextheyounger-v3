<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['content'] = $data['content'] ?? [];
        $data['properties'] = $data['properties'] ?? [];

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Project $record */
        $record = $this->record;
        $record->reorderPositions();
    }
}
