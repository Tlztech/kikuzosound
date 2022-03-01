<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Exams;
use App\QuizPack;
use App\Quiz;
use App\StethoSound;
use App\ExamGroup;

class GroupManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exams::with("exam_groups")->whereNull("deleted_at")->orderBy('disp_order', 'desc')->orderBy('id', 'asc')->get();  
        $quiz_packs = QuizPack::with("exam_groups")->whereNull("deleted_at")->orderBy('disp_order','asc')->orderBy('id', 'desc')->get(); 
        $quizzes = Quiz::with("exam_groups")->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->get();

        $stetho = StethoSound::with("exam_groups")->where('lib_type', 0)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();
        $ipax = StethoSound::with("exam_groups")->where('lib_type', 1)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();
        $palpation = StethoSound::with("exam_groups")->where('lib_type', 2)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();
        $ecg = StethoSound::with("exam_groups")->where('lib_type', 3)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();
        $inspection = StethoSound::with("exam_groups")->where('lib_type', 4)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();
        $xray = StethoSound::with("exam_groups")->where('lib_type', 5)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();
        $ucg = StethoSound::with("exam_groups")->where('lib_type', 6)->orderBy('disp_order', 'desc')->orderBy('id', 'desc')->where('deleted_at', null)->get();

        $exam_groups=ExamGroup::orderBy("id", "desc")->get();
        return view('admin.group_management.index', compact('exam_groups','exams','quiz_packs','quizzes','stetho','ipax','palpation','ecg','inspection','xray','ucg'));
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
            $group_id= $request->input("group_id");
            $des_group_id = $request->input("des_group_id");
            $exam_ids= $request->input("exam_ids");
            $quiz_pack_ids= $request->input("quiz_pack_ids");
            $quiz_ids= $request->input("quiz_ids");
            $library_ids= $request->input("library_ids");
            $exam_original_ids= $request->input("exam_original_ids");
            $quiz_pack_original_ids= $request->input("quiz_pack_original_ids");
            $quiz_original_ids= $request->input("quiz_original_ids");
            $library_original_ids= $request->input("library_original_ids");

        //$group = ExamGroup::find($group_id);
        $des_group = ExamGroup::find($des_group_id);

        $des_group->pivot_exam_exam_group()->detach($exam_original_ids);
        $des_group->pivot_exam_exam_group()->attach($exam_ids);

        $des_group->pivot_exam_group_quiz_pack()->detach($quiz_pack_original_ids);
        $des_group->pivot_exam_group_quiz_pack()->attach($quiz_pack_ids);

        $des_group->pivot_exam_group_quiz()->detach($quiz_original_ids);
        $des_group->pivot_exam_group_quiz()->attach($quiz_ids);

        $des_group->pivot_exam_group_stetho_sound()->detach($library_original_ids);
        $des_group->pivot_exam_group_stetho_sound()->attach($library_ids);
        return "seccess";
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
