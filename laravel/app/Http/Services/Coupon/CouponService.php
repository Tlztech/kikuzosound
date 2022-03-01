<?php

namespace App\Http\Services\Coupon;

use App\Coupon;
use Carbon\Carbon;

class CouponService
{
    /**
     * @var Coupon
     */
    protected $coupon;

    /**
     * Injecting dependencies.
     *
     * @param Coupon $coupon
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * Retrieve Coupons With Companies.
     *
     * @return obj
     */
    public function couponsWithCompanies()
    {
        return $this->coupon->with('companies')->get();
    }

    /**
     * Find coupon by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->coupon->find($id);
    }

    /**
     * Store caoupon
     *
     * @param int   $id
     * @param mixed $data
     *
     * @return obj
     */
    public function storeCoupon($data)
    {
        $start_at = $data['r_year'].'-'.$data['r_month'].'-'.$data['r_day'].' '.$data['r_hour'].':'.$data['r_min'];
        $end_at = $data['d_year'].'-'.$data['d_month'].'-'.$data['d_day'].' '.$data['d_hour'].':'.$data['d_min'];
        return $this->coupon->create([
            'company_id' => $data['dealer_id'],
            'code' => $data['coupon_code'],
            'cashback' => $data['cashback'],
            'memo' => $data['memo'],
            'start_at' => Carbon::parse($start_at)->toDateTimeString(),
            'end_at' => Carbon::parse($end_at)->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
    }

    /**
     * Update coupon
     *
     * @param int   $id
     * @param mixed $data
     *
     * @return obj
     */
    public function updateCoupon($data, $coupon)
    {
        $start_at = $data['r_year'].'-'.$data['r_month'].'-'.$data['r_day'].' '.$data['r_hour'].':'.$data['r_min'];
        $end_at = $data['d_year'].'-'.$data['d_month'].'-'.$data['d_day'].' '.$data['d_hour'].':'.$data['d_min'];

        return $coupon->update([
            'company_id' => $data['dealer_id'],
            'code' => $data['coupon_code'],
            'cashback' => $data['cashback'],
            'memo' => $data['memo'],
            'start_at' => Carbon::parse($start_at)->toDateTimeString(),
            'end_at' => Carbon::parse($end_at)->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}
