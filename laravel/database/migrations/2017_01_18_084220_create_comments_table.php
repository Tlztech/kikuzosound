<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 監修者コメントテーブルを作成するマイグレーションクラス
 */
class CreateCommentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('stetho_sound_id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->string('text', 2000);
      $table->timestamps();

      $table->foreign('stetho_sound_id')
            ->references('id')
            ->on('stetho_sounds');
      $table->foreign('user_id')
            ->references('id')
            ->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('comments');
  }
}
