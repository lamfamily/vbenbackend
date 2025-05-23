<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Leguo\App\Http\Controllers\GoodsController;
use Modules\Leguo\App\Http\Controllers\WebsiteController;
use Modules\Leguo\App\Http\Controllers\GoodsCategoryController;
use Modules\Leguo\App\Http\Controllers\ImageController;
use Modules\Leguo\App\Http\Controllers\OrderController;
use Modules\Leguo\App\Http\Controllers\PartnerController;

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('leguo', fn (Request $request) => $request->user())->name('leguo');
// });

Route::group(['middleware' => 'auth:api', 'prefix' => 'leguo'], function () {
    Route::group(['prefix' => 'website'], function () {
        Route::get('/', [WebsiteController::class, 'index']);
        Route::put('/', [WebsiteController::class, 'update']);
    });

    Route::apiResource('goods', GoodsController::class);

    Route::apiResource('goods-category', GoodsCategoryController::class);

    Route::apiResource('partner', PartnerController::class);

    Route::apiResource('order', OrderController::class)->except(['update', 'destroy']);

    Route::post('images/upload', [ImageController::class, 'upload']);
});


