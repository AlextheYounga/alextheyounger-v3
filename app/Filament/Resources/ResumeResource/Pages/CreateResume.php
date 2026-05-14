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

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Resume $record */
        $record = $this->record;
        $projects = $this->data['projects'] ?? [];
        $record->projects()->sync(is_array($projects) ? $projects : []);
    }

    protected function mutateExperience(array $experience): array
    {
        return collect($experience)
            ->map(function (array $item): array {
                $item['bullets'] = json_decode($item['bullets'] ?? '[]', true) ?: [];

                return $item;
            })
            ->all();
    }
}
