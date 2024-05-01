<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyHorariosSemanaisTable extends Migration
{
    public function up()
    {
        Schema::table('horarios_semanais', function (Blueprint $table) {
            $table->json('horarios')->nullable(); // Adiciona a coluna de horários como JSON
            $table->dropColumn(['dia_da_semana', 'hora_inicio', 'hora_fim']); // Remove as colunas antigas
        });
    }

    public function down()
    {
        Schema::table('horarios_semanais', function (Blueprint $table) {
            $table->string('dia_da_semana');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->dropColumn('horarios'); // Reverte a adição do campo JSON
        });
    }
}

