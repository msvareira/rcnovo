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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->unique();
            $table->string('modelo');
            $table->year('ano');
            $table->integer('quilometragem_inicial');
            $table->date('data_seguro')->nullable();
            $table->date('data_inspecao')->nullable();
            $table->enum('status', ['Disponível', 'Em uso', 'Em manutenção', 'Aguardando inspeção']);
            $table->timestamps();
        });

        Schema::create('manutencoes_veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            $table->date('data');
            $table->string('tipo_servico');
            $table->decimal('custo', 10, 2);
            $table->date('proxima_manutencao')->nullable();
            $table->timestamps();
        });

        Schema::create('reservas_veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->date('data_reserva');
            $table->enum('prioridade', ['Normal', 'Alta']);
            $table->timestamps();
        });

        Schema::create('abastecimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            $table->integer('quilometragem');
            $table->decimal('litros', 8, 2);
            $table->decimal('custo', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
