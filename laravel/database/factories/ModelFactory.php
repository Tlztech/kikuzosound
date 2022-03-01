<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use App\StethoSoundImage;
use App\StethoSound;
use App\Comment;
use App\User;
use App\QuizPack;
use App\Quiz;

$factory->define(User::class, function (Faker\Generator $faker) {
  return [
    'name' => $faker->name,
    'email' => $faker->safeEmail,
    'password' => bcrypt(str_random(10)),
    'remember_token' => str_random(10),
  ];
});

$factory->define(Comment::class, function (Faker\Generator $faker) {
  return [
    'text' => $faker->text
  ];
});

$factory->define(StethoSound::class, function ($faker) {
  $faker->addProvider(new Faker\Provider\Base($faker));
  // 監修者のIDを取得する
  $user_id = User::superintendents()->first()->id;
  $status = $faker->numberBetween(0,3);
  $is_public = ($status == 2 || $status == 3);
  return [
    'user_id' => $user_id,
    'title' => $faker->name,
    'sound_path' => '/audio/stetho_sounds/hoge.mp3',
    'type' => $faker->randomElement([1,2,3,9]),
    'area' => $faker->randomElement(['右肺葉','左肺葉']),
    'conversion_type' => '0',
    'is_normal' => $faker->numberBetween(0,1) ,
    'disease' => $faker->name . 'の代表疾患',
    'description' => $faker->text,
    'status' => $status,
    'is_public' => $is_public,
    'disp_order' => 0,
  ];
});

$factory->define(StethoSoundImage::class, function ($faker) {
  $faker->addProvider(new Faker\Provider\Base($faker));
  return [
    'title' => $faker->name,
    'image_path' => '/img/stetho_sound_images/hoge.png',
    'disp_order' => 0
  ];
});

$factory->define(QuizPack::class, function ($faker) {
  $faker->addProvider(new Faker\Provider\Base($faker));
  return [
      'title' => $faker->name,
      'title_color' => '#ff0022',
      'description' => "5問出題します",
      'icon_path' => 'img/no_image.png',
      'quiz_order_type' => $faker->numberBetween(0,1),
      'max_quiz_count' => '5',
      'is_public' => '公開',
      'disp_order' => '0'
  ];
});

$factory->define(Quiz::class, function ($faker) {
  $faker->addProvider(new Faker\Provider\Base($faker));
  return [
        'title' => $faker->randomElement(['肺音問題(サンプル)','心音問題(サンプル)','腸音問題(サンプル)']),
        'question' => '聴診器の音の種類を次の内から選択せよ。',
        'image_path' => 'img/no_image.png',
        'limit_seconds' => $faker->randomElement(['0','30']),
  ];
});

