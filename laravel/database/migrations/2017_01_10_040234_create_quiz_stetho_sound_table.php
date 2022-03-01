<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * テーブルを作成するマイグレーションクラス
 */
class CreateQuizStethoSoundTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('quiz_stetho_sound', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('stetho_sound_id')->unsigned();
      $table->integer('quiz_id')->unsigned();
      $table->integer('disp_order')->default(0);
      $table->string('description')->default("");

      // 外部キー制約
      $table->foreign('stetho_sound_id')
          ->references('id')
          ->on('stetho_sounds');
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
    Schema::drop('quiz_stetho_sound');
  }
}
