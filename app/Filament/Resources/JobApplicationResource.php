<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplicationResource\Pages;
use App\Models\CustomField;
use App\Models\JobApplication;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Tables;
use Filament\Tables\Table;

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-check';

    protected static \UnitEnum|string|null $navigationGroup = 'Recruitment';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Job Applications';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Application Status & Review')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Application Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'reviewed' => 'Reviewed',
                                'shortlisted' => 'Shortlisted',
                                'interview' => 'Interview Called',
                                'accepted' => 'Accepted / Selected',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('notes')
                            ->label('Internal Admin Notes')
                            ->placeholder('Add internal comments, interview remarks, or status notes...')
                            ->rows(4),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Application Summary ──
                Section::make('Application Overview')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('jobCircular.title')
                                ->label('Applied Position')
                                ->weight('bold')
                                ->size(TextSize::Large)
                                ->color('primary'),

                            TextEntry::make('jobCircular.country')
                                ->label('Destination Country')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('status')
                                ->label('Current Status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'pending' => 'warning',
                                    'reviewed' => 'info',
                                    'shortlisted' => 'primary',
                                    'interview' => 'warning',
                                    'accepted' => 'success',
                                    'rejected' => 'danger',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'pending' => 'Pending Review',
                                    'reviewed' => 'Reviewed',
                                    'shortlisted' => 'Shortlisted',
                                    'interview' => 'Interview Called',
                                    'accepted' => 'Accepted / Selected',
                                    'rejected' => 'Rejected',
                                    default => ucfirst($state),
                                }),

                            TextEntry::make('applicant.name')
                                ->label('Applicant Name')
                                ->weight('bold'),

                            TextEntry::make('applicant.mobile_no')
                                ->label('Applicant Mobile')
                                ->icon('heroicon-o-phone')
                                ->copyable(),

                            TextEntry::make('created_at')
                                ->label('Applied On')
                                ->dateTime('d M Y, h:i A')
                                ->icon('heroicon-o-calendar'),
                        ]),
                    ]),

                // ── Circular-Specific Custom Field Requirements & Uploaded Documents ──
                Section::make('Submitted Requirements & Documents for this Circular')
                    ->description('Custom fields, certifications, and document uploads submitted specifically for this circular.')
                    ->icon('heroicon-o-paper-clip')
                    ->schema(function (JobApplication $record): array {
                        $circular = $record->jobCircular;
                        if (!$circular) {
                            return [
                                TextEntry::make('no_circular')
                                    ->label('')
                                    ->getStateUsing(fn () => 'No associated circular found.')
                                    ->color('gray'),
                            ];
                        }

                        $attachedFields = $circular->customFields;
                        if ($attachedFields->isEmpty()) {
                            return [
                                TextEntry::make('no_requirements')
                                    ->label('')
                                    ->getStateUsing(fn () => 'This circular had no extra custom requirements.')
                                    ->color('gray'),
                            ];
                        }

                        return $attachedFields->map(function (CustomField $field) use ($record) {
                            $fieldValue = $record->customFieldValues
                                ->where('custom_field_id', $field->id)
                                ->first();

                            $val = $fieldValue?->value;

                            if ($field->type === 'file') {
                                if (!empty($val)) {
                                    $fileUrl = asset('storage/' . $val);
                                    $fileName = basename($val);
                                    return TextEntry::make("custom_field_{$field->id}")
                                        ->label($field->label)
                                        ->getStateUsing(fn () => '📎 View / Download Document (' . $fileName . ')')
                                        ->badge()
                                        ->color('success')
                                        ->url($fileUrl)
                                        ->openUrlInNewTab()
                                        ->helperText('Click to open file in new tab');
                                }

                                return TextEntry::make("custom_field_{$field->id}")
                                    ->label($field->label)
                                    ->getStateUsing(fn () => 'No file uploaded')
                                    ->badge()
                                    ->color('danger');
                            }

                            if ($field->type === 'checkbox') {
                                return IconEntry::make("custom_field_{$field->id}")
                                    ->label($field->label)
                                    ->getStateUsing(fn () => (bool) $val)
                                    ->boolean();
                            }

                            return TextEntry::make("custom_field_{$field->id}")
                                ->label($field->label)
                                ->getStateUsing(fn () => $val ?: 'Not provided')
                                ->weight('medium');
                        })->toArray();
                    }),

                // ── Applicant Profile Snapshot ──
                Section::make('Applicant Profile Snapshot')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('applicant.fathers_name')
                                ->label("Father's Name")
                                ->placeholder('Not provided'),

                            TextEntry::make('applicant.nid_passport')
                                ->label('NID / Passport No')
                                ->placeholder('Not provided'),

                            TextEntry::make('applicant.ssc_year')
                                ->label('SSC Year & Result')
                                ->getStateUsing(fn (JobApplication $record) => $record->applicant?->ssc_year ? ($record->applicant->ssc_year . ' (Result: ' . ($record->applicant->ssc_result ?? 'N/A') . ')') : 'Not provided'),

                            TextEntry::make('applicant.experience_years')
                                ->label('Experience')
                                ->getStateUsing(fn (JobApplication $record) => $record->applicant?->experience_years ? ($record->applicant->experience_years . ' Years (' . ($record->applicant->skills_trade ?? 'General') . ')') : 'Not provided'),

                            TextEntry::make('applicant.can_speak_english')
                                ->label('Speaks English')
                                ->getStateUsing(fn (JobApplication $record) => $record->applicant?->can_speak_english ? 'Yes' : 'No')
                                ->badge()
                                ->color(fn (string $state) => $state === 'Yes' ? 'success' : 'gray'),

                            TextEntry::make('resume_link')
                                ->label('Master Resume / CV')
                                ->getStateUsing(fn (JobApplication $record) => $record->applicant?->getFirstMediaUrl('resume') ? '📄 View Master Resume' : 'No resume uploaded')
                                ->badge()
                                ->color(fn (JobApplication $record) => $record->applicant?->getFirstMediaUrl('resume') ? 'primary' : 'gray')
                                ->url(fn (JobApplication $record) => $record->applicant?->getFirstMediaUrl('resume') ?: null)
                                ->openUrlInNewTab(),
                        ]),
                    ])
                    ->collapsible(),

                // ── Cover Letter & Notes ──
                Section::make('Cover Letter & Admin Notes')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->schema([
                        TextEntry::make('cover_letter')
                            ->label('Applicant Cover Letter / Message')
                            ->placeholder('No cover letter provided.')
                            ->columnSpanFull(),

                        TextEntry::make('notes')
                            ->label('Admin Internal Notes')
                            ->placeholder('No internal notes recorded yet.')
                            ->color('gray')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicant.name')
                    ->label('Applicant')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (JobApplication $record): ?string => $record->applicant?->email),

                Tables\Columns\TextColumn::make('jobCircular.title')
                    ->label('Position / Circular')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('jobCircular.country')
                    ->label('Country')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('applicant.mobile_no')
                    ->label('Mobile')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'shortlisted' => 'primary',
                        'interview' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'shortlisted' => 'Shortlisted',
                        'interview' => 'Interview',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Application Status')
                    ->placeholder('All Statuses — filter by review stage')
                    ->options([
                        'pending' => '⏳ Pending Review',
                        'reviewed' => '👁️ Reviewed',
                        'shortlisted' => '⭐ Shortlisted',
                        'interview' => '📞 Interview Called',
                        'accepted' => '✅ Accepted / Selected',
                        'rejected' => '❌ Rejected',
                    ])
                    ->native(false)
                    ->preload(),

                Tables\Filters\SelectFilter::make('job_circular_id')
                    ->label('Job Circular / Position')
                    ->placeholder('All Circulars — filter by position')
                    ->relationship('jobCircular', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'view' => Pages\ViewJobApplication::route('/{record}'),
            'edit' => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
