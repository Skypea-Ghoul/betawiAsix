<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\CartApiController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart', [CartApiController::class, 'store']);
    Route::delete('/cart/{id}', [CartApiController::class, 'destroy']);
});