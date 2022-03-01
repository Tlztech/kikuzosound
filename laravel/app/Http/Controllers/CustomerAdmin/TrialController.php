<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAdmin\TrialRequest;
use App\Http\Services\Trial\TrialService;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    /**
     * @var TrialService
     */
    protected $trialService;

    /**
     * Injecting dependencies.
     *
     * @param TrialService $trialService
     */
    public function __construct(TrialService $trialService)
    {
        $this->trialService = $trialService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer_admin.trials.index', [
            'trials' => $this->trialService->trialMembersWithAllRelations(),
        ]);
    }

    /**
     * TrialMember Csv Download.
     *
     * @return onj
     */
    public function trialCsvDownload()
    {
        $response = $this->trialService->trialCsvDownload();
        return $response->send();
    }

    /**
     * TrialMember Xlsx Download.
     *
     * @return onj
     */
    public function trialXlsxDownload()
    {
        $response = $this->trialService->trialXlsxDownload();
        return $response->send();
    }

    /**
     * Get Register Form.
     *
     * @return \Illuminate\View\View
     */
    public function getRegisterForm()
    {
        return view('customer_admin.trials.edit');
    }

    /**
     * REgister Trial.
     *
     * @param TrialRequest $request
     *
     * @return Response
     */
    public function register(TrialRequest $request)
    {
        if (!$trial = $this->trialService->registerTrialMembers($request->all())) {
            return back()->withInput();
        }
        return redirect('r-mail-form?edit='.encryptMail($trial->mail).'&suid='.encryptID($trial->id));
    }

    /**
     * Destroy trial
     * @param  Request $request
     * @return obj
     */
    public function destroy(Request $request)
    {
        $trial = $this->trialService->findById($request->trial);
        if (!$this->trialService->destroy($trial)) {
            return redirect('customer_admin/trials');
        }
        return redirect('customer_admin/trials');
    }
}
