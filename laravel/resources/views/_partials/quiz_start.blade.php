<?php 
  $lang = (Config::get('app.locale') != "ja") ? "_en" : ""; //get locale 
  $user_id=Session::get('MEMBER_3SP_ACCOUNT_ID'); //user id
?>

<!----------------------クイズスタート---------------------->
<div class="quiz_box short_box" data-quiz-pack-id="{{ $quiz_pack_id }}" data-quiz-pack-title="{{ $title }}">
  <div class="quiz_head" title="{{$title}}">
    <h2 class="quiz_title">
      {{ str_limit($title, $limit = 30, $end = '...') }}
    </h2>
    <p id="quiz_modal_close_btn" class="mfp-close close_btn">@lang('quizpacks.button_close')</p>
  </div>

  <!-- .quiz_cont -->
  <div class="quiz_cont">
    <!-- .quiz_cont_scrroll -->
  <div data-lang="{{$lang}}" class="quiz_cont_scroll">
           
     <!-- .quiz_cont_inner -->
     <div class="quiz_cont_inner">
      <div class="quiz_start">
          <!-- <div class="quiz_type">
            <label class="">
              <input type="radio" name="quiz_type" value='0' checked/>&nbsp;@lang('quizpacks.optional')
            </label>
            <label class="">
              <input type="radio" name="quiz_type" value='1'/>&nbsp;@lang('quizpacks.fill_in')
            </label>
          </div> -->
        @if ($exam_id != null)
          <div>
            <br><br>
            <h2><input id="is_quiz_start" type="checkbox"/>@lang('quizpacks.quiz_start_checkbox')</h2>
            <p class="quiz_note">@lang('quizpacks.exam_note')</p>
          </div>
          <a id="quiz_start_btn" data-url="{{ route('exam_answer_select',[$quiz_pack_id,$next_quiz_id,$exam_id]) }}" data-next_quiz_id="{{$next_quiz_id}}" style="cursor: pointer;" >
            <img src="{{asset('img/quiz_start'.$lang.'.png?v=1.1.1')}}" alt="クイズスタート" class="enable-btn"  style="display:none">
            <img src="{{asset('img/quiz_start_disabled'.$lang.'.png?v=1.1.1')}}" alt="クイズスタート"  class="disable-btn">
          </a>
        @else 
          <input id="is_quiz_start" type="checkbox" checked hidden/>
          <a id="quiz_start_btn" data-url="{{ route('quiz_answer_select',[$quiz_pack_id,$next_quiz_id]) }}" data-next_quiz_id="{{$next_quiz_id}}" style="cursor: pointer;" class="start_quiz" onclick="gtag('event', 'Click', {'event_category':'quiz_{{$quiz_pack_id}}', 'event_label':'user_{{$user_id}}'});">
            <img src="{{asset('img/quiz_start'.$lang.'.png?v=1.1.1')}}" alt="クイズスタート" class="start_quiz">
          </a>
        @endif
      </div>
      <div class="quiz_desc clearfix">
        <p class="text mB10 fl-right_pc">
          @lang('quizpacks.quiz_instruction1')<br><br>
          @lang('quizpacks.quiz_instruction2')
        </p>
        <img class="fl-left_pc mB10" src="img/<?php echo (Config::get("app.locale") == "ja") ? "quiz_desc" : "quiz_desc_en" ?>.png" alt="クイズ回答説明" />
      </div>
    </div>
    <!-- .quiz_cont_inner -->                
    
  </div>
  <!-- /.quiz_cont_scroll -->                
</div>
<!-- /.quiz_cont -->
</div>
<script type="text/javascript">
(function($){
  $('.mfp-close.close_btn').logger({
    url: '/log',
    data: function(elm) {
      $quiz_box = $('body').find('.quiz_box');
      var data = {
        from_screen_code: 'QUIZ_START',
        screen_code: 'QUIZ_PACKS',
        event_code: 'CLOSE',
        body: {
          quiz_pack: {
            id: $quiz_box.data('quiz-pack-id'),
            title: $quiz_box.data('quiz-pack-title')
          }
        }
      };
      return data;
    }
  });
})(jQuery);

$(function(){
      $('#is_quiz_start').change(function(){    
        if ($(this).is(':checked')) {
          $(".disable-btn").hide()
          $(".enable-btn").show()
        } else {
          $(".disable-btn").show()
          $(".enable-btn").hide()
        }
      });
    });

$('img').bind('contextmenu', function(e){
  return false;
});
</script>