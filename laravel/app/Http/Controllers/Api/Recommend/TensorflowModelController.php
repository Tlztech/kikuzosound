<?php

namespace App\Http\Controllers\Api\Recommend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFiletest; 

class TensorflowModelController extends Controller
{
    protected $path = 'tf_model/';
    
    public function save(Request $request)
    {
        //Log::debug($request->all());
        
        // model_json
        $file = $request->file('model_json');
        $file_name = $file->getClientOriginalName();
        $file->move($this->path , $file_name); 
        
        // model_weights_bin
        $file = $request->file('model_weights_bin');
        $file_name = $file->getClientOriginalName();
        $file->move($this->path , $file_name);
        
        return "saved";
    }
}
