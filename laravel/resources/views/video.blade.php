@extends('layouts.app')

@section('title', 'video')

@section('breadcrumb')
{!! Breadcrumbs::render('video') !!}
@endsection

@section('content')

<?php
/*
[3sp_～.mp4]のファイル名は下記cookieがないとapache(.htaccess)で拒否される
なのでhomeのダイジェスト版を3sp_digest.mp4等にすると出なくなるので注意
設定についてはpublic/.htaccess参照の事
*/
$flag = setcookie("video","accessok");
?>

<!-- メイン聴診音ライブラリ（左） -->
  <div id="container">
    <div class="container_inner">
<!-- 上級者向け -->
<div class="videoGroup"><img src="./img/video/kikuzou_icon.png">@lang('video.title')</div>
<!-- 1行 -->
<ul class="video-ul">
    <li><a href="#videoBox1" data-vid="video1"><img src="./img/video/1.png"></a><div class="videoTitle">@lang('video.video_1')</div><div class="videoAbstract">@lang('video.video1_description')</div></li>
    <li><a href="#videoBox2" data-vid="video2"><img src="./img/video/2.png"></a><div class="videoTitle">@lang('video.video_2')</div><div class="videoAbstract">@lang('video.video2_description')</div></li>
    <li><a href="#videoBox3" data-vid="video3"><img src="./img/video/3.png"></a><div class="videoTitle">@lang('video.video_3')</div><div class="videoAbstract">@lang('video.video3_description')</div></li>
    <li><a href="#videoBox4" data-vid="video4"><img src="./img/video/4.png"></a><div class="videoTitle">@lang('video.video_4')</div><div class="videoAbstract">@lang('video.video4_description')</div></li>
</ul>
<div class="videoBoxes"  oncontextmenu="return false;">
    <div id="videoBox1" class="videoWrap">
        <video id="video1" controls preload="metadata" width="100%" poster="./img/video/1.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1VdBlXGzw360k64LbB7-RsrrJ2YcD3Non" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
    <div id="videoBox2" class="videoWrap">
        <video id="video2" controls preload="metadata" width="100%" poster="./img/video/2.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1YfOfo2mNPEGf3tQoX5WgKsPFk9XOjFOW" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
    <div id="videoBox3" class="videoWrap">
        <video id="video3" controls preload="metadata" width="100%" poster="./img/video/3.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1-rZvliqNyR7fYHpduzcJG28crV1lmItW" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
    <div id="videoBox4" class="videoWrap">
        <video id="video4" controls preload="metadata" width="100%" poster="./img/video/4.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1USAjfvayNOn28p8f2asSIEDTFOMyRnnQ" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
</div>

<!-- 2行 -->
<ul class="video-ul">
    <li><a href="#videoBox5" data-vid="video5"><img src="./img/video/5.png"></a><div class="videoTitle">@lang('video.video_5')</div><div class="videoAbstract">@lang('video.video5_description')</div></li>
    <li><a href="#videoBox6" data-vid="video6"><img src="./img/video/6.png"></a><div class="videoTitle">@lang('video.video_6')</div><div class="videoAbstract">@lang('video.video6_description')</div></li>
    <li><a href="#videoBox7" data-vid="video7"><img src="./img/video/7.png"></a><div class="videoTitle">@lang('video.video_7')</div><div class="videoAbstract">@lang('video.video7_description')</div></li>
    <li></li>
</ul>
<div class="videoBoxes clearfix"  oncontextmenu="return false;">
    <div id="videoBox5" class="videoWrap">
        <video id="video5" controls preload="metadata" width="100%" poster="./img/video/5.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1uXA4LmzCNAH2Np9BbVJ9rQVa31ga9IYc" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
    <div id="videoBox6" class="videoWrap">
        <video id="video6" controls preload="metadata" width="100%" poster="./img/video/6.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1yVyZfB_a-t4ICe4pgDzlbBkprYybNV4s" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
    <div id="videoBox7" class="videoWrap">
        <video id="video7" controls preload="metadata" width="100%" poster="./img/video/7.png" controlslist="nodownload">
            <source src="https://drive.google.com/uc?export=download&id=1kCl8Ho-GdGhzxFiKl8pbRxtYxBG9YpZd" type="video/mp4">
            <p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
        </video>
        <div class="videoCloseWrap"><button class='videoClose'>@lang('video.button_close')</button></div>
    </div>
</div>

    </div>
    <!-- /#container_inner -->
  </div>
  <!-- /#container -->
</div>
@endsection
