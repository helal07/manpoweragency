<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Settings\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterviewCardController extends Controller
{
    public function show(string $token): View
    {
        $application = JobApplication::with(['applicant', 'jobCircular', 'customFieldValues.customField'])
            ->where('admit_card_token', $token)
            ->firstOrFail();

        $siteSettings = null;
        try {
            $siteSettings = app(SiteSettings::class);
        } catch (\Throwable) {}

        return view('site.interview-card', [
            'application' => $application,
            'applicant' => $application->applicant,
            'circular' => $application->jobCircular,
            'siteSettings' => $siteSettings,
        ]);
    }
}
