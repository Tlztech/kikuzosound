<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StethoSound;

class AusculaideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAusculaide(Request $request)
    {
      $id = $request->input("id");
      $case =  $request->input("case")?$request->input("case"):"heart";
      $aus = StethoSound::find($id);
      return view('bodymap.iframeBodymap',compact('aus','case'));
    }

    public function getAjaxAusculaide(Request $request)
    {
        $id = $request->input("id");
        $case = $request->input("case");
        $sound = StethoSound::find($id);
        return view('bodymap.ajaxBodymap',compact('sound','case'));
    }
}
