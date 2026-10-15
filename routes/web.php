<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PackageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Vantara Production
|--------------------------------------------------------------------------
*/

// Phase 1: Landing Page, Katalog Paket & Detail
Route::get('/', [PackageController::class, 'index'])->name('home');
Route::get('/katalog', [PackageController::class, 'catalog'])->name('packages.catalog');
Route::get('/paket/{slug}', [PackageController::class, 'show'])->name('packages.show');

// Phase 1: Ketersediaan Jadwal & Kalender Interaktif
Route::get('/cek-jadwal', [AvailabilityController::class, 'index'])->name('availability.index');
Route::get('/api/availability/calendar', [AvailabilityController::class, 'calendarData'])->name('api.availability.calendar');
Route::post('/api/availability/check', [AvailabilityController::class, 'checkDate'])->name('api.availability.check');

// Phase 2: Keranjang Belanja (Cart)
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/update/{package_id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{package_id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

// Phase 2: Checkout & Pemesanan
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pesanan/{access_token}', [CheckoutController::class, 'show'])
    ->where('access_token', '[a-f0-9]{48}')
    ->middleware('throttle:invoice')
    ->name('checkout.show');

// Phase 3 & 4: Portal Admin, Operasional & Pelaporan
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:admin-login')
        ->name('login.submit');

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Barang / Paket (CRUD)
        Route::resource('packages', AdminPackageController::class)->except(['show']);
        Route::patch('packages/{package}/toggle-active', [AdminPackageController::class, 'toggleActive'])->name('packages.toggleActive');

        Route::get('/landing-page', [LandingPageController::class, 'edit'])->name('landing-page.edit');
        Route::put('/landing-page', [LandingPageController::class, 'update'])->name('landing-page.update');
        Route::delete('/landing-page/{type}', [LandingPageController::class, 'destroy'])
            ->whereIn('type', ['hero', 'about'])
            ->name('landing-page.destroy');

        // Kelola Status Transaksi
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{order}/operational-costs', [AdminOrderController::class, 'storeOperationalCost'])->name('orders.operational-costs.store');
        Route::patch('/orders/{order}/operational-costs/{costId}', [AdminOrderController::class, 'updateOperationalCost'])->name('orders.operational-costs.update');
        Route::delete('/orders/{order}/operational-costs/{costId}', [AdminOrderController::class, 'destroyOperationalCost'])->name('orders.operational-costs.destroy');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        // Phase 4: Analisis & Pelaporan Bulanan (PDF & Print)
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/laporan-bulanan.pdf', [AdminReportController::class, 'downloadPdf'])->name('reports.pdf');
        Route::get('/reports/print', [AdminReportController::class, 'printView'])->name('reports.print');
    });
});
