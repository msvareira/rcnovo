<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordem_servico', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('cliente_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('funcionario_id')->unsigned();
            $table->date('data');
            $table->enum('tipo', ['OS', 'Orçamento']);
            $table->text('descricao');
            $table->text('observacao');
            $table->enum("Filial",['RC','Hermann']);
            $table->string('solicitante');

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordem_servico');
    }
};
