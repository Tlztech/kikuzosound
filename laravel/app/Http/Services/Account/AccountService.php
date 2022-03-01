<?php

namespace App\Http\Services\Account;

use App\Account;
use Illuminate\Support\Facades\DB;

class AccountService
{
    /**
     * @var Account
     */
    protected $account;

    /**
     * Injecting dependencies.
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    
    /**
     * Disable or Enable account to view records on univ-admin
     *
     * @param int $customer_id
     * @param int $status
     *
     * @return obj
     */
    public function disableAccountAnalytics($customer_id,$status)
    {
        return Account::where('id', $customer_id)->update(['disable_analytics' => $status]);
    }
    /**
     * Find account by id with contract.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findByAccountIdWithContrctsandProducts($id)
    {
        return $this->account->where('id', $id)->with('contracts')->with('products')->first();
    }

    /**
     * Retrieve All Account With Relations.
     *
     * @param mixed $search
     *
     * @return obj
     */
    public function listAccountsWithRelations($search)
    {
        return DB::table('accounts')
            ->where('accounts.deleted_at', null)
            ->join('contracts', function ($join) {
                $join->on('accounts.contract_id', '=', 'contracts.id');
            })
            ->join('contacts as dealer', function ($join) {
                $join->on('contracts.dealer_id', '=', 'dealer.id');
            })
            ->join('contacts as customer', function ($join) {
                $join->on('contracts.customer_id', '=', 'customer.id');
            })
            ->join('companys as d_com', function ($join) use($search){
                $join->on('dealer.company_id', '=', 'd_com.id');
            })
            ->join('companys as c_com', function ($join) {
                $join->on('customer.company_id', '=', 'c_com.id');
            })
            ->where(function ($query) use ($search) {
                $query->where('d_com.company', 'LIKE', '%'.$search.'%')
                    ->orWhere('d_com.yomi', 'LIKE', '%'.$search.'%')
                    ->orWhere('c_com.company', 'LIKE', '%'.$search.'%')
                    ->orWhere('c_com.yomi', 'LIKE', '%'.$search.'%')
                ;
            })
            ->select(
                'accounts.id',
                'accounts.user',
                'd_com.company as d_com',
                'dealer.name as d_name',
                'c_com.company as c_com',
                'customer.name as c_name'
            )->get();
    }

    /**
     * Store Account.
     *
     * @param array $data
     * @param int   $contract_id
     */
    public function storeAccount($data, $contract_id)
    {
        return $this->account->create([
            'contract_id' => $contract_id,
            'user' => ($data['user'] == '') ?  str_random(10) : $data['user'],
            'password' => $data['password'],
            'auth' => $data['auth'],
            'usage_type' => $data['usage_type'],
            'add_way' => $data['add_way'],
            'coupon_id' => $data['coupon_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
