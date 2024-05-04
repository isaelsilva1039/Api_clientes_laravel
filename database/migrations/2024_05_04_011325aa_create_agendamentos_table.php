<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medico_id'); // ID do médico
            $table->unsignedBigInteger('cliente_id'); // ID do cliente
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status')->default('pendente'); // Opções: pendente, confirmado, cancelado
            $table->timestamps();
        
            // Chaves estrangeiras
            $table->foreign('medico_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos');
    }
}
