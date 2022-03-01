<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->delete();
        DB::table('stetho_sound_images')->delete();
        DB::table('quiz_stetho_sound')->delete();
        DB::table('stetho_sounds')->delete();
        DB::table('users')->delete();

        User::create([
            'name' => '川村',
            'email' => 's_kawamura@globals.jp',
            'password' => bcrypt('password'),
            'role' => User::$ROLE_ADMIN,
            'timezone' => 'Asia/Tokyo',
        ]);
        User::create([
            'name' => '富田',
            'email' => 'tomita@globals.jp',
            'password' => bcrypt('password'),
            'role' => User::$ROLE_SUPERINTENDENT,
            'timezone' => 'Asia/Tokyo',
        ]);
    }
}
