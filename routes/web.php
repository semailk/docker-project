<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'welcome']);
Route::prefix('products')->group(function () {
    Route::get('sort', [HomeController::class, 'productsSort']);
    Route::get('show/{product}', [ProductController::class, 'show']);
    Route::post('store', [ProductController::class, 'store']);
    Route::post('update', [ProductController::class, 'update']);
    Route::post('delete/{product}', [ProductController::class, 'delete']);
});
Route::prefix('manufacturers')->group(function () {
    Route::post('store', [ManufacturerController::class, 'store'])->name('manufacturers.store');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
