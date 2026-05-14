<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages\CreateProposal;
use App\Filament\Resources\ProposalResource\Pages\EditProposal;
use App\Filament\Resources\ProposalResource\Pages\ListProposals;
use App\Models\Proposal;
use Dotswan\FilamentCodeEditor\Fields\CodeEditor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('client')->required()->maxLength(255),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\DatePicker::make('completion_date'),
            Forms\Components\Toggle::make('properties.use_client_agreement')->label(
                'Use Client Agreement?',
            ),
            Forms\Components\Placeholder::make('client_agreement')->content(
                fn($get) => $get('client_agreement') ? 'Yes' : 'No',
            ),
            Forms\Components\Placeholder::make('client_signature')->content(
                fn($get) => (string) $get('client_signature'),
            ),
            Forms\Components\Placeholder::make('client_sign_date')->content(
                fn($get) => (string) $get('client_sign_date'),
            ),
            Forms\Components\RichEditor::make('content.description')->columnSpanFull(),
            Forms\Components\RichEditor::make('content.scope')->columnSpanFull(),
            Forms\Components\RichEditor::make('content.technology')->columnSpanFull(),
            Forms\Components\RichEditor::make('content.disclaimer')->columnSpanFull(),
            Forms\Components\Repeater::make('content.payment_schedule')
                ->schema([
                    Forms\Components\TextInput::make('milestone')->required(),
                    Forms\Components\TextInput::make('description'),
                    Forms\Components\TextInput::make('amount_due')->numeric(),
                    Forms\Components\DatePicker::make('date'),
                ])
                ->columnSpanFull(),
            Forms\Components\Repeater::make('line_items')
                ->schema([
                    Forms\Components\TextInput::make('description')->required(),
                    Forms\Components\TextInput::make('price')->numeric()->required(),
                ])
                ->columnSpanFull(),
            CodeEditor::make('properties.custom_css')
                ->label('Custom CSS')
                ->helperText(
                    'Optional custom CSS for this proposal page. Tip: prefix selectors with #proposal to keep styles local.',
                )
                ->id('proposal_custom_css')
                ->minHeight(320)
                ->lightModeTheme('basic-light')
                ->darkModeTheme('gruvbox-dark')
                ->showCopyButton(true)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('total')->numeric()->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('hash')->searchable(),
                Tables\Columns\TextColumn::make('total')->money('usd')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Proposal $record): string => url('/proposals/' . $record->hash))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProposals::route('/'),
            'create' => CreateProposal::route('/create'),
            'edit' => EditProposal::route('/{record}/edit'),
        ];
    }
}
