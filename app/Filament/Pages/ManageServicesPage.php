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

class ManageServicesPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-queue-list';

    protected static \UnitEnum|string|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Services Page & Workflow';

    protected static ?string $title = 'Services Page & Workflow CMS';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user ? ($user->hasRole('super_admin', 'admin') || $user->can('manage_services_page') || $user->can('manage_website_content')) : false;
    }

    public function mount(SiteSettings $settings): void
    {
        $this->form->fill([
            'services_banner_tag' => $settings->services_banner_tag ?? 'Our Solutions',
            'services_banner_title' => $settings->services_banner_title ?? 'Overseas Recruitment & Mobility Services',
            'services_banner_desc' => $settings->services_banner_desc ?? 'End-to-end solutions for foreign employer companies and Bangladeshi job seekers.',

            'services_workflow_subtitle' => $settings->services_workflow_subtitle ?? 'Step-By-Step Workflow',
            'services_workflow_title' => $settings->services_workflow_title ?? 'How We Deploy Manpower',

            'services_workflow_step1_title' => $settings->services_workflow_step1_title ?? '01. Demand Receipt',
            'services_workflow_step1_desc' => $settings->services_workflow_step1_desc ?? 'Employer posts visa demand order with embassy endorsement.',

            'services_workflow_step2_title' => $settings->services_workflow_step2_title ?? '02. Screening & Test',
            'services_workflow_step2_desc' => $settings->services_workflow_step2_desc ?? 'Shortlisting and trade testing at certified technical workshops.',

            'services_workflow_step3_title' => $settings->services_workflow_step3_title ?? '03. Medical & Visa',
            'services_workflow_step3_desc' => $settings->services_workflow_step3_desc ?? 'GAMCA medical checkup, MOFA visa stamping & BMET Smart Card.',

            'services_workflow_step4_title' => $settings->services_workflow_step4_title ?? '04. Flight Departure',
            'services_workflow_step4_desc' => $settings->services_workflow_step4_desc ?? 'Pre-departure briefing, airline ticket issue & airport assistance.',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Services Page Header Banner')
                    ->description('Heading, category badge, and subtitle displayed at the top of /services')
                    ->schema([
                        TextInput::make('services_banner_tag')
                            ->label('Top Tag / Badge Label')
                            ->placeholder('Our Solutions')
                            ->required(),
                        TextInput::make('services_banner_title')
                            ->label('Banner Main Title')
                            ->placeholder('Overseas Recruitment & Mobility Services')
                            ->required(),
                        Textarea::make('services_banner_desc')
                            ->label('Banner Subtitle / Description')
                            ->placeholder('End-to-end solutions for foreign employer companies and Bangladeshi job seekers.')
                            ->rows(2)
                            ->required(),
                    ]),

                Section::make('Step-By-Step Manpower Deployment Workflow')
                    ->description('Headings and step cards for "How We Deploy Manpower" section on /services')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('services_workflow_subtitle')
                                ->label('Workflow Category Subtitle')
                                ->placeholder('Step-By-Step Workflow')
                                ->required(),
                            TextInput::make('services_workflow_title')
                                ->label('Workflow Main Heading')
                                ->placeholder('How We Deploy Manpower')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            Section::make('Step 01 Card')
                                ->schema([
                                    TextInput::make('services_workflow_step1_title')
                                        ->label('Step 1 Title')
                                        ->placeholder('01. Demand Receipt')
                                        ->required(),
                                    Textarea::make('services_workflow_step1_desc')
                                        ->label('Step 1 Description')
                                        ->rows(2)
                                        ->required(),
                                ]),

                            Section::make('Step 02 Card')
                                ->schema([
                                    TextInput::make('services_workflow_step2_title')
                                        ->label('Step 2 Title')
                                        ->placeholder('02. Screening & Test')
                                        ->required(),
                                    Textarea::make('services_workflow_step2_desc')
                                        ->label('Step 2 Description')
                                        ->rows(2)
                                        ->required(),
                                ]),
                        ]),

                        Grid::make(2)->schema([
                            Section::make('Step 03 Card')
                                ->schema([
                                    TextInput::make('services_workflow_step3_title')
                                        ->label('Step 3 Title')
                                        ->placeholder('03. Medical & Visa')
                                        ->required(),
                                    Textarea::make('services_workflow_step3_desc')
                                        ->label('Step 3 Description')
                                        ->rows(2)
                                        ->required(),
                                ]),

                            Section::make('Step 04 Card')
                                ->schema([
                                    TextInput::make('services_workflow_step4_title')
                                        ->label('Step 4 Title')
                                        ->placeholder('04. Flight Departure')
                                        ->required(),
                                    Textarea::make('services_workflow_step4_desc')
                                        ->label('Step 4 Description')
                                        ->rows(2)
                                        ->required(),
                                ]),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SiteSettings $settings): void
    {
        $state = $this->form->getState();

        $settings->services_banner_tag = $state['services_banner_tag'] ?? $settings->services_banner_tag;
        $settings->services_banner_title = $state['services_banner_title'] ?? $settings->services_banner_title;
        $settings->services_banner_desc = $state['services_banner_desc'] ?? $settings->services_banner_desc;

        $settings->services_workflow_subtitle = $state['services_workflow_subtitle'] ?? $settings->services_workflow_subtitle;
        $settings->services_workflow_title = $state['services_workflow_title'] ?? $settings->services_workflow_title;

        $settings->services_workflow_step1_title = $state['services_workflow_step1_title'] ?? $settings->services_workflow_step1_title;
        $settings->services_workflow_step1_desc = $state['services_workflow_step1_desc'] ?? $settings->services_workflow_step1_desc;

        $settings->services_workflow_step2_title = $state['services_workflow_step2_title'] ?? $settings->services_workflow_step2_title;
        $settings->services_workflow_step2_desc = $state['services_workflow_step2_desc'] ?? $settings->services_workflow_step2_desc;

        $settings->services_workflow_step3_title = $state['services_workflow_step3_title'] ?? $settings->services_workflow_step3_title;
        $settings->services_workflow_step3_desc = $state['services_workflow_step3_desc'] ?? $settings->services_workflow_step3_desc;

        $settings->services_workflow_step4_title = $state['services_workflow_step4_title'] ?? $settings->services_workflow_step4_title;
        $settings->services_workflow_step4_desc = $state['services_workflow_step4_desc'] ?? $settings->services_workflow_step4_desc;

        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

        Notification::make()
            ->title('Services Page & Workflow Saved Successfully')
            ->success()
            ->send();
    }
}
