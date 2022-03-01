<!-- @extends('layouts.app')

@section('title', 'Home')

@section('content')


<!-- メイン聴診音ライブラリ（左） -->
<?php
  $user_id=Session::get('MEMBER_3SP_ACCOUNT_ID');
  if($user_id==null) {
    $user_id=0;
  }
?>
  <div id="container">
    <div class="container_inner clearfix">

    <!--*********************************** .contents ***********************************-->
    <div class="contents">
      <!-- .home_mainvisual -->
      <div class="home_mainvisual">
        <img src="img/mainvisual.png" alt="">
        <div class="search_box">

          <form id="home_search_form" action="/iPax" method="get">
          <?php $search_types = [0 => trans('home.search_ipax'), 1 => trans('home.search_lib')]; ?>
            <p class="search_box_inner clearfix">
              <span class="search_type_title">
                <input type="radio"  name="search_opt" onchange="changeSearchType(this);" value="0" checked/> @lang('home.search_ipax')
                <input type="radio" class="home_search_option" name="search_opt" onchange="changeSearchType(this);" value="1" /> @lang('home.search_lib')
                <button class="search_btn"></button>
              </span> 
              <input maxlength="200" data-search_type="0" data-type_string="{{json_encode($search_types)}}" id="home_search_field" class="search_keyword" placeholder= " @lang('home.search_ipax') " type="text" name="keyword" />
              
            </p>
          </form>
        </div>
        <!-- /.search_keyword -->
      </div>
      <!-- /.home_mainvisual -->
      <?php $message = $params['message']; // 0:ログインお気に入りあり 1:ログインお気に入りなし 2:未ログイン?>
      <?php $sample_status = $params['sample_status']; // 0:未ログイン 1:ログイン(3sp) 2:ログイン(試聴音)?>
      <?php $sample_user = $params['sample_user']; // 暗号化されたメアド?>
      <?php $lang = (Config::get('app.locale') != "ja") ? "_en" : ""; //get locale?>
      <?php $lang2 = (Config::get('app.locale') == "ja") ? "" : "none;"; //get locale?>
      @if(config('app.locale') != 'en')
        @include('layouts.seminar20190816')
      @endif
      <!-- .home_menu -->
      <ul class="home_menu @if(config('app.locale') == 'en'){{'home_menu_en'}} @endif" >

        <li><a href=@if($auth == 0){{route('library-lp')}}@else{{route('contents')}}@endif ><img src="img/home_menu_1<?php echo $lang; ?>.png?1.1.2.20190523" alt="コンテンツ"></a></li>

        <li><a href=@if($auth == 0){{route('quizzes-lp')}}@else{{route('quizpacks')}}@endif><img src="img/home_menu_2<?php echo $lang; ?>.png" alt="聴診クイズ"></a></li>
        
        @if(config('app.locale') == 'en')
          <li class=@if(config('app.locale') != 'en'){{"last"}}@endif>
              <a href="{{route('kk01')}}">
              <img src="img/home_menu_31<?php echo $lang; ?>.png?20180711" alt="聴診ビデオ"></a>
          </li>
          <li class="last">
            <!-- <a href="aa01_eng" class="outlink"> -->
            <a href="https://telemedica.site/en-ipax-fb/" class="outlink" target="_blank">
              <img src="img/personalkkz3<?php echo $lang; ?>.png?1.1.2.20190816" alt="AA個人版"/>
            </a>
          </li>
        @else
          <li><a href=@if($auth == 0){{route('kk01')}}@else{{route('kk01')}}@endif><img src="img/personalkkz2<?php echo $lang; ?>.png" alt="KKZ個人向 バナー"></a></li>
          <li><a href="https://telemedica.site/ipax-lp/" target="_blank"><img src="img/personalkkz3<?php echo $lang; ?>.png" alt="AA個人版"></a></li>
        @endif

      </ul>
    <!-- /.home_menu -->

      @if($message == 2)
<?php $url = env('APP_URL')."about#achieve"; ?>
      <div class="offer_mat">
          <p>１．@lang('home.speaker_info.l1')
            <span style="color:#ff0000;">@lang('home.speaker_info.l2')</span>
              @if(config('app.locale') == 'en')
                <a href="/pdf/20210706_kikuzosound.com.contents.list_en.pdf" target="_blank">@lang('home.speaker_info.l3')</a>
              @else 
                <a href="/pdf/20210706_kikuzosound.com.contents.list_ja.pdf" target="_blank">@lang('home.speaker_info.l3')</a>
              @endif
            <span style="color:#ff0000;">@lang('home.speaker_info.l4')</span>
          </p>
          
          <p>２．@lang('home.product_brochure1')
          @if(config('app.locale') == 'en')
            <a href="pdf/Auscultation_English_manual.pdf" target="_blank"> (PDF)</a>
          @else 
          <a href="pdf/Auscultation_English_manual.pdf" style="display:<?php echo $lang2;?>" target="_blank"> (PDF)</a>
            @endif
          <p>
          
          <p>３．@lang('home.recruit_info.l1') </p>
         
<?php
/*
          <p>ご購入はこちら：<a href="{{route('corporate')}}">法人のお客様</a>　<a href="{{route('private')}}">個人のお客様</a></p>
*/
?>
      </div>

      @endif

      
      <!-- .newsound -->
      <div class="newsound">
        <?php $latest = null; //$latest = $stetho_sounds->sortByDesc('updated_at')->first();?>
        <h2 class="sub_title" style="<?php echo (Config::get("app.locale") == "ja") ? "" : "background-image:url(../img/line_orange_en.png);background-size:80% 20px;background-position:right;" ?>" >　<label style="padding-right: 40px;"><?php echo $params['title']; ?> </label>@if(!is_null($latest))<span style="display:none;">{{ $latest->updated_at->format('Y-m-d') }} 更新</span>@endif</h2>

@if($message == 2)
@if($sample_status == 0)
<?php
/*
<div class="use_sample_info">
        <div class="accordion_btn2" style="cursor:pointer;">
<p style="font-size:1.5em;text-align:center;">聴診会員ライブラリのご利用には登録が必要です</p>
        </div>
        <div class="accordion_moreconts">
<p style="margin:1em 0px 1em 0px;font-size:1em;">登録は下記、聴診会員ライブラリの「<img src="{{{asset('img/play.jpg')}}}" class="use_img">」を押して表示される「聴診会員ライブラリ利用ご登録」から行えます。<br>登録後、再度「<img src="{{{asset('img/play.jpg')}}}" class="use_img">」を押して表示される「ログイン」からご利用下さい。<p>

<p style="font-size:10px;">「聴くゾウ」をご購入した方、サイト利用プランをご利用の方は、現在ご使用している「ユーザIDとパスワード」のご入力(シリアル番号のご入力は必要ありません)で聴診会員ライブラリをご利用頂けます。</p>
        </div>
</div>

<div class="reg_mail_info" style="margin-top:10px;">
<p style="margin-bottom:10px;">
登録時にメールが届かない場合はメールアドレスを確認頂き、再度ご登録下さい。<br>
なお、<span style="color:#ff0000;">「迷惑メール」等に入る</span>場合もございますので、届かない場合はご確認下さい。<br>
</p>
</div>
*/
?>
<div style="display: block;padding:0px 0px 0px 30px;">
<p style="margin-bottom:10px;font-size:1.5em;display:none;">@lang('home.r_mail')<a href="{{route('r-mail-form')}}"> @lang('home.here')</a></p>
</div>

<div class="prvideo">
{{-- <ul class="video-ul">
    <li><a href="#videoBox5" data-vid="video5"><img style="border: 1px solid #ccc;" src="img/heart.png"></a><div class="videoTitle">PR【@lang('home.intro_vid')】</div><div class="videoAbstract">@lang('home.heart_snd')</div></li>
    <li><a href="#videoBox6" data-vid="video6"><img style="border: 1px solid #ccc;" src="img/lungs.png"></a><div class="videoTitle">PR【@lang('home.intro_vid')】</div><div class="videoAbstract">@lang('home.lung_snd')</div></li>
</ul> --}}
{{-- <div class="videoBoxes">
    <div id="videoBox5" class="videoWrap">
        <video playsinline id="video5" controls preload="metadata" width="100%" poster="./img/video/heart.png" controlslist="nodownload">
            <source src="./img/video/heart.mp4?v20190610" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('home.close_btn')</button></div>
    </div>
    <div id="videoBox6" class="videoWrap">
        <video playsinline id="video6" controls preload="metadata" width="100%" poster="./img/video/lung.png" controlslist="nodownload">
            <source src="./img/video/lung.mp4?v20190610" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('home.close_btn')</button></div>
    </div>
</div> --}}
</div>

@endif
@endif


        @if($auth == 0)
          @if($message == 1)
          <p class="favo_title">@lang('home.no_reg')</p>
          @endif
        @endif

        <!-- .sound_list -->
<!--
        <ul class="sound_list mB20 accordion">
-->
        <!-- bookmarks notation -->
        @if($message == 0)
            <div class="favo_mat" style="padding-top:20px;  display:none;">
              <ul>
                <li>@lang('home.favorites')：{{ $bookmarkCount }} @lang('home.cases')</li>
                @if(count($params['fid']) == $params['fmax'])
                <li>@lang('home.registration')</li>
                @endif
              </ul>
            </div>
            <div style="margin:20px 0px;"></div>
        @endif
        <!-- end bookmark notation -->

        <ul class="sound_list accordion" id="<?php echo isset($_GET['reorder']) ? "bookmarks__tbody" : "" ?>">
        <!--*********************************************************-->
        <?php $i = 0?>
        @foreach($stetho_sounds as $stetho_sound)
          <!-- .sound_box -->
          <!-- checking the extension -->

          <?php $type_strings = [0 => trans('list.tag-stetho'), 1 => trans('list.tag-aus'), 2 => trans('list.tag-palp'), 3 => trans('list.tag-ecg'), 4 => trans('list.tag-ins') , 5 => trans('list.tag-xray'), 6 => trans('list.tag-echo')]; ?>
          <li class="sound_box @if(empty($stetho_sound->sound_path) || $stetho_sound->lib_type==1) aus-sound_box @endif" data-id="{{ $stetho_sound->id }}">
            <input type="hidden" value="{{ $stetho_sound->favorites_id }}" name="favorites[{{ $i }}][disp_order]"/>
            <p class="tag" data-lib_type="{{$stetho_sound->lib_type}}">
              <span>{{$type_strings[$stetho_sound->lib_type] }}</span>
            </p>
            <p class="sub_description">
              @if (Config::get('app.locale') == "en")
              {{ $stetho_sound->sub_description_en }}
              @else
              {{ $stetho_sound->sub_description }}
              @endif
            </p>
            <div class="sound_box_inner">
              <!-- .sound_title -->
              <div id="@if($stetho_sound->lib_type==1)aus_{{$stetho_sound->id}}@endif" 
                data-id="{{$stetho_sound->id}}" 
                class="sound_accordion_open sound_title accordion_active aus_home  @if(empty($stetho_sound->sound_path) || $stetho_sound->lib_type==1) aus @endif @if($stetho_sound->lib_type==1)ausculaide @endif" 
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
                  // 直アクセス禁止対策
                  // 未ログインの場合は「仮音源」にして「ページのソース表示」対策をしている
                  // 「登録」して認証が通った場合、audio.min.jsで正しいsrcにしている

                  if ($message == 2) { // 2:未ログイン

                      // 「仮音源」の「5bfcd0b6dd142.mp3」は1秒の無音
                      // 「仮音源」の場所はshared/public/tmp
                      // 因みにtmpは.htaccessがないので、音源は再生される
                      //    $homesrc = "http://local3sp.telemedica.jp/audio/stetho_sounds/8.mp3".'?_='.date('YmdHis', strtotime($stetho_sound->updated_at));

                      // $domain = env('APP_URL');   // URL
                      // $homesrc = $domain."tmp/5bfcd0b6dd142.mp3".'?_='.date('YmdHis', strtotime($stetho_sound->updated_at));
                      $homesrc = $stetho_sound->sound_path.'?v='.session('version');
                  } else {    // 0:ログインお気に入りあり 1:ログインお気に入りなし
                      $homesrc = $stetho_sound->sound_path.'?v='.session('version');
                  }

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

                  <audio id="ssid_{{ $stetho_sound->id }}" data-id="{{ $stetho_sound->id }}" {{$muted}} preload="auto" src="<?= $homesrc ?>"></audio>
                  <div class="play-pauseZ">
                    <span class="line"></span>
                    @if($extension == "mp4" && $stetho_sound->is_video_show == 1)
                    <p class="playZ play_vid" onclick="gtag('event', 'Click', {'event_category':'sound_{{$stetho_sound->id}}', 'event_label':'user_{{$user_id}}'});"></p>
                    <p class="pauseZ_vid pauseZ_vid_{{$stetho_sound->id}}"></p>
                    @else
                    <p class="playZ" onclick="gtag('event', 'Click', {'event_category':'sound_{{$stetho_sound->id}}', 'event_label':'user_{{$user_id}}'});"></p>
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
                      {{$stetho_sound->title_en}}
                    @else
                      {{$stetho_sound->title}}
                    @endif
                  </p> -->
                  <p class="text">
                    @if (Config::get('app.locale') == "en")
                    {!! $stetho_sound->description_en !!}
                    @else
                    {!! $stetho_sound->description !!}
                    @endif
                  </p>
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
                        $libClassName = "";
                        break;
                    }
                  ?>
                  <!-- creating video box if extension is mp4 -->
                  @if(!empty($stetho_sound->sound_path) && $stetho_sound->lib_type!==1)
                    @if($extension == 'mp4' && $stetho_sound->is_video_show == 1)
                        <div class = "vid_open" style="padding-top:100px; display:none" oncontextmenu="return false;">
                          <!-- video custom controls-->
                          <video playsinline class="{{$libClassName}}" playsinline width="530" height="240" id="stetho_sound_video[{{ $stetho_sound->id }}]" controlslist="nodownload" src = "{{ $homesrc }}">
                        </div>
                    @endif
                  @else 
                    <?php $sound = $stetho_sound; ?>
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
                          data-front_body="{{ $sound->body_image.'?v='.session('version')}}"
                          data-back_body="{{ $sound->body_image_back.'?v='.session('version')}}"
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
                          <button id="reload_icon_{{$sound->id}}" class="btn-reload-icon"></button>
                        </li>
                        <div class="exit-fullscreen-overlay" style="display:none">
                            <div class="fullscreen-msg">
                              <div class="fullscreen-title-wrapper"> 
                                <p class="fullscreen-msg-title">@lang('aus.fullscreen_exit')</p>
                              </div>
                              <div class="fullscreen-footer-wrapper" >
                                <div class="fullscreen-btn-wrapper __ok"> 
                                  <span class="fullscreen-btn fullscreen-btn-ok">@lang('aus.Ok')</span>
                                </div>
                                <div class="fullscreen-btn-wrapper __cancel">
                                  <span class="fullscreen-btn fullscreen-btn-cancel">@lang('aus.Cancel')</span>
                                </div>
                              </div>
                            </div>
                        <div>
                      </ul>
                      <div id="controller_slider__{{$sound->id}}" class="aus_controller_wrapper" style="display: none;"> 
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

                    
                  @endif
                  @if(!empty($stetho_sound->video_path) && file_exists(public_path($stetho_sound->video_path)))
                  <div class="bx-viewport-insp">
                    <!-- single video -->

                    <video playsinline class="{{$libClassName}}" id="library_video" data-id="{{$stetho_sound->id}}" width="530" height="240" controls controlslist="nodownload" src="{{ $stetho_sound->video_path.'?v='.session('version') }}">

                  </div>
                  @endif

                  <?php 
                    $hasSwipe = 0;
                    $imageCount = (Config::get('app.locale') == "en") ? $stetho_sound->images_en->count() : $stetho_sound->images_ja->count();
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

                  @if(!empty($stetho_sound->image_path))
                  <div class="img_slide home-page">
                    <div class="img_slide_inner">
                      <ul class="bxslider_{{$sound->id}}">
                        <li  class="img-slider-container">
                          <img src="{{ $stetho_sound->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="sound_img" />
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
                  @endif

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

                                <video playsinline class="{{$libClassName}}" id="library_video" data-id="{{$stetho_sound->id}}" controls preload="metadata" width="100%" controlslist="nodownload" oncontextmenu="return false;" src="{{ $image->image_path.'?v='.session('version') }}" type="video/mp4">
                              @else
                                <img src="{{ $image->image_path.'?v='.session('version') }}" style="cursor: pointer;" class="sound_img"/>
                              @endif
                              <p hidden="" id="sl_image_title">{{$image->title}}</p>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                      @if($imageCount > 0)
                        @if($hasSwipe)
                          <script>
                            $('.bxslider_{{$stetho_sound->id}}').bxSlider({
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
                            $('.bxslider_{{$stetho_sound->id}}').bxSlider({
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
                    <p class="img_slider_text" @if(!$stetho_sound->images->first()->title) hidden @endif id="image_title">{{$stetho_sound->images->first()->title}}</p>
                  </div>
                  @endif
                  <!-- /.img_slide -->
                  <p class="aus-body_description">
                      @if (Config::get('app.locale') == "en")
                      {!! $stetho_sound->image_description_en !!}
                      @else
                      {!! $stetho_sound->image_description !!}
                      @endif
                    </p>
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

                @if($message == 0)
                <div class="clearfix fav_wrapper">
                  <button class="FavButton" data-confirm="{{trans('home.do_process')}}" data-id="{{ $stetho_sound->id }}" data-favo="{{ $stetho_sound->favo }}" data-aid="{{ $params['aid'] }}">@lang('home.remove_fave_btn')</button>
                </div>
                @endif
              </div>
                <!-- /.sound_conts -->
              </div>
              </li>
              <!-- /.sound_box -->
              <?php $i++; ?>
        @endforeach

            <!--*********************************************************-->
        </ul>
          <!-- /.sound_list -->

    <?php /* trial@telemedica.co.jpの場合は表示しない */ ?>
    <?php $exceptmail = "trial@telemedica.co.jp"; ?>
@if($sample_status == 2)
    <?php /* trial@telemedica.co.jpの場合は表示しない */ ?>
    @if($sample_user == "n89GfPNcyNCjODQeHVK0QK6eizAnRyIto7onfk8lD_4-")
<p id="use_sample" class="mT20" data-em="<?= $exceptmail ?>" style="display:none;text-align:center;"><a href="{{ route('r-mail-form') }}<?php echo("?edit=".$sample_user); ?>" id="s_edit_link" class="blue_btn">聴診会員ライブラリ利用登録変更</a></p>
    @else
<p id="use_sample" class="mT20" data-em="<?= $exceptmail ?>" style="display:block;text-align:center;"><a href="{{ route('r-mail-form') }}<?php echo("?edit=".$sample_user); ?>" id="s_edit_link" class="blue_btn">聴診会員ライブラリ利用登録変更</a></p>
    @endif
@else
<p id="use_sample" class="mT20" data-em="<?= $exceptmail ?>" style="display:none;text-align:center;"><a href="{{ route('r-mail-form') }}<?php echo("?edit=".$sample_user); ?>" id="s_edit_link" class="blue_btn">聴診会員ライブラリ利用登録変更</a></p>
@endif

          @if($message == 2)
          <p class="link_red"><a href="{{route('contents')}}">→@lang('home.see_more')</a></p>
          @else

          @if($message == 0)
          <div class="favo_mat" style="padding-top:20px;">
            <ul>
              <li>@lang('home.favorites')：{{ $bookmarkCount }} @lang('home.cases')</li>
              @if(count($params['fid']) == $params['fmax'])
              <li>@lang('home.registration')</li>
              @endif
            </ul>
          </div>
          <div style="margin:20px 0px;"></div>
          @endif

      @if($auth == 0)
        @if($message == 1)
        <div class="accordion_btn2 a_btn">@lang('home.reg_method')</div>
        <div class="accordion_moreconts">
          <div class="favo_mat" style="padding-top:20px;">
            <ul>
              <li>@lang('home.reg_info.li1')</li>
              <li>@lang('home.reg_info.li2')</li>
              <li>@lang('home.reg_info.li3a') <?php echo $params['fmax']?> @lang('home.reg_info.li3b')</li>
              <li>@lang('home.reg_info.li4')</li>
            </ul>
          </div>

          <div class="down_arrow"></div>

          <div class="favo_mat" style="margin-bottom:20px;">
            <p class="favo_explain">@lang('home.reg_stps')</p>
              <ul>
              <li>@lang('home.reg_how.li1a')「<img src="{{ asset('img/more@2.png') }}" class="favo_img">」@lang('home.reg_how.li1b')</li>
              <li>「 @if (config('app.locale')=='en')
                   <img src="/img/f1_en.png" class="favo_img"> 」@lang('home.reg_how.li2')
                   @else
                   <img src="/img/f1.png" class="favo_img"> 」@lang('home.reg_how.li2')
                   @endif
                  </li>
              <li>@lang('home.reg_how.li3a')「 @if (config('app.locale')=='en')
              <img src="/img/f2_en.png" class="favo_img"> 」@lang('home.reg_how.li3b')
              @else
              <img src="/img/f2.png" class="favo_img"> 」@lang('home.reg_how.li3b')
              @endif
              </li>
            </ul>
          </div>
        </div>
        @endif

<!-- pharmacy -->
        @if($message == 0)
        <h2 class="sub_title" style="margin-top:40px;"> @lang('home.stet_abt')</h2>
        @else
        <h2 class="sub_title" style="margin-top:20px;">　@lang('home.stet_snd')</h2>
        @endif

        @if($message == 1)
          <div class="accordion_btn2 a_btn" style="margin-bottom:20px;">  @lang('home.stet_abt')</div>
          <div class="accordion_moreconts">
          <div class="favo_mat" style="margin-bottom:30px;padding-top:20px;">
            <ul>
              <li>@lang('home.stet_info.li1')</li>
              <li>@lang('home.stet_info.li2')</li>
              <li>@lang('home.stet_info.li3')</li>
            </ul>
          </div>
          </div>
        @endif
      @endif

<!-- pharmacy -->

    @if($message == 0)
      @if($auth == 0)
        <div class="accordion_btn2 a_btn2" style="margin-bottom:10px;">@lang('home.pharmacy.accordion_btn')</div>
        <div class="accordion_moreconts">
          <div class="favo_mat" style="padding-top:20px;">
            <ul>
              <li>@lang('home.pharmacy.register_favorites.list_1')</li>
              <li>@lang('home.pharmacy.register_favorites.list_2')</li>
              <li>@lang('home.pharmacy.register_favorites.list_3_1') <?php echo $params['fmax']?>@lang('home.pharmacy.register_favorites.list_3_2')</li>
              <li>@lang('home.pharmacy.register_favorites.list_4')</li>
            </ul>
          </div>

          <div class="down_arrow"></div>

          <div class="favo_mat" style="margin-bottom:20px;">
            <p class="favo_explain">@lang('home.pharmacy.favo_explain')</p>
              <ul>
              <li>@lang('home.pharmacy.registration_method.method_1_1')「<img src="{{{asset('img/more@2.png')}}}" class="favo_img">」@lang('home.pharmacy.registration_method.method_1_2')</li>
              <li>「  @if(Config::get('app.locale') == 'en') <img src="/img/f1_en.png" class="favo_img"> @else <img src="/img/f1.png" class="favo_img"> @endif 」@lang('home.pharmacy.registration_method.method_2')</li>
              <li>@lang('home.pharmacy.registration_method.method_3_1')「 @if(Config::get('app.locale') == 'en') <img src="/img/f2_en.png" class="favo_img"> @else <img src="/img/f2.png" class="favo_img"> @endif  」@lang('home.pharmacy.registration_method.method_3_2')</li>
            </ul>
          </div>
        </div>

        @if($message == 0)
        <div class="accordion_btn2 a_btn2" style="margin-bottom:20px;">@lang('home.pharmacy.accordion_btn2')</div>
        <div class="accordion_moreconts">
        <div class="favo_mat" style="margin-bottom:30px;padding-top:20px;">
          <ul>
            <li>@lang('home.pharmacy.sounds.set_1')</li>
            <li>@lang('home.pharmacy.sounds.set_2')</li>
            <li>@lang('home.pharmacy.sounds.set_3')</li>
          </ul>
        </div>
        </div>
        @endif
      @endif
    @endif

<?php $fomax = $params['fomax']; //お気に入りオリジナルが0の場合、非表示?>
@if($fomax != 0)
        @if($message == 0)
        <div class="favo_mat" style="margin-bottom:20px;margin-top:60px;">
          <p class="favo_explain">【便利機能】別に保存</p>
          <ul>
            <li>下記「お気に入りを別に保存」で、登録した「お気に入り」を別途 <?php echo $fomax ?>個、保存できます</li>
            <li>別に保存すると「保存名の付いたボタン」が作成されます</li>
            <li>作成された「保存名の付いたボタン」を押すと、別に保存した「お気に入り」を表示します</li>
          </ul>
        </div>
        @endif

          <?php $sfpid_c = count($sfpids); ?>
          @if($sfpid_c != 0)
          <div class="f_container">
            <p class="favo_explain">別に保存したお気に入り</p>

            <?php $i=0; ?>
            <?php define("COLUMN", 3); ?>
            @foreach($sfpids as $sfpid)
            @if(($i % COLUMN) == 0)
            <div class="f_oneline">
              <ul>
            @endif
                <li>
                  <div class="f_mat1">
                    <button class="InitSetButton" data-set="0" data-aid="{{ $params['aid'] }}" data-fpid="{{ $sfpid->id }}" data-register="{{ count($params['fid']) }}">{{ $sfpid->title }}</button>
                  </div>
                </li>
            @if($i == (COLUMN-1))
              </ul>
            </div>
            @endif
            <?php if ($i == (COLUMN-1)) {
                          $i=0;
                      } else {
                          $i++;
                      } ?>
            @endforeach

            @if(($i % COLUMN) != 0)
              </ul>
            </div>
            @endif

          </div>
          @endif

          @if($sfpid_c != $fomax && $message == 0)
          <div class="f_container">
            <p class="favo_explain">お気に入りを別に保存</p>
            <div class="f_oneline">
              <ul>
                <li>
                  <div class="f_mat1">
                    <input name="b_title" id="b_title" type="text" maxlength="20" value="{{ old('b_title') }}" placeholder="保存名(10文字まで)"/>
                  </div>
                </li>
                <li>
                  <div class="f_mat1">
                    <button class="OriginalSetButton" id="OriginalSetButton" data-aid="{{ $params['aid'] }}" data-setfpid="{{ $params['fpid'] }}">別に保存</button>
                  </div>
                </li>
              </ul>
            </div>
            <div id="b_title_error"></div>
          </div>
          @endif

          <?php $sfpid_c = count($sfpids); ?>
          @if($sfpid_c != 0)
          <div class="f_container">
            <p class="favo_explain">別に保存の削除</p>

            <?php $i=0; ?>
            @foreach($sfpids as $sfpid)
            @if(($i % COLUMN) == 0)
            <div class="f_oneline">
              <ul>
            @endif
                <li>
                  <div class="f_mat1">
                    <button class="DeleteSetButton" data-aid="{{ $params['aid'] }}" data-fpid="{{ $sfpid->id }}">{{ $sfpid->title }}</button>
                  </div>
                </li>
            @if($i == (COLUMN-1))
              </ul>
            </div>
            @endif
            <?php if ($i == (COLUMN-1)) {
                          $i=0;
                      } else {
                          $i++;
                      } ?>
            @endforeach

            @if(($i % COLUMN) != 0)
              </ul>
            </div>
            @endif
          </div>
          @endif


          <?php $fr = env('FAVORITE_REC'); //お勧めセット。当面、非採用?>
          @if($fr != 0)
          <div class="f_container">
            <p class="favo_explain">お勧めセット</p>
            <div class="f_oneline">
              <ul>
                <li>
                  <div class="f_mat1">
                    <button class="InitSetButton" data-set="1" data-aid="{{ $params['aid'] }}" data-fpid="{{ $params['fpid'] }}" data-register="{{ count($params['fid']) }}">正常心音心基部</button>
                  </div>
                </li>
                <li>
                  <div class="f_mat1">
                    <button class="InitSetButton" data-set="2" data-aid="{{ $params['aid'] }}" data-fpid="{{ $params['fpid'] }}" data-register="{{ count($params['fid']) }}">正常心音心尖部</button>
                  </div>
                </li>
                <li>
                  <div class="f_mat1">
                    <button class="InitSetButton" data-set="3" data-aid="{{ $params['aid'] }}" data-fpid="{{ $params['fpid'] }}" data-register="{{ count($params['fid']) }}">正常呼吸音</button>
                  </div>
                </li>
              </ul>
            </div>
            <div class="f_oneline">
              <ul>
                <li>
                  <div class="f_mat1">
                    <button class="InitSetButton" data-set="3" data-aid="{{ $params['aid'] }}" data-fpid="{{ $params['fpid'] }}" data-register="{{ count($params['fid']) }}">笛声音wheezes</button>
                  </div>
                </li>
                <li>
                  <div class="f_mat1">
                    <button class="InitSetButton" data-set="2" data-aid="{{ $params['aid'] }}" data-fpid="{{ $params['fpid'] }}" data-register="{{ count($params['fid']) }}">正常グル音</button>
                  </div>
                </li>
                <li>
                  <div class="f_mat2">
                  </div>
                </li>
              </ul>
            </div>
          </div>
          @endif

@endif
          @endif
        </div>

        <!-- /.newsound -->
        <!-- .use -->
        @if($message == 2)
        <div class="use_mat">
          <div class="use_title">「@lang('home.operate_info.title_l1')」@lang('home.operate_info.title_l2')</div>
          <ul>
            <li>「 <img src="{{{asset('img/play.jpg')}}}" class="use_img"> 」@lang('home.operate_info.l1')</li>
            <li>「<img src="{{{asset('img/more@2.png')}}}" class="use_img">」@lang('home.operate_info.l2')</li>
            <li>「@lang('home.operate_info.detail')」@lang('home.operate_info.inner')「 <img src="{{{asset('img/movel.jpg')}}}" class="use_img"> 」「 <img src="{{{asset('img/mover.jpg')}}}" class="use_img"> 」@lang('home.operate_info.l3')</li>
            <li>「@lang('home.operate_info.detail')」@lang('home.operate_info.l4')</li>
          </ul>
        </div>
<p style="text-align:center;display:none;">@lang('home.free_use')「<a href="pdf/<?php echo (Config::get("app.locale") == "ja") ? "trial_20190520" : "trial_20190520_en" ?>.pdf" target="_blank">@lang('home.free_info')</a>」！</p><br>
        @endif
        <!-- /.use -->
        <!-- sorting function -->
        @if($auth == 1 && count($stetho_sounds)>1)
        <div class="home-sort-wrapper" style="">
            <a class="home-sort-btn"
                href="/home<?php echo isset($_GET['reorder']) ? "" : "?reorder" ?>">     
                @if(isset($_GET['reorder'])) @lang('home.sort_complete') @else @lang('home.sorting') @endif
            </a>
        </div>
        @endif
        <!-- /sorting function -->
      </div>
      <!--*********************************** /.contents ***********************************-->


      <!--*********************************** .side_column ***********************************-->
      <div class="side_column">
        <div class="home-video">
        @if(Config::get('app.locale') == 'en')
          <iframe src="https://drive.google.com/file/d/1HKHcJ-Xpzx6JIAqrWnZ0Ffo8hhIvsDb3/preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        @else 
          <iframe src="https://drive.google.com/file/d/1WieHfAYnrIDKxzpRb8Sgqo4C-cUHQtyd/preview" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        @endif
        </div>             
        <!-- 推薦文 -->
        @include('layouts.recommend')
        <!-- ニューズレター -->
        @include('layouts.newsletter')
        <!-- デモ機貸出し -->
        @if(config('app.locale') != 'en')
          @if($message == 2)
            @include('layouts.provisional')
          @endif
        @endif
        <!-- 川崎ブランド取得 -->
        @include('layouts.kawasaki_brand')
        <!-- ダイジェスト -->
        @if(config('app.locale') != 'en')
          @include('layouts.digest')
        @endif
<?php
 if ($params['init_disp'] === 'true') {
     ?>
        <!-- 聴診音付き広告 -->

        @include('layouts.advertorial')
<?php
 }
?>
        <!-- お知らせ -->
        @include('layouts.news')
<?php /*
        <!-- ケンツメディコ聴診器 -->
        @include('layouts.kms')
        <!-- 聴診スピーカーについて -->
        @include('layouts.whatspeaker')
*/ ?>
        <!-- スポンサーリンク -->
        @if(config('app.locale') != 'en')
          @include('layouts.advertisement')
        @endif

      </div>
      <!--*********************************** /.side_column ***********************************-->

    </div>
    <!-- /#container_inner -->
    <div id="ausculaid_app_wrapper" style="display:none;">
      @include('bodymap.bodymap')
    </div>
  </div>
  <!-- /#container -->
<!-- </div> -->

<script type="text/javascript" src="/js/jquery.sss_portal.audio.js?v=1.2.0.20190610"></script>
<script type="text/javascript">
audiojs.events.ready(function() {
/****************************************************************/
/* 試聴音登録 */
//return false;   // 聴診音を準備させない(要は音を出させない)
// ここで制御せず「jquery.sss_portal.audio.js」で制御に変更
/****************************************************************/
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
<script type="text/javascript" src="/js/jquery.imagebox.js?v=1.1.7"></script>
<script type="text/javascript" src="/js/bootstrap-switch-button.js?v=1.1.6.20210421"></script>
<script type="text/javascript" src="/js/offline.js"></script>
<!-- <script type="text/javascript" src="/js/bodymap.js"></script> -->
<script type="text/javascript">
//switch search type
function changeSearchType(e) {
  var search_type = parseInt(e.value);
  var search_string_type = $("#home_search_field").data("type_string");
 console.log(search_type,search_type === 0,search_string_type)
  //switch form action
  if (search_type === 0) {
    $('#home_search_form').attr('action', "/iPax");
  } else {
    $('#home_search_form').attr('action', "/contents");
  }
  //set place holder when switched
  $("#home_search_field").attr("placeholder", search_string_type[search_type]);
}

$(document).ready(function(){
  $('.sound_box img').not('img.body-map').imagebox();

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
});
</script>

<script type="text/javascript">
(function($){
  $('.sound_box .playZ').logger({
    url: '/log',
    data: function(elm) {
      var $sound_box = $(elm).closest('.sound_box');
      var data = {
        screen_code: 'HOME',
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
$(window).on('load',function () {
    $("#tabBox2").hide();
    $("#tabBox3").hide();
    $("#tabBox5").hide();

    $(".accordion_moreconts").show();
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
    window.touchPunchDelay=100;
    $("#bookmarks__tbody").sortable({
      // 回答の表示順の変更イベント
      update: function(event, ui) {
        var $li = $('#bookmarks__tbody>li');
        var arr = []
        $li.children('input[type="hidden"]').each(function(i, e){
          $(e).attr('name','favorites[' + i + '][disp_order]');
          $name = 'favorites[' + i + '][disp_order]';
          arr[i] = {
            favorites_id: $('input[name="' + $name + '"]').val(),
            disp_order: i
          }
        });

        var data = JSON.stringify({"favorites": arr});
        $.ajax({
          url : '/favorites_reorder',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data : data,
          type : 'POST',
          contentType : 'application/json',
          success: function(res){
            //if(res == 1) console.log("bookmark reordered!");
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert( "error : " + XMLHttpRequest );
          },
        });
      }
    });
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
      var v = $(this);
      $("video").each(function( index ) {
        if ($(v).attr("src") != $(this).attr("src")) {
          $(this).get(0).pause()
        }
      });
    });

    // library video
    $(".library_video_stetho, .library_video_palpa, .library_video_inspi").on("play", function (e) {
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
  </script>
<style>
.bodyFrame{
  width:100%;
  height:450px;
}
</style>
@endsection -->
