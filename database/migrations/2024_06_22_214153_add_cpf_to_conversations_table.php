<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCpfToConversationsTable extends Migration
{
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->string('cpf')->nullable()->after('phone_number');
        });
    }

    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('cpf');
        });
    }
}
