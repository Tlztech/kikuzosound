<?php

namespace App\Http\Services\Product;

use App\Product;

class ProductService
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * Injecting dependencies.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Retrieve All products With Companies.
     *
     * @return obj
     */
    public function listAll()
    {
        return $this->product->where('p_status', 0)->get();
    }

    /**
     * Find product by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->product->find($id);
    }

    /**
     * Register Product.
     *
     * @param array $data
     * @param obj   $product
     *
     * @return obj
     */
    public function insertProduct($data)
    {
        return $this->product->insert($data);
    }

    /**
     * Register Product.
     *
     * @param array $product_nos
     * @param obj   $product
     *
     * @return obj
     */
    public function changeStatus($product_nos)
    {
        return $this->product->whereIn('id', $product_nos)->update(['p_status' => 1]);
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
        return DB::table('products')
            ->where('products.deleted_at', null)
            ->join('accounts_products', function ($join) {
                $join->on('products.id', '=', 'accounts_products.product_id')
                    ->where('accounts_products.deleted_at', null)
                ;
            })
            ->join('accounts', function ($join) {
                $join->on('accounts_products.account_id', '=', 'accounts.id');
            })
            ->join('contracts', function ($join) {
                $join->on('accounts.contract_id', '=', 'contracts.id');
            })
            ->join('contacts as dealer', function ($join) {
                $join->on('contracts.dealer_id', '=', 'dealer.id');
            })
            ->join('contacts as customer', function ($join) {
                $join->on('contracts.customer_id', '=', 'customer.id');
            })
            ->select('*')->get();
    }
}
