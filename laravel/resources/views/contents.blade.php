@extends('layouts.app')

@section('title', 'Contents')

@section('breadcrumb')
{!! Breadcrumbs::render('contents') !!}
@endsection

@section('content')

<?php
  $root_class = "contents";
  if (count($stetho_sounds) <= 4) {
    $root_class = "btm_padding_contents";
  }
  $user_id=Session::get('MEMBER_3SP_ACCOUNT_ID');
?>
<div id="container">
  <div class="container_inner clearfix">

    <!----------------------------------- .contents --------------------------------- -->
    <div class="{{$root_class}}">
      <div class="contents_title clearfix">
        <h2>@lang('contents.title')
          <!-- .total_number -->
          <!-- <span style="font-size:18px;">{{$stetho_sounds->total()}} @lang('contents.cases')</span> -->
          <!-- /.total_number -->
        </h2><span class="title_line"></span>
      </div>
      <!-- .contents_search -->
      <form action="{{route('contents')}}" method="get">
        <div class="contents_search">

          <input type="hidden" name="isNormal" value="{{Input::get('isNormal')}}">
          <!-- .select_tag -->
          <ul class="select_tag pc_none append_list">
            <li><a href="{{route('contents')}}?lib_type={{Input::get('lib_type')}}&filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('isNormal') == '' ? 'active' : '' }}">@lang('contents.unspecified')</a></li>
            <li><a href="?lib_type={{Input::get('lib_type')}}&filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=1" class="{{Input::get('isNormal') == '1' ? 'active' : '' }}">@lang('contents.normal_sound')</a></li>
            <li><a href="?lib_type={{Input::get('lib_type')}}&filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=0" class="{{Input::get('isNormal') == '0' ? 'active' : '' }}">@lang('contents.abnormal_sound')</a></li>
            <?php
            /*
            <li><a href="{{route('contents')}}?show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == '' ? 'active' : '' }}">全て</a></li>
            <li><a href="?filter=2&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 2 ? 'active' : '' }}">心音</a></li>
            <li><a href="?filter=1&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 1 ? 'active' : '' }}">肺音</a></li>
            <li><a href="?filter=3&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 3 ? 'active' : '' }}">腸音</a></li>
            <li><a href="?filter=9&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 9 ? 'active' : '' }}">その他</a></li>
*/
            ?>
          </ul>
          <!-- /.select_tag -->

          <!-- .total_result -->
          <div class="total_result clearfix">
            <!-- .total_number -->
            <?php
            /*
            <p class="total_number">Total:<span style="font-size:18px;">{{$stetho_sounds->total()}}</span>件</p>
*/
            ?>
            <!-- .select_palpation -->
            <div class="palpation-filter">
              <p class="select_sort select_lib_type" style="margin-left:10px;">
                <select name="lib_type" value="">
                  <option disabled="disabled" selected="selected" value="">@lang('contents.dropdown_category')</option>
                  <option value="0" {{ Input::get('lib_type') === "0" ? 'selected' : '' }}>@lang('contents.dropdown_ausculaide')</option>
                  <option value="2" {{ Input::get('lib_type') === "2" ? 'selected' : '' }}>@lang('contents.dropdown_palpation')</option>
                  <option value="3" {{ Input::get('lib_type') === "3" ? 'selected' : '' }}>@lang('contents.dropdown_ecg')</option>
                  <option value="4" {{ Input::get('lib_type') === "4" ? 'selected' : '' }}>@lang('contents.dropdown_visual_exam')</option>
                  <option value="5" {{ Input::get('lib_type') === "5" ? 'selected' : '' }}>@lang('contents.dropdown_x_ray')</option>
                  <option value="6" {{ Input::get('lib_type') === "6" ? 'selected' : '' }}>@lang('contents.dropdown_echo')</option>
                </select>
              </p>
            </div>


            <!-- /.total_number -->
            <!-- .select_sort -->
            <p class="select_sort" style="margin-left:10px;">
              <select name="sort">
                <option value="desc" {{ Input::get('sort') != 'asc' ? 'selected' : '' }}>@lang('contents.dropdown_new_arrival')</option>
                <option value="asc" {{ Input::get('sort') == 'asc' ? 'selected' : '' }}>@lang('contents.dropdown_oldest')</option>
              </select>
            </p>

            <!-- .select_filter -->
            @if((Input::get('lib_type') == 0 )|| (Input::get('lib_type') == 1 ))
            <p class="select_sort">
              <select name="filter">
                <option value="" {{ Input::get('filter') == '' ? 'selected' : '' }}>@lang('contents.dropdown_all')</option>
                <option value="2" {{ Input::get('filter') == '2' ? 'selected' : '' }}>@lang('contents.dropdown_heart_2')</option>
                <option value="1" {{ Input::get('filter') == '1' ? 'selected' : '' }}>@lang('contents.dropdown_lung_2')</option>
                <option value="3" {{ Input::get('filter') == '3' ? 'selected' : '' }}>@lang('contents.dropdown_bowel')</option>
                <option value="9" {{ Input::get('filter') == '9' ? 'selected' : '' }}>@lang('contents.dropdown_other')</option>
              </select>
            </p>
            @endif
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
            <!-- /.select_lib_type -->
            <script type="text/javascript">
              ;
              (function($) {
                'use strict';
                $('select[name=lib_type]').on('change', function(e) {
                  var filter = $(this).val();
                  $(this).closest('form').submit();
                });
              })(jQuery);
            </script>
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
        <?php $type_strings = [0 => trans('list.tag-stetho'), 1 => trans('list.tag-aus'), 2 => trans('list.tag-palp'), 3 => trans('list.tag-ecg'), 4 => trans('list.tag-ins') , 5 => trans('list.tag-xray'), 6 => trans('list.tag-echo')]; ?>
        <!-- .sound_box -->
        <li class="sound_box @if(empty($sound->sound_path)) aus-sound_box @endif" data-id="{{ $sound->id }}">
          <!-- TODO:1:肺音、2:心音、3:腸音、9:その他 -->
          <p class="tag" id="{{ $sound->id }}" data-favo="{{ $sound->favo }}" data-lib_type="{{$sound->lib_type}}"><span>{{$type_strings[$sound->lib_type]}}</span></p>
          <p class="sub_description">
            @if (Config::get('app.locale') == "en")
            {{ $sound->sub_description_en }}
            @else
            {{ $sound->sub_description }}
            @endif
          </p>
          <div class="sound_box_inner">

            <!-- .sound_title -->
            <div class="sound_accordion_open sound_title accordion_active @if(empty($sound->sound_path)) aus @endif">
              <p class="name">
                @if (Config::get('app.locale') == "en")
                {{$sound->title_en}}
                @else
                {{$sound->title}}
                @endif
              </p>
            </div>
            <!-- /.sound_title -->
            <!-- .audio -->
            @if(!empty($sound->sound_path))
            <div class="audio">
              <div class="audiojsZ audiojsZ_{{$sound->id}}">
                <audio id="ssid_{{ $sound->id }}" data-id="{{ $sound->id }}" preload="{{ $params['preload'] }}" src="{{ $sound->sound_path.'?v='.session('version') }}"></audio>
                <div class="play-pauseZ">
                  <span class="line"></span>
                  <?php 
                    $infoPath = pathinfo($sound->sound_path);
                    $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "";
                  ?>
                  @if($sound->is_video_show == 1 && $extension != "mp3" && $extension != "")
                  <p class="playZ play_vid" id="play-vid" onclick="gtag('event', 'Click', {'event_category':'sound_{{$sound->id}}', 'event_label':'user_{{$user_id}}'});"></p>
                  <p class="pauseZ_vid pauseZ_vid_{{$sound->id}}"></p>
                  @else
                  <p class="playZ" id="play-sound" onclick="gtag('event', 'Click', {'event_category':'sound_{{$sound->id}}', 'event_label':'user_{{$user_id}}'});"></p>
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
              <div class="sound_conts_inner">
                <!-- <p class="sound_conts_inner_title">
                  @if (Config::get('app.locale') == "en")
                  {{$sound->title_en}}
                  @else
                  {{$sound->title}}
                  @endif
                </p> -->
                <?php // HTMLのタグを出力  
                ?>
                <p class="text">
                  @if (Config::get('app.locale') == "en")
                  {!! $sound->description_en !!}
                  @else
                  {!! $sound->description !!}
                  @endif
                </p>
                <!-- .img_slide -->
                @if(!empty($sound->sound_path) && file_exists(public_path($sound->sound_path)) && $sound->lib_type == 0)
                  <?php 
                    $infoPath = pathinfo($sound->sound_path);
                    $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "";
                  ?>
                  @if ($sound->is_video_show == 1 && $extension != "mp3" && $extension != "")
                    <div class="vid_open" style="padding-top:16px; display:none" oncontextmenu="return false;">
                      <video playsinline loop muted width="530" height="240" id="stetho_sound_video[{{ $sound->id }}]" controlslist="nodownload" src="{{ $sound->sound_path.'?v='.session('version') }}">
                    </div>
                  @endif
                @endif
                @if(($sound->lib_type == 2 || $sound->lib_type == 4 || $sound->lib_type == 6))
                  <!-- autoplay video -->
                  @if (!empty($sound->sound_path) && file_exists(public_path($sound->sound_path)) ) 
                    <?php 
                      $infoPath = pathinfo($sound->sound_path);
                      $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "";
                    ?>
                    @if ($sound->is_video_show == 1 && $extension != "mp3" && $extension != "")
                      <div class="vid_open" style="padding-top:16px; display:none" oncontextmenu="return false;">
                        <video playsinline loop muted width="530" height="240" id="stetho_sound_video[{{ $sound->id }}]" controlslist="nodownload" src="{{ $sound->sound_path.'?v='.session('version') }}">
                      </div>
                      <br>
                    @endif
                  @endif
                  <!-- standalone video -->
                  @if (!empty($sound->video_path) && file_exists(public_path($sound->video_path)) ) 
                    <div class="bx-viewport-insp ">
                      <video playsinline id="library_video" data-id="{{$sound->id}}" width="530" height="240" controls controlslist="nodownload" src="{{ $sound->video_path.'?v='.session('version') }}">
                    </div>
                    <div class="single-img-pager-container" style="display: block;">
                      <div class="img-pager">
                        <div class="img-pager-item">
                          <a href="" data-slide-index="0" class="img-pager-link active">1</a>
                        </div>
                      </div>
                    </div> 
                  @endif
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

                @if(!empty($sound->image_path))
                <div class="img_slide">
                  <div class="img_slide_inner">
                    <ul class="bxslider_{{$sound->id}}">
                      <li class="img-slider-container">
                        <img src="{{ $sound->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="sound_img" />
                      </li>
                    </ul>
                    @if($imageCount > 0)
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
                  </div>
                </div>
                <div class="single-img-pager-container" style="display: block;">
                  <div class="img-pager">
                    <div class="img-pager-item">
                      <a href="" data-slide-index="0" class="img-pager-link active">1</a>
                    </div>
                  </div>
                </div> 
                @endif

                @if($sound->images->count())
                <div class="img_slide">
                  <div class="img_slide_inner">
                    <ul class="bxslider_{{$sound->id}}">
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
                  @if (Config::get('app.locale') == "en")
                      @if($sound->images->first()->title_en != null )
                        <p class="img_slider_text" @if(!$sound->images->first()->title) hidden @endif id="image_title">
                          {{$sound->images->first()->title_en}}
                        </p>
                     @endif
                  @else
                    @if($sound->images->first()->title != null )
                      <p class="img_slider_text" @if(!$sound->images->first()->title) hidden @endif id="image_title">
                        {{$sound->images->first()->title}}
                      </p>
                    @endif
                  @endif
                </div>
                @endif
                <!-- /.img_slide -->
                <!-- .table_desc -->
                <?php $is_normal_strings = [1 => trans('contents.ausc_snd_type.1'), 0 => trans('contents.ausc_snd_type.2')]; ?>
                <?php $conversion_type_strings = [0 => '採取オリジナル', 1 => '加工音', 2 => '人工音']; ?>
                <div class="desc_table">
                  <dl>
                    <dt>@lang('home.aus_sound') : {{ $sound->id }}</dt>
                    <dd>
                      @if (Config::get('app.locale') == "en")
                      {{$sound->title_en}}
                      @else
                      {{$sound->title}}
                      @endif
                    </dd>
                  </dl>
                  <dl>
                    <dt>@lang('home.aus_type') :</dt>
                    <dd>{{$is_normal_strings[$sound->is_normal]}}</dd>
                  </dl>
                  <dl>
                    <dt>@lang('home.disease') :</dt>
                    <dd>
                      @if (Config::get('app.locale') == "en")
                      {{$sound->disease_en == "" ? '-' : $sound->disease_en}}
                      @else
                      {{$sound->disease == "" ? '-' : $sound->disease }}
                      @endif
                    </dd>
                  </dl>
                  <dl>
                    <dt>@lang('home.aus_site') :</dt>
                    <dd>
                      @if (Config::get('app.locale') == "en")
                      {{$sound->area_en == "" ? '-' : $sound->area_en}}
                      @else
                      {{$sound->area  == "" ? '-' : $sound->area}}
                      @endif
                    </dd>
                  </dl>
                </div>
                <!-- /.table_desc -->
              </div>
              <!-- .sound_conts_inner -->

              <?php $is_favo_strings = [1 => trans('home.remove_fave_btn'), 0 => trans('home.register_fave_btn'), 3 => trans('home.fav_exceed')]; ?>
              <div class="clearfix fav_wrapper">
                <button class="FavButton" data-lib_type="{{$sound->lib_type}}" data-confirm="{{trans('home.do_process')}}" data-trans='{{json_encode($is_favo_strings)}}' data-id="{{ $sound->id }}" data-favo="{{ $sound->favo }}" data-aid="{{ $params['aid'] }}" data-type="{{ $sound->type }}">{{ $is_favo_strings[$sound->favo] }}</button>
              </div>

            </div>
            <!-- /.sound_conts -->
          </div>
          <!-- /.sound_box_inner -->
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
          'lib_type'=>Input::get('lib_type',''),
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
      <div class="side_box mB20 sp_none contents_list">
        <!--
        <h3 class="side_title">聴くゾウライブラリ</h3>
-->
        <h3 class="side_title">@lang('contents.side_title')</h3>
        <!-- .contents_list -->
        <ul class="contents_list append_list">
          <li><a href="{{route('contents')}}?lib_type={{Input::get('lib_type')}}&filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('isNormal') == '' ? 'active' : '' }}">@lang('contents.unspecified')</a></li>
          <li><a href="?lib_type={{Input::get('lib_type')}}&filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=1" class="{{Input::get('isNormal') == '1' ? 'active' : '' }}">@lang('contents.normal_sound')</a></li>
          <li><a href="?lib_type={{Input::get('lib_type')}}&filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&isNormal=0" class="{{Input::get('isNormal') == '0' ? 'active' : '' }}">@lang('contents.abnormal_sound')</a></li>
          <?php
          /*
          <li><a href="{{route('contents')}}?show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == '' ? 'active' : '' }}">全て</a></li>
          <li><a href="?filter=2&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 2 ? 'active' : '' }}">心音</a></li>
          <li><a href="?filter=1&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 1 ? 'active' : '' }}">肺音</a></li>
          <li><a href="?filter=3&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 3 ? 'active' : '' }}">腸音</a></li>
          <li><a href="?filter=9&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}" class="{{Input::get('filter') == 9 ? 'active' : '' }}">その他</a></li>
*/
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

</div>

<script type="text/javascript" src="/js/jquery.sss_portal.audio.js?v=1.2.0.20190125"></script>
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
</script>
<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.6.20170323"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.sound_box img').imagebox();

    $(".tag").each(function() { // タグ
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

    $("video").on("play", function (e) {
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

    
    <?php
    // カテゴリによってキーワード追加を行ったがスマホでのデザインが難しい為、断念
    // JSと少しのPHPで作りたかったが、上手くいかなかったので汚いソースになっている
    /*
    // 「簡易キーワード入力」のリスト変更
    var val = $('[name=filter] option:selected').val(); // カテゴリの取得
    if(val == 2) {  // 心音
<?php
        // 「簡易キーワード」の追加
        $keyword=array("Ⅰ音","Ⅱ音");;
        for($i = 0; $i < count($keyword); $i++) {
            $class = Input::get('keyword') == $keyword[$i] ? 'active' : '';
?>
        $(".append_list").append("<li><a href='?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&keyword=<?php echo($keyword[$i]); ?>' class='<?php echo($class); ?>'><?php echo($keyword[$i]); ?></a></li>");
<?php
        }
?>
    } else if(val == 1) {   // 肺音
<?php
        // 「簡易キーワード」の追加
        $keyword=array("捻髪音");
        for($i = 0; $i < count($keyword); $i++) {
            $class = Input::get('keyword') == $keyword[$i] ? 'active' : '';
?>
        $(".append_list").append("<li><a href='?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&keyword=<?php echo($keyword[$i]); ?>' class='<?php echo($class); ?>'><?php echo($keyword[$i]); ?></a></li>");
<?php
        }
?>
    } else if(val == 3) {   // 腸音
<?php
        // 「簡易キーワード」の追加
        $keyword=array("晴れ","曇り","天気");
        for($i = 0; $i < count($keyword); $i++) {
            $class = Input::get('keyword') == $keyword[$i] ? 'active' : '';
?>
        $(".append_list").append("<li><a href='?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&keyword=<?php echo($keyword[$i]); ?>' class='<?php echo($class); ?>'><?php echo($keyword[$i]); ?></a></li>");
<?php
        }
?>
    } else if(val == 9) {   // その他
<?php
        // 「簡易キーワード」の追加
        $keyword=array("サンプル","テスト","静かにしろ");
        for($i = 0; $i < count($keyword); $i++) {
            $class = Input::get('keyword') == $keyword[$i] ? 'active' : '';
?>
        $(".append_list").append("<li><a href='?filter={{Input::get('filter')}}&show={{Input::get('show','10')}}&sort={{Input::get('sort','desc')}}&keyword=<?php echo($keyword[$i]); ?>' class='<?php echo($class); ?>'><?php echo($keyword[$i]); ?></a></li>");
<?php
        }
?>
    }
*/
    ?>
  });
</script>
<script type="text/javascript">
  (function($) {
    $('.sound_box .playZ').logger({
      url: '/log',
      data: function(elm) {
        var $sound_box = $(elm).closest('.sound_box');
        var data = {
          screen_code: 'CONTENTS',
          event_code: 'PLAY',
          body: {
            stetho_sound: {
              id: $sound_box.data('id'),
              type: $.trim($sound_box.find('.tag').text()),
              title: $sound_box.find('.sound_title .name').attr('title')
            }
          }
        };
        return data;
      }
    });
  })(jQuery);
</script>
@endsection
