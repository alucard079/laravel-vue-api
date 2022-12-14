<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('/verify/{email}', [AuthController::class, 'verify'])->name('verify');
Route::middleware('auth:api', 'verified')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/permissions/all', [PermissionController::class, 'allPermission'])->name('all-permission');

    Route::resource('permissions', PermissionController::class)->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]);

    Route::get('/roles/all', [RoleController::class, 'allRole'])->name('all-role');

    Route::resource('roles', RoleController::class)->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]);

    Route::resource('users', UserController::class)->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]);
});
