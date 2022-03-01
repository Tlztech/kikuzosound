<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToQuizChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_choices', function (Blueprint $table) {
            $table->tinyInteger('lib_type')->nullable()->default(NULL);
            $table->tinyInteger('is_fill_in')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_choices', function (Blueprint $table) {
            //
        });
    }
}
