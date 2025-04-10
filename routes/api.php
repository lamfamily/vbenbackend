<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeptController;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PermissionController;

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

// for testing jwt-auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('jwt-login', [JwtAuthController::class, 'login']);
    Route::post('jwt-logout', [JwtAuthController::class, 'logout']);
    Route::post('jwt-refresh', [JwtAuthController::class, 'refresh']);
    Route::post('jwt-me', [JwtAuthController::class, 'me']);
});

// 公开的API
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
});

// 需要认证的API
Route::group(['middleware' => 'auth:api'], function () {
    // 认证相关
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('codes', [AuthController::class, 'codes']);
    });

    Route::get('user/info', [AuthController::class, 'me'])->middleware('auth:api');

    // 用户菜单
    // Route::get('user-menu', [MenuController::class, 'userMenu']);

    // 菜单管理
    // Route::apiResource('menus', MenuController::class);

    // 用户管理
    // Route::apiResource('users', UserController::class);

    // 角色管理
    // Route::apiResource('roles', RoleController::class);

    // 权限管理
    // Route::apiResource('permissions', PermissionController::class);

    Route::get('menu/all', [MenuController::class, 'userMenu']);

    Route::group([
        'prefix' => 'system',
    ], function() {
        // role api
        Route::get('role/list', [RoleController::class, 'index']);
        Route::post('role', [RoleController::class, 'store']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
        Route::put('role/{role}', [RoleController::class, 'update']);


        // menu api
        Route::get('menu/list', [MenuController::class, 'index']);
        Route::get('menu/name-exists', [MenuController::class, 'checkNameExists']);
        Route::get('menu/path-exists', [MenuController::class, 'checkPathExists']);
        Route::get('menu/{menu}', [MenuController::class, 'show']);
        Route::get('user-menu', [MenuController::class, 'userMenu']);
        Route::delete('menu/{id}', [MenuController::class, 'destroy']);
        Route::post('menu', [MenuController::class, 'store']);
        Route::put('menu/{menu}', [MenuController::class, 'update']);

        // dept api
        Route::get('dept/list', [DeptController::class, 'index']);
        Route::post('dept', [DeptController::class, 'store']);
        Route::get('dept/{dept}', [DeptController::class, 'show']);
        Route::put('dept/{dept}', [DeptController::class, 'update']);
        Route::delete('dept/{id}', [DeptController::class, 'destroy']);

        // user api
        Route::get('user/list', [UserController::class, 'index']);
        Route::put('user/{user}', [UserController::class, 'update']);
        Route::delete('user/{id}', [UserController::class, 'destroy']);
        Route::post('user', [UserController::class, 'store']);
    });


});
