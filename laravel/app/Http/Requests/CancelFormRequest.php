<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CancelFormRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question'=>'max:500|regex:/^(?!<(\S*?)[^>]*>.*?<\/\1>)/|regex:/^(?!<.*? \/>)/'
        ];
    }


    public function messages()
    {
        // NOTE: 属性名を resources/lang/validation.php に書くと管理画面と競合する問題を解決できなかったため記載した
        return [
/*
            'question.required' => 'お問合わせ内容を入力してください',
*/
            'question.max'      => '解約理由補足は500文字以内で入力してください。',
            'question.regex'    => '解約理由補足にHTMLを入力することはできません。'
        ];
    }
}
