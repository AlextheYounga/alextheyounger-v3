<?php

namespace App\Filament\Resources\CoverLetterResource\Pages;

use App\Filament\Resources\CoverLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoverLetter extends EditRecord
{
    protected static string $resource = CoverLetterResource::class;

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
}
