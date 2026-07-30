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
        
        $totalApplications = JobApplication::where('user_id', $user->id)->count();
        $underReview = JobApplication::where('user_id', $user->id)->whereIn('status', ['reviewed', 'shortlisted'])->count();
        $upcomingInterviews = JobApplication::where('user_id', $user->id)->where('status', 'interview')->count();
        $savedJobsCount = SavedJob::where('user_id', $user->id)->count();
        
        $recentApplications = JobApplication::with('jobCircular')
            ->where('user_id', $user->id)
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
