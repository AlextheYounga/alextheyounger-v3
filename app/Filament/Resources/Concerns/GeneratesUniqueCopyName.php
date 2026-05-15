<?php

namespace App\Filament\Resources\Concerns;

trait GeneratesUniqueCopyName
{
    protected static function generateUniqueCopyValue(
        string $modelClass,
        string $column,
        ?string $originalValue,
    ): string {
        $baseValue = trim((string) ($originalValue ?? 'Untitled'));
        $candidate = $baseValue . ' (Copy)';
        $suffix = 2;

        while ($modelClass::query()->where($column, $candidate)->exists()) {
            $candidate = $baseValue . ' (Copy ' . $suffix . ')';
            $suffix++;
        }

        return $candidate;
    }
}
