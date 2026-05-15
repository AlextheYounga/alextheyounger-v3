<?php

namespace App\Filament\Resources\PageContentResource\Pages;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\PageContentResource;
use App\Models\PageContent;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageContent extends EditRecord
{
    use GeneratesUniqueCopyName;

    protected static string $resource = PageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ReplicateAction::make()
                ->excludeAttributes(['id', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['name'] = static::generateUniqueCopyValue(
                        PageContent::class,
                        'name',
                        $data['name'] ?? null,
                    );
                    unset($data['id']);

                    return $data;
                }),
        ];
    }
}
