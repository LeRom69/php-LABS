<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware([])->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store'])->withoutMiddleware(['web']);
    Route::put('/products/{id}', [ProductController::class, 'update'])->withoutMiddleware(['web']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->withoutMiddleware(['web']);
});
