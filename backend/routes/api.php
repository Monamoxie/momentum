<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "v1"], function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('tasks', TaskController::class);
    });
});
