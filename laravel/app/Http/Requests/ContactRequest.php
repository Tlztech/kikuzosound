<?php

namespace App\Http\Requests;

class ContactRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'tel' => 'required',
            'zip' => 'required',
            'address' => 'required',
            'department' => 'required',
            'auth' => 'required',
            'role' => 'required'
        ];
    }
}
