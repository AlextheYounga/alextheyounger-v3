<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    use GeneratesUniqueCopyName;

    protected static string $resource = CategoryResource::class;

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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ReplicateAction::make()
                ->excludeAttributes(['id', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['name'] = static::generateUniqueCopyValue(
                        Category::class,
                        'name',
                        $data['name'] ?? null,
                    );
                    unset($data['id']);

                    return $data;
                }),
        ];
    }

    protected function afterSave(): void
    {
        /** @var Category $record */
        $record = $this->record;
        $record->reorderPositions();
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
