<?php 

    use Carbon\Carbon;

    function sum_the_time($time, $time2) {
        $secs = strtotime($time2)-strtotime("00:00:00");
        $result = date("H:i:s",strtotime($time)+$secs);
        return $result;
    }

    function hoursToMinutes($hours) { 
        $minutes = 0; 
        if (strpos($hours, ':') !== false) 
        { 
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours); 
        } 
        return $hours * 60 + $minutes; 
    } 

    function minutesToHours($minutes) { 
        $hours = (int)($minutes / 60); 
        $minutes -= $hours * 60; 
        return sprintf("%d:%02.0f", $hours, $minutes); 
    }  

    function getUserStudyHour($userId) {
    
        $studyHours = DB::table("use_logs")->where("user_id", $userId)->where("type", 3)->whereNotNull("end_time" )->get();
        $totalStudyHour = "00:00:00";

        foreach ($studyHours as $studyHour) {
            $start = new Carbon($studyHour->stt_time);
            $end = new Carbon($studyHour->end_time);
    
            $time = $start->diff($end)->format('%H:%I:%S');
    
            $totalStudyHour = sum_the_time($totalStudyHour, $time);
        }
        return $totalStudyHour;
    }


    $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');

    $registeredUsers = DB::table("onetime_keys")->whereNotNull("customer_id" )->get();
    $userStudyHours = array();

    foreach ($registeredUsers as $registeredUser) {
        array_push($userStudyHours, [
            "id" => $registeredUser->customer_id,
            "minutes" => hoursToMinutes(getUserStudyHour($registeredUser->customer_id))
        ]);
    }

    // sort desc
    usort($userStudyHours,function($first,$second){
        return $first['minutes'] < $second['minutes'];
    });

    $ranking = 1;
    foreach ($userStudyHours as $userStudyHour) {
        if ($userStudyHour["id"] == $userId) {
            break;
        }
        $ranking++;
    }

    $dateOfRegister = DB::table("onetime_keys")->where("customer_id", $userId)->first()->created_at;
    $dateOfRegister = date('Y-m-d', strtotime($dateOfRegister));
    $todayDate = date('Y-m-d');

    $diff = date_diff(date_create($dateOfRegister), date_create($todayDate));
    $days = $diff->format("%a");

    $studyHours = DB::table("use_logs")->where("user_id", $userId)->where("type", 3)->whereNotNull("end_time" )->get();

    $totalStudyHour = "00:00:00";
    $iPax = "00:00:00";
    $Xray = "00:00:00";
    $UCG = "00:00:00";
    $ECG = "00:00:00";
    $Ins = "00:00:00";
    $Palp = "00:00:00";
    $Stetho = "00:00:00";

    foreach ($studyHours as $studyHour) {
        $start = new Carbon($studyHour->stt_time);
        $end = new Carbon($studyHour->end_time);

        $time = $start->diff($end)->format('%H:%I:%S');

        // $totalStudyHour = sum_the_time($totalStudyHour, $time);

        switch ($studyHour->quiz_type) {
            case 'iPax':
            case 'Aus':
                $iPax = sum_the_time($iPax, $time);
                break;
            case 'Xray':
                $Xray = sum_the_time($Xray, $time);
                break;
            case 'UCG':
                $UCG = sum_the_time($UCG, $time);
                break;
            case 'ECG':
                $ECG = sum_the_time($ECG, $time);
                break;
            case 'Ins':
            case 'Insp':
                $Ins = sum_the_time($Ins, $time);
                break;
            case 'Palp':
                $Palp = sum_the_time($Palp, $time);
                break;
            case 'Stetho':
                $Stetho = sum_the_time($Stetho, $time);
                break;
        }

    }

    $totalStudyHour = sum_the_time($totalStudyHour, $iPax);
    $totalStudyHour = sum_the_time($totalStudyHour, $Xray);
    $totalStudyHour = sum_the_time($totalStudyHour, $UCG);
    $totalStudyHour = sum_the_time($totalStudyHour, $ECG);
    $totalStudyHour = sum_the_time($totalStudyHour, $Ins);
    $totalStudyHour = sum_the_time($totalStudyHour, $Palp);
    $totalStudyHour = sum_the_time($totalStudyHour, $Stetho);

    $total_hr = date("H", strtotime($totalStudyHour));
    $total_min = date("i", strtotime($totalStudyHour));

    $hrs = (float)($total_hr . "." . $total_min);
    $averageDays = $days > 0 ? round($hrs / $days, 2) : $days;

?>


<div class = "chart_wrapper" style="width:640px; height:490px;">
<canvas id="usage_status_chart" width="100" height="100"></canvas>
<div class="chart-text-center">
    <p class="chart-center-p">@lang('mypage.total_hr')</p>
    <p class="chart-center-p">
        <span>@if((int)$total_hr > 0) {{ (int)$total_hr}}@lang('mypage.h') @endif</span> 
        {{(int)$total_min}}<span>@lang('mypage.mins')</span>
    </p>
    <p class="chart-center-p chart-day-count">{{ $days }} 
    @if ($days > 1)
        @lang('mypage.days')
    @else
        @lang('mypage.day')
    @endif
    </p>
</div>
</div>
<div class="chart-details">
    <p class="chart-center-p">@lang('mypage.average_day'): {{ $averageDays }}<span>@lang('mypage.h')</span></p>
    <p class="chart-center-p">@lang('mypage.average_rank'): {{ $ranking }} / {{ count($registeredUsers) }}</p>
</div>

<script type="text/javascript" src="/js/chart.min.js"></script>
<script>
var ctx = document.getElementById('usage_status_chart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['iPax', '<?php echo trans('mypage.xray'); ?>', '<?php echo trans('mypage.ucg'); ?>', '<?php echo trans('mypage.ecg'); ?>', '<?php echo trans('mypage.insp'); ?>', '<?php echo trans('mypage.palp'); ?>', '<?php echo trans('mypage.stetho_sound'); ?>'],
        datasets: [{
            data: [
                <?php echo hoursToMinutes($iPax); ?>,
                <?php echo hoursToMinutes($Xray); ?>,
                <?php echo hoursToMinutes($UCG); ?>,
                <?php echo hoursToMinutes($ECG); ?>,
                <?php echo hoursToMinutes($Ins); ?>,
                <?php echo hoursToMinutes($Palp); ?>,
                <?php echo hoursToMinutes($Stetho); ?>
            ],
            backgroundColor: [
                '#646EF7',
                '#C5C5C5',
                '#8C9844',
                '#A26319',
                '#A78774',
                '#E85C4A',
                '#71C1C7'
            ],
            borderColor: [
                '#646EF7',
                '#C5C5C5',
                '#8C9844',
                '#A26319',
                '#A78774',
                '#E85C4A',
                '#71C1C7'
            ],
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'right',
                align:"center",
                labels:{
                    padding : 30
                } 
            }
        },
        layout:{
            position:'top',
            padding:{
                top: -120
            }
        }
    }
    
});
</script>