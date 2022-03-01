<!----------------------クイズ回答確認---------------------->
<!-- .quiz_cont_inner -->
<script src="{{asset('body-js/apps.js')}}"></script>
@include('common.aus_php_function')
<?php $lib_count = $quiz->stetho_sounds()->get()->groupBy('lib_type')->count(); ?>
<div id="answer_confirm" class="quiz_cont_inner">
  <a style="cursor: pointer;"><p class="back_btn">@lang('quizpacks.back')</p></a>
  <div class="question_title" data-lang="{{Config::get('app.locale')}}" data-trans=@lang('quizpacks.question')>@lang('quizpacks.question')</div>
@if($lib_count<=1)
  @if( !empty($quiz->image_path) )
  <div class="quiz_question_img">
    <img src="{{ asset($quiz->image_path).'?v='.session('version') }}" alt="問題" style="cursor: pointer;">
  </div>
  @else
    <div style="height: 16px;">&nbsp;</div>
  @endif
  <!-- .question_box -->
  @if($lib_count==1)
  <div class="question_box" data-quiz-id="{{ $quiz->id }}" data-quiz-title="{{ $quiz->title }}" data-quiz-question="{{ $quiz->question }}">
    <!-- .question_text -->
    <p class="question_text_confirm">
    @if(config('app.locale') == 'en'){{ $quiz->question_en }} @else {{ $quiz->question }} @endif    </p>
    <!-- /.question_text -->
    <!-- .question_sound -->
    @if($quiz->stetho_sounds()->first()->lib_type==1)
      <input type="hidden" data-lib_type="1" class="content_title"/>
      <div class="aus_slide aus_slider multiple-quiz-slider pTB20">
        <div class="img_slide_inner">
          <ul id="exam_quiz"  class="bxslider">
            @foreach($quiz->stetho_sounds()->get() as $index => $content)
              <li>
                <div id="aus-img_wrapper_{{$content->id}}" class="aus-img_wrapper" data-result="{{ json_encode($content) }}" data-title="@if(Config::get('app.locale') == 'en') {{$content->title_en}} @else {{$content->title}} @endif">
                  <!-- <iframe
                      id="body-{{ $content->id }}"
                      class="bodyFrame"
                      frameborder="0"
                      data-size="1"
                      src="{{asset('/body-images/ui/bodymap/body.jpg')}}">
                  </iframe>      -->
                  <div 
                      id="body-{{ $content->id }}"
                        class="bodyFrame"
                        frameborder="0"
                        data-size="1"
                      >
                      <button id="ausculaide_load_btn_{{$content->id}}" onClick="loadAus({{$content->id}},true);" style="display:none;">Load Ausculaide</button>
                  </div>            
                </div>
                <?php $aus_attr = checkAusculaideAtrribute($content); ?>
                <span id="pulse_attribute" style="display:none;" data-has_pulse="{{$aus_attr->isPulse}}"></span>
                @if($content->type == 1)
                <button id="exam_lung_icon_{{$content->id}}" class="quiz-btn-switch-body" <?php echo $aus_attr->isLung ? "" : "disabled"; ?> 
                    data-id="{{$content->id}}"
                    data-lung-back="<?php echo $aus_attr->isLungBack ? 1 : 0; ?> "
                    data-lung-front="<?php echo $aus_attr->isLung ? 1 : 0; ?> "
                    data-front_body="{{ $content->body_image.'?_='.date('YmdHis', strtotime($content->updated_at)) }}"
                    data-back_body="{{ $content->body_image_back.'?_='.date('YmdHis', strtotime($content->updated_at)) }}"
                    data-body="front"
                  >
                @endif
              </li>
            @endforeach
          </ul>
        </div>
        <p id="sl_image_title"></p>
        @include('_partials.quiz_stetho_function',['sound_id' => $content->id])
      </div>
    @endif
    <?php $type_strings = [ 1 => '肺音', 2 => '心音', 3 => '腸音', 9 => 'その他', 0 => "" ]; ?>
    @if($quiz->stetho_sounds->count() == 1)
    <?php $s = $quiz->stetho_sounds()->get()[0]; ?>
      <div class="question_sound_list_only_one" data-stetho-sound-id="{{ $s->id }}" data-stetho-sound-type="{{ $type_strings[$s->type] }}" data-stetho-sound-title="{{ $s->title }}">
        @if(!empty($s->image_path))
        <div class="explanation_img_wrapper">
          <img src="{{ $s->image_path.'?v='.session('version') }}" style="cursor: pointer;" onerror="this.src = '/img/no_image.png';"/>
        </div>
        @endif
        @if(!empty($s->video_path))
          <div class="explanation_video_wrapper">
            <video playsinline disablepictureinpicture controls="controls" controlslist="nodownload" src="{{ $s->video_path.'?v='.session('version') }}" type="video/mp4">
          </div>
        @endif
        @if(!empty($s->sound_path))
        <?php
          $infoPath = pathinfo(public_path($s->sound_path));
          $extension = ($s->lib_type==0 && $infoPath)?$infoPath['extension']:"";
        ?>
        <div class="audio" style="@if($s->lib_type==1)display:none;@endif">
          @if($extension == 'mp4' && $s->is_video_show == 1)
            <div class="explanation_video_wrapper">
              <video playsinline data-id="{{$s->id}}" disablepictureinpicture width="100%" height="100%" id="stetho_sound_video[{{ $s->id }}]" controls controlslist="nodownload" src = "{{ asset($s->sound_path).'?v='.session('version') }}">
              <!-- <iframe src="" type="video/mp4"
                width="100%" height="100%" frameborder="0"></iframe> -->
            </div>
          @else
            <div class="audiojsZ">
              @if($s->lib_type==1)
                  <?php $aus_sound = json_decode($s->sound_path);?>
                  @include('_partials.quiz_aus_audio',['sound' => $s, 'aus_sound' => $aus_sound])
              @else
                <audio src="{{ asset($s->sound_path).'?v='.session('version') }}" preload="auto"></audio>
              @endif
              <div class="play-pauseZ">
                <span class="line"></span>
                <p class="playZ"></p>
                <p class="pauseZ"></p>
                <p class="loadingZ"></p>
                <p class="errorZ"></p>
              </div>
              <div class="scrubberZ">
                <div class="progressZ"></div>
                <div class="loadedZ"></div>
              </div>
              <div class="timeZ">
                <em class="playedZ">00:00</em>/<strong class="durationZ">00:00</strong>
              </div>
              <div class="error-messageZ"></div>
            </div>
          @endif
        </div>
        @endif
        <?php $quiz_stetho_description = $quiz->quiz_stetho_description()->where('stetho_sound_id', $s->id)->first(); ?>
        @if(!empty($quiz_stetho_description->description))
          <p class="fukidashi">
          @if(config('app.locale') == 'en' && $quiz_stetho_description->description_en)
          {{$quiz_stetho_description->description_en}} 
          @else
          {{$quiz_stetho_description->description}}
          @endif
          </p>
        @endif
      </div>
    @else

    <ul class="question_sound_box">
    @foreach($quiz->stetho_sounds()->get() as $s)
        <!-- .question_sound_list -->
        <li class="question_sound_list" data-stetho-sound-id="{{ $s->id }}" data-stetho-sound-type="{{ $type_strings[$s->type] }}" data-stetho-sound-title="{{ $s->title }}">
          @if(!empty($s->image_path))
          <div class="explanation_img_wrapper">
            <img src="{{ $s->image_path.'?v='.session('version') }}" style="cursor: pointer;" onerror="this.src = '/img/no_image.png';"/>
          </div>
          @endif
          @if(!empty($s->video_path))
            <div class="explanation_video_wrapper">
              <video playsinline disablepictureinpicture controls="controls" controlslist="nodownload" src="{{ $s->video_path.'?v='.session('version') }}" type="video/mp4">
            </div>
          @endif
          @if(!empty($s->sound_path))
          <!-- .audio -->
          <div class="audio" style="@if($s->lib_type==1)display:none;@endif">
            <div class="audiojsZ">
              @if($s->lib_type==1)
                <?php $aus_sound = json_decode($s->sound_path);?>
                @include('_partials.quiz_aus_audio',['sound' => $s, 'aus_sound' => $aus_sound])
              @else
                <audio src="{{ asset($s->sound_path).'?v='.session('version') }}" preload="auto"></audio>
              @endif
              <div class="play-pauseZ">
                <span class="line"></span>
                <p class="playZ"></p>
                <p class="pauseZ"></p>
                <p class="loadingZ"></p>
                <p class="errorZ"></p>
              </div>
              <div class="scrubberZ">
                <div class="progressZ"></div>
                <div class="loadedZ"></div>
              </div>
              <div class="timeZ">
                <em class="playedZ">00:00</em>/<strong class="durationZ">00:00</strong>
              </div>
              <div class="error-messageZ"></div>
            </div>
          </div>
          @endif
          <?php $quiz_stetho_description = $quiz->quiz_stetho_description()->where('stetho_sound_id', $s->id)->first();
          ?>
          @if(!empty($quiz_stetho_description->description))
            <p class="fukidashi" style="@if($s->lib_type==1)display:none;@endif">
            @if(config('app.locale') == 'en' && $quiz_stetho_description->description_en)
            {{$quiz_stetho_description->description_en}} 
            @else
            {{$quiz_stetho_description->description}}
            @endif
            </p>
          @endif
          <!-- /.audio -->
        </li>
        <!-- /.question_sound_list -->
      @endforeach
      </ul>
      <!-- /.question_sound -->
    @endif

    <!-- .answers_selected -->
    @if($quiz->is_optional==1)
    <ul class="answers_selected">
    @if($lib_count<=0) 
      <!-- No content Choices -->
      @foreach($quiz->quiz_choices()->whereNull('is_fill_in')->whereNull("lib_type")->get() as $c)
        <li class="@if($c->is_correct) touch_answer @endif">
          @if(config('app.locale') == 'en'){{ $c->title_en }} @else {{ $c->title }} @endif
        </li>
      @endforeach
    @else
      @foreach($quiz->quiz_choices()->whereNull('is_fill_in')->whereNotNull("lib_type")->get() as $c)
        <li class="@if($c->is_correct) touch_answer @endif">
          @if(config('app.locale') == 'en'){{ $c->title_en }} @else {{ $c->title }} @endif
        </li>
      @endforeach
    @endif
    </ul>
    @endif
    <!-- /.answers_selected -->
  </div>
  @endif
  <!-- No content Quiz -->
  @if($lib_count==0)
  <div class="question_box" data-quiz-id="{{ $quiz->id }}" data-quiz-title="{{ $quiz->title }}" data-quiz-question="{{ $quiz->question }}">
    <!-- .question_text -->
    <p class="question_text_confirm">
    @if(config('app.locale') == 'en'){{ $quiz->question_en }} @else {{ $quiz->question }} @endif    </p>
  </div>
  @endif
  <!-- /.question_box -->
  <div class="answer-wrapper">
  <p class="awnser mB10">@lang('quizpacks.correct_answer')：
      <span>
      <?php $no_content = ($lib_count<=0)?true:false; ?>
      @if($quiz->is_optional==1)
          <?php 
          $noContentQuiz_optional_answer = $quiz->correctNoContentQuizChoice("optional");
          $singleQuiz_optional_answer = $quiz->correctSingleQuizChoice("optional");
          ?>
        @if(config('app.locale') == 'en')
          {{
            ($no_content)?
              ($noContentQuiz_optional_answer ? $noContentQuiz_optional_answer->title_en : ""):
              ($singleQuiz_optional_answer ? $singleQuiz_optional_answer->title_en : "")
          }}
        @else 
          {{
            ($no_content)?
              ($noContentQuiz_optional_answer ? $noContentQuiz_optional_answer->title : ""):
              ($singleQuiz_optional_answer ? $singleQuiz_optional_answer->title:"")
          }}
        @endif
      @else
          <?php $answer=null; ?>
          @if($no_content)
            <?php $answer = $quiz->quiz_choices()->where('is_fill_in',1)->whereNull("lib_type")->where("is_correct",1)->get(); ?>
            @foreach($answer as $ans)
              {{$ans->title}}<br>
            @endforeach
            {{!$answer?"No Correct Answer":""}}
          @else
            <?php $answer = $quiz->quiz_choices()->where("is_fill_in",1)->where('lib_type',$s->lib_type)->where('is_correct',true)->first(); ?>
            {{$answer?$answer->title:"No Correct Answer"}}
          @endif

      @endif
      </span>
  </p>
  <p class="awnser mB10">@lang('quizpacks.your_answer')：
      <span>
        @foreach($old_choices as $answer)
          @if($answer['quiz_id']==$quiz->id)
            {{$answer['quiz_choice_title']}}
          @endif
        @endforeach
      </span>
  </p>
</div>
@if($lib_count>0)
<p>
  @if(config('app.locale') == 'en')
    {!! $s->image_description_en !!}
  @else 
    {!! $s->image_description !!}
  @endif
</p>
@endif
<br>
  <!-- .a_explanation -->
  <div class="awnser_explanation">
  <?php $desc_column = [
          0 => "description_stetho_sound_id",
          1 => "description_stethoscope_id",
          2 => "description_palpation_id",
          3 => "description_ecg_id",
          4 => "description_inspection_id",
          5 => "description_xray_id",
          6 => "description_echo_id",
          ];
        ?>
  <?php $sound = $lib_count>0 ? $quiz->libray_description($desc_column[$s->lib_type])->first() : null;?>
  @if($lib_count>0)
  @if($sound)
    <p class="awnser_explanation_text mB10">

      @if(Config::get('app.locale') == 'en') 
        <p>{!! $sound->description_en !!}</p>
        @if($sound->description_en == null)
          {!! $sound->description !!}
        @endif
      @else
        {!! $sound->description !!}
      @endif
      
    </p>
    <!-- .img_slide -->
    @if($sound->images->count())
    <br>
    @if($s->lib_type == 1)
      <h4>{{ trans('quizpacks.click_image') }}</h4>
    @endif
    <center>
    <div class="img_slide">
      <div class="img_slide_inner">
        <ul id="exam_quiz"  class="bxslider">
          @foreach($sound->images as $image)
            @if(Config::get('app.locale') == $image->lang)
              <li>
                @if (strpos($image['image_path'], 'mp4'))
                    <video playsinline id="library_video" data-id="{{$sound->id}}" controls disablepictureinpicture width="100%" height="100%" controlslist="nodownload"
                      src="{{ $image->image_path.'?v='.session('version') }}">
                    </video>
                @else
                  <img src="{{ $image->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="sound_img" />
                @endif
                <p hidden="" id="sl_image_title">{{$image->title}}</p>
              </li>
            @endif
          <!-- TODO: 画面説明　-->
          @endforeach
        </ul>
      </div>
      <p class="img_slider_text" @if(!$sound->images->first()->title) hidden @endif id="image_title">{{$sound->images->first()->title}}</p>
    </div>
    </center>
    @endif
    <!-- /.img_slide -->
  @else
    <?php $sound = $quiz->stetho_sounds()->get()[0]; ?>
    @if($sound->images->count())
      @if($sound->lib_type == 1)
        <h4>{{ trans('quizpacks.click_image') }}</h4>
      @endif
      <center>
      <div class="img_slide">
        <div class="img_slide_inner">
          <ul id="exam_quiz"  class="bxslider">
            @foreach($sound->images as $image)
              @if(Config::get('app.locale') == $image->lang)
                <li>
                  @if (strpos($image['image_path'], 'mp4'))
                      <video playsinline id="library_video" data-id="{{$sound->id}}" controls disablepictureinpicture width="100%" height="100%" controlslist="nodownload"
                        src="{{ $image->image_path.'?v='.session('version') }}">
                      </video>
                  @else
                    <img src="{{ $image->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="sound_img" />
                  @endif
                  <p hidden="" id="sl_image_title">{{$image->title}}</p>
                </li>
              @endif
            <!-- TODO: 画面説明　-->
            @endforeach
          </ul>
        </div>
        <p class="img_slider_text" @if(!$sound->images->first()->title) hidden @endif id="image_title">{{$sound->images->first()->title}}</p>
      </div>
      </center>
      @endif 
    @endif
    @endif

  </div>
@else
    <?php  $libs= [0 => trans('quizpacks.aus_btn'), 1 => trans('quizpacks.stetho_btn'), 2 => trans('quizpacks.palpation_btn'), 3 => trans('quizpacks.ecg_btn'), 4 => trans('quizpacks.inspection_btn'), 5 => trans('quizpacks.xray_btn'), 6 => trans('quizpacks.echo_btn')] ?>
    @foreach($quiz->stetho_sounds()->get()->sortBy("lib_type")->groupBy('lib_type') as $key => $library)
    <h1 class="multiple_quiz_lib__title">{{$libs[$library->first()->lib_type]}}</h1>
    <div class="question_box " data-quiz-id="{{ $quiz->id }}" data-quiz-title="{{ $quiz->title }}" data-quiz-question="{{ $quiz->question }}">
    <!-- for ausculaide function -->
    @if($library->first()->lib_type==1) 
      <input type="hidden" data-lib_type="1" class="content_title"/>
      <div class="aus_slide aus_slider multiple-quiz-slider pTB20">
        <div class="img_slide_inner">
          <ul id="exam_quiz"  class="bxslider">
            @foreach($library as $index => $content)
              <li>
                <div id="aus-img_wrapper_{{$content->id}}" class="aus-img_wrapper" data-result="{{ json_encode($content) }}" data-title="@if(Config::get('app.locale') == 'en') {{$content->title_en}} @else {{$content->title}} @endif">
                  <!-- <iframe
                      id="body-{{ $content->id }}"
                      class="bodyFrame"
                      frameborder="0"
                      data-size="1"
                      src="{{asset('/body-images/ui/bodymap/body.jpg')}}">
                  </iframe> -->
                  <div 
                    id="body-{{ $content->id }}"
                      class="bodyFrame"
                      frameborder="0"
                      data-size="1"
                    >
                    <button id="ausculaide_load_btn_{{$content->id}}" onClick="loadAus({{$content->id}},true);" style="display:none;">Load Ausculaide</button>
                </div>
                </div>
                <?php $aus_attr = checkAusculaideAtrribute($content); ?>
                <span id="pulse_attribute" style="display:none;" data-has_pulse="{{$aus_attr->isPulse}}"></span>
                @if($content->type == 1)
                <button id="exam_lung_icon_{{$content->id}}" class="quiz-btn-switch-body" <?php echo $aus_attr->isLung ? "" : "disabled"; ?> 
                    data-id="{{$content->id}}"
                    data-lung-back="<?php echo $aus_attr->isLungBack ? 1 : 0; ?> "
                    data-lung-front="<?php echo $aus_attr->isLung ? 1 : 0; ?> "
                    data-front_body="{{ $content->body_image.'?_='.date('YmdHis', strtotime($content->updated_at)) }}"
                    data-back_body="{{ $content->body_image_back.'?_='.date('YmdHis', strtotime($content->updated_at)) }}"
                    data-body="front"
                  >
                @endif
              </li>
            @endforeach
          </ul>
        </div>
        <p id="sl_image_title"></p>
        @include('_partials.quiz_stetho_function',['sound_id' => $content->id])
      </div>
    @endif
    <ul class="question_sound_box multi_quiz_box">
      @foreach($library as $index => $s)
        <!-- .question_sound_list -->
        <li class="@if(count($library)>1)question_sound_list @else question_sound_list_only_one @endif" style="@if($s->lib_type==1)display:none;@endif">
        @if(!empty($s->video_path))
          <div class="explanation_video_wrapper">
            <video playsinline disablepictureinpicture controls="controls"  controlslist="nodownload" src="{{ $s->video_path.'?v='.session('version') }}" type="video/mp4">
          </div>
        @endif
        @if(!empty($s->image_path))
        <div class="explanation_img_wrapper">
          <img src="{{ $s->image_path.'?v='.session('version') }}" style="cursor: pointer;" onerror="this.src = '/img/no_image.png';"/>
        </div>
        @endif
        @if(!empty($s->sound_path))
        <?php
          $infoPath = pathinfo(public_path($s->sound_path));
          $extension = ($s->lib_type==0 && $infoPath)?$infoPath['extension']:"";
        ?>
        <div class="" data-stetho-sound-id="{{ $s->id }}" data-stetho-sound-type="" data-stetho-sound-title="{{ $s->title }}">          <!-- .audio -->
          <div class="audio">
          @if($extension == 'mp4' && $s->is_video_show == 1)
            <div class="explanation_video_wrapper">
              <video playsinline data-id="{{$s->id}}" disablepictureinpicture width="100%" height="100%" id="stetho_sound_video[{{ $s->id }}]" controls controlslist="nodownload" src = "{{ asset($s->sound_path).'?v='.session('version') }}">
              <!-- <iframe src="" type="video/mp4"
                width="100%" height="100%" frameborder="0"></iframe> -->
            </div>
          @else
            <div class="audiojsZ">
              @if($s->lib_type==1)
                <?php $aus_sound = json_decode($s->sound_path);?>
                @include('_partials.quiz_aus_audio',['sound' => $s, 'aus_sound' => $aus_sound])
              @else
                <audio src="{{ asset($s->sound_path).'?v='.session('version') }}" preload="auto"></audio>
              @endif
              <div class="play-pauseZ">
                <span class="line"></span>
                <p class="playZ"></p>
                <p class="pauseZ"></p>
                <p class="loadingZ"></p>
                <p class="errorZ"></p>
              </div>
              <div class="scrubberZ">
                <div class="progressZ"></div>
                <div class="loadedZ"></div>
              </div>
              <div class="timeZ">
                <em class="playedZ">00:00</em>/<strong class="durationZ">00:00</strong>
              </div>
              <div class="error-messageZ"></div>
            </div>
          @endif
          <?php $quiz_stetho_description = $quiz->quiz_stetho_description()->where('stetho_sound_id', $s->id)->first();
          ?>
          @if(!empty($quiz_stetho_description->description))
            <p class="fukidashi">
            @if(config('app.locale') == 'en' && $quiz_stetho_description->description_en)
            {{$quiz_stetho_description->description_en}} 
            @else
            {{$quiz_stetho_description->description}}
            @endif
            </p>
          @endif
          </div>
          <!-- /.audio -->
        </div>
        @endif
        <!-- /.question_sound_list -->
      @endforeach
      </li>
    </ul>
    <!-- .answers_selected -->
    @if($quiz->is_optional==1)
    <ul class="answers_selected">
      @foreach($quiz->quiz_choices()->whereNull('is_fill_in')->where("lib_type",$library->first()->lib_type)->get() as $c)
        <li class="@if($c->is_correct) touch_answer @endif">
          @if(config('app.locale') == 'en'){{ $c->title_en }} @else {{ $c->title }} @endif
        </li>
      @endforeach
    </ul>
    @endif
    <!-- /.answers_selected -->
    </div>
    <!-- /.question_box -->
    <div class="answer-wrapper">
    <p class="awnser mB10">@lang('quizpacks.correct_answer')：
        <span>
        @if($quiz->is_optional==1)
          <?php $optional_answer = $quiz->quiz_choices()->where('lib_type',$library->first()->lib_type)->where('is_correct',true)->first(); ?>
          @if(config('app.locale') == 'en')
          {{$optional_answer?$optional_answer->title_en:"No Correct Answer"}}
          @else 
          {{$optional_answer?$optional_answer->title:"No Correct Answer"}}
          @endif
        @else
          <?php $answer = $quiz->quiz_choices()->where("is_fill_in",1)->where('lib_type',$library->first()->lib_type)->where('is_correct',true)->first(); ?>
          {{$answer?$answer->title:"No Correct Answer"}}
        @endif
        <span>
    </p>
    <p class="awnser mB10">@lang('quizpacks.your_answer')：
      <span>
        @foreach($old_choices as $answer)
          @if($answer['quiz_id']==$quiz->id)
            {{$answer['observations'][$quiz->id][$library->first()->lib_type]['quiz_choice_title']}}
          @endif
        @endforeach
      </span>
    </p>
    </div>
    <p>
      @if(config('app.locale') == 'en')
        {!! $s->image_description_en !!}
      @else 
        {!! $s->image_description !!}
      @endif
    </p>
    <br>
    <!-- .a_explanation -->
    <div class="awnser_explanation">
        <?php $desc_column = [
          0 => "description_stetho_sound_id",
          1 => "description_stethoscope_id",
          2 => "description_palpation_id",
          3 => "description_ecg_id",
          4 => "description_inspection_id",
          5 => "description_xray_id",
          6 => "description_echo_id",
          ];
        ?>
        <?php $sound = $quiz->libray_description($desc_column[$library->first()->lib_type])->first(); ?>
        @if($sound)
          <p class="awnser_explanation_text mB10">
            @if(Config::get('app.locale') == 'en') 
              <p>{!! $sound->description_en !!}</p>
              @if($sound->description_en == null)
                {!! $sound->description !!}
              @endif
            @else
              {!! $sound->description !!}
            @endif
          </p>
        @endif
    </div>
    <!-- /.a_explanation -->
    @endforeach
    <div class="answer-wrapper">
    <p class="awnser mT20 mB20">@lang('quizpacks.final_correct_answer')：
        <span>
        @if($quiz->is_optional==1)
          <?php $optional_answer = $quiz->quiz_choices()->whereNull('lib_type')->where('is_correct',true)->first(); ?>
          @if(config('app.locale') == 'en')
            {{$optional_answer?$optional_answer->title_en:"No Correct Answer"}}
          @else 
            {{$optional_answer?$optional_answer->title:"No Correct Answer"}}
          @endif
        @else
          <?php $answer = $quiz->quiz_choices()->where('is_fill_in',1)->whereNull("lib_type")->where("is_correct",1)->get(); ?>
          @foreach($answer as $ans)
            {{$ans->title}}<br>
          @endforeach
          {{!$answer?"No Correct Answer":""}}
        @endif
        </span>
    </p>
    <p class="awnser mT20 mB20">@lang('quizpacks.your_answer')：
      <span>
        @foreach($old_choices as $answer)
          @if($answer['quiz_id']==$quiz->id)
            {{$answer['quiz_choice_title']}}
          @endif
        @endforeach
      </span>
    </p>
    </div>
    @if($sound)
    <p>
      @if(config('app.locale') == 'en')
        {!! $sound->image_description_en !!}
      @else 
        {!! $sound->image_description !!}
      @endif
    </p>
    @endif
    <br>
@endif
<div id="ausculaid_app_wrapper" style="display:none;">
  @include('bodymap.bodymap')
</div>
</div>
<!-- .quiz_cont_inner -->

<!----------------------クイズ回答確認---------------------->
<script type="text/javascript" src="/js/jquery.sss_portal.audio.js"></script>
<script type="text/javascript">
audiojs.events.ready(function() {
  var audios = document.getElementsByTagName('audio');
  if (audios.length > 0) {
    var a1 = audiojs.create(audios, {
      css: false,
      createPlayer: {
        markup: false,
        playPauseClass: 'play-pauseZ',
        scrubberClass: 'scrubberZ',
        progressClass: 'progressZ',
        loaderClass: 'loadedZ',
        timeClass: 'timeZ',
        durationClass: 'durationZ',
        playedClass: 'playedZ',
        errorMessageClass: 'error-messageZ',
        playingClass: 'playingZ',
        loadingClass: 'loadingZ',
        errorClass: 'errorZ'
      }
    });
  }
  $({}).sss_portal.audio({
    audioJsObj: a1
  });
});

$(document).ready(function() {
  var lib_type = $('.content_title').data('lib_type');

  // $('.bxslider').bxSlider({
  //   slideWidth: 258,
  //   adaptiveHeight: true,
  //   infiniteLoop:(lib_type==1)?false:true,
  //   touchEnabled: (lib_type==1)?false:true,
  //   oneToOneTouch: (lib_type==1)?false:true,
  //   // 要素に対してbxsliderの準備が整うと呼ばれる
  //   onSliderLoad: function(currentIndex) {
  //     var currentSlide = $('.bxslider>li').eq(currentIndex)
  //     var id = currentSlide.find(".stethoscope").data('id');
  //     var title = currentSlide.find(".stethoscope").data('title');
  //     console.log("lib",lib_type)
  //     console.log("lib",lib_type,id,title)
  //     if(lib_type == 1){
  //       setStethoscopePosition(id);
  //       $("#sl_image_title").data("id",id).text(title);
  //       localStorage.setItem("sthetho_id", id);
  //     }
  //     // 0件の場合スライド機能をOFFにする
  //     if ( this.getSlideCount() == 1 ) {
  //       // jquery.bxslider.js によって生成された操作UI要素を削除する
  //       this.find('.bx-prev').remove();
  //       this.find('.bx-next').remove();
  //       this.closest('.bx-wrapper').find('.bx-pager').remove();
  //       // タップできないようにする
  //       //this.closest('.bx-viewport').css('pointer-events', 'none');
  //       // Bxsliderの下の余白を削減する
  //       this.closest('.bx-wrapper').css('margin-bottom', '30px');
  //     }
  //   },
  //   /**
  //    * ※引数$slideElement：スライドするjQuery要素
  //    * ※引数oldIndex：前のスライドの要素のインデックス（遷移前）
  //    * ※引数newIndex：先のスライドの要素インデックス（遷移後）
  //    */
  //   onSlideAfter:function($slideElement, oldIndex, newIndex) {
  //     var oldSlide = $('.bxslider>li').eq(oldIndex)
  //     var old_id = oldSlide.find(".stethoscope").data('id');
  //     var id = $slideElement.find(".stethoscope").data('id');
  //     var title = $slideElement.find(".stethoscope").data('title');
  //     if(lib_type == 1){
  //       resetStethoSound(old_id);
  //       setStethoscopePosition(id);
  //       $("#sl_image_title").data("id",id).text(title);
  //       localStorage.setItem("sthetho_id", id);
  //     }     
  //     // 先のスライドのタイトルを取得する
  //     var image_title = $slideElement.find("#sl_image_title").text();
  //     var titleElement = $slideElement.closest(".img_slide").find("#image_title");
  //     titleElement.text(image_title);
  //     if(image_title){
  //       titleElement.show();
  //       $("#image_title").text(image_title);
  //     } else {
  //       titleElement.hide();
  //     }
  //   }
  // });

  /**
   * set Stethoscope icon position
   * @param id id
   */
  // function setStethoscopePosition(id) {
  //   $.initSounds(id);
  //   setSpeedSlider();
  //   var bodymap = $("#bodymap_"+id);
  //   var position = bodymap.position();

  //   var d = document.getElementById("stethoscope_"+id);
  //   d.style.left = position.left+ 'px';
  //   d.style.top = position.top + 'px';
  //   localStorage.setItem("sound_active", 0);
    
  //   localStorage.setItem("body", "front"); // default body view
  //   localStorage.setItem("heart", 1); // play heart for the meantime
  //   localStorage.setItem("lung", 1);// play lung for the meantime
  //   localStorage.setItem("pulse", 1);// play pulse for the meantime
  // }
  // /**
  //  * reset sound when change stetho content
  //  * @param id id
  //  */
  // function resetStethoSound(id) {
  //   setSpeedSlider();
  //   localStorage.setItem("sound_active", 0);
  //   $( ".sound_"  + id).each(function() {
  //       var all_pnt_sound = $( this )[0];
  //       all_pnt_sound.pause() ;
  //       all_pnt_sound.volume = 0 ;
  //       all_pnt_sound.currentTime = 0;
  //   });

  //   $( ".btn-vol").each(function( index ) {
  //     $( this ).removeClass("vol-active");
  //     if ($(this).data("vol") == "1.0") {
  //       $(this).addClass("vol-active");
  //     }
  //   });
  // }
  // //Set Aus Speed Slider
  // function setSpeedSlider(){
  //     var labels = [48,54,60,72,84,96,108,120];
  //     $(".audio_slider").slider({
  //           min: 0,
  //           max: 7,
  //           value: 2,
  //           step:1
  //       })
  //       .slider("pips", {
  //           rest: "label",
  //           labels: labels
  //     }); 
  //   }
});
</script>
<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.6.20170323"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.quiz_box img').not('img.stetho_img').imagebox();
  $('.sound_box img').imagebox();

  disableImageSaving();
  disableVideoSaving();

  function disableImageSaving() {
    $('img').bind('contextmenu', function(e){
      return false;
    });
  }

  function disableVideoSaving() {
    $('video').bind('contextmenu', function(e){
        return false;
    });
  }

  $('img').click(function(){
    disableImageSaving();
  });
});
</script>
<script type="text/javascript">
(function($){
  $('.question_sound_list .playZ').logger({
    url: '/log',
    data: function(elm) {
      var $quizpack_box = $('body').find('.quiz_box');
      var $question_box = $(elm).closest('.question_box');
      var $question_sound = $(elm).closest('.question_sound_list');
      var data = {
        screen_code: 'QUIZ_ANSWER',
        event_code: 'PLAY',
        body: {
          quiz_pack: {
            id: $quizpack_box.data('quiz-pack-id'),
            title: $quizpack_box.data('quiz-pack-title')
          },
          quiz: {
            id: $question_box.data('quiz-id'),
            title: $question_box.data('quiz-title'),
            question: $question_box.data('quiz-question')
          },
          stetho_sound: {
            id: $question_sound.data('stetho-sound-id'),
            type: $question_sound.data('stetho-sound-type'),
            title: $question_sound.data('stetho-sound-title')
          }
        }
      };
      return data;
    }
  });
  $('.question_sound_list_only_one .playZ').logger({
    url: '/log',
    data: function(elm) {
      var $quizpack_box = $('body').find('.quiz_box');
      var $question_box = $(elm).closest('.question_box');
      var $question_sound = $(elm).closest('.question_sound_list_only_one');
      var data = {
        screen_code: 'QUIZ_ANSWER',
        event_code: 'PLAY',
        body: {
          quiz_pack: {
            id: $quizpack_box.data('quiz-pack-id'),
            title: $quizpack_box.data('quiz-pack-title')
          },
          quiz: {
            id: $question_box.data('quiz-id'),
            title: $question_box.data('quiz-title'),
            question: $question_box.data('quiz-question')
          },
          stetho_sound: {
            id: $question_sound.data('stetho-sound-id'),
            type: $question_sound.data('stetho-sound-type'),
            title: $question_sound.data('stetho-sound-title')
          }
        }
      };
      return data;
    }
  });
  $('.mfp-close.close_btn').off('click');
  $('.mfp-close.close_btn').logger({
    url: '/log',
    data: function(elm) {
      var $quizpack_box = $('body').find('.quiz_box');
      var $question_box = $('body').find('.question_box');
      var data = {
        from_screen_code: 'QUIZ_ANSWER',
        screen_code: 'QUIZ_PACKS',
        event_code: 'CLOSE',
        body: {
          quiz_pack: {
            id: $quizpack_box.data('quiz-pack-id'),
            title: $quizpack_box.data('quiz-pack-title')
          },
          quiz: {
            id: $question_box.data('quiz-id'),
            title: $question_box.data('quiz-title'),
            question: $question_box.data('quiz-question')
          },
        }
      };
      return data;
    }
  });
  $('.quiz_box .back_btn').off('click');
  $(".back_btn").on("click", function (e) {
    $('#btn-case-top').trigger("click");
  });
  $('.quiz_box .back_btn').logger({
    url: '/log',
    data: function(elm) {
      var $quizpack_box = $('body').find('.quiz_box');
      var $question_box = $('body').find('.question_box');
      var data = {
        from_screen_code: 'QUIZ_ANSWER',
        screen_code: 'QUIZ_SCORE',
        event_code: 'BACK',
        body: {
          quiz_pack: {
            id: $quizpack_box.data('quiz-pack-id'),
            title: $quizpack_box.data('quiz-pack-title')
          },
          quiz: {
            id: $question_box.data('quiz-id'),
            title: $question_box.data('quiz-title'),
            question: $question_box.data('quiz-question')
          },
        }
      };
      return data;
    }
  });
})(jQuery);
</script>
<script type="text/javascript" src="/js/common.js"></script>
<style>
.bodyFrame{
  width:100%;
  height:250px;
  position: relative;
}
.ausculaide-answer {
    margin-top: 4em;
}
</style>