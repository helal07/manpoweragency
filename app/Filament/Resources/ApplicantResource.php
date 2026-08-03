<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicantResource\Pages;
use App\Models\Applicant;
use App\Models\CustomField;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static \UnitEnum|string|null $navigationGroup = 'Recruitment';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // ── Header: Avatar + Name ──
                Infolists\Components\Section::make('Applicant Overview')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\ImageEntry::make('avatar')
                                ->label('')
                                ->circular()
                                ->defaultImageUrl(fn (Applicant $record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=0f172a&color=f59e0b&size=128')
                                ->getStateUsing(fn (Applicant $record) => $record->getFirstMediaUrl('avatar') ?: null)
                                ->size(80)
                                ->grow(false),
                            Infolists\Components\Group::make([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('email')
                                    ->label('')
                                    ->icon('heroicon-o-envelope')
                                    ->color('gray'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Registered')
                                    ->since()
                                    ->color('gray')
                                    ->icon('heroicon-o-clock'),
                            ]),
                        ]),
                    ])
                    ->collapsible(),

                // ── Personal Information ──
                Infolists\Components\Section::make('Personal Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Full Name'),
                        Infolists\Components\TextEntry::make('fathers_name')
                            ->label("Father's Name")
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('mothers_name')
                            ->label("Mother's Name")
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->date('d M Y')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('gender')
                            ->label('Gender')
                            ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : null)
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('marital_status')
                            ->label('Marital Status')
                            ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : null)
                            ->placeholder('Not provided'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                // ── Contact Details ──
                Infolists\Components\Section::make('Contact Details')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Infolists\Components\TextEntry::make('mobile_no')
                            ->label('Mobile No')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone')
                            ->icon('heroicon-o-phone')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope'),
                        Infolists\Components\TextEntry::make('linkedin_url')
                            ->label('LinkedIn')
                            ->icon('heroicon-o-link')
                            ->url(fn (?string $state) => $state)
                            ->openUrlInNewTab()
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // ── Education ──
                Infolists\Components\Section::make('Education')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Infolists\Components\TextEntry::make('ssc_year')
                            ->label('SSC Year')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('ssc_result')
                            ->label('SSC Result / GPA')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('hsc_year')
                            ->label('HSC Year')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('hsc_result')
                            ->label('HSC Result / GPA')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('highest_education')
                            ->label('Highest Education')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                // ── Work Experience & Skills ──
                Infolists\Components\Section::make('Work Experience & Skills')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Infolists\Components\TextEntry::make('experience_years')
                            ->label('Years of Experience')
                            ->suffix(' years')
                            ->placeholder('Not provided'),
                        Infolists\Components\IconEntry::make('can_speak_english')
                            ->label('Can Speak English')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('english_proficiency')
                            ->label('English Proficiency')
                            ->badge()
                            ->color(fn (?string $state) => match ($state) {
                                'native' => 'success',
                                'fluent' => 'info',
                                'conversational' => 'warning',
                                'basic' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : null)
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('other_languages')
                            ->label('Other Languages')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('experience_details')
                            ->label('Experience Details')
                            ->columnSpanFull()
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // ── Travel & Documents ──
                Infolists\Components\Section::make('Travel & Documents')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Infolists\Components\TextEntry::make('nid_passport')
                            ->label('NID / Passport No')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('passport_expiry')
                            ->label('Passport Expiry')
                            ->date('d M Y')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('preferred_country')
                            ->label('Preferred Country')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                // ── Addresses ──
                Infolists\Components\Section::make('Addresses')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Infolists\Components\TextEntry::make('current_address')
                            ->label('Current Address')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('permanent_address')
                            ->label('Permanent Address')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // ── Emergency Contact ──
                Infolists\Components\Section::make('Emergency Contact')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Infolists\Components\TextEntry::make('emergency_contact_name')
                            ->label('Contact Name')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('emergency_contact_phone')
                            ->label('Contact Phone')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // ── Documents (Media) ──
                Infolists\Components\Section::make('Uploaded Documents')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\TextEntry::make('resume_status')
                            ->label('Resume / CV')
                            ->getStateUsing(fn (Applicant $record) => $record->getFirstMediaUrl('resume') ? 'Uploaded' : 'Not uploaded')
                            ->badge()
                            ->color(fn (string $state) => $state === 'Uploaded' ? 'success' : 'danger')
                            ->url(fn (Applicant $record) => $record->getFirstMediaUrl('resume') ?: null)
                            ->openUrlInNewTab(),
                    ])
                    ->collapsible(),

                // ── Dynamic Custom Fields ──
                Infolists\Components\Section::make('Additional Information')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema(function (Applicant $record): array {
                        $customFields = CustomField::active()->get();

                        if ($customFields->isEmpty()) {
                            return [
                                Infolists\Components\TextEntry::make('no_custom_fields')
                                    ->label('')
                                    ->getStateUsing(fn () => 'No custom fields have been configured yet.')
                                    ->color('gray'),
                            ];
                        }

                        return $customFields->map(function (CustomField $field) use ($record) {
                            $value = $record->customFieldValues
                                ->where('custom_field_id', $field->id)
                                ->first()
                                ?->value;

                            $entry = Infolists\Components\TextEntry::make("custom_field_{$field->id}")
                                ->label($field->label)
                                ->getStateUsing(fn () => $value)
                                ->placeholder('Not provided');

                            // Show checkbox fields as icon
                            if ($field->type === 'checkbox') {
                                return Infolists\Components\IconEntry::make("custom_field_{$field->id}")
                                    ->label($field->label)
                                    ->getStateUsing(fn () => (bool) $value)
                                    ->boolean();
                            }

                            // Badge for required fields
                            if ($field->is_required && empty($value)) {
                                $entry->badge()->color('danger')->getStateUsing(fn () => 'Required — Not filled');
                            }

                            return $entry;
                        })->toArray();
                    })
                    ->columns(2)
                    ->collapsible()
                    ->visible(fn () => CustomField::active()->exists()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mobile_no')->label('Mobile')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fathers_name')->label("Father's Name")->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nid_passport')->label('NID / Passport')->searchable(),
                Tables\Columns\TextColumn::make('preferred_country')->label('Pref. Country')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('can_speak_english')->label('English')->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label('Registered')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicants::route('/'),
            'view' => Pages\ViewApplicant::route('/{record}'),
        ];
    }
}
