<?php

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

use Modules\Products\Http\Controllers\ProductsController;

Route::prefix('products')->group(function() {
    Route::get('/', [ProductsController::class, 'index'])->name('products.index');
    Route::post('/', [ProductsController::class, 'add'])->name('products.add');
    Route::get('/{product}', [ProductsController::class, 'show'])->name('products.show');
    Route::put('/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');
});
