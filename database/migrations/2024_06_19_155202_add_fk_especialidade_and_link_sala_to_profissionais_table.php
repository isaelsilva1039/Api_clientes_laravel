<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkEspecialidadeAndLinkSalaToProfissionaisTable extends Migration
{
    public function up()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_especialidade')->nullable();
            $table->string('link_sala')->nullable();

            $table->foreign('fk_especialidade')->references('id')->on('especialidades')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropForeign(['fk_especialidade']);
            $table->dropColumn('fk_especialidade');
            $table->dropColumn('link_sala');
        });
    }
}
