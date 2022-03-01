<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * クイズテーブルを作成するマイグレーションクラス
 */
class CreateQuizzesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('quizzes', function(Blueprint $table) {
      $table->increments('id');
      $table->string('title',255)->default("");
      $table->string ('question',1000)->default("");
      $table->string('image_path',255)->default("");
      $table->integer('limit_seconds')->default(0);
      $table->integer('description_stetho_sound_id')->unsigned()->nullable();
      $table->timestamps();

      // 外部キー制約
      $table->foreign('description_stetho_sound_id')
            ->references('id')
            ->on('stetho_sounds');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('quizzes');
  }
}
