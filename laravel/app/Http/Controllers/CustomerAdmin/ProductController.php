<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Services\Product\ProductService;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * Injecting dependencies.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        return view('customer_admin.products.form');
    }

    /**
     * Register Product.
     *
     * @param ProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        for ($product_no = $request->product_no_s; $product_no <= $request->product_no_e; $product_no++) {
            $data[] = [
                'product_no' => $product_no,
                'p_status' => $request->p_status,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
        }
        if (!$this->productService->insertProduct($data)) {
            return back()->withInput();
        }
        return view('customer_admin.products.result');
    }
}
