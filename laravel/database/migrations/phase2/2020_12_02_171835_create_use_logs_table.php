<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->nullable()->default(NULL);
            $table->integer('user_id')->nullable(false);
            $table->integer('university_id')->nullable()->default(NULL);
            $table->integer('exam_id')->nullable()->default(NULL);
            $table->integer('quiz_pack_id')->nullable()->default(NULL);
            $table->integer('quiz_id')->nullable()->default(NULL);
            $table->string('quiz_type')->nullable()->default(NULL);
            $table->integer('lib_id')->nullable()->default(NULL);
            $table->dateTime('stt_time')->nullable()->default(NULL);
            $table->dateTime('end_time')->nullable()->default(NULL);
            $table->boolean('is_correct')->nullable()->default(NULL);
            $table->string('question_log')->nullable()->default(NULL);
            $table->string('anwser_log')->nullable()->default(NULL);
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
        Schema::drop('use_logs');
    }
}
