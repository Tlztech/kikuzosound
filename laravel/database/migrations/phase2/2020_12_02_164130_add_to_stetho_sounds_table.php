<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToStethoSoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stetho_sounds', function (Blueprint $table) {
            $table->string('video_path')->nullable()->default(NULL);
            $table->string('image_path')->nullable()->default(NULL);
            $table->tinyInteger('lib_type')->nullable()->default(0);
            $table->string('explanatory_image')->nullable()->default(NULL);
            $table->string('explanatory_image_en')->nullable()->default(NULL);
            $table->string('body_image')->nullable()->default(NULL);
            $table->text('image_description')->nullable()->default(NULL);
            $table->text('image_description_en')->nullable()->default(NULL);
            $table->string('configuration')->nullable()->default(NULL);
            $table->string('content_group')->nullable()->default(NULL);
            $table->integer('sort')->nullable()->default(NULL);
            $table->string('coordinate')->nullable()->default(NULL);
            $table->text('supervisor_comment')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stetho_sounds', function (Blueprint $table) {
            //
        });
    }
}
