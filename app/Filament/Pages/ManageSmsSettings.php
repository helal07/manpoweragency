<?php

namespace App\Filament\Pages;

use App\Services\SmsService;
use App\Settings\SmsSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageSmsSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static \UnitEnum|string|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'SMS Gateway Settings';

    protected static ?string $title = 'SMS Gateway & Notification Settings';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user ? ($user->hasRole('super_admin', 'admin') || $user->can('manage_site_settings')) : false;
    }

    public function mount(SmsSettings $settings): void
    {
        $this->form->fill([
            'is_enabled' => $settings->is_enabled ?? true,
            'api_token' => $settings->api_token ?? '',
            'api_url' => $settings->api_url ?? 'http://api.greenweb.com.bd/api.php',
            'sender_id' => $settings->sender_id ?? '',
            'otp_expiry_minutes' => $settings->otp_expiry_minutes ?? 5,
            'otp_template' => $settings->otp_template ?? 'Your OTP for {site_name} is: {otp}. Valid for {expiry} minutes. Do not share with anyone.',
            'interview_template' => $settings->interview_template ?? 'Dear {name}, You are shortlisted for {job_title}. Interview Date: {date}, Time: {time}, Venue: {venue}. Admit Card: {card_link}',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('checkBalance')
                ->label('Check SMS Balance')
                ->icon('heroicon-o-credit-card')
                ->color('info')
                ->action(function (SmsService $smsService) {
                    $result = $smsService->checkBalance();
                    if ($result['success']) {
                        Notification::make()
                            ->title('SMS Account Balance')
                            ->body('Your remaining SMS balance / credits: ' . $result['balance'])
                            ->success()
                            ->persistent()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('SMS Balance Check')
                            ->body($result['message'])
                            ->warning()
                            ->send();
                    }
                }),

            Action::make('sendTestSms')
                ->label('Send Test SMS')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->form([
                    TextInput::make('test_phone')
                        ->label('Recipient Mobile Number')
                        ->placeholder('017XXXXXXXX')
                        ->helperText('Enter a valid 11-digit Bangladeshi mobile number.')
                        ->required(),

                    Textarea::make('test_message')
                        ->label('Test Message')
                        ->default('This is a test SMS from your Recruitment Portal. SMS Gateway connection is working perfectly!')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data, SmsService $smsService) {
                    $result = $smsService->send(
                        to: $data['test_phone'],
                        message: $data['test_message'],
                        type: 'test'
                    );

                    if ($result['success']) {
                        if (!empty($result['simulated'])) {
                            Notification::make()
                                ->title('SMS Logged in Simulation Mode')
                                ->body('Gateway is disabled or API Token is empty. Simulated log created in SMS Logs.')
                                ->info()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Live Test SMS Sent Successfully!')
                                ->body('Gateway Response: ' . $result['response'])
                                ->success()
                                ->send();
                        }
                    } else {
                        Notification::make()
                            ->title('SMS Dispatch Failed')
                            ->body('Error / Response: ' . ($result['response'] ?? 'Unknown gateway error.'))
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('bdbulksms / Greenweb API Configuration')
                    ->description('Enter your live SMS API credentials from bdbulksms.net or greenweb.com.bd')
                    ->schema([
                        Toggle::make('is_enabled')
                            ->label('Enable SMS Gateway')
                            ->helperText('If disabled, outgoing SMS will be logged in simulation mode without consuming gateway credits.')
                            ->default(true),

                        Grid::make(2)->schema([
                            TextInput::make('api_url')
                                ->label('SMS API Endpoint URL')
                                ->placeholder('http://api.greenweb.com.bd/api.php')
                                ->default('http://api.greenweb.com.bd/api.php')
                                ->helperText('Default: http://api.greenweb.com.bd/api.php or http://api.bdbulksms.net/api.php')
                                ->required(),

                            TextInput::make('sender_id')
                                ->label('Masking / Sender ID (Optional)')
                                ->placeholder('Leave blank for non-masking')
                                ->nullable(),
                        ]),

                        TextInput::make('api_token')
                            ->label('Live API Token')
                            ->placeholder('Paste your live API token from bdbulksms / greenweb developer portal')
                            ->password()
                            ->revealable()
                            ->helperText('Your confidential API token provided by the SMS service provider.')
                            ->nullable(),
                    ]),

                Section::make('Applicant Registration OTP Settings')
                    ->description('Configuration for one-time registration mobile OTP verification')
                    ->schema([
                        TextInput::make('otp_expiry_minutes')
                            ->label('OTP Code Expiry Duration (Minutes)')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->maxValue(60)
                            ->required(),

                        Textarea::make('otp_template')
                            ->label('OTP SMS Template')
                            ->helperText('Available variables: {site_name}, {otp}, {expiry}')
                            ->rows(3)
                            ->required(),
                    ]),

                Section::make('Interview Calling & Admit Card SMS Settings')
                    ->description('Default SMS template dispatched when calling shortlisted applicants for interview / trade test')
                    ->schema([
                        Textarea::make('interview_template')
                            ->label('Interview Notification Template')
                            ->helperText('Available variables: {name}, {job_title}, {date}, {time}, {venue}, {card_link}')
                            ->rows(4)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SmsSettings $settings): void
    {
        $state = $this->form->getState();

        $settings->is_enabled = (bool) ($state['is_enabled'] ?? true);
        $settings->api_url = $state['api_url'] ?? 'http://api.greenweb.com.bd/api.php';
        $settings->api_token = $state['api_token'] ?? null;
        $settings->sender_id = $state['sender_id'] ?? null;
        $settings->otp_expiry_minutes = (int) ($state['otp_expiry_minutes'] ?? 5);
        $settings->otp_template = $state['otp_template'] ?? $settings->otp_template;
        $settings->interview_template = $state['interview_template'] ?? $settings->interview_template;

        $settings->save();

        Notification::make()
            ->title('SMS Gateway Settings Saved Successfully')
            ->body('Credentials and notification templates updated.')
            ->success()
            ->send();
    }
}
