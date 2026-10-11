<?php

use Api\Models\Autenticacao\AutenticacaoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'api.',
], function () {
    Route::post('/login', [AutenticacaoController::class, 'login'])->name('login');
    Route::post('/register', [AutenticacaoController::class, 'register'])->name('register');

    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::post('/logout', [AutenticacaoController::class, 'logout'])->name('logout');
        Route::get('/me', [AutenticacaoController::class, 'me'])->name('me');
    });
});
