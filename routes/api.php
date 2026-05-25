<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

// ── Service API Routes ───────────────────────────────────
Route::apiResource('services', ServiceController::class)->names([
    'index'   => 'api.services.index',
    'store'   => 'api.services.store',
    'show'    => 'api.services.show',
    'update'  => 'api.services.update',
    'destroy' => 'api.services.destroy',
]);
Route::patch('services/{service}/activate',   [ServiceController::class,   'activate'])->name('api.services.activate');
Route::patch('services/{service}/deactivate', [ServiceController::class,   'deactivate'])->name('api.services.deactivate');

// ── Customer API Routes ──────────────────────────────────
Route::apiResource('customers', CustomerController::class)->names([
    'index'   => 'api.customers.index',
    'store'   => 'api.customers.store',
    'show'    => 'api.customers.show',
    'update'  => 'api.customers.update',
    'destroy' => 'api.customers.destroy',
]);
Route::patch('customers/{customer}/activate',   [CustomerController::class, 'activate'])->name('api.customers.activate');
Route::patch('customers/{customer}/deactivate', [CustomerController::class, 'deactivate'])->name('api.customers.deactivate');

// ── Subscription API Routes ──────────────────────────────
Route::apiResource('subscriptions', SubscriptionController::class)->names([
    'index'   => 'api.subscriptions.index',
    'store'   => 'api.subscriptions.store',
    'show'    => 'api.subscriptions.show',
    'update'  => 'api.subscriptions.update',
    'destroy' => 'api.subscriptions.destroy',
]);
Route::patch('subscriptions/{subscription}/status', [SubscriptionController::class, 'changeStatus'])->name('api.subscriptions.changeStatus');