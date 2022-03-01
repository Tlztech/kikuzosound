<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpirationMailStatusToOnetimeKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('onetime_keys', function (Blueprint $table) {
            $table->integer('expiration_mail_status')->default(0);
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
