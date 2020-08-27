<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatCustomerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('last_name',255);
            $table->string('first_name',255);
            $table->timestamps();
        });

        Schema::create('customer_phone', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('customer_id');
            $table->decimal('phone', 15, 0);
        });

        Schema::create('customer_email', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('customer_id');
            $table->string('email', '255');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
        Schema::dropIfExists('customer_phone');
        Schema::dropIfExists('customer_email');
    }
}
