<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\UserSortTable;

class UserSortTableController extends Controller
{
    
    private $success = 'ng';
    private $message = '';
    private $result = null;
    private $table = '';
    
    /**
     *
     * @return JsonResponse
     */

    function response() {
        $result = array(
            'success' => $this->success,
            'message' => $this->message,
            'result'  => $this->result,
            'table'  => $this->table,
        );
        return response()->json($result);
    }


    public function object_to_array($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[] = $value;
            }
            return $result;
        }
        return $data;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return response
     */
    public function index(Request $request)
    {
        $params = request("params");
        // $page = $params["page"]; 
        $data = UserSortTable::where('table',  $params["table"])->where('user_id', $params["id"])->get();
        if($data->isEmpty()){
            $this->result = 'no data';
            $this->table =  $params["table"];
            $this->message = "success";
            $this->success = "ok";
        }else{
            $getData = UserSortTable::where('table', $params["table"])->where('user_id', $params["id"])->first();
            $decodeOrder = json_decode($getData->order);
            $getData->oder = $decodeOrder;
            // if($page)
            $this->result = $getData->order;
                
            $this->table =  $params["table"];
            $this->message = "success";
            $this->success = "ok";
        }
        return $this->response();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return response
     */
     
    public function update(Request $request)
    {
        $params = request("params");
        $page =  $params["page"];
        $data = UserSortTable::where('table',  $params["table"])->where('user_id', $params["id"])->get();
        
        if($data->isEmpty()){
            $user_sort_table = new UserSortTable;
            $user_sort_table->order = $params["disp_order"];
            $user_sort_table->table =  $params["table"];
            $user_sort_table->user_id = $params["id"];
            $user_sort_table->save();
        }else{
            $getData = UserSortTable::where('table', $params["table"])->where('user_id', $params["id"])->first();
            $user_sort_table = UserSortTable::find($getData->id);
            
            if($page){
                $page = $page * 10;
                $user_data_table = json_decode($user_sort_table->order);
                $newOrder = json_decode($params["disp_order"]);
                $new_array = [];
                $replacements = [];
                if($user_data_table){
                
                    foreach($newOrder as $key => $add){
                        $add->disp_order = $key+$page;
                        $replacements[$key+$page] =  $add ;
                    }
                    $new_array = array_replace($user_data_table, $replacements);
                }
                $user_sort_table->order = json_encode($new_array);
            }
            else 
                $user_sort_table->order = $params["disp_order"];
            
            $user_sort_table->table =  $params["table"];
            $user_sort_table->user_id = $params["id"];
            $user_sort_table->save();
        }
        $this->result = $this->object_to_array($user_sort_table->order);
        $this->table =  $params["table"];
        $this->message = "success";
        $this->success = "ok";
        
        return $this->response();
    }
}