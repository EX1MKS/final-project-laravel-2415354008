<?php

declare(strict_types=1);

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Support\Facades\Route;

// Redirect welcome page to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Web Route
Route::get('/dashboard', function () {
    $stats = [
        'customers' => [
            'total'  => Customer::count(),
            'active' => Customer::where('status', true)->count(),
        ],
        'services' => [
            'total'  => Service::count(),
            'active' => Service::where('status', true)->count(),
        ],
        'subscriptions' => [
            'total'   => Subscription::count(),
            'active'  => Subscription::where('status', 'active')->count(),
            'revenue' => Subscription::where('subscriptions.status', 'active')
                ->join('services', 'subscriptions.service_id', '=', 'services.id')
                ->sum('services.price'),
        ],
    ];

    $latestSubscriptions = Subscription::with(['customer', 'service'])->latest()->take(5)->get();
    $latestCustomers     = Customer::latest()->take(5)->get();

    return view('dashboard', [
        'title'               => 'Dashboard',
        'subtitle'            => 'Ringkasan sistem ERP',
        'stats'               => $stats,
        'latestSubscriptions' => $latestSubscriptions,
        'latestCustomers'     => $latestCustomers,
    ]);
})->name('dashboard');

// Customer Web Routes
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/',               [CustomerController::class, 'index'])->name('index');
    Route::get('/create',         [CustomerController::class, 'create'])->name('create');
    Route::post('/',              [CustomerController::class, 'store'])->name('store');
    Route::get('/{customer}/edit',   [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}',        [CustomerController::class, 'update'])->name('update');
    Route::get('/{customer}/delete', [CustomerController::class, 'delete'])->name('delete');
    Route::delete('/{customer}',     [CustomerController::class, 'destroy'])->name('destroy');
    Route::patch('/{customer}/activate',   [CustomerController::class, 'activate'])->name('activate');
    Route::patch('/{customer}/deactivate', [CustomerController::class, 'deactivate'])->name('deactivate');
});

// Service Web Routes
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/',              [ServiceController::class, 'index'])->name('index');
    Route::get('/create',        [ServiceController::class, 'create'])->name('create');
    Route::post('/',             [ServiceController::class, 'store'])->name('store');
    Route::get('/{service}/edit',   [ServiceController::class, 'edit'])->name('edit');
    Route::put('/{service}',        [ServiceController::class, 'update'])->name('update');
    Route::get('/{service}/delete', [ServiceController::class, 'delete'])->name('delete');
    Route::delete('/{service}',     [ServiceController::class, 'destroy'])->name('destroy');
    Route::patch('/{service}/activate',   [ServiceController::class, 'activate'])->name('activate');
    Route::patch('/{service}/deactivate', [ServiceController::class, 'deactivate'])->name('deactivate');
});

// Subscription Web Routes
Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get('/',                  [SubscriptionController::class, 'index'])->name('index');
    Route::get('/create',            [SubscriptionController::class, 'create'])->name('create');
    Route::post('/',                 [SubscriptionController::class, 'store'])->name('store');
    Route::get('/{subscription}/edit',   [SubscriptionController::class, 'edit'])->name('edit');
    Route::put('/{subscription}',        [SubscriptionController::class, 'update'])->name('update');
    Route::get('/{subscription}/delete', [SubscriptionController::class, 'delete'])->name('delete');
    Route::delete('/{subscription}',     [SubscriptionController::class, 'destroy'])->name('destroy');
});
