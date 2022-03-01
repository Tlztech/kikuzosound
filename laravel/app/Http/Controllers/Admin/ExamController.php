<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ExamsRequest;
use App\Http\Controllers\Controller;
use App\Exams;
use App\QuizPack;
use App\ExamGroup;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = Exams::count();
        $exams = Exams::orderBy('disp_order', 'desc')->orderBy('id', 'asc')->paginate(isset($_GET['reorder']) ? $count : 10);       
        return view('admin.exams.index', compact('exams', 'count'));
    }

    public function reorderExam(Request $request) {
        $exams = $request->exams;
        $result = null;
        foreach($exams as $exam){
          $result = DB::table("exam")
            ->where("id", $exam['exam_id'])
            ->update([
              'disp_order' => $exam['disp_order']
            ]);
        }
        return $result;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quiz_packs=QuizPack::all();
        $exam_groups=ExamGroup::orderBy("id", "desc")->get();
        return view('admin.exams.create', compact('exams','quiz_packs','exam_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamsRequest $request)
    {
        $last_exam = Exams::max("disp_order") +1;
        $exam = new Exams();

        $exam->name = $request->input("exam_name");
        $exam->name_jp = $request->input("exam_name_jp");
        $exam->quiz_pack_id =$request->input("quiz_pack");
        $exam->result_destination_email = $request->input("destination_email");
        $exam->is_publish = $request->input("enabled");
        $exam->disp_order = $last_exam;
        $exam->save();

        //save university id's in pivot table
        $exam->exam_groups()->detach();
        foreach ($request->input("exam_group",array()) as $key => $value) {
            $exam->exam_groups()->attach($exam->id,['exam_group_id'=>$value]);
        }

        return redirect()->route('admin.exams.index')->with('message', 'Item created successfully.');
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
        $exams = Exams::find($id);
        $quiz_packs=QuizPack::all();
        $exam_groups=ExamGroup::orderBy("id", "desc")->get();
        return view('admin.exams.edit', compact('exams','quiz_packs','exam_groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamsRequest $request, $id)
    {
        $exam =  Exams::find($id);

        $exam->name = $request->input("exam_name");
        $exam->name_jp = $request->input("exam_name_jp");
        $exam->quiz_pack_id =$request->input("quiz_pack");
        $exam->result_destination_email = $request->input("destination_email");
        $exam->is_publish = $request->input("enabled");
        $exam->save();

        //save university id's in pivot table
        $exam->exam_groups()->detach();
        foreach ($request->input("exam_group",array()) as $key => $value) {
            $exam->exam_groups()->attach($exam->id,['exam_group_id'=>$value]);
        }

        return redirect()->route('admin.exams.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Exams::find($id)->delete();
        return redirect()->route('admin.exams.index');
  
    }

    /**
     * Transfer old university id's to new pivot table
     */
    public function transferOldExamGroups(){
        foreach (Exams::get() as $key => $value) {
            $exam = Exams::findOrFail($value['id']);
            if($value['university_id'] && $value['id']){
                $exam->exam_groups()->attach($exam->id,['exam_group_id'=>$value['university_id']]);
            }
        }
        return redirect()->back();
    }
}
