<?php

use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/categories', [CategoriesController::class, 'all']);

Route::prefix('/products')
    ->group(function () {
        Route::get('/', [ProductsController::class, 'all']);
        Route::post('/', [ProductsController::class, 'store']);
        Route::get('/categories', [CategoriesController::class, 'productsCategories']);
    });
