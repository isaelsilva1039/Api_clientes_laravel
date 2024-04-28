<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromCpfInProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropUnique('profissionais_cpf_unique');  // Nome da constraint pode variar
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->unique('cpf');  // Adicionar novamente a restrição ao reverter a migration
        });
    }
}
