<?php

declare(strict_types=1);

namespace App\Filament\Resources\Concerns;

use Filament\Actions\Action;

trait HasSaveHeaderAction
{
    protected function saveHeaderAction(string $submitAction): Action
    {
        return Action::make('save')
            ->label('Save')
            ->submit($submitAction)
            ->formId('form')
            ->keyBindings(['mod+s']);
    }

    protected function createFormSaveAction(): Action
    {
        return Action::make('save')
            ->label('Save')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function editFormSaveAction(): Action
    {
        return Action::make('save')
            ->label('Save')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }
}
