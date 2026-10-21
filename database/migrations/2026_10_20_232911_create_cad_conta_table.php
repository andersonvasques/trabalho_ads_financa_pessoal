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
        Schema::create('cad_conta', function (Blueprint $table) {
            $table->id('id_conta');
            $table->foreignId('id_usuario');
            $table->string('nome_conta', 60);
            $table->string('tipo_conta', 20);
            $table->string('instituicao_conta', 60)->nullable();
            $table->decimal('saldo_inicial_conta', 15, 2)->default(0);
            $table->boolean('cad_ativo')->default(true);
            $table->dateTime('criado_em_conta')->nullable();
            $table->dateTime('atualizado_em_conta')->nullable();
            $table->dateTime('excluido_em_conta')->nullable();

            $table->foreign('id_usuario', 'FK_cad_conta_users')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();

            $table->index('id_usuario', 'IX_cad_conta_usuario');
        });

        DB::statement("ALTER TABLE cad_conta ADD CONSTRAINT CK_cad_conta_tipo
            CHECK (tipo_conta IN ('corrente', 'poupanca', 'carteira', 'investimento'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cad_conta');
    }
};
