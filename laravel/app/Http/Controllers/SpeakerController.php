<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;

// 聴診専用スピーカ画面
class SpeakerController extends Controller
{
    public function __construct()
    {
        if(Session::get('lang')) App::setLocale(Session::get('lang'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('speaker');
    }
}
