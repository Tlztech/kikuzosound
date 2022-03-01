<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer_admin.application_processing.index');
    }
}
