<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MemberLoginRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize() {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'user' => 'required|max:1000',
            'password' => 'required|max:1000',
            // 'product_no' => 'required|min:8|max:16|regex:/[0-9\-]/|',
            'mail_address' => 'required',
        ];
    }

    public function messages() {
        return [
            'user.required' => 'ユーザIDを入力してください。',
            'user.max' => 'ユーザIDを入力してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.max' => '正しいパスワードを入力してください。',
            'product_no.required' => 'シリアル番号を入力してください。',
            'product_no.min' => '正しいシリアル番号を入力してください。',
            'product_no.max' => '正しいシリアル番号を入力してください。',
            'product_no.regex' => '正しいシリアル番号を入力してください。',
        ];
    }

}
