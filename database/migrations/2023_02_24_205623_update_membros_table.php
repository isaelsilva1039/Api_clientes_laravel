<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membros', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membros', function (Blueprint $table) {

                    $table->string('nome_membro')->nullable();
                    $table->string('email_dizimista')->nullable();
                    $table->string('cidade')->nullable();
                    $table->string('bairro')->nullable();
                    $table->string('endereco')->nullable();
                    $table->string('telefone')->nullable();
                    $table->boolean('batismo_agua')->nullable();
                    $table->date('data_nascimento')->nullable();
                    $table->string('cargo')->nullable();
                    $table->string('situacao')->nullable();
                    $table->unsignedBigInteger('fk_igreja')->nullable();
                    $table->date('data_batismo_espirito_santo')->nullable();
                    $table->enum('sexo', ['M', 'F'])->nullable();
        });
    }
};
