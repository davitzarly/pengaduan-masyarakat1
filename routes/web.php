<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Kategori;

// Halaman awal
Route::get('/', function () {
    $kategoris = Kategori::formOptions();
    return view('welcome', compact('kategoris'));
})->name('home');

// Form pengaduan dari landing (guest)
Route::post('/pengaduan-landing', [PengaduanController::class, 'storeLanding'])
    ->name('landing.pengaduan.store');

// Survei Kepuasan Publik (Guest)
Route::get('/survei-kepuasan', [FeedbackController::class, 'createPublic'])->name('feedback.public.create');
Route::post('/survei-kepuasan', [FeedbackController::class, 'storePublic'])->name('feedback.public.store');

// Autentikasi
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Area terautentikasi (petugas/admin)
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    // CRUD Pengaduan
    Route::resource('pengaduan', PengaduanController::class);

    // Tambahan: akses lampiran pengaduan (read-only)
    Route::get('/pengaduan/{pengaduan}/lampiran', [PengaduanController::class, 'lampiran'])
        ->name('pengaduan.lampiran');
    
    // Random Forest Tree Voting Analysis
    Route::get('/pengaduan/{pengaduan}/tree-voting', [PengaduanController::class, 'showTreeVoting'])
        ->name('pengaduan.tree-voting');

    // Feedback usability (petugas/admin)
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Tambahan: Area Admin (akses untuk semua user terautentikasi)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Kategori (tanpa show)
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // CRUD User (admin)
    Route::resource('users', UserController::class)->except(['show']);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // Feedback overview
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
});

// Fallback untuk 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
