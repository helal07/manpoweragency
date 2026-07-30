<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms\Components\FileUpload;
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

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static \UnitEnum|string|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user ? ($user->hasRole('super_admin') || str_ends_with($user->email, '@admin.com')) : false;
    }

    public function mount(SiteSettings $settings): void
    {
        $this->form->fill([
            'site_name' => $settings->site_name,
            'site_tagline' => $settings->site_tagline,
            'logo_path' => $settings->logo_path,
            'favicon_path' => $settings->favicon_path,
            'phone' => $settings->phone,
            'email' => $settings->email,
            'address' => $settings->address,
            'bmet_license_no' => $settings->bmet_license_no,
            'show_bmet_license' => $settings->show_bmet_license ?? true,
            'facebook_url' => $settings->facebook_url,
            'linkedin_url' => $settings->linkedin_url,
            'twitter_url' => $settings->twitter_url,
            'footer_copyright' => $settings->footer_copyright,
            'about_teaser' => $settings->about_teaser,

            'nav_home_label' => $settings->nav_home_label ?? 'Home',
            'nav_about_label' => $settings->nav_about_label ?? 'About',
            'nav_clients_label' => $settings->nav_clients_label ?? 'Clients',
            'nav_services_label' => $settings->nav_services_label ?? 'Services',
            'nav_circulars_label' => $settings->nav_circulars_label ?? 'Job Circular',
            'nav_notices_label' => $settings->nav_notices_label ?? 'Notice',
            'nav_login_label' => $settings->nav_login_label ?? 'Applicant Login',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Branding & Identity')
                    ->description('Dynamic site name, tagline, logo, and favicon')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('site_name')
                                ->label('Site Name')
                                ->required(),
                            TextInput::make('site_tagline')
                                ->label('Site Tagline')
                                ->nullable(),
                        ]),
                        FileUpload::make('logo_path')
                            ->label('Site Logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->nullable(),
                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->nullable(),
                    ]),

                Section::make('Navigation Menu Custom Labels')
                    ->description('Customize top menu labels displayed on both frontend and backend')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('nav_home_label')->label('Home Menu Label')->required(),
                            TextInput::make('nav_about_label')->label('About Menu Label')->required(),
                            TextInput::make('nav_clients_label')->label('Clients Menu Label')->required(),
                            TextInput::make('nav_services_label')->label('Services Menu Label')->required(),
                            TextInput::make('nav_circulars_label')->label('Job Circular Menu Label')->required(),
                            TextInput::make('nav_notices_label')->label('Notice Menu Label')->required(),
                        ]),
                        TextInput::make('nav_login_label')->label('Applicant Login Button Label')->required(),
                    ]),

                Section::make('Company Contact Info & License')
                    ->description('Public contact details displayed in header & footer')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->required(),
                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required(),
                        ]),
                        Textarea::make('address')
                            ->label('Office Address')
                            ->rows(2)
                            ->required(),
                        TextInput::make('bmet_license_no')
                            ->label('BMET / Govt. Recruiting License No.')
                            ->required(),
                        Toggle::make('show_bmet_license')
                            ->label('Show License Badge on Home Page')
                            ->default(true),
                    ]),

                Section::make('Social Links & Footer Info')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('facebook_url')
                                ->label('Facebook URL')
                                ->url()
                                ->nullable(),
                            TextInput::make('linkedin_url')
                                ->label('LinkedIn URL')
                                ->url()
                                ->nullable(),
                            TextInput::make('twitter_url')
                                ->label('Twitter / X URL')
                                ->url()
                                ->nullable(),
                        ]),
                        TextInput::make('footer_copyright')
                            ->label('Footer Copyright Line')
                            ->required(),
                        Textarea::make('about_teaser')
                            ->label('About Us Teaser')
                            ->rows(3)
                            ->nullable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SiteSettings $settings): void
    {
        $state = $this->form->getState();

        $settings->site_name = $state['site_name'] ?? $settings->site_name;
        $settings->site_tagline = $state['site_tagline'] ?? $settings->site_tagline;
        $settings->logo_path = $state['logo_path'] ?? $settings->logo_path;
        $settings->favicon_path = $state['favicon_path'] ?? $settings->favicon_path;
        $settings->phone = $state['phone'] ?? $settings->phone;
        $settings->email = $state['email'] ?? $settings->email;
        $settings->address = $state['address'] ?? $settings->address;
        $settings->bmet_license_no = $state['bmet_license_no'] ?? $settings->bmet_license_no;
        $settings->show_bmet_license = $state['show_bmet_license'] ?? $settings->show_bmet_license;
        $settings->facebook_url = $state['facebook_url'] ?? $settings->facebook_url;
        $settings->linkedin_url = $state['linkedin_url'] ?? $settings->linkedin_url;
        $settings->twitter_url = $state['twitter_url'] ?? $settings->twitter_url;
        $settings->footer_copyright = $state['footer_copyright'] ?? $settings->footer_copyright;
        $settings->about_teaser = $state['about_teaser'] ?? $settings->about_teaser;

        $settings->nav_home_label = $state['nav_home_label'] ?? $settings->nav_home_label;
        $settings->nav_about_label = $state['nav_about_label'] ?? $settings->nav_about_label;
        $settings->nav_clients_label = $state['nav_clients_label'] ?? $settings->nav_clients_label;
        $settings->nav_services_label = $state['nav_services_label'] ?? $settings->nav_services_label;
        $settings->nav_circulars_label = $state['nav_circulars_label'] ?? $settings->nav_circulars_label;
        $settings->nav_notices_label = $state['nav_notices_label'] ?? $settings->nav_notices_label;
        $settings->nav_login_label = $state['nav_login_label'] ?? $settings->nav_login_label;

        $settings->save();

        Notification::make()
            ->title('Site Settings Saved Successfully')
            ->success()
            ->send();
    }
}
