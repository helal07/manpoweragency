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

// Route to run database migrations, seed permissions, and clear caches directly via browser
Route::get('/run-migrations', function () {
    $results = [];

    // 1. Run database migrations
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = trim(Artisan::output());
        $results[] = '✅ Database Migrations: ' . ($output ?: 'Database is already up to date.');
    } catch (\Throwable $e) {
        $results[] = '⚠️ Database Migration Error: ' . $e->getMessage();
    }

    // 2. Seed Spatie RBAC Permissions & Default Roles
    try {
        Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
        $results[] = '✅ Roles & Permissions: Successfully synced and seeded.';
    } catch (\Throwable $e) {
        $results[] = '⚠️ Permission Seeder Error: ' . $e->getMessage();
    }

    // 3. Clear all caches
    try {
        Artisan::call('optimize:clear');
        $results[] = '✅ optimize:clear executed';
    } catch (\Throwable $e) {
        $results[] = '⚠️ optimize:clear: ' . $e->getMessage();
    }

    // 4. Clear compiled views & settings cache
    $viewsCache = storage_path('framework/views');
    if (is_dir($viewsCache)) {
        foreach (glob($viewsCache . '/*.php') as $file) {
            @unlink($file);
        }
        $results[] = '✅ Compiled Blade views wiped clean';
    }
    \Illuminate\Support\Facades\Cache::forget('site_settings_global_cache');

    $html = '<div style="font-family: system-ui, sans-serif; max-width: 650px; margin: 50px auto; padding: 30px; border-radius: 16px; background: #0f172a; color: #f8fafc; box-shadow: 0 15px 35px rgba(0,0,0,0.5); border: 1px solid rgba(56, 189, 248, 0.2);">';
    $html .= '<h2 style="color: #38bdf8; margin-top: 0; font-size: 1.5rem;">🚀 Database Migrations & Permissions Synced</h2>';
    $html .= '<ul style="line-height: 2; padding-left: 20px; font-size: 0.95rem;">';
    foreach ($results as $res) {
        $html .= '<li>' . htmlspecialchars($res) . '</li>';
    }
    $html .= '</ul>';
    $html .= '<div style="margin-top: 25px; display: flex; gap: 12px;">';
    $html .= '<a href="' . url('/admin') . '" style="display: inline-block; background: #0284c7; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Go to Admin Panel &rarr;</a>';
    $html .= '<a href="' . url('/') . '" style="display: inline-block; background: #334155; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Visit Website &rarr;</a>';
    $html .= '</div>';
    $html .= '</div>';

    return response($html);
});

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

// Route to diagnose and clean up server disk space usage (logs, sessions, caches, temp files)
Route::get('/cleanup', function (\Illuminate\Http\Request $request) {
    $action = $request->query('action');
    $messages = [];

    // Helper to calculate folder size
    $getDirSize = function ($dir) {
        if (!is_dir($dir)) return 0;
        $size = 0;
        try {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        } catch (\Throwable $e) {}
        return $size;
    };

    $formatBytes = function ($bytes) {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    };

    if ($action === 'purge') {
        $freedBytes = 0;

        // 1. Clean logs
        $logDir = storage_path('logs');
        if (is_dir($logDir)) {
            foreach (glob($logDir . '/*.log') as $file) {
                $freedBytes += @filesize($file);
                @unlink($file);
            }
            $messages[] = '✅ Cleaned storage/logs/*.log files';
        }

        // 2. Clean cPanel error_log in root and public
        foreach ([base_path('error_log'), public_path('error_log'), base_path('../error_log')] as $errFile) {
            if (file_exists($errFile)) {
                $freedBytes += @filesize($errFile);
                @unlink($errFile);
                $messages[] = '✅ Removed ' . basename($errFile) . ' from ' . dirname($errFile);
            }
        }

        // 3. Clean stale session files
        $sessionDir = storage_path('framework/sessions');
        $sessionCount = 0;
        if (is_dir($sessionDir)) {
            $threshold = time() - (86400 * 3); // older than 3 days
            foreach (glob($sessionDir . '/*') as $file) {
                if (is_file($file) && basename($file) !== '.gitignore' && filemtime($file) < $threshold) {
                    $freedBytes += @filesize($file);
                    @unlink($file);
                    $sessionCount++;
                }
            }
            $messages[] = "✅ Purged {$sessionCount} stale session files (>3 days old)";
        }

        // 4. Clean compiled views & bootstrap caches
        $viewsDir = storage_path('framework/views');
        if (is_dir($viewsDir)) {
            foreach (glob($viewsDir . '/*.php') as $file) {
                $freedBytes += @filesize($file);
                @unlink($file);
            }
        }
        $messages[] = '✅ Cleared compiled Blade view caches';

        // 5. Artisan clear
        try {
            Artisan::call('optimize:clear');
            $messages[] = '✅ Executed optimize:clear';
        } catch (\Throwable $e) {}

        $messages[] = '🎉 Total Space Freed: ' . $formatBytes($freedBytes);
    }

    // Inspect current sizes
    $stats = [
        'Storage Logs (`storage/logs`)' => $getDirSize(storage_path('logs')),
        'Session Files (`storage/framework/sessions`)' => $getDirSize(storage_path('framework/sessions')),
        'View Caches (`storage/framework/views`)' => $getDirSize(storage_path('framework/views')),
        'App Cache (`storage/framework/cache`)' => $getDirSize(storage_path('framework/cache')),
        'Public Uploads (`storage/app/public`)' => $getDirSize(storage_path('app/public')),
        'Node Modules (`node_modules`)' => is_dir(base_path('node_modules')) ? $getDirSize(base_path('node_modules')) : 0,
        'Git Repository (`.git`)' => is_dir(base_path('.git')) ? $getDirSize(base_path('.git')) : 0,
    ];

    $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>cPanel Disk Usage & Cleanup</title><style>body{font-family:system-ui,-apple-system,sans-serif;background:#090d16;color:#f1f5f9;margin:0;padding:20px}table{width:100%;border-collapse:collapse;margin:15px 0}th,td{padding:12px 16px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.08)}th{background:rgba(255,255,255,0.04);color:#94a3b8;font-size:12px;text-transform:uppercase}tr:hover{background:rgba(255,255,255,0.02)}.btn{display:inline-block;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:bold;cursor:pointer;border:none;font-size:14px;transition:all 0.2s}.btn-danger{background:#dc2626;color:white}.btn-danger:hover{background:#b91c1c}.btn-secondary{background:#334155;color:white}.btn-secondary:hover{background:#475569}.badge{display:inline-block;padding:4px 8px;border-radius:6px;font-size:12px;font-weight:bold}</style></head><body>';
    $html .= '<div style="max-width:700px;margin:30px auto;background:#0f172a;border-radius:18px;padding:30px;box-shadow:0 20px 40px rgba(0,0,0,0.5);border:1px solid rgba(56,189,248,0.2);">';
    $html .= '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;"><div><h1 style="color:#38bdf8;margin:0;font-size:1.5rem;">🧹 cPanel Disk Inspector &amp; Cleaner</h1><p style="color:#94a3b8;margin:5px 0 0;font-size:13px;">Manage storage consumption and purge accumulated files</p></div></div>';

    if (!empty($messages)) {
        $html .= '<div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:12px;padding:15px;margin-bottom:20px;"><ul style="margin:0;padding-left:20px;color:#34d399;font-size:13px;line-height:1.8;">';
        foreach ($messages as $msg) {
            $html .= '<li>' . htmlspecialchars($msg) . '</li>';
        }
        $html .= '</ul></div>';
    }

    $html .= '<table><thead><tr><th>Folder / Component</th><th>Disk Usage</th><th>Status</th></tr></thead><tbody>';
    foreach ($stats as $label => $bytes) {
        $isHeavy = $bytes > 50 * 1024 * 1024;
        $html .= '<tr><td>' . htmlspecialchars($label) . '</td><td style="font-weight:bold;' . ($isHeavy ? 'color:#f87171;' : 'color:#f1f5f9;') . '">' . $formatBytes($bytes) . '</td><td>' . ($isHeavy ? '<span class="badge" style="background:rgba(239,68,68,0.2);color:#f87171;">Heavy</span>' : '<span class="badge" style="background:rgba(16,185,129,0.15);color:#34d399;">Normal</span>') . '</td></tr>';
    }
    $html .= '</tbody></table>';

    $html .= '<div style="margin-top:25px;padding:15px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:12px;font-size:12px;color:#fca5a5;line-height:1.6;">';
    $html .= '<strong>⚠️ Why is cPanel getting full?</strong><br>';
    $html .= '1. <code>storage/logs/laravel.log</code> or cPanel <code>error_log</code> files can grow by gigabytes if debug logging or recurring errors occur.<br>';
    $html .= '2. <code>node_modules/</code> (500MB+) or <code>.git/</code> (200MB+) are dev folders and should NOT be uploaded to cPanel production.<br>';
    $html .= '3. Stale session files in <code>storage/framework/sessions</code> accumulate from bots and visitors.';
    $html .= '</div>';

    $html .= '<div style="margin-top:25px;display:flex;gap:12px;flex-wrap:wrap;">';
    $html .= '<a href="?action=purge" class="btn btn-danger" onclick="return confirm(\'Clean logs, old sessions, error_logs, and caches now?\')">⚡ Run Safe Disk Purge</a>';
    $html .= '<a href="/optimize" class="btn btn-secondary">Sync Caches &amp; Migrations &rarr;</a>';
    $html .= '<a href="/" class="btn btn-secondary">Visit Site &rarr;</a>';
    $html .= '</div>';

    $html .= '</div></body></html>';

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
