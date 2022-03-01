<?php

namespace App\Http\Requests\CustomerAdmin;

use App\Http\Requests\Request;

class CouponRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dealer_id' => 'required|integer|min:0',
            'coupon_code' => 'required|max:50|unique:coupons,code,'.$this->coupon,
            'cashback' => 'required|numeric|min:0',
            'r_year' => 'required',
            'r_month' => 'required',
            'r_day' => 'required',
            'r_hour' => 'required',
            'r_min' => 'required',
            'd_year' => 'required',
            'd_month' => 'required',
            'd_day' => 'required',
            'd_hour' => 'required',
            'd_min' => 'required',
            'memo' => 'required',
        ];
    }
}
