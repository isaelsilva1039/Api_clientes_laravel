<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanoIdToEspecialidadesTable extends Migration
{
    public function up()
    {
        Schema::table('especialidades', function (Blueprint $table) {
            $table->foreignId('plano_id')->nullable()->constrained('planos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('especialidades', function (Blueprint $table) {
            $table->dropForeign(['plano_id']);
            $table->dropColumn('plano_id');
        });
    }
}
