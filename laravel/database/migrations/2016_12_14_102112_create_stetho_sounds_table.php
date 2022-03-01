<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 聴診音テーブル作成するためのマイグレーションクラス
 */
class CreateStethoSoundsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stetho_sounds', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->string('sound_path',255);
      $table->string('title',255);
      $table->integer('type')->default(0); // 0:その他 1:肺音 2:心音 3:腸音
      $table->string('area',255)->default("");
      $table->integer('conversion_type')->default(0); // 0:採取オリジナル 1:加工音 2:人工音
      $table->boolean('is_normal')->default(true); // 0:異常 1:正常
      $table->string('disease',255)->default("");
      $table->string('description',1000)->default("");
      $table->integer('status')->default(0); //0:監修中 1:監修済 2:公開中
      $table->boolean('is_public')->default(false);
      $table->integer('disp_order')->default(0); 
      $table->timestamps();

      // 外部キー制約
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
    Schema::dropIfExists('stetho_sounds');
  }
}
