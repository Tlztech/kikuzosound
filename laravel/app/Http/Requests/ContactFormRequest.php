<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactFormRequest extends Request
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
            'name'=>'required|max:50',
            'mail'=>'required|email',
            'tel'=>'required|digits_between:9,15',
            'question'=>'required|max:500|regex:/^(?!<(\S*?)[^>]*>.*?<\/\1>)/|regex:/^(?!<.*? \/>)/'
        ];
    }


    public function messages()
    {
        // NOTE: 属性名を resources/lang/validation.php に書くと管理画面と競合する問題を解決できなかったため記載した
        return [
            'group.required'    => trans('validation.facility_name_required'),
            'group.max'         => trans('validation.facility_name_length'),
            'name.required'     => trans('validation.name_required'),
            'name.max'          => trans('validation.name_length'),
            'mail.required'     => trans('validation.email_required'),
            'mail.email'        => trans('validation.mail_email'),
            'tel.required'      => trans('validation.phone_required'),
            'tel.min'           => trans('validation.tel_min'),
            'tel.digits_between'    => trans('validation.tel_digits_between'),
            'tel.integer'       => trans('validation.tel_integer'),
/*
            'question.required' => 'お問合わせ内容を入力してください',
*/
            'question.max'      => trans('validation.question_max'),
            'question.regex'    => trans('validation.question_regex'),
            'question.required'    => trans('validation.question_max')
        ];
    }
}
