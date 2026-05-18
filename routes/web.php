<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\PastorController;
use App\Http\Controllers\Admin\ServiceContentController;
use App\Http\Controllers\Admin\NewsScheduleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\JemaatController as AdminJemaatController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\KeuanganController as AdminKeuanganController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\DashboardFeatureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersembahanController;
use App\Http\Controllers\RegistrasiAcaraController;

// ========== PUBLIC ROUTES ==========
Route::get('/', [ImageController::class, 'index'])->name('welcome');
Route::get('/history', [PageController::class, 'history'])->name('history');
Route::get('/vision', [PageController::class, 'vision'])->name('vision');
Route::get('/struktur', [PageController::class, 'struktur'])->name('struktur');
Route::get('/layanan', [PageController::class, 'layanan'])->name('layanan');
Route::get('/layanan/{id}', [PageController::class, 'serviceDetail'])->name('service.detail');
Route::get('/pengumuman', [PageController::class, 'pengumuman'])->name('pengumuman');
Route::get('/pengumuman/{id}', [PageController::class, 'newsDetail'])->name('news.detail');
Route::get('/pastors', [PageController::class, 'pastors'])->name('pastors');
Route::get('/gallery', [ImageController::class, 'gallery'])->name('gallery');
Route::get('/image/{id}', [ImageController::class, 'show'])->name('image.detail');
Route::get('/image/{id}/download', [ImageController::class, 'download'])->name('image.download');

// ========== AUTH ROUTES ==========
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// ========== PROTECTED ROUTES ==========
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- Absensi Jemaat ----
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/',             [AbsensiController::class, 'index'])->name('index');
        Route::post('/',            [AbsensiController::class, 'store'])->name('store');
        Route::delete('/{absensi}', [AbsensiController::class, 'destroy'])->name('destroy');
    });

    // ---- Admin Galeri ----
    Route::prefix('admin/galeri')->name('admin.galeri.')->group(function () {
        Route::get('/create',        [ImageController::class, 'create'])->name('create');
        Route::post('/store',        [ImageController::class, 'store'])->name('store');
        Route::get('/edit/{id}',     [ImageController::class, 'edit'])->name('edit');
        Route::put('/update/{id}',   [ImageController::class, 'update'])->name('update');
        Route::delete('/delete/{id}',[ImageController::class, 'destroy'])->name('delete');
    });

    // ---- Admin Routes ----
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

        // Jemaat
        Route::resource('jemaat', AdminJemaatController::class)->only(['index', 'show', 'destroy']);

        // Pastors
        Route::resource('pastors', PastorController::class)->except(['show']);

        // Services
        Route::resource('services', ServiceContentController::class)->except(['show']);

        // News (Berita)
        Route::resource('news', NewsScheduleController::class);

        // ---- Jadwal Ibadah (CRUD baru) ----
        Route::resource('schedules', ScheduleController::class);
        Route::patch('schedules/{schedule}/toggle', [ScheduleController::class, 'toggleActive'])->name('schedules.toggle');

        // ---- Manajemen Notifikasi ----
        Route::resource('notifikasi', AdminNotifikasiController::class);
        Route::post('notifikasi/send-to-all', [AdminNotifikasiController::class, 'sendToAll'])->name('notifikasi.send-to-all');

        // ---- Manajemen Keuangan ----
        Route::resource('keuangan', AdminKeuanganController::class);
        Route::get('keuangan-report', [AdminKeuanganController::class, 'report'])->name('keuangan.report');

        // ---- Persetujuan User ----
        Route::get('user-approvals', [UserApprovalController::class, 'pendingRegistrations'])->name('user-approvals.index');
        Route::patch('user-approvals/{id}/approve', [UserApprovalController::class, 'approve'])->name('user-approvals.approve');
        Route::patch('user-approvals/{id}/reject', [UserApprovalController::class, 'reject'])->name('user-approvals.reject');

        // ---- Manajemen Absensi ----
        Route::get('absensi',                           [AbsensiController::class, 'adminIndex'])->name('absensi.index');
        Route::patch('absensi/{absensi}/approve',        [AbsensiController::class, 'approve'])->name('absensi.approve');
        Route::patch('absensi/{absensi}/reject',         [AbsensiController::class, 'reject'])->name('absensi.reject');
        Route::post('absensi/bulk-approve',              [AbsensiController::class, 'bulkApprove'])->name('absensi.bulk-approve');
    });

    // ---- Dashboard Jemaat Read-only ----
    Route::prefix('dashboard/features')->name('dashboard.features.')->group(function () {
        Route::get('pastors',  [DashboardFeatureController::class, 'pastors'])->name('pastors');
        Route::get('services', [DashboardFeatureController::class, 'services'])->name('services');
        Route::get('events',   [DashboardFeatureController::class, 'events'])->name('events');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('profile/edit',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile',     [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile',    [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Acara jemaat ──
    Route::post('/acara/{news}/daftar',           [RegistrasiAcaraController::class, 'store'])->name('acara.daftar');
    Route::patch('/acara/registrasi/{registrasi}/cancel', [RegistrasiAcaraController::class, 'cancel'])->name('acara.cancel');
});

// ── Persembahan Online (publik + login) ──
Route::get('/persembahan', [PersembahanController::class, 'index'])->name('persembahan.index');
Route::post('/persembahan', [PersembahanController::class, 'store'])->name('persembahan.store');
Route::get('/persembahan/finish', [PersembahanController::class, 'finish'])->name('persembahan.finish');

// ── Webhook Midtrans - WAJIB exclude dari CSRF ──
Route::post('/webhook/midtrans', [PersembahanController::class, 'webhook'])->name('webhook.midtrans');



// ── Acara publik ──
Route::get('/acara/{news}', [RegistrasiAcaraController::class, 'show'])->name('acara.show');

// ── Admin registrasi acara ── 
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('acara/{news}/registrasi',              [RegistrasiAcaraController::class, 'adminIndex'])->name('acara.registrasi');
    Route::patch('acara/registrasi/{registrasi}/confirm', [RegistrasiAcaraController::class, 'confirm'])->name('acara.confirm');
    Route::patch('acara/registrasi/{registrasi}/reject',  [RegistrasiAcaraController::class, 'reject'])->name('acara.reject');
});