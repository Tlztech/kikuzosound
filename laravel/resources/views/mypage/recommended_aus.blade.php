<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="On the kikuzosound.com (auscultation portal site), many sounds such as heart sounds and lung sounds are released. If you listen with an appropriate device (Kikuzo-speaker with your own stethoscope), you can hear a realistic auscultation sound that is almost the same as the actual case." />
    <meta property="og:description" content="On the kikuzosound.com (auscultation portal site), many sounds such as heart sounds and lung sounds are released. If you listen with an appropriate device (Kikuzo-speaker with your own stethoscope), you can hear a realistic auscultation sound that is almost the same as the actual case." />
    <meta name="google" content="notranslate" />

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/common.js?v=1.2.0.20190125')}}"></script>
    <link rel="stylesheet" href="{{asset('css/common.css?v=1.2.0.20180301')}}">
    <link rel="stylesheet" href="{{asset('css/style.css?v=1.2.2.20190523')}}">
    <link rel="stylesheet" href="{{asset('css/style2.css?v=1.2.0.20190610')}}">
    <link rel="stylesheet" href="{{asset('css/quiz.css?v=1.2.0.20170710')}}">
    <link rel="stylesheet" href="{{asset('js/audiojs/audio.css')}}">
    <link rel="stylesheet" href="{{asset('css/respons.css?v=1.2.0.20190125')}}">
    <link rel="stylesheet" href="{{asset('css/bodymap.css')}}">
    <!-- bxSlider Javascript file -->
    <script src="{{asset('js/bxslider/jquery.bxslider.js')}}"></script>
    <!-- bxSlider CSS file -->
    <link href="{{asset('js/bxslider/jquery.bxslider.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
    <!-- thema s -->
    <link rel="stylesheet" href="{{asset('css/jquery-ui.structure.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.theme.min.css')}}">
    <!-- Aus Fullscreen Css -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/full-screen-helper.css')}}">
    <!-- thema e -->
    <!-- Jquery Range Slider -->
    <link rel="stylesheet" href="{{asset('css/jquery-ui-slider-pips.css')}}">
    <link href="{{asset('css/bootstrap-switch-button.css')}}" rel="stylesheet"> 
    <!-- Aus Fullscreen JS -->
    <script type="text/javascript" src="{{asset('js/full-screen-helper.min.js')}}"></script>
    <script src="{{asset('js/jquery-ui.js')}}"></script>
    <script src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>
    <script src="{{asset('js/audiojs/audio.min.js?v=1.1.7.20190125')}}"></script>
    <script type="text/javascript" src="{{asset('/js/jquery.logger.js?v=1.1.7.20190125')}}"></script>
    <script src="{{asset('/js/magnific-popup/jquery.magnific-popup.js')}}"></script>
    <link href="{{asset('/js/magnific-popup/magnific-popup.css')}}" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/add.css?v=1.2.3.20190610')}}"><!-- 20170531 hyono@cori.com 11 ログイン画面用に追加 -->
    <script src="{{asset('js/cookie/jquery.cookie.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>

    <script src="{{asset('js/jquery-ui-slider-pips.js')}}"></script>

    <!-- bodymapJS/CSS -->
	<!-- <script type="text/javascript" src="body-js/lib/howler.core.js"></script> -->
    <script type="text/javascript" src="body-js/data.js" charset="UTF-8"></script>
    <script type="text/javascript" src="body-js/config.js" charset="UTF-8"></script>
    <script type="text/javascript" src="body-js/common.js" charset="UTF-8"></script>
    <script type="text/javascript" src="body-js/points_config.js" charset="UTF-8"></script>
    <script src="{{asset('body-contents/heart/scripts/contents_config.js')}}"></script>
    <script src="{{asset('body-js/apps.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('body-style/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('body-style/bodymap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('body-style/style_responsive.css')}}"/>

</head>
<div id="data-recommended-aus" class="recommend_aus_content">
<ul class="sound_list accordion">
    <?php $type_strings = [0 => trans('list.tag-stetho'), 1 => trans('list.tag-aus'), 2 => trans('list.tag-palp'), 3 => trans('list.tag-ecg'), 4 => trans('list.tag-ins') , 5 => trans('list.tag-xray'), 6 => trans('list.tag-echo')]; ?>
    <li class="sound_box aus-sound_box" data-id="{{$sound->id}}">
        <div class="sound_box_inner sound_conts_open" style="">
            <div id="aus_{{$sound->id}}" class="recommended_data" data-id="{{$sound->id}}" data-result="{{ json_encode($sound) }}">
            </div>
            <div class="sound_conts accordion_conts">
                <!-- Pulse Modal -->
                <div id="pulseModal_{{$sound->id}}" class="recommended pulse-modal">
                  <!-- Pulse Modal content -->
                  <div class="pulse-modal-content">
                    <p>
                      {!! trans('aus.pulse_modal_content') !!}
                    </p>
                    <br><br>
                    <center>
                      <input type="checkbox" id="pulse_checkbox_{{$sound->id}}" class="pulse_checkbox">
                      <label for="pulse_checkbox_{{$sound->id}}" class="pulse_checkbox_label">{{trans('aus.pulse_checkbox_label')}}</label>
                      <br><br>
                      <button id="pulse_btn_close_{{$sound->id}}" class="pulse_btn_close" data-id="{{$sound->id}}">{{trans('aus.pulse_btn_label')}}</button>
                    </center>
                  </div>
                </div>
                <div class="sound_conts_inner ausc-img-container">
                <div class="desc-container" style="width: 100%;">
                  <p class="text">
                    @if (Config::get('app.locale') == "en")
                    {!! $sound->description_en !!}
                    @else
                    {!! $sound->description !!}
                    @endif
                  </p>
                  <?php 
                    $libClassName = "";
                    switch ($sound->lib_type) {
                    case 0:
                        $libClassName = "library_video_stetho";
                        break;
                    case 1:
                        $libClassName = "library_video_aus";
                        break;
                    case 2:
                        $libClassName = "library_video_palpa";
                        break;
                    case 4:
                        $libClassName = "library_video_inspi";
                        break;
                    default:
                        $libClassName = "";
                        break;
                    }
                  ?>
                </div>
                  <!-- <p class="sound_conts_inner_title">
                    @if (Config::get('app.locale') == "en")
                    {{$sound->title_en}}
                    @else
                    {{$sound->title}}
                    @endif
                  </p> -->
                  @if(!empty($sound->body_image))
                  <div class="aus-body_img">
                      <span class="aus-icon-switch_wrapper">
                        <button id="" class="btn-icon aus-fullscreen-on btn-fullscreen-on zoom">
                          @lang('aus.zoom_in')
                        </button>

                        <?php 
                          $sound_path = json_decode($sound->sound_path);
                          $config = json_decode($sound->configuration);
                          // aptm heart
                          $a = isset($sound_path->a_sound_path) && isset($config->a) && $config->a->x && $config->a->y && $config->a->r ? $sound_path->a_sound_path : "";
                          $p = isset($sound_path->p_sound_path) && isset($config->p) && $config->p->x && $config->p->y && $config->p->r ? $sound_path->p_sound_path : "";
                          $t = isset($sound_path->t_sound_path) && isset($config->t) && $config->t->x && $config->t->y && $config->t->r ? $sound_path->t_sound_path : "";
                          $m = isset($sound_path->m_sound_path) && isset($config->m) && $config->m->x && $config->m->y && $config->m->r ? $sound_path->m_sound_path : "";

                          $isHeart = 0;
                          if ($a || $p || $t || $m) {
                            $isHeart = 1;
                          } else {
                            // h1-h4
                            for ($i = 1; $i <= 4; $i++) {
                              $h = isset($sound_path->{"h" . $i . "_sound_path"}) && isset($config->{"h" . $i}) && $config->{"h" . $i}->x && $config->{"h" . $i}->y && $config->{"h" . $i}->r ? $sound_path->{"h" . $i . "_sound_path"} : "";
                              if ($h) {
                                $isHeart = 1;
                              }
                            }
                          }
                          // aptm pulse
                          $pa = isset($sound_path->pa_sound_path) && isset($config->a) && $config->a->x && $config->a->y && $config->a->r ? $sound_path->pa_sound_path : "";
                          $pp = isset($sound_path->pp_sound_path) && isset($config->p) && $config->p->x && $config->p->y && $config->p->r ? $sound_path->pp_sound_path : "";
                          $pt = isset($sound_path->pt_sound_path) && isset($config->t) && $config->t->x && $config->t->y && $config->t->r ? $sound_path->pt_sound_path : "";
                          $pm = isset($sound_path->pm_sound_path) && isset($config->m) && $config->m->x && $config->m->y && $config->m->r ? $sound_path->pm_sound_path : "";

                          $isPulse = 0;
                          if ($pa || $pp || $pt || $pm) {
                            $isPulse = 1;
                          } else {
                            // p1-p4
                            for ($i = 1; $i <= 4; $i++) {
                              $p = isset($sound_path->{"p" . $i . "_sound_path"}) && isset($config->{"h" . $i}) && $config->{"h" . $i}->x && $config->{"h" . $i}->y && $config->{"h" . $i}->r ? $sound_path->{"p" . $i . "_sound_path"} : "";
                              if ($p) {
                                $isPulse = 1;
                              }
                            }
                          }
                          // front lung
                          $tr1 = isset($sound_path->tr1_sound_path) && isset($config->tr1) && $config->tr1->x && $config->tr1->y && $config->tr1->r ? $sound_path->tr1_sound_path : "";
                          $tr2 = isset($sound_path->tr2_sound_path) && isset($config->tr2) && $config->tr2->x && $config->tr2->y && $config->tr2->r ? $sound_path->tr2_sound_path : "";
                          $br1 = isset($sound_path->br1_sound_path) && isset($config->br1) && $config->br1->x && $config->br1->y && $config->br1->r ? $sound_path->br1_sound_path : "";
                          $br2 = isset($sound_path->br2_sound_path) && isset($config->br2) && $config->br2->x && $config->br2->y && $config->br2->r ? $sound_path->br2_sound_path : "";

                          $isLung = 0;
                          if ($tr1 || $tr2 || $br1 || $br2) {
                            $isLung = 1;
                          } else {
                            for ($i = 1; $i <= 6; $i++) {
                              $ve = isset($sound_path->{"ve" . $i . "_sound_path"}) && isset($config->{"ve" . $i}) && $config->{"ve" . $i}->x && $config->{"ve" . $i}->y && $config->{"ve" . $i}->r ? $sound_path->{"ve" . $i . "_sound_path"} : "";
                              if ($ve) {
                                $isLung = 1;
                              }
                            };
                          }

                          if($sound->type==1 & !$isLung){
                            $isHeart = 0;
                          }

                          // back lung
                          $br3 = isset($sound_path->br3_sound_path) && isset($config->br3) && $config->br3->x && $config->br3->y && $config->br3->r ? $sound_path->br3_sound_path : "";
                          $br4 = isset($sound_path->br4_sound_path) && isset($config->br4) && $config->br4->x && $config->br4->y && $config->br4->r ? $sound_path->br4_sound_path : "";
                          
                          $isLungBack = 0;
                          if ($br3 || $br4) {
                            $isLungBack = 1;
                          } else {
                            for ($i = 7; $i <= 12; $i++) {
                              $ve = isset($sound_path->{"ve" . $i . "_sound_path"}) && isset($config->{"ve" . $i}) && $config->{"ve" . $i}->x && $config->{"ve" . $i}->y && $config->{"ve" . $i}->r ? $sound_path->{"ve" . $i . "_sound_path"} : "";
                              if ($ve) {
                                $isLungBack = 1;
                              }
                            };
                          }

                        ?>
                        <button id="heart_icon_{{$sound->id}}" class="btn-heart btn-icon btn-heart-grey-icon" <?php echo $isHeart ? "" : "disabled"; ?> ></button>
                        @if($sound->type == 2) <!-- if heart -->
                          <button id="pulse_icon_{{$sound->id}}" class="btn-pulse btn-icon btn-pulse-grey-icon" <?php echo $isPulse ? "" : "disabled"; ?> ></button>
                        @endif
                        <button id="lung_icon_{{$sound->id}}" class="btn-lung btn-icon btn-lung-grey-icon" <?php echo $isLung ? "" : "disabled"; ?> 
                          data-lung-back="<?php echo $isLungBack ? 1 : 0; ?> "
                          data-lung-front="<?php echo $isLung ? 1 : 0; ?> "
                        >
                        </button>
                      </span>
                      <ul class="main_body_container" id="aus-fullscreen-wrapper_{{$sound->id}}">
                        <button id="btn-fullscreen-off" class="btn-icon aus-fullscreen-off" style="display:none">
                          @lang('aus.zoom_out')
                        </button>
                        <button id="bswitch_{{$sound->id}}" 
                          class="btn-switch-body <?php echo ($sound->type == 1 && $sound->body_image_back != "") ? '' : 'btn-switch-body-disabled';?>"
                          data-front_body="{{ $sound->body_image.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                          data-back_body="{{ $sound->body_image_back.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                          data-body="front"
                          <?php echo ($sound->type == 1 && $sound->body_image_back != "")? '' : 'disabled';?>
                        >
                        </button>
                        <li>
                          <div id="aus-img_wrapper_{{$sound->id}}" class="aus-img_wrapper">
                          <!-- <iframe
                              id="body-{{ $sound->id }}"
                              class="bodyFrame"
                              frameborder="0"
                              data-size="1"
                              src="">
                          </iframe> -->
                            <div 
                              id="body-{{ $sound->id }}"
                              class="bodyFrame"
                              frameborder="0"
                              data-size="1"
                            >
                            @include('bodymap.bodymap')
                            <button id="ausculaide_load_btn_{{$sound->id}}" onClick="loadAus({{$sound->id}});" style="display:none;">Load Ausculaide</button>
                            </div>
                          </div>
                          <button id="reload_icon_{{$sound->id}}" class="btn-reload-icon"></button>
                        </li>
                        <div class="exit-fullscreen-overlay" style="display:none">
                            <div class="fullscreen-msg">
                              <div class="fullscreen-title-wrapper"> 
                                <p class="fullscreen-msg-title">@lang('aus.fullscreen_exit')</p>
                              </div>
                              <div class="fullscreen-footer-wrapper" >
                                <div data-id="{{$sound->id}}" class="fullscreen-btn-wrapper __ok"> 
                                  <span class="fullscreen-btn fullscreen-btn-ok">@lang('aus.Ok')</span>
                                </div>
                                <div class="fullscreen-btn-wrapper __cancel">
                                  <span class="fullscreen-btn fullscreen-btn-cancel">@lang('aus.Cancel')</span>
                                </div>
                              </div>
                            </div>
                        <div>
                      </ul>
                      <?php 
                        $s = json_decode($sound->sound_path);
                      ?>
                      
                      <div id="controller_slider__{{$sound->id}}" class="aus_controller_wrapper" style="display: none;"> 
                      <!-- <button id="point_a_{{$sound->id}}" data-point="a" class="btn-point btn-point_{{$sound->id}}">A</button>
                      <button id="point_p_{{$sound->id}}" data-point="p" class="btn-point btn-point_{{$sound->id}}">P</button>
                      <button id="point_t_{{$sound->id}}" data-point="t" class="btn-point btn-point_{{$sound->id}}">T</button>
                      <button id="point_m_{{$sound->id}}" data-point="m" class="btn-point btn-point_{{$sound->id}}">M</button>

                      </br> -->
                      <div class="audio_slider"></div>
                      <p class="slider_label">HR</p>
                      </div>

                      <div class="offline_switch_wrapper" data-id="{{$sound->id}}" style="bottom: 168px;">
                        <input type="button"  class="offline-btn-switch switch btn-xs" data-id="{{$sound->id}}"/>
                        <input id="offline_switch_{{$sound->id}}" type="checkbox" class="offline_btn" data-toggle="switchbutton" data-onstyle="primary" data-id="{{$sound->id}}" data-size="xs" data-onlabel="@lang('aus.offline_text')" data-offlabel="@lang('aus.offline_text')"/>
                      </div>

                      <button id="sound_{{$sound->id}}" data-sounds="{{$sound->sound_path}}"></button>
                      <button class="recommend_explanation" data-status="hide" data-text_open="@lang('aus.rec_explanation')" data-text_hide="@lang('aus.hide_explanation')">@lang('aus.rec_explanation')</button>
                  </div>
                  @endif
                  <div id="recommend_explanation_content">
                  <p class="aus-body_description">
                    @if (Config::get('app.locale') == "en")
                    {!! $sound->image_description_en !!}
                    @else
                    {!! $sound->image_description !!}
                    @endif
                  </p>
                  <?php $stetho_sound = $sound; ?>
                  @if(!empty($stetho_sound->video_path) && file_exists(public_path($stetho_sound->video_path)))
                    <div class="bx-viewport-insp">
                        <!-- single video -->
                        <video class="{{$libClassName}}" id="library_video" data-id="{{$stetho_sound->id}}" width="530"
                            height="240" controls controlslist="nodownload"
                            src="{{ $stetho_sound->video_path.'?v='.env('ASSET_VER') }}">
                    </div>
                  @endif
                  @if(!empty($stetho_sound->image_path))
                    <div class="img_slide">
                        <div class="img_slide_inner">
                            <ul class="bxslider_{{$sound->id}}">
                                <li>
                                    <img src="{{ $stetho_sound->image_path.'?v='.env('ASSET_VER') }}"
                                        style="cursor: pointer;" class="sound_img" />
                                </li>
                            </ul>
                            <script>
                            $('.bxslider_{{$sound->id}}').bxSlider({
                                touchEnabled: false,
                                oneToOneTouch: false
                            });
                            </script>
                        </div>
                    </div>
                  @endif

                  <?php 
                    $hasSwipe = 0;
                    if(Config::get('app.locale') == "en") {
                      if ($stetho_sound->images_en->count() > 1) {
                          $hasSwipe = 1;
                      }
                    } else {
                      if ($stetho_sound->images_ja->count() > 1) {
                          $hasSwipe = 1;
                      }
                    }
                  ?>

                  <?php
                    $images = false;
                    if(Config::get('app.locale') == "en") {
                    $images = $stetho_sound->images_en->count();
                    }
                    else{
                    $images = $stetho_sound->images_ja->count();
                    }
                  ?>

                  @if($images)
                    <div class="img_slide rec-page">
                      <div class="img_slide_inner">
                        <ul class="bxslider_{{$stetho_sound->id}}">
                          @foreach($stetho_sound->images()->get() as $image)
                            @if(Config::get('app.locale') == $image->lang)
                            <li>
                              @if (strpos($image['image_path'], 'mp4'))
                              <!-- multiple video -->
                                <video class="{{$libClassName}}" id="library_video" data-id="{{$stetho_sound->id}}"
                                  controls preload="metadata" width="100%" controlslist="nodownload"
                                  oncontextmenu="return false;"
                                  src="{{ $image->image_path.'?v='.env('ASSET_VER') }}"
                                  type="video/mp4">
                              @else
                                <img src="{{ $image->image_path.'?v='.env('ASSET_VER') }}"
                                  style="cursor: pointer;" class="sound_img" />
                              @endif
                              <p hidden="" id="sl_image_title">{{$image->title}}</p>
                            </li>
                            @endif
                          @endforeach
                        </ul>
                        @if($hasSwipe)
                          <script>
                          $('.bxslider_{{$stetho_sound->id}}').bxSlider(
                            {
                              wrapperClass:"bx-wrapper iframe-recomeended-aus-slider"
                            }
                          );
                          </script>
                        @else
                          <script>
                          $('.bxslider_{{$stetho_sound->id}}').bxSlider({
                            wrapperClass:"bx-wrapper iframe-recomeended-aus-slider",
                            touchEnabled: false,
                            oneToOneTouch: false
                          });
                          </script>
                        @endif
                      </div>
                      <p class="img_slider_text" @if(!$stetho_sound->images->first()->title) hidden @endif
                        id="image_title">{{$stetho_sound->images->first()->title}}</p>
                    </div>
                  @endif
                  <br><br>
                    <?php $is_favo_strings = [1 => trans('home.remove_fave_btn'), 0 => trans('home.register_fave_btn'), 3 => trans('home.fav_exceed')]; ?>
                    <div class="clearfix fav_wrapper">
                        <button class="FavButton aus-bookmark-btn" data-lib_type="{{$stetho_sound->lib_type}}" data-confirm="{{trans('home.do_process')}}" data-trans='{{json_encode($is_favo_strings)}}' data-id="{{ $stetho_sound->id }}" data-favo="{{ $stetho_sound->favo }}" data-aid="{{ $params['aid'] }}" data-type="{{ $stetho_sound->type }}">{{ $is_favo_strings[$stetho_sound->favo] }}</button>
                    </div>
                  </div>
                  </div>
                </div>
                <!-- .sound_conts_inner -->

              </div>
              <!-- /.sound_conts -->
            </div>
            
        </div>
    </li>
</ul>

</div>
<style>
.sound_list .sound_box{
 border:unset !important;
}
body{
    background:unset;
    overflow: hidden !important;
}
</style>

<script src="{{asset('js/common.js?v=1.2.0.20190125')}}"></script>
<script src="{{asset('js/jquery-ui-slider-pips.js')}}"></script>
<script src="{{asset('body-js/apps.js')}}"></script>
<script type="text/javascript" src="/js/bootstrap-switch-button.js?v=1.1.6.20210421"></script>
<script type="text/javascript" src="/js/offline.js"></script>

<script>
  document.addEventListener('click', function( event ) {
    var current_active_ipax = localStorage.getItem('sthetho_id');
    var parent_current_rec_ipax = $('#recommended_library_contents .accordion #aus_'+current_active_ipax+'.sound_accordion_open', parent.document);
    var parent_current_bookmark_ipax = $('#bookmarks .accordion #aus_'+current_active_ipax+'.sound_accordion_open', parent.document);
    if (parent_current_rec_ipax.hasClass('open_active')) {
      parent_current_rec_ipax.click();
      setPrevLocalStoarge();
    }
    if (parent_current_bookmark_ipax.hasClass('open_active')) {
      parent_current_bookmark_ipax.click();
      setPrevLocalStoarge();
    }
    localStorage.setItem('sthetho_id', sessionStorage.getItem('main_rec_ipax'));

  });
  function setPrevLocalStoarge(){
    localStorage.setItem('body', sessionStorage.getItem('rec_body'));
    localStorage.setItem('lung', sessionStorage.getItem('rec_lung'));
    localStorage.setItem('heart', sessionStorage.getItem('rec_heart'));
    localStorage.setItem('pulse', sessionStorage.getItem('rec_pulse'));
    localStorage.setItem('type', sessionStorage.getItem('rec_type'));
  }
  var exp_height = $("#recommend_explanation_content").height();
  $("#recommend_explanation_content").height(0);
  $(".recommend_explanation").click(function() { 
    if($(this).attr("data-status") == "hide") {
      $("#recommend_explanation_content").height(exp_height);
      $(this).text($(this).attr("data-text_hide"));
      $(this).attr("data-status", "open");
    } else {
      $("#recommend_explanation_content").height(0);
      $(this).text($(this).attr("data-text_open"));
      $(this).attr("data-status", "hide");
    }
	});
  $(document).ready(function() {
    setTimeout(function(){
      setParentIframeHeight();
    }, 1000);
    function setParentIframeHeight(){
      var content_height =  $("#data-recommended-aus").height();
      //console.log("iframe",content_height)
      $("#recommended_ausculaide", window.parent.document).height(content_height);
    }

    $(".recommend_explanation").on("click", function(){
      setTimeout(function(){
        setParentIframeHeight();
      }, 200);
    })

    $(window).on('resize', function () {
      FullScreenHelper.on(function () {
        setTimeout(function(){
          setParentIframeHeight();
        }, 500);
        return;
      })
      setParentIframeHeight();
    });
    $(".tag").each( function() {  // タグ
      var lib_type = $(this).data('lib_type');
    
      switch(lib_type){
        case 0:
          return $(this).css('background-image', "url(../img/tag-stetho.png)"); 
        case 1:
          return $(this).css('background-image', "url(../img/tag-aus.png)"); 
        case 2:
          return $(this).css('background-image', "url(../img/tag-palp.png)"); 
        case 3:
          return $(this).css('background-image', "url(../img/tag-ecg.png)"); 
        case 4:
          return $(this).css('background-image', "url(../img/tag-ins.png)"); 
        case 5:
          return $(this).css('background-image', "url(../img/tag-xray.png)"); 
        case 6:
          return $(this).css('background-image', "url(../img/tag-echo.png)"); 
      }
    });

    $(".tag").each(function() { // タグ
      var favo = $(this).data('favo'); // お気に入り状態

      if (favo == 1) { // お気に入りに登録されている場合
        $(this).css('background-image', 'url(../img/tag-favo.png)');
      }
    });

    $(".FavButton").each(function() { // タグ
      var favo = $(this).data('favo'); // お気に入り状態

      if (favo == 1) { // お気に入りに登録されている場合
        $(this).css('background', '#eeeeee');
        $(this).css('border-color', '#eeeeee');
        $(this).css('color', '#000000');
        $(this).css('background-image', 'url(../img/no-delete.png)');
        $(this).css('background-repeat', 'no-repeat');
        $(this).css('background-position', '8% 46%');
      }
    });
    
    // aus video
    $(".library_video_aus").on("play", function (e) {
      $('#btn-case-top').trigger("click");
      var v = $(this);
      $("video").each(function( index ) {
        if ($(v).attr("src") != $(this).attr("src")) {
          $(this).get(0).pause()
        }
      });
    });
    
  });
</script>