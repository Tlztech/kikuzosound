<div class="score-ranking-container">
    <div class="score-ranking-wrapper">
        <table id="score-ranking-table" class="stripe">
            <thead>
                <tr>
                    <td><b>@lang('mypage.content_name')</b></td>
                    <td><b>@lang('mypage.rate')</b></td>
                </tr>
            </thead>
            
            <tbody>
            <?php 

                function getRateByContent($lib_type, $is_correct = 0) {
                    $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');

                    if ($is_correct) {
                        return DB::table("use_logs")
                            ->where("user_id", $userId)
                            ->where("quiz_type", $lib_type)
                            ->where("exam_id", "<>", "")
                            ->where('is_correct', 1)
                            ->get();
                    } else {
                        return DB::table("use_logs")
                            ->where("user_id", $userId)
                            ->where("quiz_type", $lib_type)
                            ->where("exam_id", "<>", "")
                            ->whereIn('is_correct', [1, 0])
                            ->get();
                    }
                    
                }
            ?>
            <tr>
                <td>@lang('mypage.ipax')</td>
                <td>{{ count(getRateByContent("Aus", 1)) }} / {{ count(getRateByContent("Aus")) }} </td>
            </tr>
            <tr>
                <td>@lang('mypage.stetho')</td>
                <td>{{ count(getRateByContent("Stetho", 1)) }} / {{ count(getRateByContent("Stetho")) }} </td>
            </tr>
            <tr>
                <td>@lang('mypage.palp')</td>
                <td>{{ count(getRateByContent("Palp", 1)) }} / {{ count(getRateByContent("Palp")) }} </td>
            </tr>
            <tr>
                <td>@lang('mypage.ecg')</td>
                <td>{{ count(getRateByContent("ECG", 1)) }} / {{ count(getRateByContent("ECG")) }} </td>
            </tr>
            <tr>
                <td>@lang('mypage.insp')</td>
                <td>{{ count(getRateByContent("Ins", 1)) }} / {{ count(getRateByContent("Ins")) }} </td>
            </tr>
            <tr>
                <td>@lang('mypage.ucg')</td>
                <td>{{ count(getRateByContent("UCG", 1)) }} / {{ count(getRateByContent("UCG")) }} </td>
            </tr>
            <tr>
                <td>@lang('mypage.xray')</td>
                <td>{{ count(getRateByContent("Xray", 1)) }} / {{ count(getRateByContent("Xray")) }} </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
 $(document).ready(function(){
    $('#score-ranking-table').DataTable({
        bJQueryUI: true,
        bFilter: false, 
        bInfo: false,
        bPaginate: false,
        sDom: 'rt'
    });
 });
</script>