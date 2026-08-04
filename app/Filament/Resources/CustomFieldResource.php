<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomFieldResource\Pages;
use App\Models\CustomField;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CustomFieldResource extends Resource
{
    protected static ?string $model = CustomField::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static \UnitEnum|string|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Custom Fields';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Field Definition')
                    ->description('Define a custom field that applicants will fill in on their profile.')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Field Label')
                            ->placeholder('e.g., Do you have a forklift license?')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('name', \Illuminate\Support\Str::slug($state ?? '', '_'))),

                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Field Key (auto-generated)')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->helperText('Auto-generated from label. Used internally.'),

                            Forms\Components\Select::make('type')
                                ->label('Field Type')
                                ->options([
                                    'text' => 'Text (single line)',
                                    'textarea' => 'Textarea (multi-line)',
                                    'number' => 'Number',
                                    'select' => 'Dropdown (select)',
                                    'checkbox' => 'Checkbox (Yes/No)',
                                    'date' => 'Date Picker',
                                    'file' => 'File Upload',
                                ])
                                ->required()
                                ->live(),
                        ]),

                        Forms\Components\TagsInput::make('options')
                            ->label('Dropdown Options')
                            ->placeholder('Add option and press Enter')
                            ->helperText('Add each option one by one. Only used for Dropdown type.')
                            ->visible(fn ($get) => $get('type') === 'select')
                            ->required(fn ($get) => $get('type') === 'select'),
                    ]),

                Section::make('Settings')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_required')
                                ->label('Required')
                                ->helperText('If enabled, applicants must fill this field before saving their profile.')
                                ->default(false),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->helperText('Inactive fields are hidden from applicants but data is preserved.')
                                ->default(true),
                        ]),

                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Sort Order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Lower numbers appear first.'),

                            Forms\Components\TextInput::make('placeholder')
                                ->label('Placeholder Text')
                                ->placeholder('e.g., Enter certificate number')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('help_text')
                                ->label('Help Text')
                                ->placeholder('e.g., Shown below the input field')
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'gray',
                        'textarea' => 'gray',
                        'number' => 'info',
                        'select' => 'warning',
                        'checkbox' => 'success',
                        'date' => 'primary',
                        'file' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('Required')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('values_count')
                    ->label('Responses')
                    ->counts('values')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('Required Status'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ])
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomFields::route('/'),
            'create' => Pages\CreateCustomField::route('/create'),
            'edit' => Pages\EditCustomField::route('/{record}/edit'),
        ];
    }
}
