<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\Concerns\HasSaveHeaderAction;
use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBook extends EditRecord
{
    use GeneratesUniqueCopyName;
    use HasSaveHeaderAction;

    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->saveHeaderAction('save'),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return $this->editFormSaveAction();
    }

    protected function afterSave(): void
    {
        /** @var Book $record */
        $record = $this->record;
        $record->reorderPositions();
    }
}
