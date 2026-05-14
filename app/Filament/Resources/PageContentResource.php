<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageContentResource\Pages\EditPageContent;
use App\Filament\Resources\PageContentResource\Pages\ListPageContents;
use App\Models\PageContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageContentResource extends Resource
{
    protected static ?string $model = PageContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('html_id')->label('HTML ID')->disabled(),
            Forms\Components\TextInput::make('view')->disabled(),
            Forms\Components\TextInput::make('key')->disabled(),
            Forms\Components\Textarea::make('content')
                ->required()
                ->rows(20)
                ->extraAttributes(['class' => 'font-mono text-sm'])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('view')->searchable(),
                Tables\Columns\TextColumn::make('key')->searchable(),
                Tables\Columns\TextColumn::make('html_id')->label('HTML ID')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date('Y-m-d')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->date('Y-m-d')->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageContents::route('/'),
            'edit' => EditPageContent::route('/{record}/edit'),
        ];
    }
}
