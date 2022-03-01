<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Intervention\Image\Facades\Image;
use App\User;


class LibUserController extends Controller
{

    private $success = 'ng';
    private $message = '';
    private $result = null;

    protected $university_id = null;

    public function __construct(Request $request)
    {
        $this->university_id =  $request->input("university_id");
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
            'result'  => $this->result,
        );
        return response()->json($result);
    }

    public function getUser()
    {
        $user = User::superintendents()->get();
        if ($user) {
            $this->result = $user;
            $this->message = "success";
            $this->success = "ok";
        }

        return $this->response();
    }
}
