@extends('layouts.aus_app')

@section('title', 'Ausculaide')

@section('breadcrumb')
{!! Breadcrumbs::render('aus') !!}
@endsection

@section('content')

<?php
phpinfo();
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
                <input class="search_keyword" placeholder=@lang('contents.keyword_search') type="text" name="keyword" value="" />
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
                      {{trans('aus.pulse_modal_content')}}
                    </p>
                    <br><br>
                    <center>
                      <input type="checkbox" id="pulse_checkbox_{{$sound->id}}" class="pulse_checkbox">
                      <label for="pulse_checkbox_{{$sound->id}}">{{trans('aus.pulse_checkbox_label')}}</label>
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
                          $config = json_decode($sound->sound_path);

                          $a = isset($config->a_sound_path) ? $config->a_sound_path : "";
                          $p = isset($config->p_sound_path) ? $config->p_sound_path : "";
                          $t = isset($config->t_sound_path) ? $config->t_sound_path : "";
                          $m = isset($config->m_sound_path) ? $config->m_sound_path : "";
                          $h1 = isset($config->h1_sound_path) ? $config->h1_sound_path : "";
                          $h2 = isset($config->h2_sound_path) ? $config->h2_sound_path : "";
                          $h3 = isset($config->h3_sound_path) ? $config->h3_sound_path : "";
                          $h4 = isset($config->h4_sound_path) ? $config->h4_sound_path : "";

                          $isHeart = 0;
                          if ($a || $p || $t || $m || $h1 || $h2 || $h3 || $h4) {
                            $isHeart = 1;
                          }

                          $pa = isset($config->pa_sound_path) ? $config->pa_sound_path : "";
                          $pp = isset($config->pp_sound_path) ? $config->pp_sound_path : "";
                          $pt = isset($config->pt_sound_path) ? $config->pt_sound_path : "";
                          $pm = isset($config->pm_sound_path) ? $config->pm_sound_path : "";
                          $p1 = isset($config->p1_sound_path) ? $config->p1_sound_path : "";
                          $p2 = isset($config->p2_sound_path) ? $config->p2_sound_path : "";
                          $p3 = isset($config->p3_sound_path) ? $config->p3_sound_path : "";
                          $p4 = isset($config->p4_sound_path) ? $config->p4_sound_path : "";

                          $isPulse = 0;
                          if ($pa || $pp || $pt || $pm || $p1 || $p2 || $p3 || $p4) {
                            $isPulse = 1;
                          }

                          $tr1 = isset($config->tr1_sound_path) ? $config->tr1_sound_path : "";
                          $tr2 = isset($config->tr2_sound_path) ? $config->tr2_sound_path : "";
                          $br1 = isset($config->br1_sound_path) ? $config->br1_sound_path : "";
                          $br2 = isset($config->br2_sound_path) ? $config->br2_sound_path : "";
                          $br3 = isset($config->br3_sound_path) ? $config->br3_sound_path : "";
                          $br4 = isset($config->br4_sound_path) ? $config->br4_sound_path : "";

                          $isLung = 0;
                          if ($tr1 || $tr2 || $br1 || $br2 || $br3 || $br4) {
                            $isLung = 1;
                          } else {
                            for ($i = 1; $i <= 12; $i++) {
                              $ve = isset($config->{"ve" . $i . "_sound_path"}) ? $config->{"ve" . $i . "_sound_path"} : "";
                              if ($ve) {
                                $isLung = 1;
                              }
                            };
                          }
                          
                        ?>
                        <button id="heart_icon_{{$sound->id}}" class="btn-heart btn-icon btn-heart-grey-icon" <?php echo $isHeart ? "" : "disabled"; ?> ></button>
                        @if($sound->type == 2) <!-- if heart -->
                          <button id="pulse_icon_{{$sound->id}}" class="btn-pulse btn-icon btn-pulse-grey-icon" <?php echo $isPulse ? "" : "disabled"; ?> ></button>
                        @endif
                        <button id="lung_icon_{{$sound->id}}" class="btn-lung btn-icon btn-lung-grey-icon" <?php echo $isLung ? "" : "disabled"; ?> ></button>
                      </span>
                      <ul class="main_body_container" id="aus-fullscreen-wrapper_{{$sound->id}}">
                        <button id="btn-fullscreen-off" class="btn-icon aus-fullscreen-off" style="display:none">
                          @lang('aus.zoom_out')
                        </button>
                        <button id="bswitch_{{$sound->id}}" class="btn-switch-body <?php echo ($sound->type == 2)? 'btn-switch-body-disabled' : '';?>"
                          data-front_body="{{ $sound->body_image.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                          data-back_body="{{ $sound->body_image_back.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}"
                          data-body="front"
                          <?php echo ($sound->type == 2)? 'disabled' : '';?>
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
                      
                      <div id="controller_slider__{{$sound->id}}" class="aus_controller_wrapper"> 
                      <!-- <button id="point_a_{{$sound->id}}" data-point="a" class="btn-point btn-point_{{$sound->id}}">A</button>
                      <button id="point_p_{{$sound->id}}" data-point="p" class="btn-point btn-point_{{$sound->id}}">P</button>
                      <button id="point_t_{{$sound->id}}" data-point="t" class="btn-point btn-point_{{$sound->id}}">T</button>
                      <button id="point_m_{{$sound->id}}" data-point="m" class="btn-point btn-point_{{$sound->id}}">M</button>

                      </br> -->
                      <div class="audio_slider"></div>
                      <p class="slider_label">HR</p>
                      </div>

                      <button id="sound_{{$sound->id}}" data-sounds="{{$sound->sound_path}}"></button>
                  </div>
                  @endif
                  @if($sound->images->count())
                    <div class="img_slide">
                      <div class="img_slide_inner">
                        <ul class="bxslider">
                          @foreach($sound->images as $image)
                            @if(Config::get('app.locale') == $image->lang)
                              <li>
                               
                                @if (strpos($image['image_path'], 'mp4'))
                                  <video id="library_video" controls controlslist="nodownload" disablepictureinpicture width="100%"
                                    onplay="gtag('event', 'Click', {'event_category':'aus_playback_{{$sound->id}}', 'event_label':'user_{{$user_id}}'});"
                                  >
                                    <source src="{{ $image->image_path.'?_='.date('YmdHis', strtotime($image->updated_at)) }}" type="video/mp4">
                                  </video>
                                @else
                                <img loading="lazy" src="{{ $image->image_path.'?_='.date('YmdHis', strtotime($image->updated_at)) }}" style="cursor: pointer;" class="sound_img" />
                                @endif
                             
                              </li>
                            @endif
                          <!-- TODO: ???????????????-->
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  @elseif (!empty($sound->explanatory_image)||!empty($sound->explanatory_image_en))
                      <?php 
                        $explanatory_file = [];
                        $explanatory_file = (Config::get('app.locale') == "en")? json_decode($sound->explanatory_image_en) : json_decode($sound->explanatory_image);
                        $explanatory = (Config::get('app.locale') == "en") ? $sound->explanatory_image_en : $sound->explanatory_image;
                        if($explanatory && !$explanatory_file){
                          //convert old path string to array
                          $explanatory_file=[];
                          array_push($explanatory_file,$explanatory);
                        }elseif(!$explanatory && !$explanatory_file){
                            $explanatory_file=[];
                        }
                      ?>
                      <div class="img_slide">
                        <div class="img_slide_inner">
                          <ul class="bxslider">
                            @foreach($explanatory_file as $image)
                            <li>
                              <div class="slider-container">
                              @if (strpos($image, 'mp4'))
                              <video id="library_video" controls controlslist="nodownload" disablepictureinpicture width="100%">
                                <source src="{{ $image.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}" type="video/mp4">
                              </video>
                              @else
                              <img loading="lazy" src="{{ $image.'?_='.date('YmdHis', strtotime($sound->updated_at)) }}" style="cursor: pointer;" class="sound_img" />
                              @endif
                              </div>
                            </li>
                            @endforeach
                          </ul>
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
                ???????????????????????????????????????????????????????????????
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
        <h3 class="side_title">???????????????????????????</h3>
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
      <!-- ????????????????????????????????? -->
      @include('layouts.whatspeaker')
      <!-- ???????????????????????? -->
      @include('layouts.advertisement')
    </div>
    <!----------------------------------- /.side_column --------------------------------- -->

  </div>
  <!-- /#container_inner -->
  <!-- </div> -->
  <!-- /#container -->


  <div id="overlay"></div><!-- /#overlay -->
</div>

<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.6.20170323"></script>
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
  $(".tag").each(function() { // ??????
    var favo = $(this).data('favo'); // ?????????????????????
    if (favo == 1) { // ?????????????????????????????????????????????
      $(this).css('background-image', 'url(../img/tag-favo.png)');
    } else {
      $(this).css('background-image', 'url(../img/tag-aus.png)');
    }
  });

  $(".FavButton").each(function() { // ??????
    var favo = $(this).data('favo'); // ?????????????????????

    if (favo == 1) { // ?????????????????????????????????????????????
      $(this).css('background', '#eeeeee');
      $(this).css('border-color', '#eeeeee');
      $(this).css('color', '#000000');
      $(this).css('background-image', 'url(../img/no-delete.png)');
      $(this).css('background-repeat', 'no-repeat');
      $(this).css('background-position', '8% 46%');
    }
  });
  $("video").on("play", function (e) {
    console.debug("Video paused. Current time of videoplay: " + e.target.currentTime );
    var v = $(this);
    $("video").each(function( index ) {
      if ($(v).attr("src") != $(this).attr("src")) {
        $(this).get(0).pause()
      }
    });
  });
</script>
<style>
.bodyFrame{
  width:100%;
  height:450px;
}
</style>
@endsection