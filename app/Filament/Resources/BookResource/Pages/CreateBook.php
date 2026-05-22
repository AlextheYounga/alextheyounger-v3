<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\Concerns\HasSaveHeaderAction;
use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    use HasSaveHeaderAction;

    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [$this->saveHeaderAction('create')];
    }

    protected function getCreateFormAction(): Action
    {
        return $this->createFormSaveAction();
    }

    protected function afterCreate(): void
    {
        /** @var Book $record */
        $record = $this->record;
        $record->reorderPositions();
    }
}
