<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustEspecialidadesTable extends Migration
{
    public function up()
    {
        Schema::table('especialidades', function (Blueprint $table) {
            // Remover colunas desnecessárias se existirem
            if (Schema::hasColumn('especialidades', 'plano_id')) {
                $table->dropForeign(['plano_id']);
                $table->dropColumn('plano_id');
            }
            if (Schema::hasColumn('especialidades', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('especialidades', 'updated_at')) {
                $table->dropColumn('updated_at');
            }

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

            // Reverter as colunas removidas
            $table->foreignId('plano_id')->nullable()->constrained('planos')->onDelete('cascade');
            $table->timestamps();
        });
    }
}
