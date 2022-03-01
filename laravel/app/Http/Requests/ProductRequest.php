<?php

namespace App\Http\Requests;

class ProductRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_no_s' => 'required|min:1',
            'product_no_e' => 'required|min:'.(request()->product_no_s),
            'p_status' => 'required',
        ];
    }
}
