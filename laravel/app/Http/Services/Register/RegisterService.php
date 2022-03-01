<?php

namespace App\Http\Services\Register;

use App\Register;

class RegisterService
{
    /**
     * @var Register
     */
    protected $register;

    /**
     * Injecting dependencies.
     *
     * @param Register $register
     */
    public function __construct(Register $register)
    {
        $this->register = $register;
    }

    /**
     * Retrieve All registers With Companies.
     *
     * @return obj
     */
    public function listAll()
    {
        return $this->register->all();
    }

    /**
     * Find register by account id.
     *
     * @param int $account_id
     *
     * @return obj
     */
    public function findByAccountId($account_id)
    {
        return $this->register->where('account_id', $account_id)->first();
    }

    /**
     * Find Register Products.
     *
     * @param int $register_id
     *
     * @return obj
     */
    public function findRegisterProducts($register_id)
    {
        return DB::table('registers_products')->where([
            ['register_id', $register_id],
            ['deleted_at', null],
        ]);
    }

    /**
     * Find Products Not Registered.
     *
     * @param int $register_id
     *
     * @return obj
     */
    public function findNotRegisterProducts($register_id)
    {
        return DB::table('registers_products')->where([
            ['register_id', '<>', $register_id],
            ['deleted_at', null],
        ]);
    }
}
