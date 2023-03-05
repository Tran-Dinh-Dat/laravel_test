<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Resources\UserResource;
use App\LaravelPermission\Acl;
use Illuminate\Http\Request;
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

Route::post('auth/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Auth routes
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    // Api resource routes
    Route::apiResource('roles', RoleController::class)->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('users', UserController::class)->middleware('permission:' . Acl::PERMISSION_USER_MANAGE);
    Route::apiResource('permissions', PermissionController::class)->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);

    // Custom routes
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', [UserController::class, 'updatePermissions'])->middleware('permission:' .Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
});