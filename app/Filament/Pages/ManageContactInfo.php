<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageContactInfo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-phone';

    protected static \UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Contact Info & License';

    protected static ?string $title = 'Contact Info & Govt. License';

    protected static ?int $navigationSort = 7;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user ? ($user->hasRole('super_admin', 'admin') || $user->can('manage_contact_info') || $user->can('manage_website_content')) : false;
    }

    public function mount(SiteSettings $settings): void
    {
        $this->form->fill([
            'phone' => $settings->phone,
            'email' => $settings->email,
            'address' => $settings->address,
            'bmet_license_no' => $settings->bmet_license_no,
            'show_bmet_license' => $settings->show_bmet_license ?? true,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Public Contact Information')
                    ->description('Contact channels displayed in header, footer, contact widgets, and About page')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('phone')
                                ->label('Official Phone Number')
                                ->placeholder('+880 2-9876543')
                                ->required(),
                            TextInput::make('email')
                                ->label('Official Email Address')
                                ->email()
                                ->placeholder('info@globalmanpower.com')
                                ->required(),
                        ]),
                        Textarea::make('address')
                            ->label('Head Office Physical Address')
                            ->rows(3)
                            ->placeholder('Banani, Dhaka, Bangladesh')
                            ->required(),
                    ]),

                Section::make('Government Recruiting License & Verification')
                    ->description('Govt. License number and visibility controls on header, footer, homepage, and details')
                    ->schema([
                        TextInput::make('bmet_license_no')
                            ->label('BMET / Govt. Recruiting License No.')
                            ->placeholder('RL-1452')
                            ->required(),
                        Toggle::make('show_bmet_license')
                            ->label('Show License Badge on Site (Header, Footer, Home Page & Portal)')
                            ->helperText('Toggle on or off to show or hide the government recruiting license badge across header, footer, and pages.')
                            ->default(true),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SiteSettings $settings): void
    {
        $state = $this->form->getState();

        $settings->phone = $state['phone'] ?? $settings->phone;
        $settings->email = $state['email'] ?? $settings->email;
        $settings->address = $state['address'] ?? $settings->address;
        $settings->bmet_license_no = $state['bmet_license_no'] ?? $settings->bmet_license_no;
        $settings->show_bmet_license = (bool) ($state['show_bmet_license'] ?? false);

        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

        Notification::make()
            ->title('Contact & License Settings Saved Successfully')
            ->success()
            ->send();
    }
}
