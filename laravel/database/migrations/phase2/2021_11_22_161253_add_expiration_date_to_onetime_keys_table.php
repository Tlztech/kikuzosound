<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpirationDateToOnetimeKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('onetime_keys', function (Blueprint $table) {
            Schema::table('onetime_keys', function (Blueprint $table) {
                $table->timestamp('expiration_date')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('onetime_keys', function (Blueprint $table) {
            //
        });
    }
}
