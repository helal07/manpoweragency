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
            ->where('applicant_id', auth()->id())
            ->latest()
            ->get();
            
        return view('applicant.applications.index', compact('applications'));
    }
    
    public function show(JobApplication $application)
    {
        if ($application->applicant_id !== auth()->id()) {
            abort(403);
        }
        
        $application->load('jobCircular');
        return view('applicant.applications.show', compact('application'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'job_circular_id' => 'required|exists:job_circulars,id',
        ]);
        
        // Ensure they haven't applied already
        $existing = JobApplication::where('applicant_id', auth()->id())
            ->where('job_circular_id', $request->job_circular_id)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'You have already applied for this position.');
        }

        JobApplication::create([
            'applicant_id' => auth()->id(),
            'job_circular_id' => $request->job_circular_id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your application has been submitted successfully!');
    }
}
