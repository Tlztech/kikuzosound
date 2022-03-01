<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('description_stethoscope_id')->nullable()->default(NULL);
            $table->integer('description_palpation_id')->nullable()->default(NULL);
            $table->integer('description_ecg_id')->nullable()->default(NULL);
            $table->integer('description_inspection_id')->nullable()->default(NULL);
            $table->integer('description_xray_id')->nullable()->default(NULL);
            $table->integer('description_echo_id')->nullable()->default(NULL);
            $table->string('case', 1000)->nullable()->default(NULL);
            $table->string('case_en', 1000)->nullable()->default(NULL);
            $table->integer('case_age')->nullable()->default(NULL);
            $table->integer('case_gender')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            //
        });
    }
}
