<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Concerns\GeneratesUniqueCopyName;
use App\Filament\Resources\ResumeResource\Pages\CreateResume;
use App\Filament\Resources\ResumeResource\Pages\EditResume;
use App\Filament\Resources\ResumeResource\Pages\ListResumes;
use App\Models\Project;
use App\Models\Resume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResumeResource extends Resource
{
    use GeneratesUniqueCopyName;

    protected static ?string $model = Resume::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $resumeSiteBase = 'https://resume.alexyounger.me/';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\Textarea::make('bio')->rows(4)->columnSpanFull(),
            Forms\Components\Select::make('projects')
                ->multiple()
                ->relationship('projects', 'title', fn($query) => $query->where('active', true))
                ->preload()
                ->label('Projects'),
            Forms\Components\Repeater::make('contacts')
                ->schema([
                    Forms\Components\TextInput::make('key'),
                    Forms\Components\TextInput::make('href'),
                    Forms\Components\TextInput::make('text'),
                ])
                ->columnSpanFull(),
            Forms\Components\Repeater::make('references')
                ->schema([
                    Forms\Components\TextInput::make('name'),
                    Forms\Components\TextInput::make('title'),
                    Forms\Components\TextInput::make('company'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\TextInput::make('email'),
                ])
                ->columnSpanFull(),
            Forms\Components\Repeater::make('experience')
                ->schema([
                    Forms\Components\TextInput::make('title'),
                    Forms\Components\TextInput::make('company'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\TextInput::make('date'),
                    Forms\Components\TextInput::make('link'),
                    Forms\Components\TextInput::make('stack'),
                    Forms\Components\Repeater::make('bullets')
                        ->schema([Forms\Components\TextInput::make('bullet')->required()])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
            Forms\Components\Textarea::make('education')->rows(4)->columnSpanFull(),
            Forms\Components\KeyValue::make('properties')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('hash')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Resume $record): string => static::$resumeSiteBase . $record->hash)
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->excludeAttributes(['id', 'hash', 'created_at', 'updated_at'])
                    ->beforeReplicaSaved(function (Resume $replica): void {
                        $replica->name = static::generateUniqueCopyValue(
                            Resume::class,
                            'name',
                            $replica->name,
                        );
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResumes::route('/'),
            'create' => CreateResume::route('/create'),
            'edit' => EditResume::route('/{record}/edit'),
        ];
    }
}
