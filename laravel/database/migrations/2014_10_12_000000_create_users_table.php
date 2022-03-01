<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * ユーザテーブルを作成するためのマイグレーションクラス
 */
class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('email')->unique();
      $table->string('password', 60);
      $table->integer('role')->default(0);  // 0:無償ユーザ 1:有償ユーザ 10:監修者 99:システム管理者
      $table->boolean('enabled')->default(true);
      $table->string('timezone')->default('UTC');
      $table->rememberToken();
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
    Schema::drop('users');
  }
}
