<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Universities;
use App\User;

class UnivAdminRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:10000',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'university_id'=>'required'
        ];
    }

    public function messages()
    {
        return [
          'name.required' => '氏名は必須です。',
          'email.email' => "メールは有効なメールアドレスである必要があります。",
          'email.required' => "メールアドレスは必須です。",
          'email.unique' => "このメールは既に使用されています。",
          'password.required' => "パスワードは必須です。",
          'university.required' => "大学のフィールドは必須です。",
          'password.min' => "パスワードは8文字以上にする必要があります。",
      ];
    }
}
