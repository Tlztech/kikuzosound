<?php

namespace App\Http\Services\Information;

use App\Information;

class InformationService
{
    /**
     * Depency Injectio.
     *
     * @param Information $information
     */
    public function __construct(Information $information)
    {
        $this->information = $information;
    }

    /**
     * List All resources.
     *
     * @return obj
     */
    public function listAll()
    {
        return $this->information->orderBy('updated_at', 'desc')->paginate(10);
    }

    /**
     * List Active resources.
     *
     * @return obj
     */
    public function listActive($paginate = true)
    {
        if($paginate){
            return $this->information->where('status', 1)->orderBy('updated_at', 'desc')->paginate(10);
        }else{
            return $this->information->where('status', 1)->orderBy('updated_at', 'desc')->get();
        }
    }

     /**
     * List Active resources with custom pagination.
     *
     * @return obj
     */
    public function listActivePagination($page=0)
    {
        $skipRow = $page * 10;
        return $this->information->where('status', 1)->orderBy('updated_at', 'desc')->skip($skipRow)->take(10)->get();
    }

    /**
     * List Active Count.
     *
     * @return int
     */
    public function listActiveCount()
    {
        return $this->information->where('status', 1)->count();
    }

    /**
     * Find resource by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->information->find($id);
    }

    /**
     * Store Information.
     *
     * @param array $data
     *
     * @return obj
     */
    public function store($data)
    {
        $data['title_en'] = 'N/A';
        return $this->information->create($data);
    }

    /**
     * Update Information.
     *
     * @param array $data
     * @param obj   $information
     *
     * @return obj
     */
    public function update($data, $information)
    {
        $data['title_en'] = 'N/A';
        return $information->update($data);
    }
}
