<?php

namespace App\Filament\Resources\ProposalResource\Pages;

use App\Filament\Resources\ProposalResource;
use App\Models\Proposal;
use Filament\Resources\Pages\CreateRecord;

class CreateProposal extends CreateRecord
{
    protected static string $resource = ProposalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['content'] = $data['content'] ?? [];
        $data['line_items'] = $data['line_items'] ?? [];
        $data['total'] = $this->sumTotal($data['line_items']);
        $data['properties'] = $data['properties'] ?? [];

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Proposal $record */
        $record = $this->record;
        $record->save();
    }

    private function sumTotal(array $lineItems): float|int
    {
        return collect($lineItems)->sum(fn(array $item) => (float) ($item['price'] ?? 0));
    }
}
