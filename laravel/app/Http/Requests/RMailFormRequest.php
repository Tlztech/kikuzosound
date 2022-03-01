<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RMailFormRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group'=>'required|max:50',
            'name1'=>'required|max:50',
            'name2'=>'required|max:50',
            'kana1'=>'max:50',
            'kana2'=>'max:50',
            'password'=>'required|min:5|max:30|regex:/^[0-9a-zA-Z]{5,30}$/',
            'gender'=>'required',
//            'services1'=>'required',
            'mail'=>'required|email|max:200',
//            'tel'=>'required|min:9|max:20|regex:/[0-9\-]/|',
            'tel'=>'min:9|max:20|regex:/[0-9\-]/|'
        ];
    }


    public function messages()
    {
        // NOTE: 属性名を resources/lang/validation.php に書くと管理画面と競合する問題を解決できなかったため記載した
        $mail_required = trans('validation.mail_required');
        $mail_email = trans('validation.mail_email');
        $mail_max = trans('validation.mail_max');
        $password_required = trans('validation.password_required');
        $password_regex = trans('validation.password_regex');
        $name1_required = trans('validation.name1_required');
        $name1_max = trans('validation.name1_max');
        $name2_required = trans('validation.name2_required');
        $name2_max = trans('validation.name2_max');
        $kana1_required = trans('validation.kana1_required');
        $kana1_max = trans('validation.kana1_max');
        $kana2_required = trans('validation.kana2_required');
        $kana2_max = trans('validation.kana2_max');
        $group_required = trans('validation.group_required');
        $group_max = trans('validation.group_max');
        $gender_required = trans('validation.gender_required');

        $tel_min = trans('validation.tel_min');
        $tel_max = trans('validation.tel_max');
        $tel_regex = trans('validation.tel_regex');

        return [
            'group.required'    => $group_required,
            'group.max'         => $group_max,
            'name1.required'    => $name1_required,
            'name1.max'         => $name1_max,
            'name2.required'    => $name2_required,
            'name2.max'         => $name2_max,
            'kana1.required'    => $kana1_required,
            'kana1.max'         => $kana1_max,
            'kana2.required'    => $kana2_required,
            'kana2.max'         => $kana2_max,
            'mail.required'     => $mail_required,
            'mail.email'        => $mail_email,
            'mail.max'          => $mail_max,
            'password.required' => $password_required,
            'password.regex'    => $password_regex,
            'gender.required'   => $gender_required,
            'tel.min'           => $tel_min,
            'tel.max'           => $tel_max,
            'tel.regex'         => $tel_regex
        ];
    }
}
