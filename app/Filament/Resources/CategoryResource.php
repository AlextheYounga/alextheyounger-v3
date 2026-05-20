<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    use GeneratesUniqueCopyName;

    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('type')->required()->maxLength(255),
            Forms\Components\TextInput::make('position')->numeric()->required(),
            Forms\Components\Toggle::make('active')->default(true),
            Forms\Components\KeyValue::make('properties')
                ->helperText('Additional category metadata, including html_selector when needed.')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->reorderable('position')
            ->paginated(false)
            ->reorderRecordsTriggerAction(
                fn(Tables\Actions\Action $action, bool $isReordering): Tables\Actions\Action => $action
                    ->button()
                    ->label($isReordering ? 'Done Reordering' : 'Reorder Positions')
                    ->icon($isReordering ? 'heroicon-o-check' : 'heroicon-o-arrows-up-down'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position')->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
