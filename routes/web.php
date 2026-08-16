<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/load-products', [HomeController::class, 'loadProducts']);
Route::get('/product/{id}', [HomeController::class, 'detail']);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/edit', [CartController::class, 'edit']);
Route::post('/cart/delete-confirm', [CartController::class, 'deleteConfirm']);
Route::post('/cart/delete', [CartController::class, 'delete']);

Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout/action', [CheckoutController::class, 'action']);

Route::get('/status-pesanan', [OrderController::class, 'index']);
Route::post('/status-pesanan/cek', [OrderController::class, 'status']);
Route::post('/status-pesanan/update-ajax', [OrderController::class, 'updateStatusAjax']);
Route::post('/status-pesanan/cancel', [OrderController::class, 'cancelOrder']);
Route::get('/cetak-struk/{id}', [OrderController::class, 'printReceipt']);
Route::post('/payment-notification', [OrderController::class, 'notificationHandler']);

use App\Http\Controllers\AdminController;

Route::get('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/authenticate', [AdminController::class, 'authenticate']);
Route::get('/admin/logout', [AdminController::class, 'logout']);

Route::get('/admin', [AdminController::class, 'dashboard']);

Route::get('/admin/kategori', [AdminController::class, 'kategori']);
Route::get('/admin/kategori/data', [AdminController::class, 'kategoriData']);
Route::post('/admin/kategori/edit', [AdminController::class, 'kategoriEditForm']);
Route::post('/admin/kategori/action', [AdminController::class, 'kategoriAction']);
Route::post('/admin/kategori/delete-confirm', [AdminController::class, 'kategoriDeleteConfirm']);
Route::post('/admin/kategori/delete', [AdminController::class, 'kategoriDelete']);

Route::get('/admin/produk', [AdminController::class, 'produk']);
Route::get('/admin/produk/data', [AdminController::class, 'produkData']);
Route::post('/admin/produk/edit', [AdminController::class, 'produkEditForm']);
Route::post('/admin/produk/action', [AdminController::class, 'produkAction']);
Route::post('/admin/produk/delete-confirm', [AdminController::class, 'produkDeleteConfirm']);
Route::post('/admin/produk/delete', [AdminController::class, 'produkDelete']);

Route::get('/admin/pesanan', [AdminController::class, 'pesanan']);
Route::get('/admin/pesanan/data', [AdminController::class, 'pesananData']);
Route::post('/admin/pesanan/detail', [AdminController::class, 'pesananDetail']);
Route::post('/admin/pesanan/update-status', [AdminController::class, 'pesananUpdateStatus']);

Route::get('/admin/laporan', [AdminController::class, 'laporan']);
Route::get('/admin/laporan/data', [AdminController::class, 'laporanData']);
Route::get('/admin/laporan/cetak', [AdminController::class, 'laporanCetak']);
