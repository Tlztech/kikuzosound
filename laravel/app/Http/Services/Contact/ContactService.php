<?php

namespace App\Http\Services\Contact;

use App\Contact;

class ContactService
{
    /**
     * @var Contact
     */
    protected $contact;

    /**
     * Injecting dependencies.
     *
     * @param Contact $contact
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Retrieve All Contact.
     *
     * @return obj
     */
    public function listAll()
    {
        return $this->contact->all();
    }

    /**
     * Find contact by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->contact->find($id);
    }

    /**
     * Retrieve All Dealer Contacts With Companies.
     *
     * @return obj
     */
    public function listAllDealerContactsWithCompanies()
    {
        return $this->contact->whereIn('role', [0, 2])->with('company')->get();
    }

    /**
     * Retrieve All Customer Contacts With Companies.
     *
     * @return obj
     */
    public function listAllCustomerContactsWithCompanies()
    {
        return $this->contact->whereIn('role', [1, 2])->with('company')->get();
    }

    /**
     * Create Contact.
     *
     * @param array $data
     *
     * @return obj
     */
    public function storeContact($data)
    {
        return $this->contact->create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'zip' => $data['zip'],
            'address' => $data['address'],
            'department' => $data['department'],
            'auth' => $data['auth'],
            'role' => $data['role'],
            // 'yomi' => $data['yomi'],
            'memo' => $data['memo']
        ]);
    }

    /**
     * Create contact.
     *
     * @param array $data
     * @param obj   $contact
     *
     * @return obj
     */
    public function updateContact($data, $contact)
    {
        return $contact->update([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'zip' => $data['zip'],
            'address' => $data['address'],
            'department' => $data['department'],
            'auth' => $data['auth'],
            'role' => $data['role'],
            'yomi' => $data['yomi'],
            'memo' => $data['memo']
        ]);
    }
}
