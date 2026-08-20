<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageFooterInfo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-share';

    protected static \UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Social Links & Footer Info';

    protected static ?string $title = 'Social Links & Footer Info';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user ? ($user->hasRole('super_admin', 'admin') || $user->can('manage_footer_info') || $user->can('manage_website_content')) : false;
    }

    public function mount(SiteSettings $settings): void
    {
        $this->form->fill([
            'facebook_url' => $settings->facebook_url,
            'linkedin_url' => $settings->linkedin_url,
            'twitter_url' => $settings->twitter_url,
            'footer_copyright' => $settings->footer_copyright,
            'about_teaser' => $settings->about_teaser,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Official Social Media Profiles')
                    ->description('Social channel links for company profiles')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('facebook_url')
                                ->label('Facebook Page URL')
                                ->url()
                                ->placeholder('https://facebook.com/your-agency')
                                ->nullable(),
                            TextInput::make('linkedin_url')
                                ->label('LinkedIn Company URL')
                                ->url()
                                ->placeholder('https://linkedin.com/company/your-agency')
                                ->nullable(),
                            TextInput::make('twitter_url')
                                ->label('Twitter / X Profile URL')
                                ->url()
                                ->placeholder('https://x.com/your-agency')
                                ->nullable(),
                        ]),
                    ]),

                Section::make('Footer Information & Homepage Summary')
                    ->description('Copyright line and brief company summary shown in footer and homepage teaser')
                    ->schema([
                        TextInput::make('footer_copyright')
                            ->label('Footer Copyright Line')
                            ->placeholder('© 2026 Global Manpower Overseas Ltd. All Rights Reserved.')
                            ->required(),
                        Textarea::make('about_teaser')
                            ->label('Company Brief Teaser (Home Page & Footer)')
                            ->rows(4)
                            ->placeholder('Government-approved recruiting agency supplying skilled, semi-skilled, and professional manpower...')
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SiteSettings $settings): void
    {
        $state = $this->form->getState();

        $settings->facebook_url = $state['facebook_url'] ?? $settings->facebook_url;
        $settings->linkedin_url = $state['linkedin_url'] ?? $settings->linkedin_url;
        $settings->twitter_url = $state['twitter_url'] ?? $settings->twitter_url;
        $settings->footer_copyright = $state['footer_copyright'] ?? $settings->footer_copyright;
        $settings->about_teaser = $state['about_teaser'] ?? $settings->about_teaser;

        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

        Notification::make()
            ->title('Social Links & Footer Settings Saved Successfully')
            ->success()
            ->send();
    }
}
