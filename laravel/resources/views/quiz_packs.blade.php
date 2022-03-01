@extends('layouts.app')

@section('title', 'Quiz Packs')

@section('breadcrumb')
  @if($isExamGroup)
    {!! Breadcrumbs::render('exams') !!}
  @else
    {!! Breadcrumbs::render('quizpacks') !!}
  @endif
@endsection

@section('content')
<div id="container" class="container-quizpack">
  <div class="container_inner clearfix">
    <!----------------------------------- .contents ----------------------------------->
    <!-- クイズパック一覧 -->
    @if($isExamGroup) 
      <center>
        <h1 class="exam-title">{{trans('quizpacks.exam_title')}} {{ $examGroupName }} {{trans('quizpacks.exam_title2')}}</h1>
      </center>
      <br><br>
      <ul class="quiz_select clearfix">
        @foreach($exams as $exam)
        <?php
          $hasExamResults = DB::table('exam_results')
            ->where("customer_id", $userId)
            ->where("exam_id", $exam->id)
            ->get();
          $hasExamLogs = DB::table('use_logs')
            ->where("type", 1)
            ->where("user_id", $userId)
            ->where("exam_id", $exam->id)
            ->get();
        ?>
        <li id="exam_list_{{$exam->id}}" class="quiz_selectbox <?php if (!$hasExamResults && !$hasExamLogs) {
            echo 'quiz_selected';
        } ?> exam-selectbox" 
          data-quiz-pack-url="{{ route('exam_start',[$exam->quiz_pack->id, $exam->id]) }}" style="cursor: pointer;">
          <?php if ($hasExamResults || $hasExamLogs) {  ?>
          <div class="quiz-overlay">
            <img src="../img/done-icon.png">
          </div>
          <?php } ?>
          <a class="quiz_modal">
          <p class="quiz_titles" style="color:{{$exam->quiz_pack->title_color}} !important; font-size:24px;">
            @if (Config::get('app.locale') == "en")
              {{ $exam->name }}
            @else
              {{ $exam->name_jp }}
            @endif
          </p>
          <div class="quiz_pack_icon_image">
            @if(!empty($exam->quiz_pack->icon_path))
            <img src="{{ asset($exam->quiz_pack->icon_path).'?_='.date('YmdHis', strtotime($exam->quiz_pack->updated_at)) }}" alt="{{trans('quizpacks.quiz_pack')}}" onerror="this.src='../img/no_image.png'">
            @endif
          </div>
            <div class="textbox">
              <p class="title">
                @if (Config::get('app.locale') == "en")
                  {{ $exam->quiz_pack->description_en }}
                @else
                  {{ $exam->quiz_pack->description }}
                @endif
              </p>
              <p class="date" style="display:none;">{{ date('Y/n/j h:m', strtotime($exam->quiz_pack->created_at))}}</p>
            </div>
          </a>
        </li>
        @endforeach
      </ul>
    @else
      <ul class="quiz_select clearfix">
        @foreach($quiz_packs as $quiz_pack)
        <li id="quiz_pack_{{$quiz_pack->id}}" class="quiz_selectbox quiz_selected quiz-pack-box" 
          data-quiz-pack-url="{{ route('quiz_start',[$quiz_pack->id]) }}" style="cursor: pointer;"
          >
          <a class="quiz_modal">
          <p class="quiz_titles" style="color:{{$quiz_pack->title_color}} !important; font-size:24px;">
            @if (Config::get('app.locale') == "en")
              {{ $quiz_pack->title_en }}
            @else
              {{ $quiz_pack->title }}
            @endif
          </p>
          <div class="quiz_pack_icon_image">
            @if(!empty($quiz_pack->icon_path))
            <img src="{{ asset($quiz_pack->icon_path).'?_='.date('YmdHis', strtotime($quiz_pack->updated_at)) }}" alt="{{trans('quizpacks.quiz_pack')}}" onerror="this.src='../img/no_image.png'">
            @endif
          </div>
            <div class="textbox">
              <p class="title">
                @if (Config::get('app.locale') == "en")
                  {{ $quiz_pack->description_en }}
                @else
                  {{ $quiz_pack->description }}
                @endif
              </p>
              <p class="date" style="display:none;">{{ date('Y/n/j h:m', strtotime($quiz_pack->created_at))}}</p>
            </div>
          </a>
        </li>
        @endforeach
      </ul>
    @endif
    <!-- /#container -->
  </div>

</div>
<!-- /#container_inner -->
<!-- <script src="js/quiz.js?v=1.1.2"></script> -->
<script type="text/javascript" src="js/jquery.sss_portal.quiz.js?v=1.2.3.20180118"></script>
<script type="text/javascript" src="js/jquery.valign.js?v=1.1.6.20170322"></script>
<script type="text/javascript">

(function($){
  $(window).load(function(){
    // クイズパックの表示位置変更
    $('.quiz_selectbox').valign({
      targetClasses: [             // 項目内でY軸を揃えたいセレクタ
        '.quiz_title',
        '.textbox .title'
      ],
      middleAlignmentClasses: [
        '.quiz_title'              // 項目内で縦中央に揃えたいセレクタ
      ],
      deviceWidth: 600,
      minCols: 2,
      maxCols: 3
    });
    // クイズ機能への遷移
    $('.container').sss_portal.quiz();
  });
})(jQuery);
</script>
@endsection
