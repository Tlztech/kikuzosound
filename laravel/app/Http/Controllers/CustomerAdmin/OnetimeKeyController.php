<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Account;
use App\BrowserResets;
use App\BrowserResetsHistory;
use App\Companys;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAdmin\OnetimeKeyRequest;
use App\Http\Services\Company\CompanyService;
use App\OnetimeKey;
use App\Universities;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;

class OnetimeKeyController extends Controller
{
    /**
     * Dependeny Injection.
     *
     * @param CompanyService $companyService
     */
    public function __construct(
        CompanyService $companyService
    ) {
        $this->companyService = $companyService;
        $this->Query = OnetimeKey::select('*')->orderBy('created_at', 'DESC');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
        return view('customer_admin.management.create', [
            'companies' => $this->companyService->listVendorCompanies(),
            'universities' => Universities::orderBy('id', 'DESC')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $result = DB::table('onetime_keys')
        ->where('onetime_keys.id', $id)
        ->select(
            'onetime_keys.id',
            'onetime_keys.customer_id',
            'onetime_keys.agency',
            'onetime_keys.onetime_key',
            'onetime_keys.created_at',
            'onetime_keys.updated_at',
            'onetime_keys.status',
            'onetime_keys.university_id',
            'companys.company',
            'companys.id as company_id',
            'accounts.id as account_id',
            'accounts.user',
            'accounts.email',
            'accounts.name'
        )
        ->leftJoin('accounts', 'onetime_keys.customer_id', '=', 'accounts.id')
        ->leftJoin('contracts', 'accounts.contract_id', '=', 'contracts.id')
        ->leftJoin('contacts', 'contracts.dealer_id', '=', 'contacts.id')
        ->leftJoin('companys', 'contacts.company_id', '=', 'companys.id')
        ->get();

        // dd($result[0]);
        return view('customer_admin.management.edit', [
            'key' => $result[0],
            'universities' => Universities::orderBy('id', 'DESC')->get(),
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
    public function update(OnetimeKeyRequest $request, $id)
    {
        $oneTimeKey = OnetimeKey::find($id);
        $oneTimeKey->update([
            'agency' => $request->agency,
            'university_id' => $request->university_id,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if($request->university_id!=null||$request->university_id!=0){
            $oneTimeKey->update([
                'is_exam_group' => 1,
            ]);
        } else {
            $oneTimeKey->update([
                'is_exam_group' => 0,
            ]);
        }

        Companys::where('id', $request->company_id)->update(['company' => $request->company]);
        Account::where('id', $request->account_id)->update([
            'user' => $request->user,
            'name' => $request->name,
            'email' => $request->email
        ]);
        return redirect('/customer_admin');
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
        $result = OnetimeKey::destroy($id);
        return redirect()->route('customer_admin');
    }

    /**
     * Bulk Delete unregistered liscense key.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDeleteLicense(Request $request)
    {
        $selected_ids = json_decode($request->input('selected_id'));
        $result = OnetimeKey::destroy($selected_ids);
        return redirect()->route('customer_admin');
    }

    /**
     * Delete all invalid liscense key.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAllInvalidLicense(Request $request)
    {
        $result = OnetimeKey::where('expiration_date','<=', Carbon::now())->whereNull('customer_id')->delete();
        return redirect()->route('customer_admin');
    }

    
    public function getId($array)
    {
        return array_map(function ($item) {
            return $item->id;
        }, $array);
    }

    /**
     * Get onetime key list.
     *
     * @param Request $request
     *
     * @return view
     */
    public function get_oneTimePassword(Request $request)
    {
        $showEntries = isset($_COOKIE["showEntries"]) ? $_COOKIE["showEntries"] : 10;
        $search_key = $request->input('search_key');
        $result[] = [];

        if ('' == $search_key) {
            $result = OnetimeKey::select(
                    'onetime_keys.*',
                    'companys.company',
                    'accounts.user',
                    'accounts.email',
                    'accounts.name',
                    'accounts.disable_analytics',
                    'universities.name AS exam_group'
                )
                ->leftJoin('accounts', 'onetime_keys.customer_id', '=', 'accounts.id')
                ->leftJoin('contracts', 'accounts.contract_id', '=', 'contracts.id')
                ->leftJoin('contacts', 'contracts.dealer_id', '=', 'contacts.id')
                ->leftJoin('companys', 'contacts.company_id', '=', 'companys.id')
                ->leftJoin('universities', 'onetime_keys.university_id', '=', 'universities.id')
                ->paginate($showEntries)
            ;

            return view('customer_admin.management.index', compact('result', 'search_key'));
        }

        //get user id by user
        $user = DB::table('accounts')->where('user', 'LIKE', $search_key)->get();

        $userIds = $this->getId($user);

        $user ? $user_id = $userIds : $user_id = [];

        //get user id by name
        $username = DB::table('accounts')->where('name', 'LIKE', $search_key)->get();

        $userNameIds = $this->getId($username);

        $username ? $username_id = $userNameIds : $username_id = [];

        //get user id by email
        $email = DB::table('accounts')->where('email', 'LIKE', $search_key)->get();

        $userNameIds = $this->getId($email);

        $email ? $email_id = $userNameIds : $email_id = [];

        $whereIn = array_merge($user_id, $username_id, $email_id);

        $whereAre = [
            ['onetime_keys.agency', 'LIKE', "$search_key%"],
            ['onetime_keys.onetime_key', 'like', $search_key],
            ['onetime_keys.created_at', 'like', "$search_key%"],
            ['companys.company', 'LIKE', $search_key],
            ['universities.name', 'LIKE', $search_key],
        ];

        $result = OnetimeKey::select(
                'onetime_keys.*',           
                'companys.company',
                'accounts.user',
                'accounts.email',
                'accounts.name',
                'accounts.disable_analytics',
                'universities.name AS exam_group'
            )
            ->leftJoin('accounts', 'onetime_keys.customer_id', '=', 'accounts.id')
            ->leftJoin('contracts', 'accounts.contract_id', '=', 'contracts.id')
            ->leftJoin('contacts', 'contracts.dealer_id', '=', 'contacts.id')
            ->leftJoin('companys', 'contacts.company_id', '=', 'companys.id')
            ->leftJoin('universities', 'onetime_keys.university_id', '=', 'universities.id');

        foreach ($whereAre as $where) {
            list($key, $operator, $value) = $where;
            $result = $result->orWhere($key, $operator, $value);
        }
        $result = $result->orWhereIn('onetime_keys.customer_id', $whereIn)->paginate($showEntries);
        $result -> appends(['search_key'=>$search_key]);
        return view('customer_admin.management.index', compact('result', 'search_key'))->with('search_key', $search_key);
    }

    /**
     * Generate new onetime key.
     *
     * @param Request $request
     *
     * @return redirect
     */
    public function newOnetimeIssue(Request $request)
    {
        $count = $request->input('count');
        $expiry =$request->input('is_has_expiry') ? new Carbon($request->input('expiration_date')) : null;
        //check validation
        if ($request->input('is_exam_group') == 1) {
            $v = Validator::make($request->all(), [
                'agency' => 'required|max:255',
                'count' => 'required|numeric|max:10000',
                'university_id' => 'required',
            ]);
        } else {
            $v = Validator::make($request->all(), [
                'agency' => 'required|max:255',
                'count' => 'required|numeric|max:10000',
            ]);
        }
        
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        //save ontime key if no form errors
        for ($i = 1; $i <= $count; ++$i) {

            DB::table('onetime_keys')->insert([
                'agency'        => $request->input('agency'),
                'onetime_key'   => substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4),
                'university_id' => $request->input('university_id'),
                'is_exam_group' => $request->input('is_exam_group'),
                'expiration_date' => $expiry,
                'created_at'    => date('Y-m-d H:i:s')
            ]);
        }
        return redirect('/customer_admin');
    }

    public function stopLicense($id) {
        OnetimeKey::where('id', $id)->update(['status' => 3]);
        return redirect()->back();
    }

    public function updateLicense($id)
    {
        $onetimeKey = OnetimeKey::find($id);

        $browserResetHistory = new BrowserResetsHistory();
        $browserResetHistory->onetime_key_id = $onetimeKey->id;
        $browserResetHistory->prev_onetime_key = $onetimeKey->onetime_key;
        $browserResetHistory->save();

        $newLicenseKey = substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4);
        $onetimeKey->onetime_key = $newLicenseKey;
        $onetimeKey->status = 2;
        $onetimeKey->bwtk = null;
        $onetimeKey->save();

        $token = str_random(64);
        $url = env('APP_URL').'update_browser/'.$token;

        DB::table("browser_resets")->where("customer_id", $onetimeKey->customer_id)->delete();
        DB::table("browser_resets")->insert([
            "customer_id" => $onetimeKey->customer_id,
            "token" => $token,
            "created_at" => date('Y-m-d H:i:s')
        ]);

        $account = DB::table('accounts')->find($onetimeKey->customer_id);
        
        //send the mail to the recipient
        Mail::send('emails.reapplybrowser', 
        ['content1' => trans('reapply_browser.email_reapply_content1'),
            'content2' => trans('reapply_browser.email_reapply_content2'),
            'name' => $account->name,
            'email' => $account->email, 
            'newLicense' => $newLicenseKey, 
            'url' => $url], function ($m) use ($account) {
            $m->from(env('CONTACT_FORM_MAIL_TO'));
            $m->to($account->email)->subject(trans('reapply_browser.email_subject'));
        });

        return view('customer_admin.management.update_license', [
            'name' => $account->name,
            'email' => $account->email,
            'newLicense' => $newLicenseKey,
            'url' => $url,
        ]);
    }

    public function browserResetHistory($id)
    {
        $browserResetHistory = BrowserResetsHistory::where('onetime_key_id', $id)->orderBy('created_at', 'DESC')->get();
        $customer = DB::table('accounts')->find(OnetimeKey::find($id)->customer_id);
        return view('customer_admin.management.browser_reset_history', [
            'browserResetHistory' => $browserResetHistory,
            'customer' => $customer,
        ]);
    }

    /**
     * Download onetime key list csv file.
     *
     * @param Request $request
     */
    public function downloadCsv(Request $request)
    {
        $selected_ids = json_decode($request->input('selected_id'));
        // $selected_all = json_decode($request->input('selected_all'));
        $fileType = $request->input('button_type');

        $result = DB::table('onetime_keys')
            ->select(
                'onetime_keys.id',
                'onetime_keys.customer_id',
                'onetime_keys.agency',
                'onetime_keys.onetime_key',
                'onetime_keys.created_at',
                'onetime_keys.updated_at',
                'onetime_keys.status',
                'companys.company',
                'accounts.user',
                'accounts.email',
                'accounts.name',
                'universities.name AS exam_group'
            )
            ->leftJoin('accounts', 'onetime_keys.customer_id', '=', 'accounts.id')
            ->leftJoin('contracts', 'accounts.contract_id', '=', 'contracts.id')
            ->leftJoin('contacts', 'contracts.dealer_id', '=', 'contacts.id')
            ->leftJoin('companys', 'contacts.company_id', '=', 'companys.id')
            ->leftJoin('universities', 'onetime_keys.university_id', '=', 'universities.id')
            ->whereIn('onetime_keys.id', $selected_ids)
            ->get();

        return $this->create_downloadCSV($result, $fileType);
    }

    /**
     * Generate new onetime key csv file and download.
     *
     * @param array $data
     * @param mixed $fileType
     */
    public function create_downloadCSV($data, $fileType)
    {
        $csv_file = [];

        foreach ($data as $OneTimeKey) {
            $status = "";
            if ($OneTimeKey->status == 0) { // issued
                $status = "発行済";
            } else if ($OneTimeKey->status == 1) { // used
                $status = "使用済";
            } else if ($OneTimeKey->status == 2) { // changed
                $status = "変更済";
            } else if ($OneTimeKey->status == 3) { // stopped
                $status = "停止中";
            }
            $csv_row = [
                '代理店' => $OneTimeKey->agency,
                'Examグループ名' => $OneTimeKey->exam_group != null ? $OneTimeKey->exam_group : '----',
                'ライセンスキー' => $OneTimeKey->onetime_key,
                'ステータス' => $status,
                '作成日' => $OneTimeKey->created_at,
                '更新日' => ($OneTimeKey->customer_id) ? $OneTimeKey->updated_at : '----',
                '法人名' => ($OneTimeKey->customer_id) ? $OneTimeKey->company : '----',
                'ユーザーID' => ($OneTimeKey->customer_id) ? $OneTimeKey->user : '----',
                '顧客名' => ($OneTimeKey->customer_id) ? $OneTimeKey->name : '----',
                '登録者 email' => ($OneTimeKey->customer_id) ? $OneTimeKey->email : '----',
            ];
            array_push($csv_file, $csv_row);
        }
        //download csv
        return \Excel::create('OnetimeKey', function ($excel) use ($csv_file) {
            $excel->sheet('OnetimeKeyList', function ($sheet) use ($csv_file) {
                $sheet->fromArray($csv_file);
            });
        })->download($fileType);
    }

    /**
     * Set expiration date.
     *
     * @param Request $request
     */
    public function setExpiration(Request $request)
    {
        $selected_ids = json_decode($request->input('selected_id'));
        $exp_date = $request->input('is_has_expiry') ? new Carbon($request->input('expiration_date')) : null;
        
        $result = OnetimeKey::whereIn('id', $selected_ids)->update(['expiration_date' => $exp_date, 'expiration_mail_status' => 0]);
        return redirect()->route('customer_admin');
    }
}
