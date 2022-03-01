<?php

namespace App\Http\Requests\CustomerAdmin;

use App;
use App\Http\Requests\Request;

class OnetimeKeyRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agency' => 'required',
            'user' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'company' => 'required'
        ];
    }

    public function messages()
    {
        App::setLocale('ja');
        return [
            'agency.required' => trans('validation.required'),
            'user.required' => trans('validation.required'),
            'name.required' => trans('validation.required'),
            'email.required' => trans('validation.required'),
            'email.email' => trans('validation.email'),
            'company.required' => trans('validation.required')
        ];
    }
}
