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
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('applicant.saved-jobs.index', compact('savedJobs'));
    }
}
