<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Mes', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->unsignedBigInteger('user_id'); // Relacionamento com o usuário
            $table->string('mes'); // Nome do mês
            $table->boolean('isActive')->default(false); // Status de ativo ou inativo
            $table->integer('value'); // Valor representando o mês (começando de 0)
            $table->timestamps(); // Campos padrão created_at e updated_at

            // Definição de chave estrangeira para o usuário
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Mes');
    }
}
