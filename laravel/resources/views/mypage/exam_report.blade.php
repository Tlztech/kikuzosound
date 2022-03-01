<div class="exam-report-container">
    <div class="exam-report-wrapper">
        <table id="exam-report-table" class="stripe">
            <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
            </thead>
            <tbody >
            <tr>
                <td class="first-row"><b>@lang('mypage.exam_name')</b></td>
                <td><b>@lang('mypage.rate')</b></td>
                <td><b>@lang('mypage.exam_date')</b></td>
            </tr>

            <?php
                use App\OnetimeKey;
                use App\Exams;
                use App\ExamGroup;

                $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');
                $universityId = $oneTimeKey = OnetimeKey::where("customer_id", $userId)->first();

                $exams = [];
                if ($oneTimeKey->is_exam_group == 1) {
                    $universityId = $oneTimeKey->university_id;
              
                    $exams = Exams::where("is_publish", 1)
                    ->with(array('quiz_pack'))
                    ->where(function($groups) use ($universityId) {
                      $groups->whereHas('exam_groups', function($query1) use ($universityId){
                          $query1->where('exam_group_id',$universityId);//has group attribute
                      })->orHas('exam_groups', '<', 1);//has no group attribute
                    })
                    ->whereNull('deleted_at')
                    ->orderBy("disp_order", "desc")
                    ->get();
                }
            ?>

            @if(count($exams) > 0)
                @foreach($exams as $exam)
                    <?php 
                        $totalChoices        = DB::table("use_logs")->where("exam_id", $exam->id)->whereIn('is_correct', [1, 0])->get();
                        $totalCorrectChoices = DB::table("use_logs")->where("exam_id", $exam->id)->where("is_correct", 1)->get();
                        $dateOfExams         = DB::table("use_logs")->where("exam_id", $exam->id)->first();

                        $hasExamLogs = DB::table('use_logs')
                            ->where("type", 1)
                            ->where("user_id", $userId)
                            ->where("exam_id", $exam->id)
                            ->get();
                    ?>
                    @if ($hasExamLogs)
                        <tr>
                            <td>{{$exam->name}}</td>
                            <td>{{ count($totalCorrectChoices) }} / {{count($totalChoices)}}</td>
                            <td>{{ date( 'm.d.Y', strtotime($dateOfExams->created_at ))  }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
<script>
 $(document).ready(function(){
    $('#exam-report-table').DataTable({
        bSort : false,
        bJQueryUI: true,
        bFilter: false, 
        bInfo: false,
        bPaginate: false,
        sDom: 'rt'
    });
 });
</script>