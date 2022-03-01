<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    public $success = 'ng';
    public $message = '';
    public $result = [];

    public $model_name;
    public $Model;

    protected $university_id = null;


    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->university_id = $request->input("university_id");
        $this->Model = new $this->model_name;
    }


    /**
     * response
     *
     * @return void
     */
    public function response()
    {
        $result = array(
            'success' => $this->success,
            'message' => $this->message,
            'result'  => $this->result,
        );
        return response()->json($result);
    }

    public function getModel()
    {
        return $this->Model;
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $Model = $this->getModel();
        $Obj = $Model::find($id);
        if (!$Obj) {
            return $this->message = "Can not find {$id}";
        }
        
        $params = request("params");
        foreach ($params as $key => $value) {
            $Model->$key = $value;
        }
        if (!$Model->save()) {
            return $this->message = "Failed update {$id}";
        }

        $this->message = "Library item Updated Successfully";
        $this->success = "ok";
        $this->result = $Obj;

        return $this->response();
    }
    
    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        $Model = $this->getModel();
        $Obj = $Model::find($id);
        if (!$Obj) {
            return $this->message = "Can not find {$id}";
        }

        if (!$Obj->delete()) {
            return $this->message = "Failed delete {$id}";
        }

        $this->message = "Library item Deleted Successfully";
        $this->success = "ok";
        $this->result = $Obj;

        return $this->response();
    }
}
