<?php

namespace App\Http\Services\Contract;

use App\Contracts as Contract;

class ContractService
{
    /**
     * @var Contract
     */
    protected $contract;

    /**
     * Injecting dependencies.
     *
     * @param Contract $contact
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Find contract by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->contract->find($id);
    }

    /**
     * Store Contract.
     *
     * @param array $data
     */
    public function storeContract($data)
    {
        return $this->contract->create([
            'dealer_id' => $data['dealer_id'],
            'customer_id' => $data['customer_id'],
            'memo' => $data['memo'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
