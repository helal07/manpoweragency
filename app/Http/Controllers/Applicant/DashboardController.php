<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobApplication;
use App\Models\SavedJob;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $totalApplications = JobApplication::where('applicant_id', $user->id)->count();
        $underReview = JobApplication::where('applicant_id', $user->id)->whereIn('status', ['reviewed', 'shortlisted'])->count();
        $upcomingInterviews = JobApplication::where('applicant_id', $user->id)->where('status', 'interview')->count();
        $savedJobsCount = SavedJob::where('applicant_id', $user->id)->count();
        
        $recentApplications = JobApplication::with('jobCircular')
            ->where('applicant_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        return view('dashboard', compact(
            'totalApplications',
            'underReview',
            'upcomingInterviews',
            'savedJobsCount',
            'recentApplications'
        ));
    }
}
