<?php

namespace App\Http\Services\Exam;

use App\Exam;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * @var Exam
     */
    protected $exam;

    /**
     * Injecting dependencies.
     *
     * @param Exam $exam
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Retrieve Exams With Sounds.
     *
     * @return obj
     */
    public function listAllExams()
    {
        return $this->exam->all();
    }

    /**
     * Find exam by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->exam->find($id);
    }

    /**
     * Store Exam.
     *
     * @param array $data
     *
     * @return obj
     */
    public function storeExam($data)
    {
        try {
            DB::beginTransaction();
            $exam = $this->exam->create([
                'user' => $data['user'],
                'password' => bcrypt($data['password']),
                'speaker' => bcrypt($data['speaker']),
                'unit' => $data['unit'],
                'max_connections' => $data['max_connections'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $sounds = explode(',', $data['disp_order']);
            $array = [];
            foreach ($sounds as $key => $sound) {
                $array[$sound] = [
                    'disp_order' => ($key + 1),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            }
            $exam->sounds()->sync($array);
            DB::commit();
            return $exam;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update Exam.
     *
     * @param array $data
     * @param mixed $exam
     *
     * @return obj
     */
    public function updateExam($data, $exam)
    {
        try {
            DB::beginTransaction();
            $updateData = [
                'user' => $data['user'],
                'unit' => $data['unit'],
                'max_connections' => $data['max_connections'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
            if ('' != $data['password']) {
                $updateData = $updateData + ['password' => bcrypt($data['password'])];
            }
            if ('' != $data['speaker']) {
                $updateData = $updateData + ['speaker' => bcrypt($data['speaker'])];
            }
            $exam->update($updateData);

            $sounds = explode(',', $data['disp_order']);
            $array = [];
            foreach ($sounds as $key => $sound) {
                $array[$sound] = [
                    'disp_order' => ($key + 1),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            }
            $exam->sounds()->sync($array);
            DB::commit();
            return $exam;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
