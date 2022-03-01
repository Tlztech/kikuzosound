<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Services\Company\CompanyService;
use App\Http\Services\Contact\ContactService;

class ContactController extends Controller
{
    /**
     * @var ContactService
     */
    protected $contactService;

    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * Injecting dependencies.
     *
     * @param ContactService $contactService
     * @param CompanyService $companyService
     */
    public function __construct(
        ContactService $contactService,
        CompanyService $companyService
    ) {
        $this->contactService = $contactService;
        $this->companyService = $companyService;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_admin.contacts.create', [
            'companies' => $this->companyService->listAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        if (!$this->contactService->storeContact($request->all())) {
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
        return view('customer_admin.contacts.edit', [
            'contact' => $this->contactService->findById($id),
            'companies' => $this->companyService->listAll($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactRequest $request
     * @param int            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ContactRequest $request, $id)
    {
        $contact = $this->contactService->findById($id);
        if (!$this->contactService->updateContact($request->all(), $contact)) {
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
