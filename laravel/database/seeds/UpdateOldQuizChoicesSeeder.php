<?php

use Illuminate\Database\Seeder;
use App\QuizChoice;

class UpdateOldQuizChoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizChoice::whereNull('lib_type')->update(['lib_type'=>0]);
    }
}
