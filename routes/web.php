<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\MemberAdminController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// 1. Root Route
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 2. Authentication Routes (Guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Protected Routes (Authenticated users only)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('verified_member')->group(function () {
        Route::get('/unverified', [AuthController::class, 'showUnverified'])->name('unverified');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.edit');
        Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

        // ============================================
        // MEMBER SPECIFIC ROUTES
        // ============================================
        Route::middleware('role:user,member')->group(function () {
            Route::get('/catalog', [MemberController::class, 'catalog'])->name('catalog');
            Route::get('/member/card', [MemberController::class, 'card'])->name('member.card');
            Route::get('/member/history', [MemberController::class, 'history'])->name('member.history');
            Route::get('/member/rewards', [MemberController::class, 'rewards'])->name('member.rewards');
            Route::post('/member/redeem', [MemberController::class, 'redeem'])->name('member.redeem');
        });

        // ============================================
        // PETUGAS & SUPER ADMIN SHARED ROUTES
        // ============================================
        Route::middleware('role:admin,super_admin,petugas')->group(function () {
            Route::get('/borrows', [BorrowController::class, 'index'])->name('borrows.index');
            Route::post('/borrows/checkout', [BorrowController::class, 'checkout'])->name('borrows.checkout');
            Route::post('/borrows/checkin', [BorrowController::class, 'checkin'])->name('borrows.checkin');
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/members', [MemberAdminController::class, 'index'])->name('members.index');
            Route::post('/admin/members/{member}/verify', [MemberAdminController::class, 'verify'])->name('members.verify');
            Route::post('/admin/members/{member}/reject', [MemberAdminController::class, 'reject'])->name('members.reject');

            // Books CRUD
            Route::resource('/admin/books', BookController::class)->names([
                'index' => 'books.index',
                'create' => 'books.create',
                'store' => 'books.store',
                'edit' => 'books.edit',
                'update' => 'books.update',
                'destroy' => 'books.destroy',
            ])->except(['show']);
        });

        // ============================================
        // SUPER ADMIN ONLY ROUTES
        // ============================================
        Route::middleware('role:super_admin')->group(function () {

            // Member Adjustment
            Route::get('/admin/members/create', [MemberAdminController::class, 'create'])->name('members.create');
            Route::post('/admin/members', [MemberAdminController::class, 'store'])->name('members.store');
            Route::get('/admin/members/{member}/edit', [MemberAdminController::class, 'edit'])->name('members.edit');
            Route::put('/admin/members/{member}', [MemberAdminController::class, 'update'])->name('members.update');
            Route::delete('/admin/members/{member}', [MemberAdminController::class, 'destroy'])->name('members.destroy');

            // Officers CRUD
            Route::resource('/admin/officers', OfficerController::class)->names([
                'index' => 'officers.index',
                'create' => 'officers.create',
                'store' => 'officers.store',
                'edit' => 'officers.edit',
                'update' => 'officers.update',
                'destroy' => 'officers.destroy',
            ])->except(['show']);

            // Transaction logs
            Route::get('/admin/borrows/history', [BorrowController::class, 'history'])->name('borrows.history');

            // Settings & Google Sheets Sync
            Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/admin/settings', [SettingController::class, 'update'])->name('settings.update');
            Route::post('/admin/settings/sync-sheets', [SettingController::class, 'syncSheets'])->name('settings.sync_sheets');
        });
    });
});
