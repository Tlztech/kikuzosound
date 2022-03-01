<?php

use Illuminate\Database\Seeder;
use Laracasts\TestDummy\Factory as TestDummy;
use App\StethoSound;
use App\StethoSoundImage;

class StethosSoundsTableSeeder extends Seeder {
  public function run()
  {
    DB::table('stetho_sounds')->delete();
    DB::table('stetho_sound_images')->delete();

    // DB::table('quiz_stetho_sound')->truncate();
    // DB::table('stetho_sound_images')->truncate();
    // DB::table('comments')->truncate();
    // StethoSound::unguard();
    // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    // DB::table('stetho_sounds')->truncate();
    // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    // StethoSound::reguard();

    // // コンテンツのサンプルデータを300件作成
    $stetho_sounds = factory(StethoSound::class, 10)->create()->each(function($s){
      $from_path = base_path() . '/database/seeds/sounds/musicbox.mp3';
      $to_path = public_path() . '/audio/stetho_sounds/' . $s->id . '.mp3';
      if ( File::copy($from_path, $to_path)) {
         $s->sound_path = '/audio/stetho_sounds/' . $s->id . '.mp3';
         $s->save();
      }
      factory(StethoSoundImage::class, 2)->create(['stetho_sound_id' => $s->id])->each(function ($stetho_sound_image) {
        $from_img_path = base_path() . '/database/seeds/images/slidetest.jpg';
        $to_img_path = public_path() . '/img/stetho_sound_images/' . $stetho_sound_image->id . '.jpg';
        if ( File::copy($from_img_path, $to_img_path)) {
          $stetho_sound_image->image_path = '/img/stetho_sound_images/' . $stetho_sound_image->id . '.jpg';
          $stetho_sound_image->save();
        }
      });
    });
  }
}