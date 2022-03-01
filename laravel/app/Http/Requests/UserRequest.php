<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;

class UserRequest extends Request
{
  protected $dates = ['updated_at'];

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    // POST用
    $rules = [
      'name' => 'required|max:255',
      'email' => 'required|unique:users|email',
      'enabled' => 'required',
      'password' => 'required',
      'updated_at' => ''
    ];

    if ($this->method() == "PUT" || $this->method() == "PATCH") {
      $user = User::find($this->users);
      $rules['email'] = 'required|unique:users,email,'.$user->id.'|email';
      $rules['password'] = 'required|min:6';
    }   
    return $rules;
  }

  public function messages()
  {
      return [
          'name.required' => '氏名は必須です。',
          'name.max' => '氏名は255文字まで入力可能です。',
          'email.email' => '有効なメールアドレスを入力してください。',
          'email.required' => 'メールアドレスは必須です。',
          'password.required' => 'パスワードは必須です。',
          'name.required' => '氏名は必須です。',
          'email.unique' => 'そのメールアドレスは既に使用されています。', 
          'password.min' => 'パスワードは6文字以上にする必要があります。'
      ];
  }
}
