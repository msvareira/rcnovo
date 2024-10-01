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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255)->nullable();
            $table->string('rg', 50)->nullable()->unique();
            $table->string('cpf', 50)->nullable()->unique();
            $table->string('carta_motorista', 50)->nullable()->unique();
            $table->double('carga_horaria_dia')->nullable();
            $table->double('valor_hora_extra')->nullable();
            $table->double('salario')->nullable();
            $table->mediumText('digital')->nullable();
            $table->integer('cod_cracha')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
