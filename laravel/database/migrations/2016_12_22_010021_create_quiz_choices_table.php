<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * クイズ選択肢テーブルを作成するマイグレーションクラス
 */
class CreateQuizChoicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('quiz_choices', function(Blueprint $table) {
      $table->increments('id');
      $table->string('title',255);
      $table->integer('quiz_id')->unsigned();
      $table->integer('disp_order')->default(0);
      $table->boolean('is_correct')->default(false);

      // 外部キー制約
      $table->foreign('quiz_id')
            ->references('id')
            ->on('quizzes');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('quiz_choices');
  }
}
