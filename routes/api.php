<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Middleware\XSS;

Route::middleware(['throttle:api'])->group(function () {
    Route::controller(CourseController::class)->group(function () {
        Route::get('/courses', 'index');
        Route::post('/courses', 'store');
        Route::get('/courses/{course}', 'show');
        Route::put('/courses/{course}', 'update');
        Route::patch('/courses/{course}', 'patch');
        Route::delete('/courses/{course}', 'destroy');
    });
});