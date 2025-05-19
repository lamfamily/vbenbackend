<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'website'], function () {
        Route::get('/', [WebsiteController::class, 'index'])->name('website.index');
        Route::put('/', [WebsiteController::class, 'update'])->name('website.update');
    });
});
