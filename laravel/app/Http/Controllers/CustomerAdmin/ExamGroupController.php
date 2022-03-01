<?php

namespace App\Http\Controllers\CustomerAdmin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ExamGroupRequest;
use App\Http\Controllers\Controller;
use App\ExamGroup;

class ExamGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->has('search_key')) {
            $names = ExamGroup::select("name","id")->get();
            $searchKey = request()->search_key;
            $ids = [];

            foreach ($names as $name) {
                if( strpos($name->name, $searchKey)===false) 
                    continue;
                    $ids[] = $name->id;     
            }
            $exam_groups = ExamGroup::whereIn('id', $ids)->orderBy("id", "desc")->paginate(10);
        }else{
            $exam_groups = ExamGroup::orderBy("id", "desc")->paginate(10); 
        }
        return view('customer_admin.exam_groups.index', compact('exam_groups'))->withInput('serach_key');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer_admin.exam_groups.create', compact('exam_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamGroupRequest $request)
    {
        $exam_group = new ExamGroup();

        $exam_group->name = $request->input("exam_group_name");
        $exam_group->save();
        return redirect()->route('customer_admin.exam_groups.index');
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
        $exam_group = ExamGroup::find($id);
        return view('customer_admin.exam_groups.edit', compact('exam_group'));
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
        $exam_group = ExamGroup::find($id);
        if(strcmp( strtolower($exam_group->name), strtolower($request->input("exam_group_name")) )!== 0){
            $validation = $this->validateUpdateRequests($request);
            if (count($validation->errors()->all())) {
                return redirect()->back()->withErrors($validation)->withInput();
            }
        }
        $exam_group->name = $request->input("exam_group_name");
        $exam_group->save();
        return redirect()->route('customer_admin.exam_groups.index');
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

    private function validateUpdateRequests($request){
        $rules = [
            'exam_group_name' => 'required|unique:universities,name',
        ];
        $messages = [
            'exam_group_name.unique' => 'Examグループ名はすでに存在します。',
            'exam_group_name.required' => 'Examグループ名を入力してください'
        ];
        $selected_rules = array_intersect_key($rules, $request->all());
        $validator = \Validator::make($request->all(), $selected_rules, $messages);
        return $validator;
    }
    
}
