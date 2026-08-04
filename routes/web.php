<?php

use App\Http\Controllers\ProfileController;
use App\Models\Client;
use App\Models\HeroBanner;
use App\Models\JobCircular;
use App\Models\Leader;
use App\Models\Notice;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

// Route to optimize live server, clear all stale bootstrap/view caches, run migrations, and fix storage permissions
Route::get('/optimize', function () {
    $results = [];

    // 1. Clear all Artisan caches
    try {
        Artisan::call('optimize:clear');
        $results[] = '✅ optimize:clear executed';
    } catch (\Throwable $e) {
        $results[] = '⚠️ optimize:clear: ' . $e->getMessage();
    }

    // 2. Clear manual bootstrap cache files
    $bootstrapCache = base_path('bootstrap/cache');
    if (is_dir($bootstrapCache)) {
        foreach (glob($bootstrapCache . '/*.php') as $file) {
            @unlink($file);
        }
        $results[] = '✅ bootstrap/cache/*.php files wiped clean';
    }

    // 3. Clear compiled Blade views in storage/framework/views
    $viewsCache = storage_path('framework/views');
    if (is_dir($viewsCache)) {
        foreach (glob($viewsCache . '/*.php') as $file) {
            @unlink($file);
        }
        $results[] = '✅ storage/framework/views/*.php templates wiped clean';
    }

    // 4. Run database migrations
    try {
        Artisan::call('migrate', ['--force' => true]);
        $results[] = '✅ Database migrations applied: ' . trim(Artisan::output());
    } catch (\Throwable $e) {
        $results[] = '⚠️ Database migration: ' . $e->getMessage();
    }

    // 5. Fix storage permissions & establish symlink
    $storagePublic = storage_path('app/public');
    if (is_dir($storagePublic)) {
        @chmod(storage_path(), 0755);
        @chmod(storage_path('app'), 0755);
        @chmod($storagePublic, 0755);

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePublic, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $item) {
                if ($item->isDir()) {
                    @chmod($item->getPathname(), 0755);
                } else {
                    @chmod($item->getPathname(), 0644);
                }
            }
        } catch (\Throwable $e) {}
    }

    try {
        Artisan::call('storage:link');
        $results[] = '✅ Storage symlink verified';
    } catch (\Throwable $e) {
        $results[] = 'ℹ️ Storage symlink: ' . $e->getMessage();
    }

    // 6. Clear application global cache
    \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

    $html = '<div style="font-family: system-ui, sans-serif; max-width: 600px; margin: 50px auto; padding: 25px; border-radius: 12px; background: #0f172a; color: #f8fafc; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">';
    $html .= '<h2 style="color: #38bdf8; margin-top: 0;">🚀 Deployment & Cache Synchronization Complete</h2>';
    $html .= '<ul style="line-height: 1.8; padding-left: 20px;">';
    foreach ($results as $res) {
        $html .= '<li>' . htmlspecialchars($res) . '</li>';
    }
    $html .= '</ul>';
    $html .= '<div style="margin-top: 20px;"><a href="' . url('/admin') . '" style="display: inline-block; background: #0284c7; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Go to Admin Panel &rarr;</a></div>';
    $html .= '</div>';

    return response($html);
});

// Storage fallback route for shared hosts where Apache symlinks might be restricted or pending
Route::get('/storage/{path}', function ($path) {
    $disk = Storage::disk('public');
    if (!$disk->exists($path)) {
        abort(404);
    }
    return $disk->response($path);
})->where('path', '.*');

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
    $circular = JobCircular::with('customFields')->where('slug', $slug)->firstOrFail();
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
