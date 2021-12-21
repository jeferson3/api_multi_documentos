<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/auth'], function () {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
    Route::post('/me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);
});

Route::group(['prefix' => '/', 'middleware' => 'authApi'], function () {
    Route::apiResource('/company', \App\Http\Controllers\Company\CompanyController::class);
    Route::apiResource('/user', \App\Http\Controllers\User\UserController::class);
    Route::apiResource('/document', \App\Http\Controllers\Document\DocumentController::class);
});
