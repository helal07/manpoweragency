<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobCircularResource\Pages;
use App\Models\JobCircular;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class JobCircularResource extends Resource
{
    protected static ?string $model = JobCircular::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static \UnitEnum|string|null $navigationGroup = 'Recruitment';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job Circular Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(JobCircular::class, 'slug', ignoreRecord: true),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('country')
                            ->required(),
                        Forms\Components\TextInput::make('category')
                            ->required(),
                    ]),
                    Grid::make(3)->schema([
                        Forms\Components\TextInput::make('vacancy')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Forms\Components\TextInput::make('salary_range')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'closed' => 'Closed',
                            ])
                            ->default('open')
                            ->required(),
                    ]),
                    Forms\Components\DatePicker::make('deadline'),
                    Forms\Components\Textarea::make('description')
                        ->rows(3),
                    Forms\Components\Textarea::make('requirements')
                        ->rows(3),
                ]),
                Section::make('Attachments')->schema([
                    \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('circular_image')
                        ->collection('circular-image')
                        ->disk('public')
                        ->label('Circular Image')
                        ->image()
                        ->imageEditor()
                        ->helperText('Upload a job circular image (JPG, PNG, WebP).')
                        ->nullable(),
                    \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('circular_attachments')
                        ->collection('circular-attachments')
                        ->disk('public')
                        ->label('PDF / Document Attachments')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->multiple()
                        ->helperText('Upload PDF files or additional images for this circular.')
                        ->nullable(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),
                Tables\Columns\TextColumn::make('country')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('vacancy')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('salary_range')
                    ->searchable(),
                Tables\Columns\TextColumn::make('computed_status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn ($record) => ($record->deadline && $record->deadline->isFuture()) || $record->status === 'open' ? 'Open' : 'Closed')
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'success',
                        'Closed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('deadline')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ])
            ->striped()
            ->defaultPaginationPageOption(25);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobCirculars::route('/'),
            'create' => Pages\CreateJobCircular::route('/create'),
            'edit' => Pages\EditJobCircular::route('/{record}/edit'),
        ];
    }
}
