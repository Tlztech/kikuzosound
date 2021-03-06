<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->call('UserTableSeeder');
    $this->command->info('User table seeded!');
    $this->call('QuizTableSeeder');
    $this->command->info('Quiz table seeded!');
    $this->call('StethosSoundsTableSeeder');
    $this->command->info('StethosSounds table seeded!');
  }
}
