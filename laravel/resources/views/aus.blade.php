@extends('layouts.app')

@section('title', 'Ausculaide')

@section('breadcrumb')
{!! Breadcrumbs::render('aus') !!}
@endsection

@section('content')

<?php
$user_id=Session::get('MEMBER_3SP_ACCOUNT_ID');
$root_class = "contents";
if (count($stetho_sounds) <= 4) {
    $root_class = "btm_padding_contents";
}
?>
<div id="container">
  <div class="container_inner clearfix">
    <!----------------------------------- .contents --------------------------------- -->
    <div class="{{$root_class}}">
      <div class="contents_title clearfix">
        <h2>@lang('aus.title')
        </h2><span class="title_line"></span>
      </div>
      <!-- .contents_search -->
      <form action="{{route('aus')}}" method="get">
        <div class="contents_search">
          <input type="hidden" name="isNormal" value="{{Input::get('isNormal')}}">
          <!-- .select_tag -->
          <ul class="select_tag pc_none append_list">
            <li><a href="?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('isNormal') == '' ? 'active' : '' }}">@lang('contents.unspecified')</a></li>
            <li><a href="?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=1" class="{{Input::get('isNormal') == '1' ? 'active' : '' }}">@lang('contents.normal_sound')</a></li>
            <li><a href="?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=0" class="{{Input::get('isNormal') == '0' ? 'active' : '' }}">@lang('contents.abnormal_sound')</a></li>
          </ul>
          <!-- /.select_tag -->

          <!-- .total_result -->
          <div class="total_result clearfix">
            <!-- .select_sort -->
            <p class="select_sort" style="margin-left:10px;">
              <select name="sort">
                <option value="desc" {{ Input::get('sort') != 'asc' ? 'selected' : '' }}>@lang('contents.dropdown_new_arrival')</option>
                <option value="asc" {{ Input::get('sort') == 'asc' ? 'selected' : '' }}>@lang('contents.dropdown_oldest')</option>
              </select>
            </p>
            <p class="select_sort">
              <select name="filter">
                <option value="" {{ Input::get('filter') == '' ? 'selected' : '' }}>@lang('contents.dropdown_all')</option>
                <option value="2" {{ Input::get('filter') == '2' ? 'selected' : '' }}>@lang('contents.dropdown_heart')</option>
                <option value="1" {{ Input::get('filter') == '1' ? 'selected' : '' }}>@lang('contents.dropdown_lung')</option>
              </select>
            </p>
            <!-- .select_show -->
            <p class="select_sort">
              <select name="show">
                <option value="10" {{ Input::get('show') == '10' ? 'selected' : '' }}>@lang('contents.dropdown_10')</option>
                <option value="30" {{ Input::get('show') == '30' ? 'selected' : '' }}>@lang('contents.dropdown_30')</option>
                <option value="50" {{ Input::get('show') == '50' ? 'selected' : '' }}>@lang('contents.dropdown_50')</option>
              </select>
            </p>

            <div class="search_box">
              <p class="search_box_inner clearfix">
                <input maxlength="200" class="search_keyword" placeholder=@lang('contents.keyword_search') type="text" name="keyword" value="" />
                <button class="search_btn"></button>
              </p>
            </div>
            <!-- /.search_box -->

            <script type="text/javascript">
              ;
              (function($) {
                'use strict';
                $('select[name=sort]').on('change', function(e) {
                  var sort = $(this).val();
                  $(this).closest('form').submit();
                });
              })(jQuery);
            </script>
            <!-- /.select_sort -->
            <script type="text/javascript">
              ;
              (function($) {
                'use strict';
                $('select[name=filter]').on('change', function(e) {
                  var filter = $(this).val();
                  $(this).closest('form').submit();
                });
              })(jQuery);
            </script>
            <!-- /.select_filter -->
            <script type="text/javascript">
              ;
              (function($) {
                'use strict';
                $('select[name=show]').on('change', function(e) {
                  var show = $(this).val();
                  $(this).closest('form').submit();
                });
              })(jQuery);
            </script>
            <!-- /.select_show -->
          </div>
          <!-- /.total_result -->
        </div>
      </form>
      
      <!-- /.contents_search -->
      <!-- .sound_list -->
      <ul class="sound_list accordion">

        <!--*********************************************************-->
        @forelse($stetho_sounds as $sound)
        <!-- .sound_box -->
        <li class="sound_box aus-sound_box" data-id="{{ $sound->id }}">
          <p class="tag" id="{{ $sound->id }}" data-favo="{{ $sound->favo }}" data-lib_type="{{$sound->lib_type}}" style="bacground-image:url(../img/tag-aus.png) !important;">
            <span>{{trans('list.tag-aus')}}</span>
          </p>
          <div class="body-img-container sound_box_inner">
            <!-- .sound_title -->
            <div id="aus_{{$sound->id}}" class="sound_accordion_open sound_title accordion_active aus ausculaide" data-id="{{$sound->id}}" data-result="{{ json_encode($sound) }}">
              <p class="name" id="name_{{ $sound->id }}">
                @if (Config::get('app.locale') == "en")
                {{$sound->title_en}}
                @else
                {{$sound->title}}
                @endif
              </p>
            </div>
            <!-- .sound_conts -->
            <div class="sound_conts accordion_conts">
                <!-- Pulse Modal -->
                <div id="pulseModal_{{$sound->id}}" class="pulse-modal">
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
                <div class="desc-container">
                  <p class="text">
                    @if (Config::get('app.locale') == "en")
                    {!! $sound->description_en !!}
                    @else
                    {!! $sound->description !!}
                    @endif
                  </p>
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
                      <div class="offline_switch_wrapper" data-id="{{$sound->id}}">
                          <input type="button"  class="offline-btn-switch switch btn-xs" data-id="{{$sound->id}}"/>
                          <input id="offline_switch_{{$sound->id}}" type="checkbox" class="offline_btn" data-toggle="switchbutton" data-onstyle="primary" data-id="{{$sound->id}}" data-size="xs" data-onlabel="@lang('aus.offline_text')" data-offlabel="@lang('aus.offline_text')"/>
                      </div>
                      <button id="sound_{{$sound->id}}" data-sounds="{{$sound->sound_path}}"></button>
                  </div>
                  @endif
                  <?php 
                    $hasSwipe = 0;
                    $imageCount = (Config::get('app.locale') == "en") ? $sound->images_en->count() : $sound->images_ja->count();
                    if(Config::get('app.locale') == "en") {
                      if ($sound->images_en->count() > 1) {
                        $hasSwipe = 1;
                      }
                    } else {
                      if ($sound->images_ja->count() > 1) {
                        $hasSwipe = 1;
                      }
                    }
                  ?>
                  @if($sound->images->count())
                    <div class="img_slide">
                      <div class="img_slide_inner">
                        <ul class="bxslider_{{$sound->id}}">
                          @foreach($sound->images as $image)
                            @if(Config::get('app.locale') == $image->lang)
                              <li>
                               
                                @if (strpos($image['image_path'], 'mp4'))
                                  <video playsinline id="library_video" controls controlslist="nodownload" disablepictureinpicture width="100%"
                                    onplay="gtag('event', 'Click', {'event_category':'aus_playback_{{$sound->id}}', 'event_label':'user_{{$user_id}}'});"
                                    src="{{ $image->image_path.'?v='.session('version') }}" 
                                  >
                                  </video>
                                @else
                                <img loading="lazy" src="{{ $image->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="sound_img" />
                                @endif
                             
                              </li>
                            @endif
                          <!-- TODO: 画面説明　-->
                          @endforeach
                        </ul>
                        @if($imageCount > 0)
                          @if($hasSwipe)
                            <script>
                              $('.bxslider_{{$sound->id}}').bxSlider({
                                onSliderLoad: function(currentIndex) {
                                      if (this.getSlideCount() == 0) {
                                          $(this).closest(".img_slide").find($(".bx-prev")).hide();
                                          $(this).closest(".img_slide").find($(".bx-next")).hide();
              
                                      }
                                  }
                              });
                            </script>
                          @else
                            <script>
                              $('.bxslider_{{$sound->id}}').bxSlider({
                                touchEnabled: false,
                                oneToOneTouch: false,
                                onSliderLoad: function(currentIndex) {
                                      if (this.getSlideCount() == 0) {
                                          $(this).closest(".img_slide").find($(".bx-prev")).hide();
                                          $(this).closest(".img_slide").find($(".bx-next")).hide();
              
                                      }
                                  }
                              });
                            </script>
                          @endif
                        @endif
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
                  <?php $is_favo_strings = [1 => trans('home.remove_fave_btn'), 0 => trans('home.register_fave_btn'), 3 => trans('home.fav_exceed')]; ?>
                  <div class="clearfix fav_wrapper">
                    <button class="FavButton" 
                      data-lib_type="{{$sound->lib_type}}" 
                      data-confirm="{{trans('home.do_process')}}" 
                      data-trans='{{json_encode($is_favo_strings)}}' 
                      data-id="{{ $sound->id }}" 
                      data-favo="{{ $sound->favo }}" 
                      data-aid="{{ $params['aid'] }}" 
                      data-type="{{ $sound->type }}">
                      {{ $is_favo_strings[$sound->favo] }}
                    </button>
                  </div>
                </div>
                <!-- .sound_conts_inner -->

              </div>
              <!-- /.sound_conts -->
            </div>
        </li>
        <!-- /.sound_box -->
        @empty
        <li class="sound_box">
          <div class="sound_box_inner">
            <p class="sound_box_empty_msg">
              @if(config('app.locale') == 'en') 
                No content found.
              @else 
                一致するコンテンツは見つかりませんでした。
              @endif
            </p>
          </div>
        </li>

        @endforelse

      </ul>
      <!-- /.sound_list -->

      <!-- .pager -->
      <div class="pager">
        <div class="pager_inner">
          {!! (new App\Http\Paginators\PagingPaginator(
          $stetho_sounds->appends([
          'show'=>Input::get('show','10'),
          'keyword'=>Input::get('keyword',''),
          'filter'=>Input::get('filter',''),
          'sort'=>Input::get('sort','')
          ])
          ))->render()
          !!}
        </div>
      </div>
      <!-- /.pager -->

    </div>
    <!-- /.contents -->

    <!----------------------------------- /.contents --------------------------------- -->



    <!----------------------------------- .side_column --------------------------------- -->
    <div class="side_column">

      <!-- .side_box contents_list -->
      <div class="side_box mB20 sp_none ausculaide_list">
        <!--
        <h3 class="side_title">聴くゾウライブラリ</h3>
-->
        <h3 class="side_title">@lang('contents.side_title')</h3>
        <!-- .contents_list -->
        <ul class="contents_list append_list">
          <li><a href="{{route('aus')}}?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('isNormal') == '' ? 'active' : '' }}">@lang('contents.unspecified')</a></li>
          <li><a href="?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=1" class="{{Input::get('isNormal') == '1' ? 'active' : '' }}">@lang('contents.normal_sound')</a></li>
          <li><a href="?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=0" class="{{Input::get('isNormal') == '0' ? 'active' : '' }}">@lang('contents.abnormal_sound')</a></li>
          <?php
          ?>
        </ul>
        <!-- /.contents_list -->
      </div>
      <!-- /.side_box -->
      <!-- 聴診スピーカーについて -->
      @include('layouts.whatspeaker')
      <!-- スポンサーリンク -->
      @include('layouts.advertisement')
    </div>
    <!----------------------------------- /.side_column --------------------------------- -->

  </div>
  <!-- /#container_inner -->
  <!-- </div> -->
  <!-- /#container -->


  <div id="overlay"></div><!-- /#overlay -->

  <div id="ausculaid_app_wrapper" style="display:none;">
      @include('bodymap.bodymap')
  </div>
</div>

<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.6.20170323"></script>
<script type="text/javascript" src="/js/bootstrap-switch-button.js?v=1.1.6.20210421"></script>
<script type="text/javascript" src="/js/offline.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.sound_box img').imagebox();

    // auto scroll to aus content when exit fullscreen
    $(".__ok").on("click", function (e) {
      var el = document.getElementById("name_" + $(this).data("id"));
      el.scrollIntoView(true);
    });
  });
</script>    
<script>
  $('iframe').load( function() {	
    $("iframe").contents().find('video')
      .attr("controlslist", "nodownload")
      .removeAttr("autoplay")
      .attr("height", "100%")
      .attr("width", "100%")
      .attr("disablepictureinpicture", "");	
    $("iframe").contents().find('body').attr("oncontextmenu", "return false;");	
  });
  $(".tag").each(function() { // タグ
    var favo = $(this).data('favo'); // お気に入り状態
    if (favo == 1) { // お気に入りに登録されている場合
      $(this).css('background-image', 'url(../img/tag-favo.png)');
    } else {
      $(this).css('background-image', 'url(../img/tag-aus.png)');
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
  $("video").on("play", function (e) {
    var v = $(this);
    $("video").each(function( index ) {
      if ($(v).attr("src") != $(this).attr("src")) {
        $(this).get(0).pause()
      }
    });
  });
</script>
<script>
if ('serviceWorker' in navigator) {
  var CACHE_NAME = "aus-cache{{session('cache_version')}}";
  caches.keys().then(function(cacheNames) {
    return Promise.all(
      cacheNames.map(function(cacheName) {
        if(cacheName != CACHE_NAME) {
          return caches.delete(cacheName);
        }
      })
    );
  });
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw.js?cache_version='+CACHE_NAME).then(function(registration) {
      // Registration was successful
      //console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }, function(err) {
      // registration failed :(
      //console.log('ServiceWorker registration failed: ', err);
    });
  });
}
</script>
<style>
.bodyFrame{
  width:100%;
  height:450px;
}
</style>
@endsection