<?php

use Api\Models\Autenticacao\AutenticacaoController;
use Api\Models\Categoria\CategoriaController;
use Api\Models\Conta\ContaController;
use Api\Models\Lancamento\LancamentoController;
use Api\Models\Orcamento\OrcamentoController;
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

        Route::apiResource('categorias', CategoriaController::class)->parameters(['categorias' => 'id']);
        Route::apiResource('contas', ContaController::class)->parameters(['contas' => 'id']);
        Route::apiResource('lancamentos', LancamentoController::class)->parameters(['lancamentos' => 'id']);
        Route::apiResource('orcamentos', OrcamentoController::class)->parameters(['orcamentos' => 'id']);
    });
});
