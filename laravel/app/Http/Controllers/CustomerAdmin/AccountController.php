<?php

namespace App\Http\Controllers\CustomerAdmin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAdmin\AccountRequest;
use App\Http\Services\Coupon\CouponService;
use App\Http\Services\Delete\DeleteService;
use App\Http\Services\Account\AccountService;
use App\Http\Services\Company\CompanyService;
use App\Http\Services\Contact\ContactService;
use App\Http\Services\Contract\ContractService;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Register\RegisterService;

class AccountController extends Controller
{
    /**
     * @var DeleteService
     */
    protected $deleteService;

    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * @var CouponService
     */
    protected $couponService;

    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @var ContractService
     */
    protected $contractService;

    /**
     * @var RegisterService
     */
    protected $registerService;

    /**
     * Injecting dependencies.
     *
     * @param DeleteService   $deleteService
     * @param CouponService   $couponService
     * @param ContactService  $contactService
     * @param CompanyService  $companyService
     * @param AccountService  $accountService
     * @param ProductService  $productService
     * @param ContractService $contractService
     * @param RegisterService $registerService
     */
    public function __construct(
        DeleteService $deleteService,
        CouponService $couponService,
        ContactService $contactService,
        CompanyService $companyService,
        AccountService $accountService,
        ProductService $productService,
        ContractService $contractService,
        RegisterService $registerService
    ) {
        $this->deleteService = $deleteService;
        $this->couponService = $couponService;
        $this->contactService = $contactService;
        $this->companyService = $companyService;
        $this->accountService = $accountService;
        $this->productService = $productService;
        $this->contractService = $contractService;
        $this->registerService = $registerService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
    }

    /**
     * Account Data.
     *
     * @return obj
     */
    public function accountData()
    {
        return $this->accountService->listAccountsWithRelations(request()->search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $seed = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPUQRSTUVWXYZ';
        $password = substr(str_shuffle($seed), 0, 8);
        return view('customer_admin.accounts.create', [
            'sales_contacts' => $this->contactService->listAllDealerContactsWithCompanies(),
            'customer_contacts' => $this->contactService->listAllCustomerContactsWithCompanies(),
            'coupons' => $this->couponService->couponsWithCompanies(),
            'not_register_products' => $this->registerService->listAll(),
            'products' => $this->productService->listAll(),
            'password' => $password,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AccountRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
        try {
            $data = $request->all();
            $contract = $this->contractService->storeContract($data);
            $account = $this->accountService->storeAccount($data, $contract->id);
            if (array_key_exists('product_no', $data)) {
                $account->products()->sync($data['product_no']);
            }
            $this->productService->changeStatus($data['product_no']);
            return view('customer_admin.companies.result');
        } catch (Exception $e) {
            return back()->withInput();
        }
        $this->productService->changeStatus($data['product_no']);
        return view('customer_admin.companies.result');
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
        return view('customer_admin.accounts.edit', [
            'register' => $this->registerService->findByAccountId($id),
            'account' => $this->accountService->findByAccountIdWithContrctsandProducts($id),
            'sales_contacts' => $this->contactService->listAllDealerContactsWithCompanies(),
            'customer_contacts' => $this->contactService->listAllCustomerContactsWithCompanies(),
            'coupons' => $this->couponService->couponsWithCompanies(),
            'not_register_products' => $this->registerService->listAll(),
            'deletes' => $this->deleteService->findByAccountId($id),
            'products' => $this->productService->listAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AccountRequest $request
     * @param int            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(/*Contact*/Request $request, $id)
    {
        return redirect()->route('customer_admin_registrations');
        $contact = $this->contactService->findById($id);
        if (!$this->contactService->updateContact($request->all(), $contact)) {
            return back()->withInput();
        }
        return view('customer_admin.companies.result');
    }
    /**
     * Disable/Enable acoounts to show on univ admin
     *
     * @param AccountRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function disableAccountAnalytics(Request $request)
    {
        $customer_id = $request->input("customer_id");
        $status = $request->input("disabled");
        return $this->accountService->disableAccountAnalytics($customer_id,$status);
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
