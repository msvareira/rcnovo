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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razao', 255)->nullable();
            $table->string('fantasia', 255)->nullable();
            $table->string('cpf_cnpj', 255)->nullable();
            $table->string('rg_ie', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('cep', 255)->nullable();
            $table->string('estado', 255)->nullable();
            $table->string('cidade', 255)->nullable();
            $table->string('bairro', 255)->nullable();
            $table->string('rua', 255)->nullable();
            $table->string('numero', 255)->nullable();
            $table->string('complemento', 255)->nullable();
            $table->string('cod_ibge', 255)->nullable();
            $table->string('fone1', 255)->nullable()->unique();
            $table->string('fone2', 255)->nullable();
            $table->string('fone3', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->dateTime('datacad')->nullable();
            $table->string('ult_alteracao', 255)->nullable();
            $table->text('obs')->nullable();
            $table->enum('tipo_cadastro', ['CLIENTE', 'FORNECEDOR', 'AMBOS']);
            $table->timestamps();

            $table->unique(['cpf_cnpj', 'tipo_cadastro']);
            $table->unique(['rg_ie', 'tipo_cadastro']);
            $table->index('estado');
            $table->index('cidade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
