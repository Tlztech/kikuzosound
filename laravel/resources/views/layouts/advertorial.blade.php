<!-- 聴診スピーカーとは？  -->
<div class="side_speaker mB20" style="display:none;">
  <h3 class="side_title" style="font-size:1.2em;text-align:left;">
<!-- ロゴを一旦外す -->
<!--
    <div class="rec_content">
      <img src="{{{asset('img/ami-logo.png')}}}" alt="社名" height="50px">
    </div>
-->
  <?php
  if(Config::get('app.locale') == 'en'){
    ?>
    @lang('advertorial.title')
   <?php } else {
    ?>
    <?php echo $params['init_title']; ?>
  <?php
  }
?>
  </h3>
<!--
  <div class="side_box_inner side_speaker_text clearfix">
-->
  <div style="margin-top:10px;margin-bottom:10px;">
<?php
    if($params['init_img1'] !== 'false') {
?>
      <!-- イメージを表示 -->
      <img src="
      <?php
        if(Config::get('app.locale') == 'en'){
          echo "img/asthma_en.jpg";
        } else {
          echo $params['init_img1'];
        }
      ?>
      " alt="<?php echo $params['init_alt1']; ?>" width="230px">
<?php
    }
?>
  </div>
  <p style="font-size:1.5em;font-weight:bold;">
  <?php
  if(Config::get('app.locale') == 'en'){
    ?>
    @lang('advertorial.subtitle')
   <?php } else {
    ?>
    <?php echo $params['init_subtitle1']; ?>
  <?php
  }
?>

  </p>

<!--
  <div class="side_box_inner notice_list" style="text-align:left;">
-->
  <div style="text-align:left;">
    <p>
    <?php
  if(Config::get('app.locale') == 'en'){
    ?>
    @lang('advertorial.text1')
   <?php } else {
    ?>
    <?php echo $params['init_text1']; ?>
  <?php
  }
?>
    </p>
  </div>

<!-- 指定した音を表示 -->
        @foreach($ad_sounds1 as $ad_sound)
          <div class="sound_box" data-id="{{ $ad_sound->id }}">
            <div class="sound_box_inner">
              <!-- .sound_title -->
              <div class="sound_accordion_open sound_title accordion_active">
                <p style="margin-top:10px;" class="name" title="{{ $ad_sound->title }}">
                @if(Config::get('app.locale') == 'en')
                  {{ $ad_sound->title_en }}
                @else
                  {{ $ad_sound->title }}
                @endif
                </p>
              </div>
              <!-- /.sound_title -->
              <!-- .audio -->
              <div class="audio">
                <div class="audiojsZ">
<?php
// 直アクセス禁止対策
// 未ログインの場合は「仮音源」にして「ページのソース表示」対策をしている
// 「登録」して認証が通った場合、audio.min.jsで正しいsrcにしている

if($message == 2) { // 2:未ログイン

// 「仮音源」の「5bfcd0b6dd142.mp3」は1秒の無音
// 「仮音源」の場所はshared/public/tmp
// 因みにtmpは.htaccessがないので、音源は再生される
//    $homesrc = "http://local3sp.telemedica.jp/audio/stetho_sounds/8.mp3".'?_='.date('YmdHis', strtotime($ad_sound->updated_at));

    $domain = env('APP_URL');   // URL
    $homesrc = $ad_sound->sound_path.'?_='.date('YmdHis', strtotime($ad_sound->updated_at));
} else {    // 0:ログインお気に入りあり 1:ログインお気に入りなし
    $homesrc = $ad_sound->sound_path.'?_='.date('YmdHis', strtotime($ad_sound->updated_at));
}

$dot = explode(".",$ad_sound->sound_path);  // 音源を.で分割
$ext = ".".$dot[count($dot)-1]; // 音の拡張子
?>

<?php
/*
                  <audio src="{{ asset($ad_sound->sound_path).'?_='.date('YmdHis', strtotime($ad_sound->updated_at)) }}"></audio>
*/
?>
                  <audio preload="auto" src="<?= $homesrc ?>" data-ext="<?= $ext ?>"></audio>
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
              <!-- /.audio -->
            </div>
          </div>
            @endforeach
<!-- 指定した音を表示 -->


  <p style="font-size:1.5em;font-weight:bold;margin-top:30px;">
  <?php
  if(Config::get('app.locale') == 'en'){
    ?>
    @lang('advertorial.subtitle2')
   <?php } else {
    ?>
    <?php echo $params['init_subtitle2']; ?>
  <?php
  }
?>

  </p>
  <img src="img/vessel.gif" width="230px"/>
  <div style="margin-bottom:10px;">
<?php
    if($params['init_img2'] !== 'false') {
?>
      <!-- イメージを表示 -->
      <img src="<?php echo $params['init_img2']; ?>" alt="<?php echo $params['init_alt2']; ?>" width="230px">
<?php
    }
?>
  </div>

<!--
  <div class="side_box_inner notice_list" style="text-align:left;">
-->
  <div style="text-align:left;">
    <p>
    <?php
  if(Config::get('app.locale') == 'en'){
    ?>
    @lang('advertorial.text2')
   <?php } else {
    ?>
    <?php echo $params['init_text2']; ?>
  <?php
  }
?>
    </p>
  </div>

<!-- 指定した音を表示 -->
        @foreach($ad_sounds2 as $ad_sound)
          <div class="sound_box" data-id="{{ $ad_sound->id }}">
            <div class="sound_box_inner">
              <!-- .sound_title -->
              <div class="sound_accordion_open sound_title accordion_active">
                <p style="margin-top:6px;" class="name" title="{{ $ad_sound->title }}">
                @if(Config::get('app.locale') == 'en')
                  {{ $ad_sound->title_en }}
                @else
                  {{ $ad_sound->title }}
                @endif</p>
              </div>
              <!-- /.sound_title -->
              <!-- .audio -->
              <div class="audio">
                <div class="audiojsZ">
<?php
// 直アクセス禁止対策
// 未ログインの場合は「仮音源」にして「ページのソース表示」対策をしている
// 「登録」して認証が通った場合、audio.min.jsで正しいsrcにしている

if($message == 2) { // 2:未ログイン

// 「仮音源」の「5bfcd0b6dd142.mp3」は1秒の無音
// 「仮音源」の場所はshared/public/tmp
// 因みにtmpは.htaccessがないので、音源は再生される
//    $homesrc = "http://local3sp.telemedica.jp/audio/stetho_sounds/8.mp3".'?_='.date('YmdHis', strtotime($ad_sound->updated_at));

    $domain = env('APP_URL');   // URL
    $homesrc = $ad_sound->sound_path.'?_='.date('YmdHis', strtotime($ad_sound->updated_at));
} else {    // 0:ログインお気に入りあり 1:ログインお気に入りなし
    $homesrc = $ad_sound->sound_path.'?_='.date('YmdHis', strtotime($ad_sound->updated_at));
}

$dot = explode(".",$ad_sound->sound_path);  // 音源を.で分割
$ext = ".".$dot[count($dot)-1]; // 音の拡張子
?>

<?php
/*
                  <audio src="{{ asset($ad_sound->sound_path).'?_='.date('YmdHis', strtotime($ad_sound->updated_at)) }}"></audio>
*/
?>
                  <audio preload="auto" src="<?= $homesrc ?>" data-ext="<?= $ext ?>"></audio>
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
              <!-- /.audio -->
            </div>
          </div>
            @endforeach
<!-- 指定した音を表示 -->
  <!--
  <div style="text-align: right;margin-top:10px;">
    <img src="{{{asset('img/ami-logo.png')}}}" alt="社名" height="40px">
  </div>
-->
</div>
