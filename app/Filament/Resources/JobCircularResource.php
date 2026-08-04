<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobCircularResource\Pages;
use App\Filament\Resources\JobCircularResource\RelationManagers;
use App\Models\CustomField;
use App\Models\JobCircular;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
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
            ->columns(12)
            ->components([
                // ── LEFT COLUMN (7 Cols): Main Job Circular Information ──
                Group::make([
                    Section::make('Job Circular Details')
                        ->icon('heroicon-o-briefcase')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Job Position Title')
                                ->placeholder('e.g. Senior Electrician / Security Guard')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->unique(JobCircular::class, 'slug', ignoreRecord: true),

                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('country')
                                    ->label('Destination Country')
                                    ->placeholder('e.g. Saudi Arabia, UAE, Qatar')
                                    ->required(),

                                Forms\Components\TextInput::make('category')
                                    ->label('Job Category / Industry')
                                    ->placeholder('e.g. Construction, Security, Hospitality')
                                    ->required(),
                            ]),

                            Grid::make(3)->schema([
                                Forms\Components\TextInput::make('vacancy')
                                    ->label('Vacancies')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),

                                Forms\Components\TextInput::make('salary_range')
                                    ->label('Salary Range')
                                    ->placeholder('e.g. 1500 - 2000 SAR / Month')
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'open' => 'Open',
                                        'closed' => 'Closed',
                                    ])
                                    ->default('open')
                                    ->required()
                                    ->native(false),
                            ]),

                            Forms\Components\DatePicker::make('deadline')
                                ->label('Application Deadline')
                                ->native(false),

                            Forms\Components\Textarea::make('description')
                                ->label('Job Description & Responsibilities')
                                ->placeholder('Provide detailed overview of duties, work hours, accommodation, etc...')
                                ->rows(4),

                            Forms\Components\Textarea::make('requirements')
                                ->label('Candidate Eligibility & Requirements')
                                ->placeholder('List educational requirements, age limit, required experience...')
                                ->rows(4),
                        ]),

                    Section::make('Circular Media & Attachments')
                        ->icon('heroicon-o-paper-clip')
                        ->schema([
                            \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('circular_image')
                                ->collection('circular-image')
                                ->disk('public')
                                ->label('Featured Banner / Poster Image')
                                ->image()
                                ->imageEditor()
                                ->helperText('Upload official poster image (JPG, PNG, WebP).')
                                ->nullable(),

                            \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('circular_attachments')
                                ->collection('circular-attachments')
                                ->disk('public')
                                ->label('Document Attachments (PDFs / Additional Images)')
                                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                                ->multiple()
                                ->helperText('Attach official demand letters, visa clearance PDFs, or trade test flyers.')
                                ->nullable(),
                        ]),
                ])->columnSpan(['default' => 12, 'lg' => 7]),

                // ── RIGHT COLUMN (5 Cols): Applicant Custom Field Requirements ──
                Group::make([
                    Section::make('Applicant Custom Requirements')
                        ->description('Configure dynamic fields or documents applicants MUST provide when applying.')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->schema([
                            Forms\Components\Repeater::make('customFieldRequirements')
                                ->label('')
                                ->schema([
                                    Forms\Components\Select::make('custom_field_id')
                                        ->label('Required Custom Field / Document')
                                        ->options(
                                            CustomField::active()->pluck('label', 'id')
                                        )
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('-- Select a Custom Field --')
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->native(false),

                                    Forms\Components\Toggle::make('is_required')
                                        ->label('Mandatory Requirement (Applicant cannot submit without filling/uploading this)')
                                        ->default(false)
                                        ->inline(false)
                                        ->onColor('danger')
                                        ->offColor('gray'),
                                ])
                                ->defaultItems(0)
                                ->addActionLabel('+ Add Requirement')
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(function (array $state): ?string {
                                    if (!empty($state['custom_field_id'])) {
                                        $field = CustomField::find($state['custom_field_id']);
                                        if ($field) {
                                            $badge = ($state['is_required'] ?? false) ? '🔴 Mandatory' : '🟢 Optional';
                                            return "{$field->label} — {$badge}";
                                        }
                                    }
                                    return null;
                                })
                                ->helperText('Applicants will see these specific fields & file upload inputs on the apply modal.'),
                        ]),

                    Section::make('Custom Fields Guide')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Placeholder::make('guide')
                                ->label('')
                                ->content('Need additional fields like "Passport Scan", "Medical Test", or "Police Clearance"? Create new custom fields anytime from Recruitment > Custom Fields.'),
                        ])
                        ->collapsed(),
                ])->columnSpan(['default' => 12, 'lg' => 5]),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\CustomFieldsRelationManager::class,
        ];
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
