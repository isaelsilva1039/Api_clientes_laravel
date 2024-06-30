<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlanosTable extends Migration
{
    public function up()
    {
        Schema::table('planos', function (Blueprint $table) {
            $table->text('descricao')->change();
            $table->boolean('fidelidade')->change();
            $table->string('periodo_fidelidade')->nullable()->change();
            $table->decimal('valor', 8, 2)->change();
            $table->json('especialidades')->nullable(); // Adiciona a coluna 'especialidades' como JSON
        });
    }

    public function down()
    {
        Schema::table('planos', function (Blueprint $table) {
            $table->text('descricao')->change();
            $table->tinyInteger('fidelidade')->change(); // Reverta para o tipo original, se necessário
            $table->string('periodo_fidelidade')->nullable(false)->change();
            $table->decimal('valor', 8, 2)->change();
            $table->dropColumn('especialidades'); // Remove a coluna 'especialidades' no rollback
        });
    }
}
