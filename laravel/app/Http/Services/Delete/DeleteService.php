<?php

namespace App\Http\Services\Delete;

use App\Deletes;

class DeleteService
{
    /**
     * @var Deletes
     */
    protected $deletes;

    /**
     * Injecting dependencies.
     *
     * @param Deletes $deletes
     */
    public function __construct(Deletes $deletes)
    {
        $this->deletes = $deletes;
    }

    /**
     * Find deletes by account_id.
     *
     * @param int $account_id
     *
     * @return obj
     */
    public function findByAccountId($account_id)
    {
        return $this->deletes->find($account_id);
    }
}
