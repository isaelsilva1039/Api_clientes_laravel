<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustEspecialidadesTableColumns extends Migration
{
    public function up()
    {
        Schema::table('especialidades', function (Blueprint $table) {
            // Adicionar colunas se não existirem
            if (!Schema::hasColumn('especialidades', 'nome')) {
                $table->string('nome');
            }
            if (!Schema::hasColumn('especialidades', 'valor')) {
                $table->string('valor');
            }
            if (!Schema::hasColumn('especialidades', 'quantidade_consultas')) {
                $table->integer('quantidade_consultas')->nullable();
            }
            if (!Schema::hasColumn('especialidades', 'sem_limite')) {
                $table->boolean('sem_limite');
            }
            if (!Schema::hasColumns('especialidades', ['created_at', 'updated_at'])) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('especialidades', function (Blueprint $table) {
            if (Schema::hasColumn('especialidades', 'nome')) {
                $table->dropColumn('nome');
            }
            if (Schema::hasColumn('especialidades', 'valor')) {
                $table->dropColumn('valor');
            }
            if (Schema::hasColumn('especialidades', 'quantidade_consultas')) {
                $table->dropColumn('quantidade_consultas');
            }
            if (Schema::hasColumn('especialidades', 'sem_limite')) {
                $table->dropColumn('sem_limite');
            }
            if (Schema::hasColumns('especialidades', ['created_at', 'updated_at'])) {
                $table->dropTimestamps();
            }
        });
    }
}
