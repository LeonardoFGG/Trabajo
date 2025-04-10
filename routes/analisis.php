<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'checktime'])->group(function () {
    Route::middleware(['password_expired'])->group(function () {
        Route::get('indexAnalisis', [App\Http\Controllers\Analisis\AnalisisController::class, 'index'])
            ->name('analisis.indexAnalisis');
    });
});

Route::middleware(['auth', 'checktime'])->group(function () {
    Route::middleware(['password_expired'])->group(function () {
        Route::get('indexGestion', [App\Http\Controllers\Analisis\AnalisisController::class, 'indexGestion'])
            ->name('analisis.indexGestion');
    });
});

Route::middleware(['auth', 'checktime'])->group(function () {
    Route::middleware(['password_expired'])->group(function () {
        Route::get('indexActividades', [App\Http\Controllers\Analisis\AnalisisController::class, 'indexActividades'])
            ->name('analisis.indexActividades');
    });
});

Route::middleware(['auth', 'checktime'])->group(function () {
    Route::middleware(['password_expired'])->group(function () {
        Route::get('indexMatriz', [App\Http\Controllers\Analisis\AnalisisController::class, 'indexMatriz'])
            ->name('analisis.indexMatriz');
    });
});

Route::middleware(['auth', 'checktime'])->group(function () {
    Route::middleware(['password_expired'])->group(function () {
        Route::get('indexSatisfaccion', [App\Http\Controllers\Analisis\AnalisisController::class, 'indexSatisfaccion'])
            ->name('analisis.indexSatisfaccion');
    });
});

Route::middleware(['auth', 'checktime'])->group(function () {
    Route::middleware(['password_expired'])->group(function () {
        Route::get('indexExterno', [App\Http\Controllers\Analisis\AnalisisController::class, 'indexExterno'])
            ->name('analisis.indexExterno');
    });
});



