<?php $lang = (Config::get('app.locale') != "ja") ? "_en" : ""; //get locale ?>

@extends('layouts.app')

@section('title', trans('app.registration'))

@section('breadcrumb')
{!! Breadcrumbs::render('register') !!}
@endsection

@section('content')
{{-- 送信後のメッセージ表示 --}}
<!-- メインコンテンツ（左） -->
<div id="container">
  <!-- シリアル番号の登録 -->
  <div class="container_inner clearfix">
    <div class="contents"> 
      <div class="contents_box sp_mTB20 mTB20">
        <div class="contents_box_inner pTB20">
          <h2 class="title_m mTB20">@lang('register.h1')</h2>
          <p>@lang('register.p1')</p>
          <br>
          <p>@lang('register.p2')<span style="color:#ff0000;font-weight:bold;">@lang('register.s1')</span>@lang('register.p3')</p>
          <br>
          <p>@lang('register.p4')</p>

<p style="text-align:center;"><img src="{{{asset('img/serial'.$lang.'.png')}}}" style="width:80%;height:auto;margin:20px auto;"></p>

          <p>@lang('register.p5')</p>
          <br>
          <p>@lang('register.p6')</p>
          <br>
          <p>@lang('register.p7')</p>
          <br>
          <p class="mTB20 sp_t_center"><a href="{{ route('customer_registration') }}" class="blue_btn">@lang('register.a1')</a></p>
        </div>
      </div>
    </div>

    <!-- サイドコンテンツ（右） -->
    <div class="side_column">
      <!-- Aboutリンクー -->
      @include('layouts.about_menus')
      <!-- 聴診専用スピーカとは？ -->
      @include('layouts.whatspeaker')
    </div>

  </div>
</div>
@endsection
