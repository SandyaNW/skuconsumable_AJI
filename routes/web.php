<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SKUController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginAJIController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\auth\ForgotPasswordController;

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    // --- AUTH ONLY GROUP ---
    Route::group(['middleware' => ['auth']], function () {

        // 1. Dashboard & Logout
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('home.dashboard');

        // 2. SKU Management
        // Dashboard & Show
        Route::get('/sku/dashboard', [SKUController::class, 'dashboard'])->name('sku.dashboard');
        Route::get('/sku', [SKUController::class, 'index'])->name('sku.index');
        Route::get('/sku/show/{id}', [SKUController::class, 'show'])->name('sku.show');

        // Create, Store & Edit (PIC)
        Route::get('/sku/create', [SKUController::class, 'create'])->name('sku.create');
        Route::post('/sku/store', [SKUController::class, 'store'])->name('sku.store');
        Route::get('/sku/{id}/edit', [SKUController::class, 'edit'])->name('sku.edit');
        Route::put('/sku/{id}/update', [SKUController::class, 'update'])->name('sku.update');

        // Approval (Dept Head)
        Route::post('/sku/approve/{id}', [SKUController::class, 'approve'])->name('sku.approve');
        Route::post('/sku/reject/{id}', [SKUController::class, 'reject'])->name('sku.reject');

        // Finance (FA) & Utilities
        Route::post('/sku/update-fa/{id}', [SKUController::class, 'updateByFA'])->name('sku.update_fa');
        Route::get('/sku/export', [SKUController::class, 'export'])->name('sku.export');
        Route::get('/sku/check-conflicts/{id}', [SKUController::class, 'checkConflicts'])->name('sku.check_conflicts');
        Route::get('/sku/search-master-products', [SKUController::class, 'searchMasterProducts'])->name('sku.search_master_products');

        // 3. Master Product (Nested Group)
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/store', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [ProductController::class, 'destroy'])->name('destroy');
            Route::get('/export', [ProductController::class, 'export'])->name('export');

            // Smart Import Routes
            Route::get('/download-template', [ProductController::class, 'downloadTemplate'])->name('download_template');
            Route::post('/import', [ProductController::class, 'import'])->name('import');
            Route::post('/import-preview', [ProductController::class, 'previewImport'])->name('import_preview');
            Route::post('/import-process', [ProductController::class, 'processImport'])->name('import_process');
        });

        // 4. Notifications
        Route::post('/notifications/mark-all-read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.markAllRead');

        Route::get('/get-unread-count', function () {
            return response()->json(['total' => auth()->user()->unreadNotifications->count()]);
        })->name('sku.get_unread_count');

        Route::get('/get-notif-list', function () {
            $unreadNotifications = auth()->user()->unreadNotifications->take(5);
            return view('layouts.partials.notification-list', compact('unreadNotifications'))->render();
        })->name('sku.get_notif_list');
    });
    
    // --- LOCAL AUTH ROUTES ---
    Route::get('/login', [LoginController::class, 'show'])->name('login.show');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.perform');
    Route::any('/logout', [LogoutController::class, 'perform'])->name('logout');
    Route::get('/', function () {
        return redirect()->route('sku.dashboard');
    });

    // --- FORGOT PASSWORD ROUTES ---
    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

    // --- PORTAL ROUTES ---
    Route::post('/loginPortalAJI', [LoginAJIController::class, 'loginPortalAJI'])->name('EHS.Patrol.loginPortalAJI');
    
    // Pastikan tombol di view menggunakan form method="POST" atau ubah jadi Route::any jika pakai link <a>
    Route::post('/to-portal', [UsersController::class, 'toPortal'])->name('to.portal');
});