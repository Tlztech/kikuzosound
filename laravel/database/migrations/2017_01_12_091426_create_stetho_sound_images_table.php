<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 聴診音画像テーブルを作成するマイグレーションクラス
 */
class CreateStethoSoundImagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stetho_sound_images', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('stetho_sound_id')->unsigned();
      $table->string('title', 255);
      $table->string('image_path', 255);
      $table->integer('disp_order')->default(0);
      $table->timestamps();

      // 外部キー制約
      $table->foreign('stetho_sound_id')
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
    Schema::drop('stetho_sound_images');
  }
}
