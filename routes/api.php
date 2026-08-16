<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;

// Products & Categories
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/best-sellers', [ProductController::class, 'bestSellers']);
Route::get('/categories/{id}/products', [ProductController::class, 'byCategory']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Tables / QR
Route::get('/tables/{table_code}', [TableController::class, 'validateQr']);

// Orders
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order_number}', [OrderController::class, 'show']);
Route::post('/orders/track', [OrderController::class, 'track']);
Route::post('/orders/{order_number}/cancel', [OrderController::class, 'cancel']);
Route::post('/orders/{order_number}/upload-proof', [OrderController::class, 'uploadProof']);

