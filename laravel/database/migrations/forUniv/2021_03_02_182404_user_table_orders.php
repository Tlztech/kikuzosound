<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_table_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->default(NULL);
            $table->string('table')->nullable()->default(NULL);
            $table->longText('order')->nullable()->default(NULL);
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
        Schema::table('user_table_orders', function (Blueprint $table) {
            //
        });
    }
}
