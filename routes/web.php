<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PersembahanController;
use App\Http\Controllers\RegistrasiAcaraController;
use App\Http\Controllers\UserApprovalController;
use App\Http\Controllers\DashboardFeatureController;
use App\Http\Controllers\Admin\PastorController;
use App\Http\Controllers\Admin\ServiceContentController;
use App\Http\Controllers\Admin\NewsScheduleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\JemaatController as AdminJemaatController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\KeuanganController as AdminKeuanganController;

// ================================================================
// PUBLIC ROUTES
// ================================================================

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

// ================================================================
// AUTH ROUTES
// ================================================================

Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ================================================================
// PROTECTED ROUTES
// ================================================================

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================================================================
    // PROFILE
    // ================================================================

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ================================================================
    // ABSENSI JEMAAT
    // ================================================================

    Route::prefix('absensi')->name('absensi.')->group(function () {

        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::post('/', [AbsensiController::class, 'store'])->name('store');
        Route::delete('/{absensi}', [AbsensiController::class, 'destroy'])->name('destroy');

        // QR Scan
        Route::get('/scan/{token}', [AbsensiController::class, 'scanQr'])->name('scan');
        Route::post('/scan/{token}', [AbsensiController::class, 'storeQr'])->name('scan.store');

        // Notifikasi
        Route::get('/notifikasi', [AbsensiController::class, 'getNotifikasi'])->name('notifikasi');

        Route::patch(
            '/notifikasi/{notifikasi}/read',
            [AbsensiController::class, 'readNotifikasi']
        )->name('notifikasi.read');

        Route::post(
            '/notifikasi/read-all',
            [AbsensiController::class, 'readAllNotifikasi']
        )->name('notifikasi.read-all');
    });

    // ================================================================
    // DASHBOARD FEATURES
    // ================================================================

    Route::prefix('dashboard/features')->name('dashboard.features.')->group(function () {

        Route::get('/pastors', [DashboardFeatureController::class, 'pastors'])->name('pastors');

        Route::get('/services', [DashboardFeatureController::class, 'services'])->name('services');

        Route::get('/events', [DashboardFeatureController::class, 'events'])->name('events');
    });

    // ================================================================
    // ACARA JEMAAT
    // ================================================================

    Route::post(
        '/acara/{news}/daftar',
        [RegistrasiAcaraController::class, 'store']
    )->name('acara.daftar');

    Route::patch(
        '/acara/registrasi/{registrasi}/cancel',
        [RegistrasiAcaraController::class, 'cancel']
    )->name('acara.cancel');

    // ================================================================
    // ADMIN GALERI
    // ================================================================

    Route::prefix('admin/galeri')->name('admin.galeri.')->group(function () {

        Route::get('/create', [ImageController::class, 'create'])->name('create');
        Route::post('/store', [ImageController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [ImageController::class, 'edit'])->name('edit');

        Route::put('/update/{id}', [ImageController::class, 'update'])->name('update');

        Route::delete('/delete/{id}', [ImageController::class, 'destroy'])->name('delete');
    });
});

// ================================================================
// PERSEMBAHAN
// ================================================================

Route::get('/persembahan', [PersembahanController::class, 'index'])
    ->name('persembahan.index');

Route::post('/persembahan', [PersembahanController::class, 'store'])
    ->name('persembahan.store');

Route::get('/persembahan/finish', [PersembahanController::class, 'finish'])
    ->name('persembahan.finish');

// Webhook Midtrans
Route::post('/webhook/midtrans', [PersembahanController::class, 'webhook'])
    ->name('webhook.midtrans');

// ================================================================
// ACARA PUBLIK
// ================================================================

Route::get('/acara/{news}', [RegistrasiAcaraController::class, 'show'])
    ->name('acara.show');

// ================================================================
// ADMIN ROUTES
// ================================================================

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ============================================================
        // JEMAAT
        // ============================================================

        Route::resource('jemaat', AdminJemaatController::class);

        // Pastors
        Route::resource('pastors', PastorController::class)
            ->except(['show']);

        // Services
        Route::resource('services', ServiceContentController::class)
            ->except(['show']);

        // News
        Route::resource('news', NewsScheduleController::class);

        // Schedules
        Route::resource('schedules', ScheduleController::class);

        Route::patch(
            'schedules/{schedule}/toggle',
            [ScheduleController::class, 'toggleActive']
        )->name('schedules.toggle');

        // Notifikasi
        Route::resource('notifikasi', AdminNotifikasiController::class);

        Route::post(
            'notifikasi/send-to-all',
            [AdminNotifikasiController::class, 'sendToAll']
        )->name('notifikasi.send-to-all');

        // Keuangan
        Route::resource('keuangan', AdminKeuanganController::class);

        Route::get(
            'keuangan-report',
            [AdminKeuanganController::class, 'report']
        )->name('keuangan.report');

        // User Approval
        Route::get(
            'user-approvals',
            [UserApprovalController::class, 'pendingRegistrations']
        )->name('user-approvals.index');

        Route::patch(
            'user-approvals/{id}/approve',
            [UserApprovalController::class, 'approve']
        )->name('user-approvals.approve');

        Route::patch(
            'user-approvals/{id}/reject',
            [UserApprovalController::class, 'reject']
        )->name('user-approvals.reject');

        // ============================================================
        // ABSENSI ADMIN
        // ============================================================

        Route::prefix('absensi')->name('absensi.')->group(function () {

            Route::get('/', [AbsensiController::class, 'adminIndex'])->name('index');

            Route::patch(
                '/{absensi}/approve',
                [AbsensiController::class, 'approve']
            )->name('approve');

            Route::patch(
                '/{absensi}/reject',
                [AbsensiController::class, 'reject']
            )->name('reject');

            Route::post(
                '/bulk-approve',
                [AbsensiController::class, 'bulkApprove']
            )->name('bulk-approve');

            // Massal
            Route::get('/massal', [AbsensiController::class, 'massal'])
                ->name('massal');

            Route::post('/massal', [AbsensiController::class, 'storeMassal'])
                ->name('massal.store');

            // Rekap
            Route::get('/rekap', [AbsensiController::class, 'rekap'])
                ->name('rekap');

            // Export
            Route::get('/export/excel', [AbsensiController::class, 'exportExcel'])
                ->name('export.excel');

            Route::get('/export/pdf', [AbsensiController::class, 'exportPdf'])
                ->name('export.pdf');

            // QR Generate
            Route::post('/qr/{schedule}', [AbsensiController::class, 'generateQr'])
                ->name('qr.generate');
        });

        // ============================================================
        // REGISTRASI ACARA
        // ============================================================

        Route::get(
            'acara/{news}/registrasi',
            [RegistrasiAcaraController::class, 'adminIndex']
        )->name('acara.registrasi');

        Route::patch(
            'acara/registrasi/{registrasi}/confirm',
            [RegistrasiAcaraController::class, 'confirm']
        )->name('acara.confirm');

        Route::patch(
            'acara/registrasi/{registrasi}/reject',
            [RegistrasiAcaraController::class, 'reject']
        )->name('acara.reject');
    });
