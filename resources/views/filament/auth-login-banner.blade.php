@php
    $siteSettings = null;
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('app_settings')) {
            $siteSettings = app(\App\Settings\SiteSettings::class);
        }
    } catch (\Throwable $e) {}

    $siteName = $siteSettings->site_name ?? config('app.name', 'Global Manpower Overseas Ltd.');
    $siteTagline = $siteSettings->site_tagline ?? 'Government Approved Overseas Recruiting Agency';
    $showLicense = !empty($siteSettings->show_bmet_license) && !empty($siteSettings->bmet_license_no);
    $licenseNo = $siteSettings->bmet_license_no ?? '';
@endphp

<div style="margin-bottom: 1.5rem; padding: 1.25rem; border-radius: 1.25rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(217, 119, 6, 0.5); text-align: center; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.35);">
    <div style="position: absolute; inset: 0; background-image: url('/images/agency_employees.png'); background-size: cover; background-position: center; opacity: 0.18; pointer-events: none;"></div>
    
    <!-- Dynamic Brand Name -->
    <div style="font-size: 1.25rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.4rem; line-height: 1.3;">
        <span style="color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">{{ $siteName }}</span>
    </div>

    @if($showLicense)
    <!-- Dynamic Gold & Navy License Badge (Only shown when enabled in backend) -->
    <div style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.875rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background-color: #020617; color: #fbbf24; border: 1px solid #d97706; box-shadow: 0 2px 4px rgba(0,0,0,0.4); margin-bottom: 0.35rem;">
        <span style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #f59e0b; box-shadow: 0 0 8px #f59e0b;"></span>
        Govt. License: {{ $licenseNo }}
    </div>
    @endif

    @if(!empty($siteTagline))
    <div style="font-size: 0.75rem; font-weight: 600; color: #94a3b8;">{{ $siteTagline }}</div>
    @endif
</div>
