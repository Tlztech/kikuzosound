<?php
  $user_id=Session::get('MEMBER_3SP_ACCOUNT_ID');
  if($user_id==null) {
    $user_id=0;
  }
?>
<ul class="sound_list accordion">
<!--*********************************************************-->
<?php $i = 0?>
@foreach($recommended_libs as $stetho_sound)
<!-- .sound_box -->
<!-- checking the extension -->

<?php $type_strings = [0 => trans('list.tag-stetho'), 1 => trans('list.tag-aus'), 2 => trans('list.tag-palp'), 3 => trans('list.tag-ecg'), 4 => trans('list.tag-ins') , 5 => trans('list.tag-xray'), 6 => trans('list.tag-echo')]; ?>
<li class="rec_sound_box sound_box @if(empty($stetho_sound->sound_path) || $stetho_sound->lib_type==1) aus-sound_box @endif"
    data-id="{{ $stetho_sound->id }}">
    <p class="tag" id="{{ $stetho_sound->id }}" data-favo="{{ $stetho_sound->favo }}" data-lib_type="{{$stetho_sound->lib_type}}"><span>{{$type_strings[$stetho_sound->lib_type]}}</span></p>
    <p class="sub_description">
        @if (Config::get('app.locale') == "en")
        {{ $stetho_sound->sub_description_en }}
        @else
        {{ $stetho_sound->sub_description }}
        @endif
    </p>
    <div class="sound_box_inner @if($stetho_sound->lib_type==1) body-img-container @endif">
        <!-- .sound_title -->
        <div id="@if($stetho_sound->lib_type==1)aus_{{$stetho_sound->id}}@endif" data-id="{{$stetho_sound->id}}"
            class="rec_ipax sound_accordion_open sound_title accordion_active aus_home @if(empty($stetho_sound->sound_path) || $stetho_sound->lib_type==1) aus @endif @if($stetho_sound->lib_type==1)ausculaide @endif"
            data-result="{{ json_encode($stetho_sound) }}">

            <p class="name">
                @if (Config::get('app.locale') == "en")
                {{$stetho_sound->title_en}}
                @else
                {{$stetho_sound->title}}
                @endif
            </p>
        </div>
        <!-- /.sound_title -->
        <!-- .audio -->
        @if(!empty($stetho_sound->sound_path) && $stetho_sound->lib_type!==1)
        <div class="audio">
            <div class="audiojsZ audiojsZ_{{$stetho_sound->id}}">
                <?php
                    $homesrc = $stetho_sound->sound_path.'?v='.session('version');
                    $dot = explode(".", $stetho_sound->sound_path);  // 音源を.で分割
                    $ext = ".".$dot[count($dot)-1]; // 音の拡張子
                ?>
                <?php
                    $infoPath = $stetho_sound->sound_path?pathinfo(public_path($stetho_sound->sound_path)):null;
                    $extension = $infoPath?$infoPath['extension']:"";
                    $muted = '';
                    if ($extension == "mp4" && $stetho_sound->is_video_show == 1) {
                        $muted = 'muted';
                    }
                ?>

                <audio id="ssid_{{ $stetho_sound->id }}" data-id="{{ $stetho_sound->id }}" data-lib_type="recommended" {{$muted}} preload="auto"
                    src="<?= $homesrc ?>"></audio>
                <div class="play-pauseZ">
                    <span class="line"></span>
                    @if($extension == "mp4" && $stetho_sound->is_video_show == 1)
                    <p class="playZ play_vid"
                        onclick="gtag('event', 'Click', {'event_category':'sound_{{$stetho_sound->id}}', 'event_label':'user_{{$user_id}}'});">
                    </p>
                    <p class="pauseZ_vid pauseZ_vid_{{$stetho_sound->id}}"></p>
                    @else
                    <p class="playZ"
                        onclick="gtag('event', 'Click', {'event_category':'sound_{{$stetho_sound->id}}', 'event_label':'user_{{$user_id}}'});">
                    </p>
                    <p class="pauseZ"></p>
                    @endif
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
        <!-- /.audio -->
        <!-- .sound_conts -->
        <div class="sound_conts accordion_conts">
            <div class="sound_conts_inner @if($stetho_sound->lib_type==1) ausc-img-container @endif">
                <div class="desc-container">
                <p class="text">
                    @if (Config::get('app.locale') == "en")
                    {!! $stetho_sound->description_en !!}
                    @else
                    {!! $stetho_sound->description !!}
                    @endif
                </p>
                </div>
                <!-- .img_slide -->
                <?php 
                $libClassName = "";
                switch ($stetho_sound->lib_type) {
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
                    $libClassName = "library_video";
                    break;
                }
            ?>
                <!-- creating video box if extension is mp4 -->
                @if(!empty($stetho_sound->sound_path) && $stetho_sound->lib_type!==1)
                @if($extension == 'mp4' && $stetho_sound->is_video_show == 1)
                <div class="vid_open" style="padding-top:100px; display:none" oncontextmenu="return false;">
                    <!-- video custom controls-->
                    <video class="{{$libClassName}}" playsinline loop muted width="530" height="240"
                        id="recommended_stetho_sound_video[{{ $stetho_sound->id }}]" controlslist="nodownload"
                        src="{{ $homesrc }}">
                </div>
                @endif
                @else
                <?php $sound = $stetho_sound; ?>
                @if(!empty($sound->body_image))
                <div id="recommended_ipax_cont-{{$sound->id}}" class="recommended_ipax_cont">
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
                            class="btn-switch-body <?php echo ($sound->type == 2)? 'btn-switch-body-disabled' : '';?>"
                            data-front_body="{{ $sound->body_image.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                            data-back_body="{{ $sound->body_image_back.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                            data-body="front" <?php echo ($sound->type == 2)? 'disabled' : '';?>>
                        </button>
                        <li>
                            <div id="aus-img_wrapper_{{$sound->id}}" class="aus-img_wrapper">
                                <div id="body-{{ $sound->id }}" class="bodyFrame" frameborder="0" data-size="1">
                                    <button id="ausculaide_load_btn_{{$sound->id}}"
                                        onClick="loadAus({{$sound->id}});" style="display:none;">Load
                                        Ausculaide</button>
                                </div>
                                <button id="reload_icon_{{$sound->id}}" class="btn-reload-icon"></button>
                        </li>
                        <div class="exit-fullscreen-overlay" style="display:none">
                            <div class="fullscreen-msg">
                                <div class="fullscreen-title-wrapper">
                                    <p class="fullscreen-msg-title">@lang('aus.fullscreen_exit')</p>
                                </div>
                                <div class="fullscreen-footer-wrapper">
                                    <div class="fullscreen-btn-wrapper __ok">
                                        <span class="fullscreen-btn fullscreen-btn-ok">@lang('aus.Ok')</span>
                                    </div>
                                    <div class="fullscreen-btn-wrapper __cancel">
                                        <span
                                            class="fullscreen-btn fullscreen-btn-cancel">@lang('aus.Cancel')</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                    </ul>
                    <div id="controller_slider__{{$sound->id}}" class="aus_controller_wrapper" style="display:none;">
                        <div class="audio_slider"></div>
                        <p class="slider_label">HR</p>
                    </div>
                    <div class="offline_switch_wrapper" data-id="{{$sound->id}}">
                          <input type="button"  class="offline-btn-switch switch btn-xs" data-id="{{$sound->id}}"/>
                          <input id="offline_switch_{{$sound->id}}" type="checkbox" class="offline_btn" data-toggle="switchbutton" data-onstyle="primary" data-id="{{$sound->id}}" data-size="xs" data-onlabel="@lang('aus.offline_text')" data-offlabel="@lang('aus.offline_text')"/>
                    </div>

                    <button id="sound_{{$sound->id}}" data-sounds="{{$sound->sound_path}}"></button>
                </div>
                </div>
                @endif

                <p class="aus-body_description">
                    @if (Config::get('app.locale') == "en")
                    {!! $sound->image_description_en !!}
                    @else
                    {!! $sound->image_description !!}
                    @endif
                </p>
                @endif
                @if(!empty($stetho_sound->video_path) && file_exists(public_path($stetho_sound->video_path)))
                <div class="bx-viewport-insp">
                    <!-- single video -->
                    <video class="{{$libClassName}}" id="library_video" data-id="{{$stetho_sound->id}}" width="530"
                        height="240" controls controlslist="nodownload"
                        src="{{ $stetho_sound->video_path.'?v='.session('version') }}">
                </div>
                @endif
                @if(!empty($stetho_sound->image_path))
                <div class="img_slide home-page">
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
                <div class="img_slide home-page">
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
                                    src="{{ $image->image_path.'?v='.session('version') }}"
                                    type="video/mp4">
                                    @else
                                    <img src="{{ $image->image_path.'?v='.session('version') }}"
                                        style="cursor: pointer;" class="sound_img" />
                                    @endif
                                    <p hidden="" id="sl_image_title">{{$image->title}}</p>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        @if($hasSwipe)
                        <script>
                        $('.bxslider_{{$stetho_sound->id}}').bxSlider();
                        </script>
                        @else
                        <script>
                        $('.bxslider_{{$stetho_sound->id}}').bxSlider({
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
                <!-- /.img_slide -->
                <!-- .table_desc -->
                <?php $is_normal_strings = [1 => trans('contents.ausc_snd_type.1'), 0 => trans('contents.ausc_snd_type.2')]; ?>
                <?php $conversion_type_strings = [0 => '採取オリジナル', 1 => '加工音', 2 => '人工音']; ?>

                @if($stetho_sound->lib_type != 1)
                <div class="desc_table">
                    <dl>
                        <dt>@lang('home.aus_sound') :</dt>
                        <dd>
                            @if (Config::get('app.locale') == "en")
                            {{$stetho_sound->title_en}}
                            @else
                            {{$stetho_sound->title}}
                            @endif
                            ({{ $stetho_sound->id }})
                        </dd>
                    </dl>
                    <dl>
                        <dt>@lang('home.aus_type') :</dt>
                        <dd>{{$is_normal_strings[$stetho_sound->is_normal]}}</dd>
                    </dl>
                    <dl>
                        <dt>@lang('home.disease') :</dt>
                        <dd>
                            @if (Config::get('app.locale') == "en")
                            {{$stetho_sound->disease_en}}
                            @else
                            {{$stetho_sound->disease}}
                            @endif
                        </dd>
                    </dl>
                    <dl>
                        <dt>@lang('home.aus_site') :</dt>
                        <dd>
                            @if (Config::get('app.locale') == "en")
                            {{$stetho_sound->area_en}}
                            @else
                            {{$stetho_sound->area}}
                            @endif
                        </dd>
                    </dl>
                </div>
                <!-- .sound_conts_inner -->
                @endif
            </div>
            <?php $is_favo_strings = [1 => trans('home.remove_fave_btn'), 0 => trans('home.register_fave_btn'), 3 => trans('home.fav_exceed')]; ?>
            <div class="clearfix fav_wrapper">
                <button class="FavButton" data-lib_type="{{$stetho_sound->lib_type}}" data-confirm="{{trans('home.do_process')}}" data-trans='{{json_encode($is_favo_strings)}}' data-id="{{ $stetho_sound->id }}" data-favo="{{ $stetho_sound->favo }}" data-aid="{{ $params['aid'] }}" data-type="{{ $stetho_sound->type }}">{{ $is_favo_strings[$stetho_sound->favo] }}</button>
            </div>
        </div>
        <!-- /.sound_conts -->
    </div>
</li>
<!-- /.sound_box -->
<?php $i++; ?>
@endforeach

<!--*********************************************************-->
</ul>

<div style="margin:20px 0px;"></div>
</div>
<div id="ausculaid_app_wrapper" style="display:none;">
      @include('bodymap.bodymap')
</div>

<script src="{{asset('js/common.js?v=1.2.0.20190125')}}"></script>
<script src="{{asset('js/jquery-ui-slider-pips.js')}}"></script>
<script src="{{asset('body-js/apps.js')}}"></script>
<script type="text/javascript" src="/js/bootstrap-switch-button.js?v=1.1.6.20210421"></script>
<script type="text/javascript" src="/js/offline.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.sound_box img').imagebox();

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

    // library video
    $(".library_video_stetho, .library_video_palpa, .library_video_inspi").on("play", function (e) {
      $( '#recommended_ausculaide' ).attr( 'src', function ( i, val ) { return val; });
      $('#btn-case-top').trigger("click");
      if($(this).attr("id") == "library_video") {
        var id = $(this).attr("data-id");
        var className = $(".audiojsZ_" + id).attr("class");
        if (className.includes("playingZ")) {
          $(".pauseZ_vid_" + id).trigger("click");
        }
        var v = $(this);
        $("video").each(function( index ) {
          if ($(v).attr("src") != $(this).attr("src")) {
            $(this).get(0).pause()
          }
        });
      } else {
        var v = $(this);
        $("video").each(function( index ) {
          if ($(v).attr("src") != $(this).attr("src")) {
            $(this).get(0).pause()
          }
        });
      }
    });

    $(".library_video").on("play", function (e) {
      $( '#recommended_ausculaide' ).attr( 'src', function ( i, val ) { return val; });
      $('#btn-case-top').trigger("click");
    });

  });
</script>