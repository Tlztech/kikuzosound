<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OnetimeKey;
use App\Account;
use App\Accounts;
use App\Contracts;
use App\Http\Services\Contract\ContractService;
use App\Http\Services\Account\AccountService;
use App\Http\Services\Product\ProductService;
use App\Lib\GlobalFunction;
use DB;
use Carbon\Carbon;

class UserManagementController extends Controller
{

    private $success = 'ng';
    private $message = '';
    private $result = null;
    private $total_page = 0;

    /**
     * @var ContactService
     */
    protected $contractService;

    /**
     * @var accountService
     */
    protected $accountService;

    /**
     * @var ProductService
     */
    protected $productService;


	protected $auth_user = null;
    protected $university_id = null;



    public function __construct(Request $request, AccountService $accountService, ContractService $contractService, ProductService $productService)
    {
        $this->accountService = $accountService;
        $this->contractService = $contractService;
        $this->productService = $productService;
        
		$this->auth_user = $request->input("auth_user");
        $this->university_id = $request->input("university_id");
    }
    /**
     *
     * @return JsonResponse
     */

    function response()
    {
        $result = array(
            'success' => $this->success,
            'message' => $this->message,
			'total_page'  => $this->total_page,
            'result'  => $this->result,
        );
        return response()->json($result);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page, $enabled)
    {
        $params = request("params");
        $search_params = $params['search'];
        $resCount=0;
        // if($this->university_id == 101)
        //     $key = OnetimeKey::whereNotNull('customer_id')->get()->pluck("customer_id");
        // else
        $key_enabled = $enabled ? 1 : 3;
        $itemCount = 10;
        $key = OnetimeKey::where('status',$key_enabled)
            ->whereNotNull('customer_id')->get()->pluck("customer_id");
		$this_university_id = $this->university_id;
        if($this->auth_user->role === 101){
            $enabledCount = Account::whereIn("id", $key)->count();
            if ($page == 'all') {
                $users = Accounts::select('accounts.*', 'univ.name as university_name', 'key.created_at as created_at')
                ->where('accounts.disable_analytics',0)
                ->whereIn("accounts.id", $key)->with('onetime_key')
                ->leftJoin('onetime_keys as key', 'accounts.id', '=', 'key.customer_id')
                ->leftJoin('universities as univ', 'univ.id', '=', 'key.university_id')
                ->get();
                $resCount=$users->count();
            } else {
    			$skipRow = $page * $itemCount;
                $users = Accounts::select('accounts.*', 'univ.name as university_name', 'key.created_at as created_at')
                    ->where('accounts.disable_analytics',0)
                    ->whereIn("accounts.id", $key)->with('onetime_key')
                    ->leftJoin('onetime_keys as key', 'accounts.id', '=', 'key.customer_id')
                    ->leftJoin('universities as univ', 'univ.id', '=', 'key.university_id');

                if(isset($search_params) && !empty($search_params)){
                    $users = $users->where(function ($query) use ($search_params) {
                        $query->where('accounts.name', 'like', "%{$search_params}%")
                        ->orWhere('accounts.user', 'like', "%{$search_params}%")
                        ->orWhere('accounts.email', 'like', "%{$search_params}%")
                        ->orWhere('univ.name', 'like', "%{$search_params}%")
                        ->orWhere('accounts.id', $search_params); 
                    });      
                }   

                $resCount = $users->count();
                $users = $users->skip($skipRow)
                        ->take($itemCount)
                        ->get();
            }
        }

        if($this->auth_user->role === 201){
            $enabledCount = Accounts::whereIn("id", $key)
                ->whereHas('onetime_key', function ($query) use ($this_university_id) {
                    $query->where('university_id', $this_university_id)->where('disable_analytics',0);
                })->count();
            if ($page == 'all') {
                $users = Accounts::select('accounts.*', 'univ.name as university_name', 'key.created_at as created_at')->with('onetime_key')
                    ->where('accounts.disable_analytics',0)
                    ->whereIn("accounts.id", $key)
                    ->whereHas('onetime_key', function ($query) use ($this_university_id) {
                        $query->where('university_id', $this_university_id);
                    })->leftJoin('onetime_keys as key', 'accounts.id', '=', 'key.customer_id')
                    ->leftJoin('universities as univ', 'univ.id', '=', 'key.university_id')->get();
            } else {
    			$skipRow = $page * $itemCount;
                $users = Accounts::select('accounts.*', 'univ.name as university_name', 'key.created_at as created_at')->with('onetime_key')
                    ->where('accounts.disable_analytics',0)
                    ->whereIn("accounts.id", $key)
                    ->whereHas('onetime_key', function ($query) use ($this_university_id) {
                        $query->where('university_id', $this_university_id);
                    })->leftJoin('onetime_keys as key', 'accounts.id', '=', 'key.customer_id')
                    ->leftJoin('universities as univ', 'univ.id', '=', 'key.university_id');

                    if(isset($search_params) && !empty($search_params)){
                        $users = $users->where(function ($query) use ($search_params) {
                            $query->where('accounts.name', 'like', "%{$search_params}%")
                            ->orWhere('accounts.user', 'like', "%{$search_params}%")
                            ->orWhere('accounts.email', 'like', "%{$search_params}%")
                            ->orWhere('univ.name', 'like', "%{$search_params}%")
                            ->orWhere('accounts.id', $search_params); 
                        });      
                    }  

                    $resCount = $users->count();
                    $users = $users->skip($skipRow)->take($itemCount)->get();
            }
        }
        if ($users) {
            $this->result = $users;
            $this->message = "success";
            $this->success = "ok";
			$this->total_page = ceil($resCount / $itemCount);
        }

        return $this->response();
    }

    public function allUsersByExamGroup($examGroupId)
    {
        $key = OnetimeKey::where("university_id", $examGroupId)->whereNotNull('customer_id')->get()->pluck("customer_id");
        $users = Accounts::whereIn("id", $key)->get();

        if ($users) {
            $this->result = $users;
            $this->message = "success";
            $this->success = "ok";
        }

        return $this->response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = request("params");
            $hashed_password = password_hash($data["password"], PASSWORD_DEFAULT);
            $data["password"] = $hashed_password;

            $contract = $this->contractService->storeContract($data);

            $account = Accounts::create([
                'contract_id' => $contract->id,
                'user' => ($data['user'] == '') ?  str_random(10) : $data['user'],
                'email' => $data['email'],
                'password' => $data['password'],
                'auth' => $data['auth'] ?? 0,
                'usage_type' => $data['usage_type'] ?? 0,
                'add_way' => $data['add_way'] ?? 0,
                'coupon_id' => $data['coupon_id'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            //db commit
            DB::commit();

            $this->result = $account;
            $this->message = "success";
            $this->success = "ok";
        } catch (\Exception $e) {
            DB::rollback();
            $this->message = "failed to store account";
        }

        return $this->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Accounts::where("id", $id)->with("contracts")->get()->first();
        $this->result = $account;
        $this->message = "success";
        $this->success = "ok";

        return $this->response();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Accounts::where("id", $id)->with("contracts")->get()->first();
        $this->result = $account;
        $this->message = "success";
        $this->success = "ok";

        return $this->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = request("params");
            //             $hashed_password = password_hash($data["password"], PASSWORD_DEFAULT);
            //             $data["password"] = $hashed_password;

            $account = Accounts::findOrFail($id);
            $account->user = $data['user'];
            $account->email = $data['email'];
            $account->save();

            //db commit
            DB::commit();

            $this->result = $account;
            $this->message = "success";
            $this->success = "ok";
        } catch (\Exception $e) {
            DB::rollback();
            $this->message = "failed to store account";
        }

        return $this->response();
    }

    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $params = request("params");

            $account = Accounts::findOrFail($id);
            $account->enabled = $params['enabled'];
            if ($params['enabled'] == 0) {
                $account->disabled_date = Carbon::now()->toDateTimeString('Y-m-d H:i:s');
            }
            $account->save();

            //db commit
            DB::commit();

            $this->result = $account;
            $this->message = "success";
            $this->success = "ok";
        } catch (\Exception $e) {
            DB::rollback();
            $this->message = "failed_update";
        }

        return $this->response();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Accounts::find($id)->delete();

            DB::commit();
            $this->message = "success";
            $this->success = "ok";
        } catch (\Exception $e) {
            DB::rollback();
            $this->message = "failed to delete";
        }

        return $this->response();
    }
}
