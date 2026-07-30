<?php

namespace App\Filament\Widgets;

use App\Models\JobCircular;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestJobCircularsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Job Circulars Overview';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JobCircular::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Job Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('Destination Country')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('vacancy')
                    ->label('Vacancies')
                    ->numeric(),
                Tables\Columns\TextColumn::make('salary_range')
                    ->label('Salary Range'),
                Tables\Columns\BadgeColumn::make('computed_status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => ($record->deadline && $record->deadline->isFuture()) || $record->status === 'open' ? 'Open' : 'Closed')
                    ->colors([
                        'success' => 'Open',
                        'danger' => 'Closed',
                    ]),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date(),
            ]);
    }
}
