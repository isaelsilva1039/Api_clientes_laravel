<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->string('nome', 100);
            $table->string('email', 100);
            $table->string('cpf', 14);
            $table->date('data_de_nascimento');
            $table->string('endereco', 100);
            $table->string('bairro', 100);
            $table->string('cidade', 100);
            $table->string('estado', 50);
            $table->string('celular', 20);
            $table->string('numero', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dependentes');
    }
}
