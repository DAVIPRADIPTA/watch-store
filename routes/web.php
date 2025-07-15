<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerAuthController;



Route::get('/', function () {
    return view('home');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {


    Route::post('/category/{id}/sync-to-hub', [CategoryController::class, 'syncToHub'])->name('categories.sync');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::post('products/sync/{id}', [ProductController::class, 'sync'])->name('products.sync');
    Route::post('category/sync/{id}', [CategoryController::class, 'sync'])->name('category.sync');

    Route::get('/order', [OrderController::class, 'index'])->name('order');


    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // 

});

Route::group(['prefix' => 'customer'], function () {
    Route::controller(CustomerAuthController::class)->group(function () {
        Route::group(['middleware' => 'check_customer_login'], function () {
            //tampilkan halaman login
            Route::get('login', 'login')->name('customer.login');
            //aksi login
            Route::post('login', 'store_login')->name('customer.store_login');
            //tampilkan halaman register
            Route::get('register', 'register')->name('customer.register');
            //aksi register
            Route::post('register', 'store_register')->name('customer.store_register');
        });

        //aksi logout
        Route::post('logout', 'logout')->name('customer.logout');
    });
});

require __DIR__ . '/auth.php';
