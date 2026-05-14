<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoverLetterResource\Pages\CreateCoverLetter;
use App\Filament\Resources\CoverLetterResource\Pages\EditCoverLetter;
use App\Filament\Resources\CoverLetterResource\Pages\ListCoverLetters;
use App\Models\CoverLetter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CoverLetterResource extends Resource
{
    protected static ?string $model = CoverLetter::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static string $coverLetterSiteBase = 'https://resume.alexyounger.me/cover-letters/';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('company')->maxLength(255),
            Forms\Components\TextInput::make('hiring_manager')->maxLength(255),
            Forms\Components\RichEditor::make('content')->columnSpanFull(),
            Forms\Components\KeyValue::make('properties')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('company')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('hash')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn(CoverLetter $record): string => static::$coverLetterSiteBase .
                            $record->hash,
                    )
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoverLetters::route('/'),
            'create' => CreateCoverLetter::route('/create'),
            'edit' => EditCoverLetter::route('/{record}/edit'),
        ];
    }
}
