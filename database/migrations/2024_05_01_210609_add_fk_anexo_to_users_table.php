<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkAnexoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna fk_anexo, que será a chave estrangeira
            $table->unsignedBigInteger('fk_anexo')->nullable()->after('cpf');

            // Estabelece a relação com a tabela anexos
            $table->foreign('fk_anexo')->references('id')->on('anexos')->onDelete('set null');
        });
    }

    /**
     * Revert the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove a chave estrangeira antes de remover a coluna
            $table->dropForeign(['fk_anexo']);
            // Remove a coluna fk_anexo
            $table->dropColumn('fk_anexo');
        });
    }
}
