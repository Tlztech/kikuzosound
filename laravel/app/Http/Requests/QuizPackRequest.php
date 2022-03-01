<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class QuizPackRequest extends Request
{
  protected $dates = ['updated_at'];

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $quizzes = $this->input('quizzes');
    $max_quiz_count_rule = empty($quizzes) ? '' : '|max:'.count($quizzes);

    $rules = [
      'title' => 'required|max:255',
      'title_en' => 'required|max:10000',
      'title_color' => 'required',
      'description' => 'required',
      'description_en' => 'required|max:10000',
      'bg_img' => 'mimes:jpeg,jpg,png,gif',
      'quiz_order_type' => 'required|numeric',
      'max_quiz_count' => 'required|numeric|min:1'.$max_quiz_count_rule,
      'is_public' => 'required',
      'quizzes' => 'required|array|min:1',
      
    ];
    if ($this->method() == 'POST'||$this->method() == 'PUT') {
      $rules['bg_img'] = 'required_without:icon_path|mimes:jpeg,jpg,png,gif';
    }
    if ('PUT' == $this->method() || 'PATCH' == $this->method()) {
      $rules['bg_img'] = 'mimes:jpeg,jpg,png,gif';
    }
    return $rules;
  }

  public function messages()
  {
      return [
          'title.required' => 'タイトル(JP)は必須です。',
          'title_en.required'      => 'タイトル(EN)は必須です。',
          'title_en.max'      => 'タイトルは10000文字以内で入力してください。',
          'description.required' => '説明(JP)は必須です。',
          'description_en.required'    => '説明(EN)は必須です。',
          'description_en.max'    => '説明は10000文字以内で入力してください。',
          'bg_img.required_without' => 'アイコンは必須です。',
          'max_quiz_count.min'=>'出題数は少なくとも1問以上設定してください。',
          'quizzes.required' => 'クイズは必須です。',
          'bg_img.mimes' => 'アイコンは .jpg .gif .pngのみ設定できます。'

      ];
  }

  // 検証後に呼ばれる
  // 検証エラー時にユーザがアップロードしたファイルを再度アップロードしなくて良いようにするため。
  public function after($validator)
  {
    parent::after($validator);

    if( count($validator->invalid()) > 0 ) {
      // イラストファイルがアップロードされていた場合は、公開エリアに移動してパスをセッションに入れる。
      $this->setSessionAndMoveSingleFile('bg_img', '/tmp/quiz_packs/', 'tmp_quiz_pack_image_path');
    }
  }
}
