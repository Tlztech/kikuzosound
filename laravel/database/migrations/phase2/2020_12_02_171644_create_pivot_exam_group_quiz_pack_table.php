<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotExamGroupQuizPackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_exam_group_quiz_pack', function (Blueprint $table) {
            $table->integer('exam_group_id')->unsigned()->nullable(false);
            $table->integer('quiz_pack_id')->unsigned()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pivot_exam_group_quiz_pack');
    }
}
