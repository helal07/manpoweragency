<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SavedJob;

class SavedJobController extends Controller
{
    public function index()
    {
        $savedJobs = SavedJob::with('jobCircular')
            ->where('applicant_id', auth()->id())
            ->latest()
            ->get();
            
        return view('applicant.saved-jobs.index', compact('savedJobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_circular_id' => 'required|exists:job_circulars,id',
        ]);

        SavedJob::firstOrCreate([
            'applicant_id' => auth()->id(),
            'job_circular_id' => $request->job_circular_id,
        ]);

        return back()->with('success', 'Job saved successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'job_circular_id' => 'required|exists:job_circulars,id',
        ]);

        SavedJob::where('applicant_id', auth()->id())
            ->where('job_circular_id', $request->job_circular_id)
            ->delete();

        return back()->with('success', 'Job removed from saved list.');
    }
}
