<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use App\Models\Resume;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResume extends EditRecord
{
    protected static string $resource = ResumeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ReplicateAction::make()
                ->excludeAttributes(['id', 'hash', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['name'] = $data['name'] . ' (Copy)';
                    unset($data['id'], $data['hash']);

                    return $data;
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['experience'] = collect($data['experience'] ?? [])->map(function (array $item): array {
            $item['bullets'] = json_decode($item['bullets'] ?? '[]', true) ?: [];

            return $item;
        })->all();

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Resume $record */
        $record = $this->record;
        $projects = $this->data['projects'] ?? [];
        $record->projects()->sync(is_array($projects) ? $projects : []);
    }
}
