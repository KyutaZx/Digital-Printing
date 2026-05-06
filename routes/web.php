<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ManagerController;

/*
|--------------------------------------------------------------------------
| AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PUBLIC (Produk & Landing)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index']);
Route::get('/katalog', [ProductController::class, 'catalog']);
Route::get('/produk/{id}', [ProductController::class, 'show']);
Route::view('/tentang', 'about');
Route::view('/cara-order', 'cara-order');
Route::view('/kontak', 'contact');
Route::view('/syarat-ketentuan', 'terms');
Route::view('/kebijakan-privasi', 'privacy');

/*
|--------------------------------------------------------------------------
| CUSTOMER (Login Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session:customer,owner,admin,staff'])->group(function () {

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::post('/cart/update', [CartController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);

    // Orders & Checkout
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/pesanan/{id}/selesai', [OrderController::class, 'confirmCompleted']);
    Route::get('/pesanan/{id}/invoice/pdf', [OrderController::class, 'downloadInvoice']);

    // Payment
    Route::get('/pembayaran/metode', [PaymentController::class, 'methods']);
    Route::post('/pembayaran/{orderId}/upload', [PaymentController::class, 'uploadProof']);

    // Design Upload
    Route::post('/desain/{orderItemId}/upload', [DesignController::class, 'upload']);
    Route::get('/desain/{orderItemId}', [DesignController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| STAFF PANEL
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->middleware(['auth.session:staff,owner,admin'])->group(function () {
    Route::get('/', fn() => redirect('/staff/dashboard'));
    Route::get('/dashboard', [StaffController::class, 'dashboard']);
    Route::get('/verifikasi', [StaffController::class, 'verifikasi']);
    Route::get('/verifikasi/{id}', [StaffController::class, 'verifikasiDetail']);
    Route::post('/pembayaran/{id}/setujui', [PaymentController::class, 'approve']);
    Route::post('/pembayaran/{id}/tolak', [PaymentController::class, 'reject']);
    Route::get('/desain', [StaffController::class, 'desainList']);
    Route::post('/desain/{id}/review', [DesignController::class, 'addReview']);
    Route::get('/produksi', [StaffController::class, 'produksi']);
    Route::post('/produksi/{orderId}/mulai', [ProductionController::class, 'start']);
    Route::post('/produksi/{orderId}/selesai', [ProductionController::class, 'finish']);
});

/*
|--------------------------------------------------------------------------
| MANAGER / OWNER PANEL
|--------------------------------------------------------------------------
*/
Route::prefix('manager')->middleware(['auth.session:owner,admin'])->group(function () {
    Route::get('/', fn() => redirect('/manager/dashboard'));
    Route::get('/dashboard', [ManagerController::class, 'dashboard']);
    Route::get('/produk', [ManagerController::class, 'produk']);
    Route::post('/produk', [ManagerController::class, 'storeProduk']);
    Route::put('/produk/{id}', [ManagerController::class, 'updateProduk']);
    Route::delete('/produk/{id}', [ManagerController::class, 'deleteProduk']);
    Route::get('/material', [MaterialController::class, 'index']);
    Route::post('/material', [MaterialController::class, 'store']);
    Route::put('/material/{id}', [MaterialController::class, 'update']);
    Route::post('/material/{id}/restock', [MaterialController::class, 'restock']);
    Route::get('/monitoring', [ManagerController::class, 'monitoring']);
    Route::get('/pesanan', [ManagerController::class, 'pesanan']);
    Route::get('/laporan', [ReportController::class, 'index']);
});