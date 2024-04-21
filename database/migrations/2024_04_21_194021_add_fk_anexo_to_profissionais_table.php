<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkAnexoToProfissionaisTable extends Migration
{
    public function up()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            // Adiciona a coluna fk_anexo
            $table->unsignedBigInteger('fk_anexo')->nullable()->after('email');

            // Estabelece a relação de chave estrangeira
            $table->foreign('fk_anexo')->references('id')->on('anexos')
                  ->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            // Remove a chave estrangeira e a coluna
            $table->dropForeign(['fk_anexo']);
            $table->dropColumn('fk_anexo');
        });
    }
}
