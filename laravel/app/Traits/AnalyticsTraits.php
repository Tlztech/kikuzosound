<?php

namespace App\Traits;

use Illuminate\Http\Request;
use DB;
use App\ExamResults;
use App\QuizChoice;
use App\Quiz;

trait AnalyticsTraits
{

    /**
     * Get the top 5 exam of user of specified university
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopExams($id, $examGroupId, $whereCapable)
    {
        list($min, $max) = $this->capability($whereCapable);
        $results = ExamResults::select("exam_id", DB::raw('COUNT(is_correct) AS is_correct'))
        ->where("customer_id",$id)
        ->where("is_correct","=",1)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->with("exam")
        ->groupBy("exam_id")
        ->take(5)
        ->latest()->get();

        $res = [];
        foreach ($results as $result) {
            if ($result["is_correct"] >= $min && $result["is_correct"] <= $max  ) {
                $res[] = $result;
            }
        }

        return $res;
    }

    /**
     * Get the exam score of customer on specified university
     *
     * @param $id, $examGroupId
     * @return \Illuminate\Database\Eloquent\Collection
     *
     *
     * */
    public function getScoreExam($id, $examGroupId, $whereCapable)
    {
        list($min, $max) = $this->capability($whereCapable);
        $results = ExamResults::select("exam_id", DB::raw('COUNT(is_correct) AS is_correct'))
        ->where("customer_id",$id)
        ->where("is_correct","=",1)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->with("exam")
        ->groupBy("exam_id")
        ->latest()->get();

        $res = [];
        foreach ($results as $result) {
            if ($result["is_correct"] >= $min && $result["is_correct"] <= $max  ) {
                $res[] = $result;
            }
        }

        return $res;
    }

    /**
     * @param $id, $examGroupId
     *
     *
     **/
    public function getExamStudyTime($id, $examGroupId, $whereCapable)
    {
        list($min, $max) = $this->capability($whereCapable);
        $results = ExamResults::select("start_time", "finish_time", "exam_id", DB::raw('COUNT(is_correct) AS is_correct'), DB::raw('sum(floor(TIMEDIFF(finish_time, start_time))) AS total_time'))
        ->where("customer_id",$id)
        ->where("is_correct","=",1)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->with("exam")
        ->groupBy("exam_id")
        ->latest()->get()->toArray();

        $res = [];
        foreach ($results as $result) {
            $examName = "";
            if ($result["is_correct"] >= $min && $result["is_correct"] <= $max  ) {
                if ($result["exam"]) {
                    $examName = $result["exam"]["name"];
                } else {
                    $examName = Exam::find($result["exam_id"])->get()->first()->pluck("name");
                }

                $res[] = [
                    "exam_title" => $examName,
                    "exam_id" => $result["exam_id"],
                    "start_date" => $result["start_time"],
                    "end_date" => $result["finish_time"],
                    "used_time" => $result["total_time"],
                ];
            }

        }
        return $res;
    }

    /**
     * Get the percentage of Exam per user of the unviversity
     *
     * @param $id, $examGroupId
     * @return array
     *
     *
     * */

    public function getPercentExams($id, $examGroupId, $whereCapable)
    {
        list($min, $max) = $this->capability($whereCapable);
        $results = ExamResults::select("exam_id", DB::raw('COUNT(is_correct) AS is_correct'))
        ->where("customer_id",$id)
        ->where("is_correct","=",1)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->with("exam")
        ->groupBy("exam_id")

        ->latest()->get()->toArray();

        $count = ExamResults::select("is_correct")
        ->where("customer_id",$id)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->count();

        $res = [];
        foreach ($results as $result) {
            if ($result["is_correct"] >= $min && $result["is_correct"] <= $max  ) {
                $res[] = [
                    "exam_label" => $result["exam"]["name"],
                    "exam_id" => $result["exam_id"],
                    "is_correct" => $result["is_correct"] ,
                    "exam_percentage" => ceil($result["is_correct"] / $count * 100) . "%",
                    "total" => $count
                ];
            }

        }

        return $res;

    }

    /**
     * @param $id, $examGroupId
     * @return array
     *
     *
     * **/

    public function getPercentQuiz($id, $examGroupId, $whereCapable)
    {
        list($min, $max) = $this->capability($whereCapable);
        $results = ExamResults::select("quiz_id", DB::raw('COUNT(is_correct) AS is_correct'))
        ->where("customer_id",$id)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->groupBy("quiz_id")
        ->latest()->get()->toArray();

        $count = ExamResults::select("quiz_id", "is_correct")
        ->where("customer_id",$id)
        ->whereHas('exam', function ($query) use ($examGroupId) {
            $query->where('university_id', $examGroupId);
        })
        ->count();
        $res = [];
        foreach ($results as $result) {
            if ($result["is_correct"] >= $min && $result["is_correct"] <= $max  ) {
                if (!$result["quiz_id"]) continue;
                $quiz = QuizChoice::select("quiz_id")->find((int)$result["quiz_id"]);
                if (!$quiz) continue;
                $quiz = quiz::find((int)$quiz->quiz_id);
                $res[] = [
                    "is_correct" => $result["is_correct"],
                    "quiz_id" => $result["quiz_id"],
                    "quiz_percentage" => ceil((int)$result["is_correct"] / $count * 100) . "%",
                    "total" => $count,
                    "quiz_title" => str_replace(":"," ",$quiz->title_en)
                ];
            }
        }

        return $res;
    }

    public function capability ($capabilityScore)
    {
        switch ($capabilityScore) {
            case 1 : return [0, 9];
                break;
            case 2 : return [10, 29];
                break;
            case 3 : return [30, 49];
                break;
            case 4 : return [50, 69];
                break;
            case 5 : return [70, 79];
                break;
            case 6 : return [80, 89];
                break;
            case 7 : return [90, 100];
                break;
        }
    }
}