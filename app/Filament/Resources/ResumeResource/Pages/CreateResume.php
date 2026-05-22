<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use App\Models\Resume;
use Filament\Resources\Pages\CreateRecord;

class CreateResume extends CreateRecord
{
    protected static string $resource = ResumeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['experience'] = $this->mutateExperience($data['experience'] ?? []);
        $data['expertise'] = $this->mutateExpertise($data['expertise'] ?? []);
        $data['properties'] = $this->normalizeProperties($data['properties'] ?? []);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Resume $record */
        $record = $this->record;
        $this->syncProjects($record, $this->data['projects'] ?? []);
    }

    protected function mutateExperience(array $experience): array
    {
        return collect($experience)
            ->map(function (array $item): array {
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
            })
            ->all();
    }

    protected function mutateExpertise(array $expertise): array
    {
        return collect($expertise)
            ->map(function (mixed $item): ?string {
                if (is_array($item)) {
                    $item = $item['expertise'] ?? null;
                }

                if (!is_string($item)) {
                    return null;
                }

                $item = trim($item);

                return $item === '' ? null : $item;
            })
            ->filter()
            ->values()
            ->all();
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

    protected function syncProjects(Resume $record, mixed $projects): void
    {
        if (! is_array($projects)) {
            $record->projects()->sync([]);

            return;
        }

        $record->projects()->sync(
            collect($projects)
                ->filter(fn (mixed $projectId): bool => is_numeric($projectId))
                ->map(fn (mixed $projectId): int => (int) $projectId)
                ->values()
                ->mapWithKeys(fn (int $projectId, int $index): array => [
                    $projectId => ['position' => $index + 1],
                ])
                ->all(),
        );
    }
}
