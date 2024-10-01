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
        Schema::table('ordem_servico', function (Blueprint $table) {
            $table->string('status')->default('Aberto')->change();;
            $table->text('observacao')->nullable()->change();
            $table->string('descricao')->nullable()->change();
            $table->string('tipo')->nullable()->default('OS')->change();

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
