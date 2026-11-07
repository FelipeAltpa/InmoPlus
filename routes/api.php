<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\LoanSimulationController;
use App\Http\Controllers\Api\PropertyController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('/exchange-rates', ExchangeRateController::class)->middleware('throttle:30,1');
Route::post('/loan-simulations', LoanSimulationController::class)->middleware('throttle:20,1');

Route::get('/properties', [PropertyController::class, 'index'])->middleware('throttle:60,1');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->middleware('throttle:60,1');

Route::post('/properties', [PropertyController::class, 'store'])->middleware(['auth:api', 'active', 'abilities:properties:write']);

Route::put('/properties/{property}', [PropertyController::class, 'update'])->middleware(['auth:api', 'active', 'abilities:properties:write']);
Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->middleware(['auth:api', 'active', 'abilities:properties:write']);
