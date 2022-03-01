<?php

namespace App\Http\Requests\CustomerAdmin;

use App\Http\Requests\Request;

class TrialRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mail' => 'required|email|unique:trialmembers,mail'
        ];
    }

    public function messages()
    {
        return [
            'mail.required' =>'メールアドレスを入力してください。',
            'mail.email' => 'メールは有効なメールアドレスである必要があります。',
            'mail.unique' => '既存のメールアドレスは登録できません。'
        ];
    }
}
