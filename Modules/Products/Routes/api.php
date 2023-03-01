<?php

use Illuminate\Http\Request;
use Modules\Products\Http\Controllers\ProductsController;

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

//Route::middleware('auth:api')->get('/products', function (Request $request) {
//    return $request->user();
//});
//
Route::prefix('products')->group(function() {
    Route::get('/', [ProductsController::class, 'index'])->name('products.index');
    Route::post('/', [ProductsController::class, 'add'])->name('products.add');
    Route::get('/{product}', [ProductsController::class, 'show'])->name('products.show');
    Route::put('/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');
});
