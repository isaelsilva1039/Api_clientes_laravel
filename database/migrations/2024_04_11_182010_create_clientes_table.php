<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobilePhone')->nullable();
            $table->string('cpfCnpj')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('address')->nullable();
            $table->string('addressNumber')->nullable();
            $table->string('complement')->nullable();
            $table->string('province')->nullable();
            $table->string('externalReference')->nullable();
            $table->boolean('notificationDisabled')->default(false);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
