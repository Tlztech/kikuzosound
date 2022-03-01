<!----------------------クイズ回答選択---------------------->
<!-- .quiz_cont_inner -->
<script src="{{asset('body-js/apps.js')}}"></script>
<div class="quiz_cont_inner">
@include('common.aus_php_function')
  <?php 
    $start_time=(isset($start_time))?$start_time->format('Y-m-d H:i:s'):"";
    $lib_count = $quiz->stetho_sounds()->get()->groupBy('lib_type')->count(); 
  ?>
  <p class="quiz_number"><span>{{count($old_choices)+1}}/{{$quiz_pack->max_quiz_count}}</span>@lang('quizpacks.quiz_question')</p>
  <input type="hidden" id="is_correct_number" value="{{count($old_choices)+1}}/{{$quiz_pack->max_quiz_count}}"/>
  <input type="hidden" value="{{$start_time}}" id="quiz_start_time"/>
    <!-- Sinngle Quiz -->
    @if($lib_count==1)
        @if( !empty($quiz->image_path) )
        <div class="quiz_question_img">
          <img src="{{ asset($quiz->image_path).'?v='.session('version') }}" alt="問題" style="cursor: pointer;">
        </div>
        @else
        <div style="height: 16px;">&nbsp;</div>
        @endif
    @endif
  <!-- .question_box -->
  <div class="question_box @if($lib_count>1) _multiple @endif" data-quiz-id="{{ $quiz->id }}" data-quiz-title="{{ $quiz->title }}" data-quiz-question="{{ $quiz->question }}">
    @if($quiz->limit_seconds != 0)
      <p class="question_number" data-seconds="{{$quiz->limit_seconds}}"><span>{{$quiz->limit_seconds}}</span></p>
    @endif

    <!-- .question_text -->
    <p class="question_text" @if($quiz->limit_seconds != 0)style="width:calc(100% - 90px);"@endif >
    @if (Config::get('app.locale') == "en")
      {{ $quiz->question_en }}
    @else
      {{ $quiz->question }}
    @endif

    <!-- Sinngle Quiz -->
    @if($lib_count==1)
    <!-- Multiple Quiz -->
    @else
        <?php 
        $is_final_answer = (isset($is_final_answer))?$is_final_answer:false;
        $content_type="";
        $btns= [0 => trans('quizpacks.aus_btn'), 1 => trans('quizpacks.stetho_btn'), 2 => trans('quizpacks.palpation_btn'), 3 => trans('quizpacks.ecg_btn'), 4 => trans('quizpacks.inspection_btn'), 5 => trans('quizpacks.xray_btn'), 6 => trans('quizpacks.echo_btn')] 
        ?>
        @if(!$is_final_answer)
        <div class="row multiple_quiz_wrapper">
            <div class="multiple_quiz__content">
                <h1 data-lib_type="@if(!empty($contents)) {{$contents->first()->lib_type}} @endif" class="multiple_quiz_lib__title content_title">@if(!empty($contents)){{$btns[$contents->first()->lib_type]}} @endif</h1>
                @if(empty($contents))
                <img src="{{ $quiz->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="content_file__img illustration__img" onerror="this.src = '/img/no_image.png';"/>
                @endif
                @if(!empty($contents))
                <!-- for quiz content examination -->
                <?php $content_type = $contents->first()->lib_type;?>
                <div>
                  @if($content_type!==0)
                  <div class="img_slide aus_answer_select multiple-quiz-slider">
                    <div class="img_slide_inner">
                      <ul id="exam_quiz"  class="bxslider">
                        @foreach($contents as $key => $content)
                          @if($content_type==1)
                            <li>
                              <div id="aus-img_wrapper_{{$content->id}}" class="aus-img_wrapper" data-result="{{ json_encode($content) }}" data-title="Ausculaide{{$key+1}}">
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
                          @endif
                          @if(!empty($content->image_path))
                          <li>
                            <img src="{{ $content->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="content_file__img" onerror="this.src = '/img/no_image.png';"/>
                          </li>
                          @endif
                          @if(!empty($content->video_path) && file_exists(public_path($content->video_path)))
                          <li>
                            <video playsinline data-id="{{$content->id}}" disablepictureinpicture controls="controls" controlslist="nodownload" src="{{ $content->video_path.'?v='.session('version') }}">
                            <!-- <iframe src="" type="video/mp4"
                              width="100%" height="100%" frameborder="0"></iframe> -->
                          </li>
                          @endif
                        @endforeach
                        </ul>
                    </div>
                  </div>
                  @endif
                  <div class="question_sound_list sound_wrapper">
                  @foreach($contents as $key => $content)
                    @if(!empty($content->sound_path))
                      <!-- .audio -->
                      <?php
                        $is_file_exist = file_exists(public_path().$content->sound_path);
                        $infoPath = pathinfo(public_path($content->sound_path));
                        $extension = $is_file_exist ? $infoPath['extension'] : null;
                      ?>
                      @if($extension == 'mp4' && $content->is_video_show == 1)
                      <div class="explanation_video_wrapper">
                          <video preload="metadata" playsinline data-id="{{$content->id}}" disablepictureinpicture width="100%" height="100%" id="stetho_sound_video[{{ $content->id }}]" controls controlslist="nodownload" src = "{{ asset($content->sound_path).'?v='.date('YmdHis', strtotime($content->updated_at)) }}">
                          <!-- <iframe src="" type="video/mp4"
                            width="100%" height="100%" frameborder="0"></iframe> -->
                      </div>
                      @endif
                      <div class="audio" style="@if($content_type==1 || ($extension == 'mp4' && $content->is_video_show == 1))display:none;@endif">
                          <div class="audiojsZ  audiojsZ_{{$content->id}}">
                          @if($content_type==1)
                            <?php $aus_sound = json_decode($content->sound_path);?>
                            @include('_partials.quiz_aus_audio',['sound' => $content, 'aus_sound' => $aus_sound])
                          @else
                          <audio data-id="{{ $content->id }}" src="{{ asset($content->sound_path).'?v='.session('version') }}" preload="auto" id="stethosound_{{$content->id}}"></audio>
                          @endif
                          <div class="play-pauseZ">
                              <span class="line"></span>
                              <p class="playZ"></p>
                              <p class="pauseZ pauseZ_vid_{{$content->id}}"></p>
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
                          </div>
                          <?php $quiz_stetho_description = $quiz->quiz_stetho_description()->where('stetho_sound_id',$content->id)->first(); ?>
                          <div style="@if($content->lib_type==1)display:none;@endif">
                            @if(config('app.locale') == 'en')
                              @if(!empty($quiz_stetho_description->description_en))
                                <p class="fukidashi">{{$quiz_stetho_description->description_en}}</p>
                              @endif
                            @else
                              @if(!empty($quiz_stetho_description->description))
                                <p class="fukidashi">{{$quiz_stetho_description->description}}</p>
                              @endif
                            @endif
                          </div>
                      </div>
                    @endif
                    <br>
                  @endforeach
                  </div>
                </div>
                @endif
            </div>
            <div class="multiple_quiz__history">
              <div class="multiple_quiz_history_wrapper">
                <div class="history__title">

                  <p id="multiple_quiz_history__title">@lang('quizpacks.medical_history')</p>
                </div>
                <div class="history__content">
                  <?php $gender = [0 => trans('quizpacks.case_male'), 1 => trans('quizpacks.case_female')]; ?>
                  <p>{{$quiz->case_age}}&emsp;{{$gender[$quiz->case_gender]}}</p>
                  <div class="details">
                    @if (Config::get('app.locale') == "en")
                      {!!$quiz->case_en!!}
                    @else
                      {!!$quiz->case!!}
                    @endif
                  </div>
                </div>
              </div>
              <div class="multiple_quiz_libBtns_wrapper">
                  @if($lib_count>=1)<p class="libBtns_title">@lang('quizpacks.clinical_exam')</p>@endif
                  @foreach($quiz->stetho_sounds()->get()->sortBy("lib_type")->groupBy('lib_type') as $key => $library)
                    <?php 
                      if(isset($old_observations[$quiz->id])){
                        $is_has_observation = isset($old_observations[$quiz->id][$library->first()->lib_type])?$old_observations[$quiz->id][$library->first()->lib_type]:[];
                      }
                      $done_class = (!empty($is_has_observation))?"observation_done":"";                      
                      $active_class = ($content_type === $library->first()->lib_type)?"active":"";
                    ?>
                    @if ($exam_id != null)
                      <button data-lib="{{$library->first()->lib_type}}" data-url="{{ route('multi_exam_content_select',[$quiz_pack->id, $quiz->id, $library->first()->lib_type, $exam_id]) }}" class="multiple_quiz__btns lib_buttons {{$active_class}} {{$done_class}}" data-quiz_type="{{$quiz_type}}" data-data="{{json_encode($old_choices)}}" data-observations="{{json_encode($old_observations)}}">{{$btns[$library->first()->lib_type]}} @if(!empty($done_class))&#10004;@endif</button>
                    @else
                      <button data-lib="{{$library->first()->lib_type}}" data-url="{{ route('multi_quiz_content_select',[$quiz_pack->id, $quiz->id, $library->first()->lib_type]) }}" class="multiple_quiz__btns lib_buttons {{$active_class}} {{$done_class}}" data-quiz_type="{{$quiz_type}}" data-data="{{json_encode($old_choices)}}" data-observations="{{json_encode($old_observations)}}">{{$btns[$library->first()->lib_type]}} @if(!empty($done_class))&#10004;@endif</button>
                    @endif
                  @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="multiple_quiz_libBtns_wrapper">
          <ul class="question_sound_box">
            @foreach($quiz->stetho_sounds()->get()->sortBy("lib_type")->groupBy('lib_type') as $key => $library)
              <?php 
                if(isset($old_observations[$quiz->id])){
                  $is_has_observation = isset($old_observations[$quiz->id][$library->first()->lib_type])?$old_observations[$quiz->id][$library->first()->lib_type]:[];
                }
              ?>
               <li class="question_sound_list lib_buttons">
                <button data-lib="{{$library->first()->lib_type}}" data-url="{{ route('multi_quiz_content_select',[$quiz_pack->id, $quiz->id, $library->first()->lib_type]) }}" class="multiple_quiz__btns final_answer_btns" data-quiz_type="{{$quiz_type}}" data-data="{{json_encode($old_choices)}}" data-observations="{{json_encode($old_observations)}}">{{$btns[$library->first()->lib_type]}}</button>
                <p class="final_answer_btns">{{$is_has_observation['quiz_choice_title']}}</p>
              </li>
            @endforeach
          </ul>
        </div>
        @endif
    @endif
    </p>
  
    <!-- /.question_text -->
    <!-- .question_sound only one-->
    <?php $type_strings = [ 1 => '肺音', 2 => '心音', 3 => '腸音', 9 => 'その他', 0 => "" ]; ?>
    <!-- Sinngle Quiz -->
    @if($lib_count==1)
        <?php $content_type = $quiz->stetho_sounds()->first()->lib_type;?>
        @if($content_type==1)
          <h1 data-lib_type="{{$content_type}}" class="content_title" style="display:none;"></h1>
          <div class="aus_slide aus_answer_select multiple-quiz-slider pTB20">
            <div class="img_slide_inner">
              <ul id="exam_quiz" class="bxslider">
                @foreach($quiz->stetho_sounds()->get() as $key => $content)
                    <li>
                      <div id="aus-img_wrapper_{{$content->id}}" class="aus-img_wrapper" data-result="{{ json_encode($content) }}" data-title="Ausculaide{{$key+1}}">
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
          </div>
        @endif
        <h1 data-lib_type="{{$content_type}}" class="content_title" style="display:none;"></h1>
        @if($quiz->stetho_sounds->count() == 1)
        <?php $s = $quiz->stetho_sounds()->first(); ?>
        <div class="question_sound_list_only_one" data-stetho-sound-id="{{ $s->id }}" data-stetho-sound-type="{{ $type_strings[$s->type] }}" data-stetho-sound-title="{{ $s->title }}">
            @if(!empty($s->image_path))
            <div class="explanation_img_wrapper">
              <img src="{{ $s->image_path.'?v='.session('version') }}" style="cursor: pointer;" onerror="this.src = '/img/no_image.png';"/>
            </div>
            @endif
            @if(!empty($s->video_path))
              <div style="display:flex; justify-content: center;" oncontextmenu="return false;">
                <video playsinline data-id="{{ $s->id }}" width="530" height="240" disablepictureinpicture controls="controls" controlslist="nodownload" src="{{ $s->video_path.'?v='.session('version') }}" type="video/mp4">
                <!-- <iframe src="" type="video/mp4"
                  width="100%" height="100%" frameborder="0"></iframe> -->
              </div>
            @endif
            @if(!empty($s->sound_path))
            <?php
            $infoPath = pathinfo(public_path($s->sound_path));
            $extension = ($s->lib_type==0 && $infoPath)?$infoPath['extension']:"";
            ?>
            @if($extension == 'mp4' && $s->is_video_show == 1)
            <div style="display:flex; justify-content: center;" oncontextmenu="return false;">
                <video playsinline data-id="{{ $s->id }}" disablepictureinpicture width="530" height="240" id="stetho_sound_video[{{ $s->id }}]" controls controlslist="nodownload" src = "{{ asset($s->sound_path).'?v='.session('version') }}">
                <!-- <iframe src="" type="video/mp4"
                  width="100%" height="100%" frameborder="0"></iframe> -->
            </div>
            <br>
            @endif
            <div class="audio" style="@if($s->lib_type==1 || ($extension == 'mp4' && $s->is_video_show == 1))display:none;@endif">
            <div class="audiojsZ audiojsZ_{{$s->id}}">
                @if($s->lib_type==1)
                  <?php $aus_sound = json_decode($s->sound_path);?>
                  @include('_partials.quiz_aus_audio',['sound' => $s, 'aus_sound' => $aus_sound])
                @else
                <audio data-id="{{ $s->id }}" src="{{ asset($s->sound_path).'?v='.session('version') }}" preload="auto"></audio>
                @endif
                <div class="play-pauseZ">
                <span class="line"></span>
                <p class="playZ"></p>
                <p class="pauseZ pauseZ_vid_{{$s->id}}"></p>
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
            <?php $quiz_stetho_description = $quiz->quiz_stetho_description()->where('stetho_sound_id',$s->id)->first(); ?>
            <div style="@if($s->lib_type==1)display:none;@endif">
              @if(config('app.locale') == 'en')
                @if(!empty($quiz_stetho_description->description_en))
                  <p class="fukidashi">{{$quiz_stetho_description->description_en}}</p>
                @endif
              @else
                @if(!empty($quiz_stetho_description->description))
                  <p class="fukidashi">{{$quiz_stetho_description->description}}</p>
                @endif
              @endif
            </div>
        </div>
        @else
        <!-- .question_sound -->
        <ul class="question_sound_box">
        @foreach($quiz->stetho_sounds()->get() as $key => $s)

            <!-- .question_sound_list -->
            <li class="question_sound_list" data-stetho-sound-id="{{ $s->id }}" data-stetho-sound-type="{{ $type_strings[$s->type] }}" data-stetho-sound-title="{{ $s->title }}">
            <!-- .audio -->
            @if(!empty($s->image_path))
            <div class="explanation_img_wrapper">
              <img src="{{ $s->image_path.'?v='.session('version') }}" style="cursor: pointer;" onerror="this.src = '/img/no_image.png';"/>
            </div>
            @endif
            @if(!empty($s->video_path))
              <div class="explanation_video_wrapper">
                <video playsinline data-id="{{ $s->id }}" disablepictureinpicture controls="controls"  preload="auto" controlslist="nodownload" src="{{ $s->video_path.'?v='.session('version') }}" type="video/mp4">
                <!-- <iframe src="" type="video/mp4"
                  width="100%" height="100%" frameborder="0"></iframe> -->
              </div>
            @endif
            @if(!empty($s->sound_path))
              <?php
                $infoPath = pathinfo(public_path($s->sound_path));
                $extension = ($s->lib_type==0 && $infoPath)?$infoPath['extension']:"";
              ?>
              @if($extension == 'mp4' && $s->is_video_show == 1)
                <div class="explanation_video_wrapper">
                  <video playsinline data-id="{{ $s->id }}" disablepictureinpicture width="100%" height="100%" id="stetho_sound_video[{{ $s->id }}]" controls controlslist="nodownload" src = "{{ asset($s->sound_path).'?v='.session('version') }}">
                  <!-- <iframe src="" type="video/mp4"
                    width="100%" height="100%" frameborder="0"></iframe> -->
                </div>
              @endif
            <div class="audio" style="@if($s->lib_type==1 || ($extension == 'mp4' && $s->is_video_show == 1))display:none;@endif">
                <div class="audiojsZ audiojsZ_{{$s->id}}">
                @if($s->lib_type==1)
                  <?php $aus_sound = json_decode($s->sound_path);?>
                  @include('_partials.quiz_aus_audio',['sound' => $s, 'aus_sound' => $aus_sound])
                @else
                <audio data-id="{{$s->id}}" src="{{ asset($s->sound_path).'?v='.session('version') }}" preload="auto"></audio>
                @endif
                <div class="play-pauseZ">
                    <span class="line"></span>
                    <p class="playZ"></p>
                    <p class="pauseZ pauseZ_vid_{{$s->id}}"></p>
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
            <?php $quiz_stetho_description = $quiz->quiz_stetho_description()->where('stetho_sound_id',$s->id)->first();
            ?>
            <div style="@if($s->lib_type==1)display:none;@endif">
              @if(config('app.locale') == 'en')
                @if(!empty($quiz_stetho_description->description_en))
                  <p class="fukidashi">{{$quiz_stetho_description->description_en}}</p>
                @endif
              @else
                @if(!empty($quiz_stetho_description->description))
                  <p class="fukidashi">{{$quiz_stetho_description->description}}</p>
                @endif
              @endif
            </div>
            <!-- /.audio -->
            </li>

            <!-- /.question_sound_list -->
        @endforeach
        </ul>
        <!-- /.question_sound -->
        @endif
    @endif
  </div>
  <!-- /.question_box -->

    <!-- Sinngle Quiz -->
    @if($lib_count<=1)
    <!-- .quiz_answer_box -->
    <div class="quiz_answer_box @if((int)$quiz_type!==0) fill_in @endif">
    <!-- .answer_select -->
    <ul class="answer_select">                  
        @if((int)$quiz_type===0)
        @if($lib_count < 1)
        <!-- no content quiz -->
          @foreach($quiz->quiz_choices()->whereNull("is_fill_in")->whereNull("lib_type")->get() as $c)
            <li>
              <a class="quiz_choice_btn" data-exam_id="{{ $exam_id }}" data-quiz_type="{{$quiz_type}}" data-url="{{ route('quiz_answer_choice',[$quiz_pack->id, $quiz->id]) }}" data-data="{{json_encode($old_choices)}}" data-choice_id="{{$c->id}}" style="cursor: pointer;">
                @if (Config::get('app.locale') == "en")
                  {{ $c->title_en }}
                @else
                  {{ $c->title }}
                @endif
              </a>
            </li>
          @endforeach
        @else
          @foreach($quiz->quiz_choices()->whereNull("is_fill_in")->whereNotNull("lib_type")->get() as $c)
            <li>
              <a class="quiz_choice_btn" data-exam_id="{{ $exam_id }}" data-quiz_type="{{$quiz_type}}" data-url="{{ route('quiz_answer_choice',[$quiz_pack->id, $quiz->id]) }}" data-data="{{json_encode($old_choices)}}" data-choice_id="{{$c->id}}" style="cursor: pointer;">
                @if (Config::get('app.locale') == "en")
                  {{ $c->title_en }}
                @else
                  {{ $c->title }}
                @endif
              </a>
            </li>
          @endforeach
        @endif
      @else
        <?php $is_correct = $quiz->quiz_choices()->where("is_fill_in",1)->first() ?>
        <div class="multiple-choices-wrapper">
          <div class="choices_select">
            <input id="fill_in_answer" type="text" class="fill_in__input" placeholder="{{ trans('quizpacks.fill_in_placeholder') }}"></input>
          </div>
          <div class="choices_save">
            <button class="quiz_choice_btn observation_btn__save"  type="button" data-exam_id="{{ $exam_id }}" data-quiz_type="{{$quiz_type}}" @if(!empty($is_correct)) data-url="{{ route('quiz_answer_choice',[$quiz_pack->id, $quiz->id]) }}" data-choice_id="{{$is_correct->id}}" @endif data-data="{{json_encode($old_choices)}}" data-observations="{{json_encode($old_observations)}}">Submit</button>
          </div>
        <div>
      @endif
      @if ($exam_id == null && (int)$quiz_type===0)
        <?php $choices = $quiz->quiz_choices()->whereNull("is_fill_in")->whereNotNull("lib_type")->get(); ?>
        @if(!$choices->isEmpty())
          <li>
            <a class="quiz_end" data-quiz_type="{{$quiz_type}}">@lang('quizpacks.quiz_end')</a>
          </li>
        @else
          <li>
            <a class="quiz_choice_btn" data-exam_id="{{ $exam_id }}" data-quiz_type="{{$quiz_type}}" data-url="{{ route('quiz_answer_choice',[$quiz_pack->id, $quiz->id]) }}" data-data="{{json_encode($old_choices)}}" data-choice_id="{{0}}" style="cursor: pointer;">
              @lang('quizpacks.quiz_end')
            </a>
          </li>
        @endif
      @endif
    </ul>
    <!-- /.answer_select -->
    </div>
    <!-- /.quiz_answer_box -->
    @else
    <!-- Multiple Quiz -->
      @if(!empty($contents))
      <div class="multiple-choices-wrapper">
          <div class="choices_label"><label class="">@lang('quizpacks.observation')</label></div>
          <div class="choices_select">
            <?php 
            $is_correct = ""; 
            $old_observation_key="";
            if(isset($old_observations[$quiz->id])){
              $is_has_observation = isset($old_observations[$quiz->id][$content_type])?$old_observations[$quiz->id][$content_type]:[];
              $old_observation_key = (!empty($is_has_observation))?$is_has_observation['quiz_choice_id']:0; 
            } 
            ?>
            @if((int)$quiz_type===0)
            <select id="multi-quiz-observation__select" class="form-control">
                <option class="observation_select__default" disabled="disabled" selected="selected" value="">@lang('quizpacks.diagnosis')</option>
                @foreach($quiz->quiz_choices()->whereNull("is_fill_in")->where("lib_type",$content_type)->orderBy('disp_order','ASC')->get() as $key => $c)
                <option value="{{$c->id}}" @if($c->id==$old_observation_key) selected @endif>
                    @if (Config::get('app.locale') == "en")
                      {{ $c->title_en }}
                    @else
                      {{ $c->title }}
                    @endif
                </option>
                @endforeach
            </select>
            @else
              <?php $is_correct = $quiz->quiz_choices()->where("lib_type",$content_type)->where("is_fill_in",1)->first(); ?>
              <input id="fill_in_answer" data-choice_id="{{$is_correct?$is_correct->id:0}}" type="text" class="fill_in__input" placeholder="{{ trans('quizpacks.fill_in_placeholder') }}"></input>
            @endif
          </div>
          <div class="choices_save">
            <button id="multi_quiz_observation_btn" class="observation_btn__save" type="button" data-exam_id="{{ $exam_id }}" data-quiz_type="{{$quiz_type}}" data-url="{{ route('multi_quiz_observation_choice',[$quiz_pack->id, $quiz->id]) }}" data-data="{{json_encode($old_choices)}}" data-observations="{{json_encode($old_observations)}}" data-content_type="{{$content_type}}">Submit</button>
          </div>
      </div>
      @else
      <div class="quiz_answer_box" style="display:none;">
        
      </div>
      @endif
      <!-- for final answer -->
      @if($is_final_answer)
      <div class="multiple-choices-wrapper">
          <div class="choices_label"><label class="">@lang('quizpacks.final_diagnosis')</label></div>
          <div class="choices_select">
            @if((int)$quiz_type===0)
            <select id="multi-quiz-observation__select" class="form-control">
                <option class="observation_select__default" disabled="disabled" selected="selected" value="">@lang('quizpacks.diagnosis')</option>
                @foreach($quiz->quiz_choices()->whereNull("lib_type")->whereNull("is_fill_in")->orderBy('disp_order','ASC')->get() as $key => $c)
                <?php $is_correct = $c->is_correct; ?>
                <option value="{{$c->id}}">
                    @if (Config::get('app.locale') == "en")
                      {{ $c->title_en }}
                    @else
                      {{ $c->title }}
                    @endif
                </option>
                @endforeach
            </select>
            @else
              <?php $is_correct = $quiz->quiz_choices()->whereNull("lib_type")->where("is_fill_in",1)->first(); ?>
              <input id="fill_in_answer" data-choice_id="{{$is_correct?$is_correct->id:0}}" type="text" class="fill_in__input" placeholder="{{ trans('quizpacks.fill_in_placeholder') }}"></input>
            @endif
          </div>
          <div class="choices_save">
            <button id="multi_quiz_final_answer_btn" class="observation_btn__save" type="button" data-exam_id="{{ $exam_id }}" data-quiz_type="{{$quiz_type}}" data-url="{{ route('quiz_answer_choice',[$quiz_pack->id, $quiz->id]) }}" data-data="{{json_encode($old_choices)}}" data-observations="{{json_encode($old_observations)}}" data-content_type="{{$content_type}}">Submit</button>
          </div>
      </div>
      @endif
    @endif
    <div id="ausculaid_app_wrapper" style="display:none;">
      @include('bodymap.bodymap')
    </div>

<!--
<div><button class="abc">解答せずに終了</button></div>
-->
</div>
<!-- .quiz_cont_inner -->
<script type="text/javascript" src="/js/jquery.sss_portal.audio.js?v=1.1.6.20170323"></script>
<script type="text/javascript">
// $('iframe').load( function() {	
//   $("iframe").contents().find('video')
//     .attr("controlslist", "nodownload")
//     .removeAttr("autoplay")
//     .attr("height", "100%")
//     .attr("width", "100%")
//     .attr("disablepictureinpicture", "");	
//   $("iframe").contents().find('body').attr("oncontextmenu", "return false;");	
// });
$('video').bind('contextmenu', function(e){
  return false;
});
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
</script>
<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.6.20170323"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.quiz_box img').not('img.stetho_img').imagebox();
  $("video").on("play", function (e) {
    // pause all audio
    $("audio").each(function( index ) {
      var id = $(this).attr("data-id");
      var className = $(".audiojsZ_" + id).attr("class");
      if (className.includes("playingZ")) {
        $(".pauseZ_vid_" + id).trigger("click");
      }
    });

    var v = $(this);
    $("video").each(function( index ) {
      if ($(v).attr("src") != $(this).attr("src")) {
        $(this).get(0).pause()
      }
    });
  });

  $("audio").on("play", function (e) {
    // pause all video
    $("video").each(function( index ) {
      $(this).get(0).pause();
    });
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
        screen_code: 'QUIZ',
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
        screen_code: 'QUIZ',
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
        from_screen_code: 'QUIZ',
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
          }
        }
      };
      return data;
    }
  });
})(jQuery);

(function($){
    $(window).on("beforeunload", function() { 
        return true;
    })
})(jQuery);
$(document).ready(function () {
  setTimeout(function() {
    $(".quiz_cont_scroll").scrollTop(10000);
  }, 100);

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

  /**
   * set Stethoscope icon position
   * @param id id
   */
  // function setStethoscopePosition(id) {
  //   $.initSounds(id);
  //   console.log("test")
  //   var bodymap = $("#bodymap_"+id);
  //   var position = bodymap.position();

  //   var d = document.getElementById("stethoscope_"+id);
  //   d.style.left = position.left+ 'px';
  //   d.style.top = position.top + 'px';
  //   localStorage.setItem("sound_active", 0);
  //   var type = $("#stethoscope_" +id).data("type") == 1 ? "lung" : "heart";
  //   localStorage.setItem("type", type);
    
  //   localStorage.setItem("body", "front"); // default body view
  //   localStorage.setItem("heart", 1); // play heart for the meantime
  //   localStorage.setItem("lung", 1);// play lung for the meantime
  //   localStorage.setItem("pulse", 1);// play pulse for the meantime
  // }
  /**
   * reset sound when change stetho content
   * @param id id
   */
  // function resetStethoSound(id) {
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
});

</script>
<script type="text/javascript" src="/js/common.js"></script>
<style>
.bodyFrame{
  width:100%;
  height:250px;
}
</style>