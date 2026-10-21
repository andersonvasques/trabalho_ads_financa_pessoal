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
        Schema::create('pla_orcamento', function (Blueprint $table) {
            $table->id('id_orcamento');
            $table->foreignId('id_usuario');
            $table->foreignId('id_categoria');
            $table->integer('ano_orcamento');
            $table->integer('mes_orcamento');
            $table->decimal('valor_limite_orcamento', 15, 2);
            $table->dateTime('criado_em_orcamento')->nullable();
            $table->dateTime('atualizado_em_orcamento')->nullable();

            $table->foreign('id_usuario', 'FK_pla_orcamento_users')
                ->references('id')->on('users')->noActionOnDelete();
            $table->foreign('id_categoria', 'FK_pla_orcamento_categoria')
                ->references('id_categoria')->on('cad_categoria')->noActionOnDelete();

            $table->unique(
                ['id_usuario', 'id_categoria', 'ano_orcamento', 'mes_orcamento'],
                'UQ_pla_orcamento_usuario_categoria_mes'
            );
        });

        DB::statement('ALTER TABLE pla_orcamento ADD CONSTRAINT CK_pla_orcamento_mes
            CHECK (mes_orcamento BETWEEN 1 AND 12)');

        DB::statement('ALTER TABLE pla_orcamento ADD CONSTRAINT CK_pla_orcamento_valor
            CHECK (valor_limite_orcamento > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pla_orcamento');
    }
};
