<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use App\Models\Proposal;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProposal extends EditRecord
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ReplicateAction::make()
                ->excludeAttributes(['id', 'hash', 'created_at', 'updated_at'])
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['title'] = $data['title'] . ' (Copy)';
                    unset($data['id'], $data['hash']);

                    return $data;
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['content'] = $data['content'] ?? [];
        $data['line_items'] = $data['line_items'] ?? [];
        $data['properties'] = $data['properties'] ?? [];
        $data['total'] = collect($data['line_items'])->sum(
            fn(array $item) => (float) ($item['price'] ?? 0),
        );

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Proposal $record */
        $record = $this->record;
        $record->save();
    }
}
