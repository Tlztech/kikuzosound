<?php

namespace App\Http\Controllers\CustomerAdmin;

use Illuminate\Http\Request;
use App\Http\Requests\ExamRequest;
use App\Http\Controllers\Controller;
use App\Http\Services\Company\CompanyService;
use App\Http\Services\Account\AccountService;
use App\Http\Services\Sound\StethoSoundService;

class RegistrationController extends Controller
{
    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var StethoSoundService
     */
    protected $stethoSoundService;

    /**
     * Injecting dependencies.
     *
     * @param CompanyService     $companyService
     * @param AccountService     $accountService
     * @param StethoSoundService $stethoSoundService
     */
    public function __construct(
        CompanyService $companyService,
        AccountService $accountService,
        StethoSoundService $stethoSoundService
    ) {
        $this->companyService = $companyService;
        $this->accountService = $accountService;
        $this->stethoSoundService = $stethoSoundService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer_admin.registrations.index');
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $accounts = $this->companyService->listAccountsWithRelations();
        if(request()->status == 1){
            $accounts = collect($accounts)->filter(function ($value) {
                return $value->p_status == 1 && $value->aid !=null;
            });
        }
        return view('customer_admin.companies.index',[
            'accounts' => $accounts
        ]);
    }

    /**
     * get Companies Search Data.
     *
     * @return obj
     */
    public function getCompaniesSearchData()
    {
        return $this->companyService->companiesWithContactsByKeyword(request()->search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customer_admin.exams.create', [
            'sounds' => $this->stethoSoundService->listAllStethoSounds(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(ExamRequest $request)
    {
        if (!$exam = $this->examService->storeExam($request->all())) {
            return back()->withInput();
        }
        return redirect()->route('customer_admin_exams');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $exam_id
     *
     * @return \Illuminate\View\View
     */
    public function edit($exam_id)
    {
        return view('customer_admin.exams.edit', [
            'exam' => $this->examService->findById($exam_id),
            'sounds' => $this->stethoSoundService->listAllStethoSounds(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamRequest $request
     * @param int         $exam_id
     *
     * @return \Illuminate\View\View
     */
    public function update(ExamRequest $request, $exam_id)
    {
        $exam = $this->examService->findById($exam_id);
        if (!$exam = $this->examService->updateExam($request->all(), $exam)) {
            return back()->withInput();
        }
        return redirect()->route('customer_admin_exams');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int   $id
     * @param mixed $exam_id
     *
     * @return \Illuminate\View\View
     */
    public function destroy($exam_id)
    {
        $exam = $this->examService->findById($exam_id);
        $exam->sounds()->detach();
        $exam->delete();
        return redirect()->route('customer_admin_exams');
    }

    /**
     * get Contracts Search Data.
     *
     * @return obj
     */
    public function getContractsSearchData()
    {
        return $this->accountService->listAccountsWithRelations(request()->search);
    }
}
