<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InformationRequest;
use App\Http\Services\Information\InformationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class InformationController extends Controller
{
    private $success = 'ng';
    private $message = '';
    private $result = null;
    private $total_page = 0;

    /**
     * @var InformationService
     */
    protected $informationService;

    /**
     * Dependency Injection.
     *
     * @param InformationService $informationService
     */
    public function __construct(InformationService $informationService)
    {
        $this->informationService = $informationService;
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
    public function index($page=0)
    {
        $resCount=$this->informationService->listActiveCount();
        $result = $this->informationService->listActivePagination($page);
        $this->result = $result;
        $this->message = "success";
        $this->success = "ok";
        $this->total_page = ceil($resCount / 10);;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
