<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * クイズパックテーブルを作成するマイグレーションクラス
 */
class CreateQuizPacksTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('quiz_packs', function(Blueprint $table) {
      $table->increments('id');
      $table->string('title',255);
      $table->string ('title_color',7);
      $table->string('description',255);
      $table->string('icon_path',255);
      $table->integer('quiz_order_type')->default(0);
      $table->integer('max_quiz_count')->default(0);
      $table->boolean('is_public')->default(0);
      $table->integer('disp_order')->default(0);
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
    Schema::drop('quiz_packs');
  }
}
