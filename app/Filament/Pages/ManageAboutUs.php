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

class ManageAboutUs extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-information-circle';

    protected static \UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'About Us Page';

    protected static ?string $title = 'About Us Page CMS';

    protected static ?int $navigationSort = 6;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user ? ($user->hasRole('super_admin', 'admin') || $user->can('manage_about_page') || $user->can('manage_website_content')) : false;
    }

    public function mount(SiteSettings $settings): void
    {
        $this->form->fill([
            'about_banner_title' => $settings->about_banner_title ?? ('About ' . ($settings->site_name ?? 'Global Manpower Overseas Ltd.')),
            'about_banner_subtitle' => $settings->about_banner_subtitle ?? 'Over 18 years of pioneering excellence in overseas human resource recruitment and ethical labor migration.',
            'about_story_title' => $settings->about_story_title ?? 'Who We Are',
            'about_story_p1' => $settings->about_story_p1 ?? ($settings->about_teaser ?? 'Global Manpower Overseas Ltd. is a premier government-licensed overseas recruitment agency in Bangladesh (RL-1452). Specializing in sourcing, trade-testing, and deploying skilled, semi-skilled, and professional personnel across the Middle East, Southeast Asia, and Europe.'),
            'about_story_p2' => $settings->about_story_p2 ?? 'With our state-of-the-art Trade Testing Center and experienced management team, we bridge the gap between Bangladeshi talent and international corporate demand, upholding the highest standards of worker welfare, legal compliance, and operational efficiency.',
            'about_mission_title' => $settings->about_mission_title ?? 'Our Mission',
            'about_mission_statement' => $settings->about_mission_statement ?? 'To empower Bangladeshi workforce with dignified, legal overseas employment while providing top-tier labor solutions to global employers.',
            'about_vision_title' => $settings->about_vision_title ?? 'Our Vision',
            'about_vision_statement' => $settings->about_vision_statement ?? 'To be the most ethical, transparent, and preferred overseas manpower consultancy in South Asia.',
            'about_accreditation_title' => $settings->about_accreditation_title ?? 'Government Accreditation',
            'about_accreditation_1' => $settings->about_accreditation_1 ?? 'Ministry of Expatriates\' Welfare & Overseas Employment Approved',
            'about_accreditation_2' => $settings->about_accreditation_2 ?? 'BAIRA (Bangladesh Association of International Recruiting Agencies) Member',
            'about_accreditation_3' => $settings->about_accreditation_3 ?? 'Authorized Embassy Enrolment Agency (Saudi Arabia, UAE, Qatar, Malaysia)',
            'about_accreditation_4' => $settings->about_accreditation_4 ?? 'ISO 9001:2015 Certified Quality Management System for Overseas Recruitment',
            'about_office_title' => $settings->about_office_title ?? 'Visit Our Corporate Office',
            'about_office_hours' => $settings->about_office_hours ?? 'Office Hours: Sat - Thu (09:00 AM - 06:00 PM)',
            'about_leadership_title' => $settings->about_leadership_title ?? 'Our Leadership Team',
            'about_leadership_subtitle' => $settings->about_leadership_subtitle ?? 'Board of Directors',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Header Banner')
                    ->description('Top banner heading and tagline displayed on /about')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('about_banner_title')
                                ->label('Header Banner Title')
                                ->required(),
                            TextInput::make('about_banner_subtitle')
                                ->label('Header Banner Subtitle / Tagline')
                                ->required(),
                        ]),
                    ]),

                Section::make('Company Story Section')
                    ->description('Who We Are description and background paragraphs')
                    ->schema([
                        TextInput::make('about_story_title')
                            ->label('Section Title (e.g. Who We Are)')
                            ->required(),
                        Textarea::make('about_story_p1')
                            ->label('Story Paragraph 1')
                            ->rows(3)
                            ->required(),
                        Textarea::make('about_story_p2')
                            ->label('Story Paragraph 2')
                            ->rows(3)
                            ->nullable(),
                    ]),

                Section::make('Mission & Vision Statements')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('about_mission_title')
                                ->label('Mission Card Title')
                                ->required(),
                            Textarea::make('about_mission_statement')
                                ->label('Mission Statement')
                                ->rows(3)
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('about_vision_title')
                                ->label('Vision Card Title')
                                ->required(),
                            Textarea::make('about_vision_statement')
                                ->label('Vision Statement')
                                ->rows(3)
                                ->required(),
                        ]),
                    ]),

                Section::make('Government Accreditations & Badges')
                    ->description('Accreditation bullet points displayed in the dark accreditation box')
                    ->schema([
                        TextInput::make('about_accreditation_title')
                            ->label('Accreditations Box Title')
                            ->required(),
                        Grid::make(2)->schema([
                            TextInput::make('about_accreditation_1')
                                ->label('Accreditation Point 1')
                                ->required(),
                            TextInput::make('about_accreditation_2')
                                ->label('Accreditation Point 2')
                                ->required(),
                            TextInput::make('about_accreditation_3')
                                ->label('Accreditation Point 3')
                                ->required(),
                            TextInput::make('about_accreditation_4')
                                ->label('Accreditation Point 4 (Optional)')
                                ->nullable(),
                        ]),
                    ]),

                Section::make('Corporate Office & Hours')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('about_office_title')
                                ->label('Corporate Office Box Title')
                                ->required(),
                            TextInput::make('about_office_hours')
                                ->label('Office Operating Hours')
                                ->required(),
                        ]),
                    ]),

                Section::make('Leadership Section Headings')
                    ->description('Manage individual leaders & directors under the "Leaders" menu in Website Content')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('about_leadership_title')
                                ->label('Leadership Section Title')
                                ->required(),
                            TextInput::make('about_leadership_subtitle')
                                ->label('Leadership Section Subtitle / Category')
                                ->required(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SiteSettings $settings): void
    {
        $state = $this->form->getState();

        $settings->about_banner_title = $state['about_banner_title'] ?? $settings->about_banner_title;
        $settings->about_banner_subtitle = $state['about_banner_subtitle'] ?? $settings->about_banner_subtitle;
        $settings->about_story_title = $state['about_story_title'] ?? $settings->about_story_title;
        $settings->about_story_p1 = $state['about_story_p1'] ?? $settings->about_story_p1;
        $settings->about_story_p2 = $state['about_story_p2'] ?? $settings->about_story_p2;
        $settings->about_mission_title = $state['about_mission_title'] ?? $settings->about_mission_title;
        $settings->about_mission_statement = $state['about_mission_statement'] ?? $settings->about_mission_statement;
        $settings->about_vision_title = $state['about_vision_title'] ?? $settings->about_vision_title;
        $settings->about_vision_statement = $state['about_vision_statement'] ?? $settings->about_vision_statement;
        $settings->about_accreditation_title = $state['about_accreditation_title'] ?? $settings->about_accreditation_title;
        $settings->about_accreditation_1 = $state['about_accreditation_1'] ?? $settings->about_accreditation_1;
        $settings->about_accreditation_2 = $state['about_accreditation_2'] ?? $settings->about_accreditation_2;
        $settings->about_accreditation_3 = $state['about_accreditation_3'] ?? $settings->about_accreditation_3;
        $settings->about_accreditation_4 = $state['about_accreditation_4'] ?? $settings->about_accreditation_4;
        $settings->about_office_title = $state['about_office_title'] ?? $settings->about_office_title;
        $settings->about_office_hours = $state['about_office_hours'] ?? $settings->about_office_hours;
        $settings->about_leadership_title = $state['about_leadership_title'] ?? $settings->about_leadership_title;
        $settings->about_leadership_subtitle = $state['about_leadership_subtitle'] ?? $settings->about_leadership_subtitle;

        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

        Notification::make()
            ->title('About Us Page Content Saved Successfully')
            ->success()
            ->send();
    }
}
