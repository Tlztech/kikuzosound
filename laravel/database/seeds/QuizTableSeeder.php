<?php

use Illuminate\Database\Seeder;
use Laracasts\TestDummy\Factory as TestDummy;
use App\Quiz;
use App\QuizPack;
use App\QuizQuizPack;
use App\QuizChoice;

class QuizTableSeeder extends Seeder {
  public function run()
  {
    DB::table('quiz_quiz_pack')->delete();
    DB::table('quiz_choices')->delete();
    DB::table('quizzes')->delete();
    DB::table('quiz_packs')->delete();

    // クイズパックのサンプルデータを作成
    $quiz_packs = factory(QuizPack::class, 3)->create()->each(function($q){
      $from_path = base_path() . '/database/seeds/images/quiz_pack1.png';
      $to_path = public_path() . '/img/quiz_packs/' . $q->id . '.png';
      if ( File::copy($from_path, $to_path)) {
         $q->icon_path = '/img/quiz_packs/' . $q->id . '.png';
         $q->save();
      }
      // クイズ
      $quiz_packs = factory(Quiz::class, 3)->create()->each(function($quiz) use ($q) {
        $from_img_path = base_path() . '/database/seeds/images/slidetest.jpg';
        $to_img_path = public_path() . '/img/quizzes/' . $quiz->id . '.jpg';
        if ( File::copy($from_img_path, $to_img_path)) {
          $quiz->image_path = '/img/quizzes/' . $quiz->id . '.jpg';
          $quiz->save();
        }

        QuizQuizPack::create([
          'quiz_id' => $quiz->id,
          'quiz_pack_id' => $q->id
        ]);
        // クイズの回答選択肢を作成する
        $answer1 = QuizChoice::create([
          'title' => '1.呼吸音',
          'quiz_id' => $quiz->id,
          'disp_order' => '0',
          'is_correct' => '1'
          ]);
        $answer2 = QuizChoice::create([
          'title' => '2.笛声音',
          'quiz_id' => $quiz->id,
          'disp_order' => '1',
          'is_correct' => '0'
          ]);
      });
    });
  }
}