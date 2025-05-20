<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Leguo\App\Http\Controllers\GoodsCategoryController;
use Modules\Leguo\App\Http\Controllers\WebsiteController;

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
        Route::get('/', [WebsiteController::class, 'index'])->name('leguo.website.index');
        Route::put('/', [WebsiteController::class, 'update'])->name('leguo.website.update');
    });

    Route::apiResource('goods-category', GoodsCategoryController::class, [
        'as' => 'leguo',
        'names' => [
            // 真正的路由名称 leguo.goods-category.index, 即前面加leguo
            'index' => 'goods-category.index',
            'store' => 'goods-category.store',
            'show' => 'goods-category.show',
            'update' => 'goods-category.update',
            'destroy' => 'goods-category.destroy',
        ],
    ]);
});
