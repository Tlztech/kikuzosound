<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'title' => 'required|max:255',
            'description' => 'required|max:10000',
            'description_en' => 'required|max:10000',
        ];
    }

    /**
     * Validation Messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => '日付(JP)は必須です。',
            'title_en.required' => '日付(EN)は必須です。',
            'description.required' => 'お知らせ(JP)は必須です。',
            'description_en.required' => 'お知らせ(EN)は必須です。',
            'description.max' => 'お知らせ(JP)は10000文字以内で入力してください。',
            'description_en.max' => 'お知らせ(en)は10000文字以内で入力してください。',
        ];
    }
}
