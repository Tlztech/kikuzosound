<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Requests\CompanyRequest;
use App\Http\Controllers\Controller;
use App\Http\Services\Company\CompanyService;

class CompanyController extends Controller
{
    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * Injecting dependencies.
     *
     * @param CompanyService $companyService
     */
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer_admin.companies.index');
    }

    /**
     * Get Company Data.
     */
    public function getCompanyData()
    {
        // dd(request()->all());
        return $this->companyService->companiesWithContactsByKeywordAndRole(request()->search, request()->role);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        if (!$this->companyService->storeCompany($request->all())) {
            return back()->withInput();
        }
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
        return view('customer_admin.companies.edit', [
            'company' => $this->companyService->findById($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CompanyRequest $request
     * @param int            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $id)
    {
        $company = $this->companyService->findById($id);
        if (!$this->companyService->updateCompany($request->all(), $company)) {
            return back()->withInput();
        }
        return view('customer_admin.companies.result');
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
