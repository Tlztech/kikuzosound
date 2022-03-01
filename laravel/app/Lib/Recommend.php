<?php
namespace App\Lib;

use Illuminate\Support\Facades\DB;
use Session;
class Recommend
{
    /**
     * Get learn data
     */
    public function getLearningData()
    {
        $data = DB::select('
select
  Z3.lib_id,
  Z2.*
from use_logs as Z3
left join (
  select
    user_id,
    sec,
    Z1.correct,
    Z1.in_correct,
    round(Z1.correct/(Z1.correct+Z1.in_correct) * 100) as correct_rate,
    round(Z1.in_correct/(Z1.correct+Z1.in_correct) * 100) as in_correct_rate
  from (
    select
      user_id,
      count(user_id) as all_rows,
      sum(case when is_correct=1 then 1 else 0 end) as correct,
      sum(case when is_correct=0 then 1 else 0 end) as in_correct,
      sum(TIMEDIFF(end_time, stt_time)) as sec
    from use_logs group by user_id
  ) as Z1
) Z2 on Z2.user_id = Z3.user_id
where Z3.lib_id is not null and Z2.correct_rate is not null
');
        return $data;
    }
    
    /**
     * Get recommend data
     */
    public function getAnalyzedData($user_id)
    {
        $correct_ids = $this->countCorrectLibIds($user_id);
        $incorrect_ids = $this->countIncorrectLibIds($user_id);
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        $param = [
            'university_id' => $universityId
        ];
        $rows = DB::select('
SELECT 
  id,
  lib_type
FROM stetho_sounds 
WHERE is_public = 1
AND deleted_at is null
');
        $ids = [];
        foreach ($rows as $row) {
            $pivot_exam_group_stetho_sound = DB::table("pivot_exam_group_stetho_sound")->where("stetho_sound_id", $row->id)->get();
            $ids[] = [
                'lib_id'   => $row->id,
                'lib_type' => $row->lib_type,
                'correct_cnt' => (isset($correct_ids[$row->id])) ? $correct_ids[$row->id] : 0,
                'incorrect_cnt' => (isset($incorrect_ids[$row->id])) ? $incorrect_ids[$row->id] : 0,
                'univ_ids' => $pivot_exam_group_stetho_sound
            ];
        }
        
        // diff
        foreach ($ids as $key => $data) {
            $ids[$key]['diff'] = $data['correct_cnt'] - $data['incorrect_cnt'];
            $total = $data['correct_cnt'] + $data['incorrect_cnt'];
            if ($total === 0) { $ids[$key]['correct_ratio'] = 0; continue; }
            $ids[$key]['correct_ratio'] = 100 * $data['correct_cnt'] / $total;
        }
        
        // sort
        $ids = $this->sort($ids);

        return $ids;
    }

    private function sort($id_data)
    {
        $diff_ary          = $this->createArrayForSort('diff', $id_data);
        $correct_ratio_ary = $this->createArrayForSort('correct_ratio', $id_data);
        array_multisort($correct_ratio_ary, SORT_ASC, $diff_ary, SORT_ASC ,$id_data);
        return $id_data;
    }

    /**
     */
    private function countCorrectLibIds($user_id)
    {
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        $param = [
            'user_id' => $user_id,
            'university_id' => $universityId
        ];
        $rows = DB::select('
        SELECT
        question_log
        FROM use_logs
        WHERE is_correct =1
        AND user_id = :user_id
        AND university_id = :university_id
        AND quiz_type is not null 
        AND quiz_type!="final"
        AND JSON_EXTRACT(question_log,"$.lib") is not null
        ', $param);
        return $this->countLibIdFromQuestionLog($rows);
    }

    /**
     */
    private function countIncorrectLibIds($user_id)
    {
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        $param = [
            'user_id' => $user_id,
            'university_id' => $universityId
        ];
        $rows = DB::select('
        SELECT
        question_log,
        JSON_EXTRACT(question_log,"$.lib") as libs
        FROM use_logs
        WHERE is_correct = 0
        AND user_id = :user_id
        AND university_id = :university_id
        AND quiz_type is not null 
        AND quiz_type!="final"
        AND JSON_EXTRACT(question_log,"$.lib") is not null
        ', $param);
        return $this->countLibIdFromQuestionLog($rows);
    }

    private function countLibIdFromQuestionLog($rows)
    {
        $ids = [];
        foreach ($rows as $row) {
            if (!isset($row->question_log)) { continue; }
            $question_log_data = json_decode($row->question_log);
            
            if (!isset($question_log_data->lib)) { continue; }
            if (!is_array($question_log_data->lib)) { continue; }
            foreach ($question_log_data->lib as $id) {
                if (! isset($ids[$id])) {
                    $ids[$id] = 1;
                } else {
                    $ids[$id] ++;
                }
            }
        }
        return $ids;
    }

    private function createArrayForSort($key_name, $array)
    {
        foreach ($array as $key => $value) {
            $standard_key_array[$key] = $value[$key_name];
        }
        return $standard_key_array;
    }
}
