<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Lang;

class RegisterFormRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cust_mail_address'=>'required|email',
            'company'=>'required|max:1000',
            'name'=>'required|max:1000',
            'userId'=>'required|max:1000',
            'password'=>'required|max:1000',
            'passwordConfirm'=>'required|max:1000',
            'oneTimePassword'=>'required|max:50',

            // 'dealer'=>'required|max:50',
            // 'contact'=>'required|max:50',
            // 'distr_mail_address'=>'required|email',
            // 'phone_number'=>'required|min:9|max:20|regex:/[0-9\-]/|'
        ];

        // return [
        //     'company'=>'required|max:50',
        //     'name'=>'required|max:50',
        //     'mail'=>'required|email',
        //     'tel'=>'required|min:9|max:20|regex:/[0-9\-]/|',
        //     'dealer'=>'required|max:50',
        //     'contact'=>'required|max:50',
        //     'cmail'=>'required|email',
        //     'ctel'=>'required|min:9|max:20|regex:/[0-9\-]/|',
        //     'number'=>'required|min:1|max:8|regex:/[0-9]/|',
        //     'kind'=>'required',
        //     'serial1'=>'required|min:1|max:200|regex:/[0-9\-\,]/|',
        //     'question'=>'max:500|regex:/^(?!<(\S*?)[^>]*>.*?<\/\1>)/|regex:/^(?!<.*? \/>)/'
        // ];

    }


    public function messages()
    {
        // NOTE: 属性名を resources/lang/validation.php に書くと管理画面と競合する問題を解決できなかったため記載した
        return [
            'cust_mail_address.unique' => Lang::get('customer_registration.exists_email'),
            'company.required'  => '法人(施設)名を入力してください。',
            'company.max'       => Lang::get('customer_registration.company_max_1000'),
            'name.required'     => 'お名前を入力してください。',
            'name.max'          => Lang::get('customer_registration.name_max_1000'),
            'mail.required'     => 'メールアドレスを入力してください。',
            'mail.email'        => '正しいメールアドレスを入力してください。',
            'tel.required'      => '電話番号を入力してください。',
            'tel.min'           => '電話番号は9〜20桁の数字とハイフンで入力してください。',
            'tel.max'           => '電話番号は9〜20桁の数字とハイフンで入力してください。',
            'tel.regex'         => '電話番号は9〜20桁の数字とハイフンで入力してください。',
            'dealer.required'   => '販社名を入力してください。',
            'dealer.max'        => '販社名は50文字以内で入力してください。',
            'contact.required'  => '担当者名を入力してください。',
            'contact.max'       => '担当者名は50文字以内で入力してください。',
            'cmail.required'    => 'メールアドレスを入力してください。',
            'cmail.email'       => '正しいメールアドレスを入力してください。',
            'ctel.required'     => '電話番号を入力してください。',
            'ctel.min'          => '電話番号は9〜20桁の数字とハイフンで入力してください。',
            'ctel.max'          => '電話番号は9〜20桁の数字とハイフンで入力してください。',
            'ctel.regex'        => '電話番号は9〜20桁の数字とハイフンで入力してください。',
            'number.required'   => '購入台数を入力してください。',
            'number.min'        => '購入台数は1〜8桁の数字で入力してください。',
            'number.max'        => '購入台数は1〜8桁の数字で入力してください。',
            'number.regex'      => '購入台数は1〜8桁の数字で入力してください。',
            'kind.required'     => '購入種類を入力してください。',
            'serial1.required'  => 'シリアル番号を入力してください。',
            'serial1.min'       => 'シリアル番号は1〜100桁の数字とハイフン・カンマで入力してください。',
            'serial1.max'       => 'シリアル番号は1〜100桁の数字とハイフン・カンマで入力してください。',
            'serial1.regex'     => 'シリアル番号は1〜100桁の数字とハイフン・カンマで入力してください。',
            'question.required' => 'お問合わせ内容を入力してください。',
            'question.max'      => 'お問合わせ内容は500文字以内で入力してくださ>い。',
            'question.regex'    => 'お問合わせ内容にHTMLを入力することはできません。',
            'userId.max'            => Lang::get('customer_registration.user_id_max_1000'),
            'password.max'      => Lang::get('customer_registration.password_max_1000')
        ];
    }
}
