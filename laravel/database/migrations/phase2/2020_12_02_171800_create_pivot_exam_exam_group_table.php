<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotExamExamGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_exam_exam_group', function (Blueprint $table) {
            $table->integer('exam_id')->unsigned()->nullable(false);
            $table->integer('exam_group_id')->unsigned()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pivot_exam_exam_group');
    }
}
