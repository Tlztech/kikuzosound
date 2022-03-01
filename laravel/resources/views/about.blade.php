@extends('layouts.app')

@section('title', 'About us')

@section('breadcrumb')
{!! Breadcrumbs::render('about') !!}
@endsection

@section('content')
<div id="container">
  <!-- サイトについて -->
  <div class="container_inner clearfix">

    <!-- メインコンテンツ（左） -->
    <div class="contents">
      <div class="about">
        <div class="about_title">@lang('about.title')</div>
        </br>
        <div class="about_text">
          <p>
          @lang('about.p1')
          </p>
          </br>
          <p>
          @lang('about.p2')
          </p>
          </br>
          <p>
          @lang('about.p3')
          </p>
          </br>
          <p>
          @lang('about.p4')
          </p>
          </br></br>
          <p>
            @lang('about.p5') <br>
            @lang('about.p6')
          </p>
          <span><img src="img/profile_pic.png" class="profile_pic"></span>
          <!-- @if(config('app.locale') != 'en')
          <img src="img/fujiki.JPG" width="90px" "alt=" 藤木清志" style="margin:2px 0px 0px;">
          @endif -->
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
