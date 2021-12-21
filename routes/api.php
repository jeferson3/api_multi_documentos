<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
    Route::post('/me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);
});

Route::group(['prefix' => '/', 'middleware' => 'authApi'], function () {
    Route::apiResource('/companies', \App\Http\Controllers\Company\CompanyController::class);
    Route::apiResource('/users', \App\Http\Controllers\User\UserController::class);
    Route::apiResource('/documents', \App\Http\Controllers\Document\DocumentController::class);

    Route::group(['prefix' => 'permissions', 'middleware' => 'admin'], function (){
        Route::get('/', [\App\Http\Controllers\Permission\PermissionController::class, 'index']);
        Route::post('/setPermission', [\App\Http\Controllers\Permission\PermissionController::class, 'setPermission']);
        Route::post('/removePermission', [\App\Http\Controllers\Permission\PermissionController::class, 'removePermission']);
    });
});
