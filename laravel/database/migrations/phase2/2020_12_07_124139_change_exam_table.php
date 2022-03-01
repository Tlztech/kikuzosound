<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE exam CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        DB::statement("ALTER TABLE exam MODIFY name_jp TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam', function (Blueprint $table) {
            //
        });
    }
}
