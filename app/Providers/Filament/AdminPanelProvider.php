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
                fn (): string => '<link rel="stylesheet" href="' . asset('css/filament-custom.css') . '">'
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('
                    <div style="margin-bottom: 1.5rem; padding: 1.25rem; border-radius: 1.25rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(217, 119, 6, 0.5); text-align: center; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.35);">
                        <div style="position: absolute; inset: 0; background-image: url(\'/images/agency_employees.png\'); background-size: cover; background-position: center; opacity: 0.18; pointer-events: none;"></div>
                        
                        <!-- Dual-Tone Navy, Gold & White Brand Name -->
                        <div style="font-size: 1.25rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.4rem; line-height: 1.3;">
                            <span style="color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">GLOBAL MANPOWER </span>
                            <span style="color: #f59e0b; text-shadow: 0 0 12px rgba(245, 158, 11, 0.6);">OVERSEAS LTD.</span>
                        </div>

                        <!-- Gold & Navy License Badge -->
                        <div style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.875rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background-color: #020617; color: #fbbf24; border: 1px solid #d97706; box-shadow: 0 2px 4px rgba(0,0,0,0.4); margin-bottom: 0.35rem;">
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #f59e0b; box-shadow: 0 0 8px #f59e0b;"></span>
                            Govt. License: RL-1452
                        </div>
                        <div style="font-size: 0.75rem; font-weight: 600; color: #94a3b8;">Government Approved Overseas Recruiting Agency</div>
                    </div>
                ')
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
