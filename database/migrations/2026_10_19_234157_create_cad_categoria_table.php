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
        Schema::create('cad_categoria', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->foreignId('id_usuario')->nullable();
            $table->string('nome_categoria', 60);
            $table->string('tipo_categoria', 10);
            $table->string('icone_categoria', 50)->nullable();
            $table->char('cor_categoria', 7)->nullable();
            $table->dateTime('criado_em_categoria')->nullable();
            $table->dateTime('atualizado_em_categoria')->nullable();
            $table->dateTime('excluido_em_categoria')->nullable();

            $table->foreign('id_usuario', 'FK_cad_categoria_users')
                ->references('id')
                ->on('users')
                ->noActionOnDelete();
        });

        DB::statement("ALTER TABLE cad_categoria ADD CONSTRAINT CK_cad_categoria_tipo
            CHECK (tipo_categoria IN ('receita', 'despesa'))");

        DB::statement('CREATE UNIQUE INDEX UQ_cad_categoria_nome
            ON cad_categoria (id_usuario, nome_categoria, tipo_categoria)
            WHERE excluido_em_categoria IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cad_categoria');
    }
};
