<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ApplicationFormRequest extends Request
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
            'tel'=>'required|min:9|max:20|regex:/[0-9\-]/|',
            'affiliation'=>'required|max:100',
            'zip'=>'required',
            'address'=>'required|max:200',
            'kind'=>'max:100',
            'dealer'=>'max:100',
            'area'=>'max:100',
            'sales'=>'max:50',
            'salestel'=>'min:9|max:20|regex:/[0-9\-]/|',
            'salesmail'=>'email',
            'agree'=>'required',
            'question'=>'max:500|regex:/^(?!<(\S*?)[^>]*>.*?<\/\1>)/|regex:/^(?!<.*? \/>)/'
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
            'tel.max'           => trans('validation.tel_max'),
            'tel.regex'         => trans('validation.tel_regex'),
            'affiliation.required'    => trans('validation.affiliation_required'),
            'affiliation.max'         => trans('validation.application_max'),
            'zip.required'    => trans('validation.zip_required'),
            'address.required'    => trans('validation.address_required'),
            'address.max'         => trans('validation.address_max'),
            'dealer.max'          => trans('validation.dealer_max'),
            'area.max'          => trans('validation.area_max'),
            'sales.max'          => trans('validation.name_length'),
            'salestel.min'           => trans('validation.tel_min'),
            'salestel.max'           => trans('validation.tel_max'),
            'salestel.regex'         => trans('validation.tel_regex'),
            'salesmail.email'        => trans('validation.mail_email'),
            'agree.required'        => trans('validation.agree_required'),
            'kind.max'          => trans('validation.kind_max'),
            'question.max'      => trans('validation.question_max'),
            'question.regex'    => trans('validation.question_regex')
        ];
    }
}
