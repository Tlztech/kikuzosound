<?php

namespace App\Http\Controllers\Api\Recommend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Lib\Recommend;

class RecommendController extends Controller
{
    public function get_learning_data()
    {
        $data = DB::select('
                                select
                                Z3.lib_id,
                                (select 
                                    sum(case when is_correct=1 then 1 else 0 end)
                                    from use_logs
                                    where quiz_type is not null and quiz_type!="final" and is_correct is not null and JSON_CONTAINS(JSON_EXTRACT(question_log,"$.lib"),Z3.lib_id,"$")
                                ) as correct,
                                (select 
                                    sum(case when is_correct=0 then 1 else 0 end)
                                    from use_logs
                                    where quiz_type is not null and quiz_type!="final" and is_correct is not null and JSON_CONTAINS(JSON_EXTRACT(question_log,"$.lib"),Z3.lib_id,"$")
                                ) as in_correct,
                                (select 
                                    round(sum(case when is_correct=1 then 1 else 0 end) / (sum(case when is_correct=1 then 1 else 0 end) + sum(case when is_correct=0 then 1 else 0 end)) * 100)
                                    from use_logs
                                    where quiz_type is not null and quiz_type!="final" and is_correct is not null and JSON_CONTAINS(JSON_EXTRACT(question_log,"$.lib"),Z3.lib_id,"$")
                                ) as correct_rate,
                                (select 
                                    round(sum(case when is_correct=0 then 1 else 0 end) / (sum(case when is_correct=1 then 1 else 0 end) + sum(case when is_correct=0 then 1 else 0 end)) * 100)
                                    from use_logs
                                    where quiz_type is not null and quiz_type!="final" and is_correct is not null and JSON_CONTAINS(JSON_EXTRACT(question_log,"$.lib"),Z3.lib_id,"$")
                                ) as in_correct_rate,
                                (select 
                                    sum(TIMEDIFF(end_time, stt_time)) as sec
                                    from use_logs
                                    where type=3 and lib_id = Z3.lib_id
                                ) as sec
                                from use_logs as Z3
                                where Z3.lib_id is not null
                                and (Z3.type = 1 or Z3.type = 2)
                                group by Z3.user_id,Z3.lib_id
                            ');
        $json = Collection::make($data)->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $json;
    }

    public function get_recommended_data(Request $request)
    {
        $user_id = $request->input('user_id');

        if (! isset($request->user_id)) {
            return 'Error: reuired';
        }

        $Recommend = new Recommend();
        $libid_data = $Recommend->getAnalyzedData($request->user_id);

        return json_encode($libid_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
