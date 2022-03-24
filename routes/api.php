<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', [RegistrationController::class, 'register'])->name('users.new');

Route::post('login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function(){

    Route::prefix('users')->controller(UserController::class)->group(function(){
        Route::get('/', 'index')->name('users.all');
        Route::get('/{user}', 'show')->name('users.show');
        Route::put('/{user}', 'update')->name('users.update');
        Route::delete('/{user}', 'destroy')->name('users.delete');
    });

    Route::middleware('can:isBuyer')->group(function(){
    });

    Route::middleware('can:isSeller')->group(function(){
        Route::prefix('products')->controller(ProductController::class)->group(function(){
            Route::post('/', 'store')->name('products.create');
            Route::put('/{product}', 'update')->name('products.update');
            Route::delete('/{product}', 'destroy')->name('products.delete');
        });
    });

    Route::get('products', [ProductController::class, 'index'])->name('products.all');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::get('logout/all', [LoginController::class, 'logout'])->name('logout');
});
