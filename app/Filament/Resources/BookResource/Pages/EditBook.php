<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBook extends EditRecord
{
    use GeneratesUniqueCopyName;

    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ReplicateAction::make()
                ->excludeAttributes(['id', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['title'] = static::generateUniqueCopyValue(
                        Book::class,
                        'title',
                        $data['title'] ?? null,
                    );
                    unset($data['id']);

                    return $data;
                }),
        ];
    }

    protected function afterSave(): void
    {
        /** @var Book $record */
        $record = $this->record;
        $record->reorderPositions();
    }
}
