<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==== Controllers ====
// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\ScholarshipApplicationController as AdminScholarshipApplicationController;
use App\Http\Controllers\Admin\PartnershipProposalController as AdminPartnershipProposalController;
use App\Http\Controllers\Admin\AchievementController as AdminAchievementController;

// Anggota
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\ActivityController as AnggotaActivityController;
use App\Http\Controllers\Anggota\PartnershipProposalController as AnggotaPartnershipProposalController;
use App\Http\Controllers\Anggota\ScholarshipApplicationController as AnggotaScholarshipApplicationController;
use App\Http\Controllers\Anggota\AchievementController as AnggotaAchievementController;

// Umum
use App\Http\Controllers\ProfileController;

Route::get('/', fn() => view('welcome'))->name('welcome');

Auth::routes();

// ======================================================================
//                              AREA ADMIN
// ======================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kegiatan
        Route::resource('activities', AdminActivityController::class);

        // Beasiswa
        Route::resource('scholarship', AdminScholarshipApplicationController::class);
        Route::get('scholarship/export/pdf', [AdminScholarshipApplicationController::class, 'exportPDF'])->name('scholarship.export.pdf');
        Route::get('scholarship/export/excel', [AdminScholarshipApplicationController::class, 'exportExcel'])->name('scholarship.export.excel');

        // Kemitraan
        Route::resource('partnerships', AdminPartnershipProposalController::class);
        Route::get('partnerships/{partnership}/download', [AdminPartnershipProposalController::class, 'download'])->name('partnerships.download');
        Route::get('partnerships/export/pdf', [AdminPartnershipProposalController::class, 'exportPDF'])->name('partnerships.export.pdf');
        Route::get('partnerships/export/excel', [AdminPartnershipProposalController::class, 'exportExcel'])->name('partnerships.export.excel');

        // Prestasi
        Route::resource('achievements', AdminAchievementController::class);
        Route::get('achievements/export/pdf', [AdminAchievementController::class, 'exportPDF'])->name('achievements.export.pdf');
        Route::get('achievements/export/excel', [AdminAchievementController::class, 'exportExcel'])->name('achievements.export.excel');
        Route::get('achievements/{achievement}/download', [AdminAchievementController::class, 'download'])->name('achievements.download');
    });

// ======================================================================
//                              AREA ANGGOTA
// ======================================================================
Route::middleware(['auth', 'role:anggota'])
    ->prefix('anggota')
    ->name('anggota.')
    ->group(function () {
        Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');

        // -------------------------------------------------------------
        // KEGIATAN
        // -------------------------------------------------------------
        Route::get('activities/{activity}/create', [AnggotaActivityController::class, 'create'])->name('activities.create');

        Route::post('activities/{activity}/register', [AnggotaActivityController::class, 'register'])->name('activities.register');

        Route::resource('activities', AnggotaActivityController::class)->only(['index', 'show']);

        // -------------------------------------------------------------
        // BEASISWA (Scholarship)
        // -------------------------------------------------------------
        // Form apply (halaman utama)
        Route::get('scholarship', [AnggotaScholarshipApplicationController::class, 'index'])->name('scholarship.index');

        // Submit form
        Route::post('scholarship/store', [AnggotaScholarshipApplicationController::class, 'store'])->name('scholarship.store');

        // KEMITRAAN
        // Halaman index (daftar atau form awal)
    Route::get('partnerships', [AnggotaPartnershipProposalController::class, 'index'])
        ->name('partnerships.index');

    // Simpan pengajuan kerjasama (POST)
    Route::post('partnerships', [AnggotaPartnershipProposalController::class, 'store'])
        ->name('partnerships.store');

    // Daftar proposal saya
    Route::get('partnerships/my-proposals', [AnggotaPartnershipProposalController::class, 'myProposals'])
        ->name('partnerships.my_proposals');

    // Download file proposal
    Route::get('partnerships/download/{proposal}', [AnggotaPartnershipProposalController::class, 'downloadFile'])
        ->name('partnerships.download');

        // -------------------------------------------------------------
        // PRESTASI
        // -------------------------------------------------------------
        Route::prefix('prestasi')
            ->name('achievements.')
            ->group(function () {
                Route::get('/', [AnggotaAchievementController::class, 'index'])->name('index');
                Route::get('/create', [AnggotaAchievementController::class, 'create'])->name('create');
                Route::post('/store', [AnggotaAchievementController::class, 'store'])->name('store');
                Route::get('/show/{id}', [AnggotaAchievementController::class, 'show'])->name('show');
            });

        // -------------------------------------------------------------
        // PAGE KECIL
        // -------------------------------------------------------------
        Route::get('/profile', fn() => 'profile')->name('profile');
        Route::get('/kegiatan', fn() => 'kegiatan')->name('kegiatan');

        Route::prefix('pengumuman')
            ->name('announcements.')
            ->group(function () {
                Route::get('/', fn() => 'index')->name('index');
            });
    });

// ======================================================================
//                      Redirect Otomatis Dashboard
// ======================================================================
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'anggota' => redirect()->route('anggota.dashboard'),
        default => redirect()->route('welcome'),
    };
})->name('dashboard');

// ======================================================================
//                             PROFILE UMUM
// ======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
