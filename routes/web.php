<?php

use App\Http\Controllers\ProfileController;
use App\Models\Client;
use App\Models\HeroBanner;
use App\Models\JobCircular;
use App\Models\Leader;
use App\Models\Notice;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

// Public Site Routes
Route::get('/', function () {
    $heroBanners = HeroBanner::where('is_active', true)->orderBy('order')->get();
    $featuredCirculars = JobCircular::where('status', 'open')->latest()->take(4)->get();
    $latestNotices = Notice::orderBy('is_pinned', 'desc')->latest()->take(3)->get();
    $services = Service::orderBy('order')->get();
    $clients = Client::orderBy('order')->take(8)->get();
    $leaders = Leader::orderBy('order')->get();

    return view('site.home', compact('heroBanners', 'featuredCirculars', 'latestNotices', 'services', 'clients', 'leaders'));
})->name('home');

Route::get('/about', function () {
    $leaders = Leader::orderBy('order')->get();
    return view('site.about', compact('leaders'));
})->name('about');

Route::get('/clients', function () {
    $clients = Client::orderBy('order')->get();
    return view('site.clients', compact('clients'));
})->name('clients');

Route::get('/services', function () {
    $services = Service::orderBy('order')->get();
    return view('site.services', compact('services'));
})->name('services');

Route::get('/job-circulars', function () {
    $circulars = JobCircular::latest()->get();
    return view('site.circulars', compact('circulars'));
})->name('circulars.index');

Route::get('/job-circulars/{slug}', function ($slug) {
    $circular = JobCircular::where('slug', $slug)->firstOrFail();
    return view('site.circular-detail', compact('circular'));
})->name('circulars.show');

Route::get('/notices', function () {
    $notices = Notice::orderBy('is_pinned', 'desc')->latest()->get();
    return view('site.notices', compact('notices'));
})->name('notices.index');

// Applicant Auth Dashboard
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Applicant\DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes (existing Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Applications
    Route::get('/applications', [\App\Http\Controllers\Applicant\ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/applications', [\App\Http\Controllers\Applicant\ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [\App\Http\Controllers\Applicant\ApplicationController::class, 'show'])->name('applications.show');
    
    // Saved Jobs
    Route::get('/saved-jobs', [\App\Http\Controllers\Applicant\SavedJobController::class, 'index'])->name('saved-jobs.index');
    Route::post('/saved-jobs', [\App\Http\Controllers\Applicant\SavedJobController::class, 'store'])->name('saved-jobs.store');
    Route::delete('/saved-jobs', [\App\Http\Controllers\Applicant\SavedJobController::class, 'destroy'])->name('saved-jobs.destroy');
    
    // Security
    Route::get('/security', function() {
        return view('profile.security'); // We'll create this view
    })->name('security.edit');
});

require __DIR__.'/auth.php';
