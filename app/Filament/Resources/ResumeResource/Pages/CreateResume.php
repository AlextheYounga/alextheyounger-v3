<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\Concerns\HasSaveHeaderAction;
use App\Filament\Resources\ResumeResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateResume extends CreateRecord
{
    use HasSaveHeaderAction;

    protected static string $resource = ResumeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->saveHeaderAction('create'),
        ];
    }

    protected function getCreateFormAction(): Action
    {
        return $this->createFormSaveAction();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['experience'] = $this->mutateExperience($data['experience'] ?? []);
        $data['expertise'] = $this->mutateExpertise($data['expertise'] ?? []);
        $data['properties'] = $this->normalizeProperties($data['properties'] ?? []);

        return $data;
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

}
