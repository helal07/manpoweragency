<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\JobApplication;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::with('jobCircular')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('applicant.applications.index', compact('applications'));
    }
    
    public function show(JobApplication $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }
        
        $application->load('jobCircular');
        return view('applicant.applications.show', compact('application'));
    }
}
