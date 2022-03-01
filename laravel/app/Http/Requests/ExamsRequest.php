<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Exams;

class ExamsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'exam_name_jp' => 'required|max:10000',
            'exam_name' => 'required|max:10000',
            'exam_group' => 'required',
            'quiz_pack' => 'required',
            'destination_email'=>'required|email'
        ];
    }

  public function messages()
  {
      return [
          'exam_name_jp.required' => '試験名（JP）は必須です。',
          'exam_name.required' => '試験名（EN）は必須です。',
          'exam_name.max' => '試験名は10000文字以内で入力してください。',
          'exam_group.required' => 'Examグループ名は必須です。',
          'quiz_pack.required' => 'クイズパックは必須です。',
          'destination_email.required' => '結果送付先emailは必須です。',
          'destination_email.email' => '有効なメールアドレスを入力してください。'
      ];
  }
}
