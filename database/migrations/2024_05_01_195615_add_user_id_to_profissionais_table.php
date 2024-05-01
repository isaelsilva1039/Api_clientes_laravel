<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            // Adiciona a coluna user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Define a chave estrangeira referenciando a tabela users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
            // Remove a chave estrangeira primeiro
            $table->dropForeign(['user_id']);

            // Remove a coluna user_id
            $table->dropColumn('user_id');
        });
    }
}
