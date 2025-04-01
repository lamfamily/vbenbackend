<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JwtAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('jwt-login', [JwtAuthController::class, 'login']);
    Route::post('jwt-logout', [JwtAuthController::class, 'logout']);
    Route::post('jwt-refresh', [JwtAuthController::class, 'refresh']);
    Route::post('jwt-me', [JwtAuthController::class, 'me']);
});
