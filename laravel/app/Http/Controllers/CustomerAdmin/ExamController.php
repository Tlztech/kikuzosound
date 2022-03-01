<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamRequest;
use App\Http\Services\Exam\ExamService;
use App\Http\Services\Sound\StethoSoundService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * @var ExamService
     */
    protected $examService;

    /**
     * @var StethoSoundService
     */
    protected $stethoSoundService;

    /**
     * Injecting dependencies.
     *
     * @param ExamService        $examService
     * @param StethoSoundService $stethoSoundService
     */
    public function __construct(
        ExamService $examService,
        StethoSoundService $stethoSoundService
    ) {
        $this->examService = $examService;
        $this->stethoSoundService = $stethoSoundService;
    }

    /**
     * Index View.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customer_admin.exams.index', [
            'exams' => $this->examService->listAllExams(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customer_admin.exams.create', [
            'sounds' => $this->stethoSoundService->listAllStethoSounds(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(ExamRequest $request)
    {
        if (!$exam = $this->examService->storeExam($request->all())) {
            return back()->withInput();
        }
        return redirect()->route('customer_admin_exams');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $exam_id
     *
     * @return \Illuminate\View\View
     */
    public function edit($exam_id)
    {
        return view('customer_admin.exams.edit', [
            'exam' => $this->examService->findById($exam_id),
            'sounds' => $this->stethoSoundService->listAllStethoSounds(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamRequest $request
     * @param int         $exam_id
     *
     * @return \Illuminate\View\View
     */
    public function update(ExamRequest $request, $exam_id)
    {
        $exam = $this->examService->findById($exam_id);
        if (!$exam = $this->examService->updateExam($request->all(), $exam)) {
            return back()->withInput();
        }
        return redirect()->route('customer_admin_exams');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function destroy($exam_id)
    {
        $exam = $this->examService->findById($exam_id);
        $exam->sounds()->detach();
        $exam->delete();
        return redirect()->route('customer_admin_exams');
    }
}
