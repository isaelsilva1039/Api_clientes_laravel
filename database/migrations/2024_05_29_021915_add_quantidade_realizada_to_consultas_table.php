<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantidadeRealizadaToConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->integer('quantidade_realizada')->default(0); // Adiciona o campo com um valor padrão de 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('quantidade_realizada'); // Garante que pode remover a coluna se a migração for revertida
        });
    }
}
