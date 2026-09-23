<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsLogResource\Pages;
use App\Models\SmsLog;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SmsLogResource extends Resource
{
    protected static ?string $model = SmsLog::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-queue-list';

    protected static \UnitEnum|string|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'SMS Logs & History';

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SMS Dispatch Record')
                    ->schema([
                        TextEntry::make('recipient')->label('Recipient Mobile Number')->copyable()->weight('bold'),
                        TextEntry::make('type')->badge()->color(fn ($state) => match ($state) {
                            'otp' => 'warning',
                            'interview_card' => 'success',
                            'test' => 'info',
                            default => 'gray',
                        }),
                        TextEntry::make('status')->badge()->color(fn ($state) => match ($state) {
                            'sent' => 'success',
                            'failed' => 'danger',
                            'simulated' => 'info',
                            default => 'gray',
                        }),
                        TextEntry::make('created_at')->label('Sent Time')->dateTime('d M Y, h:i:s A'),
                        TextEntry::make('applicant.name')->label('Related Applicant')->placeholder('N/A'),
                        TextEntry::make('jobApplication.jobCircular.title')->label('Related Job Circular')->placeholder('N/A'),
                        TextEntry::make('message')->label('Message Body')->columnSpanFull()->markdown(),
                        TextEntry::make('gateway_response')->label('Gateway Raw Response')->columnSpanFull()->fontFamily('mono'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recipient')
                    ->label('Recipient')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->label('SMS Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'otp' => 'warning',
                        'interview_card' => 'success',
                        'test' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'otp' => '🔑 OTP Code',
                        'interview_card' => '📋 Interview Slip',
                        'test' => '🧪 Test SMS',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'simulated' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sent' => '✅ Delivered/Sent',
                        'failed' => '❌ Failed',
                        'simulated' => '💡 Simulated',
                        default => ucfirst($state),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Message Preview')
                    ->limit(65)
                    ->tooltip(fn (SmsLog $record) => $record->message)
                    ->wrap(),

                Tables\Columns\TextColumn::make('applicant.name')
                    ->label('Applicant')
                    ->searchable()
                    ->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'otp' => 'OTP Code',
                        'interview_card' => 'Interview Slip',
                        'test' => 'Test SMS',
                        'general' => 'General',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'sent' => 'Delivered / Sent',
                        'failed' => 'Failed',
                        'simulated' => 'Simulated',
                    ])
                    ->native(false),
            ])
            ->actions([
                Actions\ViewAction::make(),
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
            'index' => Pages\ListSmsLogs::route('/'),
            'view' => Pages\ViewSmsLog::route('/{record}'),
        ];
    }
}
