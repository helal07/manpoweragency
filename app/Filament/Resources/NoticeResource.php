<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoticeResource\Pages;
use App\Models\Notice;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';

    protected static \UnitEnum|string|null $navigationGroup = 'Recruitment';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Notice Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(Notice::class, 'slug', ignoreRecord: true),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('category')
                            ->default('General')
                            ->required(),
                        Forms\Components\DatePicker::make('published_at')
                            ->default(now())
                            ->required(),
                    ]),
                    Forms\Components\Toggle::make('is_pinned')
                        ->label('Pin Notice to Top')
                        ->default(false),
                    Forms\Components\Textarea::make('description')
                        ->rows(5)
                        ->required(),
                ]),
                Section::make('Attachments')->schema([
                    \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('notice_image')
                        ->collection('notice-image')
                        ->disk('public')
                        ->label('Featured Image')
                        ->image()
                        ->imageEditor()
                        ->helperText('Upload a notice image (JPG, PNG, WebP). This will be displayed on the public notice board.')
                        ->nullable(),
                    \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('notice_attachments')
                        ->collection('notice-attachments')
                        ->disk('public')
                        ->label('PDF / Document Attachments')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                        ->multiple()
                        ->helperText('Upload PDF files or additional images. Visitors can view/download these from the notice page.')
                        ->nullable(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->sortable(),
                Tables\Columns\IconColumn::make('is_pinned')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
            ])
            ->filters([])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotices::route('/'),
            'create' => Pages\CreateNotice::route('/create'),
            'edit' => Pages\EditNotice::route('/{record}/edit'),
        ];
    }
}
