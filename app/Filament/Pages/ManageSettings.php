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

    protected static ?string $navigationLabel = 'Site Settings & Branding';

    protected static ?string $title = 'Site Settings & Branding';

    protected static ?int $navigationSort = 2;

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
                    ->description('Dynamic company site name, tagline, logo, and browser favicon')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('site_name')
                                ->label('Site Name')
                                ->placeholder('Global Manpower Overseas Ltd.')
                                ->required(),
                            TextInput::make('site_tagline')
                                ->label('Site Tagline')
                                ->placeholder('Connecting Skilled Talent with Global Opportunities')
                                ->nullable(),
                        ]),
                        FileUpload::make('logo_path')
                            ->label('Site Logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->nullable(),
                        FileUpload::make('favicon_path')
                            ->label('Browser Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->nullable(),
                    ]),

                Section::make('Navigation Menu Custom Labels')
                    ->description('Customize main header navigation menu labels displayed across the website')
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

        $settings->nav_home_label = $state['nav_home_label'] ?? $settings->nav_home_label;
        $settings->nav_about_label = $state['nav_about_label'] ?? $settings->nav_about_label;
        $settings->nav_clients_label = $state['nav_clients_label'] ?? $settings->nav_clients_label;
        $settings->nav_services_label = $state['nav_services_label'] ?? $settings->nav_services_label;
        $settings->nav_circulars_label = $state['nav_circulars_label'] ?? $settings->nav_circulars_label;
        $settings->nav_notices_label = $state['nav_notices_label'] ?? $settings->nav_notices_label;
        $settings->nav_login_label = $state['nav_login_label'] ?? $settings->nav_login_label;

        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

        Notification::make()
            ->title('Site Settings & Branding Saved Successfully')
            ->success()
            ->send();
    }
}
