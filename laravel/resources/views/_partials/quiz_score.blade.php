<!----------------------クイズ成績---------------------->
<!-- .quiz_cont_inner -->
<div id='quiz_score' class="quiz_cont_inner">
  @if ($exam_id != null)
    <br><br>
    <div class="score mB30">@lang('quizpacks.exam_complete')</div>
    <div class="exam_confirm_divider"></div>
@else
    <div class="score_title mT20">@lang('quizpacks.score_title')</div>
    <div class="score mB30"><span>{{$correct_count}}/{{count($scores)}}</span>@lang('quizpacks.correct_answer')</div>  
    <!-- .score_table -->
    <ul class="score_table">
      @foreach($scores as $s)
    <?php 
      $isArrowVisible = true;

      // $quizId = $s['quiz_id'];

      // if ($quizId) {
      //   $quizChoices = DB::table("quiz_choices")->where("quiz_id", $quizId)->whereNotNull("is_fill_in")->whereNull("lib_type")->first();
      //   if ($quizChoices) {
      //     $isArrowVisible = true;
      //   } else {
      //     $isArrowVisible = false;
      //   }

      // } else {
      //   $isArrowVisible = false;
      // }

    ?>
        <a data-score="{{json_encode($s)}}" class="answer_confirm_btn" data-old_choices="{{json_encode($old_choices)}}" data-quiz_type="{{$quiz_type}}" data-url="{{ route('quiz_answer_confirm',[$quiz_pack_id,$s['quiz_id']]) }}" 
        data-quiz_number="{{$s['number']+1}}" style="cursor: pointer;">
          <li class="score_cell {{$s['is_correct'] ? 'true' : 'false'}}">
            <p style="@if(!$isArrowVisible) background-image: none; @endif">
              @if(Config::get("app.locale") == "en")
                @lang('quizpacks.question'){{$s['number']+1}}
              @else
                {{$s['number']+1}}@lang('quizpacks.question')
              @endif
              <span class="sp_none" style="float:right; margin-right: 40px;">@lang('quizpacks.commentary')</span>
            </p>
          </li> 
        </a>
      @endforeach
    </ul>
    <p>@lang('quizpacks.commentary2')</p>
  @endif
  
  <!-- /.score_table -->
  <button class="close_btn_foot mTB20 btn_finish"><img src="img/<?php echo (Config::get("app.locale") == "ja") ? "close_btn2" : "finish_en" ?>.png" alt="閉じる" width="150" style="cursor: pointer;"/></button>
  <center class="loader" style="display: none;">
    <img src="loading.gif" width="100"/>
    <h2 class="mTB20">@lang('quizpacks.exam_notice')</h2>
  </center>
  @if ($exam_id != null)
    <div class="exam_confirm_divider"></div>
    <div class="exam_result_message">
    <h2 style="color:#FF7992">
      @lang("quizpacks.exam_complete_descrp2")
    </h2>
    </div>
  @endif
</div>
<script type="text/javascript">
(function($){
  $( ".close_btn_foot" ).click(function(e) {
    $(window).unbind('beforeunload');
    if (window.location.href == "{{url('/exams')}}") {
      $(".loader").show();
      $(".close_btn_foot").prop('disabled', true).hide();
      e.preventDefault();
      setTimeout(() => {
        $.ajax({
          type: "GET",
          async: false,
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          url: "{{url('/csv/send/exam')}}/{{$exam_id}}/quiz/{{$quiz_pack_id}}",
          success: function(data){
            location.reload();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            //$(".loader").hide();
            //$(".close_btn_foot").prop('disabled', false).show();
            location.reload();
            //alert("Please check your network connection."); alert("Please check your network connection.");
          },
        });
      }, 1000);
    } else {
      $(".close_btn_foot").prop('disabled', true).hide();
      e.preventDefault();
      setTimeout(() => {
        $.ajax({
          type: "GET",
          async: false,
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          url: "{{url('quizpacks/end_quiz')}}",
          success: function(data){
            location.reload();
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            // $(".close_btn_foot").prop('disabled', false).show();
            // alert("Please check your network connection.");
            location.reload();
          },
        });
      }, 1000);
    }
  });
  // $('.mfp-close.close_btn').off('click');
  // $('.mfp-close.close_btn,img.mfp-close').logger({
  //   url: '/log',
  //   data: function(elm) {
  //     $quizpack_box = $('body').find('.quiz_box');
  //     var data = {
  //       from_screen_code: 'QUIZ_SCORE',
  //       screen_code: 'QUIZ_PACKS',
  //       event_code: 'CLOSE',
  //       body: {
  //         quiz_pack: {
  //           id: $quizpack_box.data('quiz-pack-id'),
  //           title: $quizpack_box.data('quiz-pack-title')
  //         }
  //       }
  //     };
  //     return data;
  //   }
  // });
})(jQuery);
</script>

<script type="text/javascript" src="/js/bodymap.js"></script>
