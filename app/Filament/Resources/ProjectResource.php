<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\TextInput::make('position')->numeric()->required(),
            Forms\Components\TextInput::make('scope')->maxLength(255),
            Forms\Components\TextInput::make('external_link')->url()->maxLength(2048),
            Forms\Components\TextInput::make('external_image_link')->url()->maxLength(2048),
            Forms\Components\TextInput::make('properties.image_name')
                ->label('Local Image Name')
                ->maxLength(255),
            Forms\Components\RichEditor::make('content.description')->columnSpanFull(),
            Forms\Components\Textarea::make('content.excerpt')->rows(4)->columnSpanFull(),
            Forms\Components\Repeater::make('content.bullets')
                ->schema([Forms\Components\TextInput::make('bullet')->required()])
                ->columnSpanFull(),
            Forms\Components\Repeater::make('content.technology')
                ->schema([Forms\Components\TextInput::make('name')->required()])
                ->columnSpanFull(),
            Forms\Components\Toggle::make('active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('scope')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position')->sortable(),
                Tables\Columns\IconColumn::make('active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
