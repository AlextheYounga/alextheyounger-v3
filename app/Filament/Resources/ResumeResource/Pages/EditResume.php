<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use App\Models\Resume;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResume extends EditRecord
{
    protected static string $resource = ResumeResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['properties'] = $this->normalizeProperties($data['properties'] ?? []);
        $data['experience'] = collect($data['experience'] ?? [])
            ->map(function (array $item): array {
                $item['bullets'] = collect($item['bullets'] ?? [])
                    ->map(function (mixed $bullet): ?array {
                        if (!is_string($bullet)) {
                            return null;
                        }

                        $bullet = trim($bullet);

                        return $bullet === '' ? null : ['bullet' => $bullet];
                    })
                    ->filter()
                    ->values()
                    ->all();

                return $item;
            })
            ->all();

        return $data;
    }

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
        $data['properties'] = $this->normalizeProperties($data['properties'] ?? []);
        $data['experience'] = collect($data['experience'] ?? [])->map(function (array $item): array {
            $item['bullets'] = collect($item['bullets'] ?? [])
                ->map(function (mixed $bullet): ?string {
                    if (is_array($bullet)) {
                        $bullet = $bullet['bullet'] ?? null;
                    }

                    if (!is_string($bullet)) {
                        return null;
                    }

                    $bullet = trim($bullet);

                    return $bullet === '' ? null : $bullet;
                })
                ->filter()
                ->values()
                ->all();

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
