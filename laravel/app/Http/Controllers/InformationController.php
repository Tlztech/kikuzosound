<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\MemberController;
use App\Http\Services\Information\InformationService;

class InformationController extends Controller
{
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
     * Information List.
     *
     * @return Response
     */
    public function frontendIndex()
    {
        if(Session::get('lang')){
            App::setLocale(Session::get('lang'));
        }
        return view('news', [
            'news' => $this->informationService->listActive(),
        ]);
    }
}
