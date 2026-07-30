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

        if (Schema::hasTable('app_settings')) {
            try {
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
            } catch (\Throwable $e) {
                // Fallback default
            }
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('admin')
            ->brandName($siteName)
            ->brandLogo($logoUrl)
            ->favicon($faviconUrl)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
                    <style>
                        body.fi-body-simple, .fi-simple-layout {
                            font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
                            -webkit-font-smoothing: antialiased !important;
                            -moz-osx-font-smoothing: grayscale !important;
                            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.35), rgba(15, 23, 42, 0.45)), url("/images/airplane_bg.png") no-repeat center center fixed !important;
                            background-size: cover !important;
                            min-height: 100vh !important;
                            width: 100% !important;
                        }
                        .fi-simple-main, .fi-simple-main-ctn, div.fi-simple-main-ctn {
                            background-color: rgba(255, 255, 255, 0.96) !important;
                            border: 1px solid rgba(255, 255, 255, 0.9) !important;
                            border-radius: 1.5rem !important;
                            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.45) !important;
                            backdrop-filter: blur(16px) !important;
                        }

                        /* Navy Blue & Gold Header Brand Name (Top Header & Sidebar) */
                        .fi-logo, .fi-brand, .fi-sidebar-header span, .fi-simple-header div, .fi-simple-header a, .fi-simple-header {
                            font-size: 1.5rem !important;
                            font-weight: 800 !important;
                            color: #b45309 !important;
                            background: linear-gradient(135deg, #1e3a8a 0%, #d97706 60%, #f59e0b 100%) !important;
                            -webkit-background-clip: text !important;
                            -webkit-text-fill-color: transparent !important;
                            letter-spacing: -0.02em !important;
                            filter: drop-shadow(0px 2px 4px rgba(15, 23, 42, 0.5)) !important;
                        }

                        .fi-simple-header-heading, .fi-header-heading, h1, h2 {
                            color: #0f172a !important;
                            font-weight: 800 !important;
                            letter-spacing: -0.02em !important;
                        }
                        .fi-simple-header-subheading, p {
                            color: #475569 !important;
                        }
                        label, label span {
                            color: #1e293b !important;
                            font-weight: 600 !important;
                        }
                        input[type="email"], input[type="password"] {
                            background-color: #ffffff !important;
                            color: #0f172a !important;
                            border: 1px solid #cbd5e1 !important;
                            border-radius: 0.75rem !important;
                            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                        }
                        input[type="email"]:focus, input[type="password"]:focus {
                            border-color: #d97706 !important;
                            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.2) !important;
                        }
                        button[type="submit"] {
                            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%) !important;
                            color: #f59e0b !important;
                            border: 1px solid #d97706 !important;
                            border-radius: 0.75rem !important;
                            font-weight: 800 !important;
                            letter-spacing: 0.02em !important;
                            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3) !important;
                        }
                        button[type="submit"]:hover {
                            background: linear-gradient(135deg, #0f172a 0%, #020617 100%) !important;
                            color: #fbbf24 !important;
                        }
                        svg {
                            max-width: 100%;
                        }
                    </style>
                ')
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
