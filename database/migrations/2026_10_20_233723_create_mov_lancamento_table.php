<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mov_lancamento', function (Blueprint $table) {
            $table->id('id_lancamento');
            $table->foreignId('id_conta');
            $table->foreignId('id_categoria');
            $table->string('tipo_lancamento', 10);
            $table->string('descricao_lancamento', 150);
            $table->decimal('valor_lancamento', 15, 2);
            $table->date('data_lancamento');
            $table->date('data_pagamento_lancamento')->nullable();
            $table->string('observacao_lancamento', 500)->nullable();
            $table->dateTime('criado_em_lancamento')->nullable();
            $table->dateTime('atualizado_em_lancamento')->nullable();

            $table->foreign('id_conta', 'FK_mov_lancamento_conta')
                ->references('id_conta')
                ->on('cad_conta')
                ->noActionOnDelete();

            $table->foreign('id_categoria', 'FK_mov_lancamento_categoria')
                ->references('id_categoria')
                ->on('cad_categoria')
                ->noActionOnDelete();

            $table->index(['id_conta', 'data_lancamento'], 'IX_mov_lancamento_conta_data');
            $table->index(['id_categoria', 'data_lancamento'], 'IX_mov_lancamento_categoria_data');
        });

        DB::statement("ALTER TABLE mov_lancamento ADD CONSTRAINT CK_mov_lancamento_tipo
            CHECK (tipo_lancamento IN ('receita', 'despesa'))");

        DB::statement('ALTER TABLE mov_lancamento ADD CONSTRAINT CK_mov_lancamento_valor
            CHECK (valor_lancamento > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mov_lancamento');
    }
};
