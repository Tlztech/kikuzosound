<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\Company\CompanyService;
use App\Http\Services\Coupon\CouponService;
use App\Http\Requests\CustomerAdmin\CouponRequest;

class CouponController extends Controller
{
    /**
     * @var CouponService
     */
    protected $couponService;

    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * Injecting dependencies.
     *
     * @param CouponService  $couponService
     * @param CompanyService $companyService
     */
    public function __construct(
        CouponService $couponService,
        CompanyService $companyService
    ) {
        $this->couponService = $couponService;
        $this->companyService = $companyService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer_admin.coupons.index', [
            'coupons' => $this->couponService->couponsWithCompanies(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_admin.coupons.create', [
            'companies' => $this->companyService->listAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        if (!$coupon = $this->couponService->storeCoupon($request->all())) {
            return back()->withInput();
        }
        return redirect()->route('customer_admin_coupons');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('customer_admin.coupons.edit', [
            'coupon' => $this->couponService->findById($id),
            'companies' => $this->companyService->listAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request, $id)
    {
        $coupon = $this->couponService->findById($id);
        if (!$this->couponService->updateCoupon($request->all(), $coupon)) {
            return back()->withInput();
        }
        return redirect()->route('customer_admin_coupons');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
