<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * クイズパック利用クイズテーブルを作成するマイグレーションクラス
 */
class CreateQuizQuizPackTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('quiz_quiz_pack', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('quiz_pack_id')->unsigned();
      $table->integer('quiz_id')->unsigned();
      $table->integer('disp_order')->default(0);

          // 外部キー制約
      $table->foreign('quiz_pack_id')
            ->references('id')
            ->on('quiz_packs');
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
    Schema::drop('quiz_quiz_pack');
  }
}
