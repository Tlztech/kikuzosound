<?php

namespace App\Http\Services\Company;

use App\Companys as Company;
use DB;

class CompanyService
{
    /**
     * @var Company
     */
    protected $company;

    /**
     * Injecting dependencies.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Retrieve All Companys With Companies.
     *
     * @return obj
     */
    public function listAll()
    {
        return $this->company->all();
    }

    /**
     * Find Company by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->company->find($id);
    }

    /**
     * Search Company with contacts.
     *
     * @param mixed $search
     *
     * @return obj
     */
    public function companiesWithContactsByKeyword($search)
    {
        return $this->company->where('company', 'LIKE', '%'.$search.'%')->with('contacts')->get();
    }

    /**
     * Search Company with contacts.
     *
     * @param mixed $search
     * @param int   $role
     *
     * @return obj
     */
    public function companiesWithContactsByKeywordAndRole($search, $role)
    {
        return $this->company
            ->where('company', 'LIKE', '%'.$search.'%')
            ->where(function ($query) use ($role) {
                (1 == $role) ? $query->whereIn('role', [1, 2]) : $query->whereIn('role', [0, 2]);
            })
            ->with('contacts')->get();
    }

    /**
     * listVendorCompanies.
     *
     * @return obj
     */
    public function listVendorCompanies()
    {
        return $this->company->whereIn('role', [0, 2])->get();
    }

    /**
     * Update coampany.
     *
     * @param array $data
     * @param obj   $company
     *
     * @return obj
     */
    public function updateCompany($data, $company)
    {
        return $company->update($data);
    }

    /**
     * Store coampany.
     *
     * @param array $data
     *
     * @return obj
     */
    public function storeCompany($data)
    {
        return $this->company->create($data);
    }

    /**
     * Retrieve All Account With Relations.
     *
     * @param mixed      $search
     * @param null|mixed $status
     *
     * @return obj
     */
    public function listAccountsWithRelations($status = null)
    {
        return DB::table('products')
            ->whereNull('products.deleted_at')
            ->leftJoin('accounts_products', function ($join) {
                $join->on('products.id', '=', 'accounts_products.product_id')
                    ->whereNull('accounts_products.deleted_at')
                ;
            })
            ->leftJoin('accounts', function ($join) {
                $join->on('accounts_products.account_id', '=', 'accounts.id');
            })
            ->leftJoin('contracts', function ($join) {
                $join->on('accounts.contract_id', '=', 'contracts.id');
            })
            ->leftJoin('contacts as dealer', function ($join) {
                $join->on('contracts.dealer_id', '=', 'dealer.id');
            })
            ->leftJoin('contacts as customer', function ($join) {
                $join->on('contracts.customer_id', '=', 'customer.id');
            })
            ->leftJoin('companys as d_com', function ($join) {
                $join->on('dealer.company_id', '=', 'd_com.id');
            })
            ->leftJoin('companys as c_com', function ($join) {
                $join->on('customer.company_id', '=', 'c_com.id');
            })
            ->select('accounts.id AS aid', 'product_no', 'p_status', 'd_com.company AS d_com', 'dealer.name AS d_name', 'c_com.company AS c_com', 'customer.name AS c_name')->get();
    }
}
