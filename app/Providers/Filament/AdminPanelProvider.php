<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\AgencyStatsOverview;
use App\Filament\Widgets\LatestJobCircularsWidget;
use App\Filament\Widgets\RecruitmentOverviewChart;
use App\Settings\SiteSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $siteName = 'Global Manpower Overseas Ltd.';
        $logoUrl = null;
        $faviconUrl = null;

        try {
            if (Schema::hasTable('app_settings')) {
                $siteSettings = app(SiteSettings::class);
                if (!empty($siteSettings->site_name)) {
                    $siteName = $siteSettings->site_name;
                }
                if (!empty($siteSettings->logo_path)) {
                    $logoUrl = asset('storage/' . $siteSettings->logo_path);
                }
                if (!empty($siteSettings->favicon_path)) {
                    $faviconUrl = asset('storage/' . $siteSettings->favicon_path);
                }
            }
        } catch (\Throwable $e) {
            // Fallback default branding gracefully if database is not reachable during CLI commands
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile(\App\Filament\Pages\Auth\EditProfile::class, isSimple: false)
            ->authGuard('admin')
            ->brandName($siteName)
            ->brandLogo($logoUrl)
            ->brandLogoHeight('2.85rem')
            ->favicon($faviconUrl)
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => view('filament.topbar-actions')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<link rel="stylesheet" href="' . asset('css/filament-custom.css') . '?v=' . (file_exists(public_path('css/filament-custom.css')) ? filemtime(public_path('css/filament-custom.css')) : time()) . '">'
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn () => view('filament.auth-login-banner')
            )
            ->navigationGroups([
                'Website Content',
                'Recruitment',
                'Administration',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AgencyStatsOverview::class,
                RecruitmentOverviewChart::class,
                LatestJobCircularsWidget::class,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::FOOTER,
                fn () => view('filament.footer'),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
