<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['content'] = $data['content'] ?? [];
        $data['content']['bullets'] = collect($data['content']['bullets'] ?? [])
            ->map(fn(string $bullet): array => ['bullet' => $bullet])
            ->all();
        $data['content']['technology'] = collect($data['content']['technology'] ?? [])
            ->map(fn(string $name): array => ['name' => $name])
            ->all();

        return $data;
    }

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
        $data['content'] = $this->mutateContent($data['content'] ?? []);
        $data['properties'] = $data['properties'] ?? [];

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Project $record */
        $record = $this->record;
        $record->reorderPositions();
    }

    protected function mutateContent(array $content): array
    {
        $content['bullets'] = collect($content['bullets'] ?? [])
            ->pluck('bullet')
            ->filter()
            ->values()
            ->all();

        $content['technology'] = collect($content['technology'] ?? [])
            ->pluck('name')
            ->filter()
            ->values()
            ->all();

        return $content;
    }
}
