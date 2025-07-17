<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerAuthController;
use App\Models\Product;
use App\Models\Category;



Route::get('/', function () {
    $products = Product::all();
    $cheapestProduct = Product::orderBy('price')->first();
    $categories = Category::all();

    return view('home', compact('products', 'cheapestProduct', 'categories'));
})->name('home');

Route::get('/category/{id}', function ($id) {
    $products = Product::where('category_id', $id)->get();
    $categories = Category::all();
    return view('home', compact('products', 'categories'));
})->name('category.filter');

Route::get('/products/{id}', function ($id) {
    $product = Product::where('id', $id)->firstOrFail();
    return view('products.show', compact('product'));
})->name('products.show');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    Route::get('/order', [OrderController::class, 'index'])->name('order');


    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::post('products/{product}/sync', [ProductController::class, 'sync'])->name('products.sync');
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    Route::post('categories/{category}/sync', [CategoryController::class, 'sync'])->name('categories.sync');
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');


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
