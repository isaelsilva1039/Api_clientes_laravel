<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    



    public function up()
{
    Schema::table('profissionais', function (Blueprint $table) {
        $table->string('avatar')->nullable(); // Permite NULL se o avatar não for obrigatório
    });
}

public function down()
{
    Schema::table('profissionais', function (Blueprint $table) {
        $table->dropColumn('avatar');
    });
}



};
