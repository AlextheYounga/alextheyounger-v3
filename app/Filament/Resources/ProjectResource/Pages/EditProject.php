<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ReplicateAction::make()
                ->excludeAttributes(['id', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['title'] = $data['title'] . ' (Copy)';
                    unset($data['id']);

                    return $data;
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['content'] = $data['content'] ?? [];
        $data['properties'] = $data['properties'] ?? [];

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Project $record */
        $record = $this->record;
        $record->reorderPositions();
    }
}
