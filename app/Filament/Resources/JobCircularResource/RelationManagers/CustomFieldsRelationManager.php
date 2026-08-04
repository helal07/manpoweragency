<?php

namespace App\Filament\Resources\JobCircularResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CustomFieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'customFields';

    protected static ?string $title = 'Custom Application Requirements';

    protected static ?string $modelLabel = 'Application Field';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Field Name')
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

                Tables\Columns\IconColumn::make('pivot.is_required')
                    ->label('Must Fill (Required)')
                    ->boolean(),

                Tables\Columns\TextColumn::make('help_text')
                    ->label('Guidance')
                    ->limit(30),
            ])
            ->headerActions([
                Actions\AttachAction::make()
                    ->label('Add Required / Custom Field')
                    ->preloadRecordSelect()
                    ->schema(fn (Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Toggle::make('is_required')
                            ->label('Required for this Job Circular')
                            ->helperText('If enabled, applicants cannot submit application without filling this field / uploading document.')
                            ->default(true),
                    ]),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_required')
                            ->label('Required for this Job Circular'),
                    ]),
                Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Actions\DetachBulkAction::make(),
            ]);
    }
}
