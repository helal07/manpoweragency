<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static \UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Company Details')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Company / Client Name')
                            ->placeholder('e.g. Almarai Food & Beverage Company')
                            ->required(),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Client Logo / Corporate Photo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('clients')
                            ->helperText('Upload official client logo or branch photo (PNG with transparent background, SVG, or JPG recommended).')
                            ->nullable(),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('country')
                                ->label('Country / Location')
                                ->placeholder('e.g. Saudi Arabia, UAE, Qatar')
                                ->required(),

                            Forms\Components\TextInput::make('sector')
                                ->label('Industry / Sector')
                                ->placeholder('e.g. Construction & Civil Engineering, FMCG')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('website_url')
                                ->label('Website URL')
                                ->placeholder('https://example.com')
                                ->url()
                                ->nullable(),

                            Forms\Components\TextInput::make('order')
                                ->label('Display Sort Order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower numbers appear first in the carousel.'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->height(40)
                    ->defaultImageUrl(fn () => null)
                    ->placeholder('No Logo')
                    ->circular(false),

                Tables\Columns\TextColumn::make('name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('country')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sector')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('website_url')
                    ->label('Website')
                    ->limit(25)
                    ->url(fn ($record) => $record->website_url, true)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
