@extends('layouts.app')

@section('title', 'speaker')

@section('breadcrumb')
{!! Breadcrumbs::render('speaker') !!}
@endsection

@section('content')

<!-- メイン聴診音ライブラリ（左） -->
<div id="container">
  <div class="container_inner">
    <div class="contents_box_inner ">
      <p class="contact_submit sp_none">
        <?php
          $manual_pdf = config('app.locale') == 'en' ? "pdf/3s_20171205_en.pdf" : "pdf/3s_20171205.pdf"
        ?>
        <a href="{{{asset($manual_pdf)}}}" target="_blank" class="submit_backbtn"
            style="background: #47B8E8;">@lang('speaker.info')
				</a>
      </p>
      <p class="contact_submit pc_none" style="text-align: center; margin-top:10px;">
        <?php
          $manual_pdf = config('app.locale') == 'en' ? "pdf/3s_20171205_en.pdf" : "pdf/3s_20171205.pdf"
        ?>
        <a href="{{{asset($manual_pdf)}}}" target="_blank" class="submit_backbtn"
            style="background: #47B8E8;width: 240px;">@lang('speaker.info')</a><br><br>
    	</p>
    </div>
			<div class="youtube_contents" style="margin-top:10px;">
					<?php $SPEAKER_YOUTUBE_URL = env('SPEAKER_YOUTUBE_URL'); ?>
					<iframe src="{{ $SPEAKER_YOUTUBE_URL }}" frameborder="0" allowfullscreen class="sp_none"></iframe>
					<iframe src="{{ $SPEAKER_YOUTUBE_URL }}" frameborder="0" allowfullscreen class="pc_none"></iframe>

					<!--  <p class="link_red"><a href="pdf/3s.pdf" target="_blank">資料はこちらです>>></a></p>
			<p class="link_red"><a href="https://telemedica.jp/kikuzou" target="_blank">ご注文はこちらです>>></a></p> -->
			</div>
    </div>
    <!-- /#container_inner -->
    <div class="speaker-section vest">
        <div class="vest_title">@lang('speaker.vest_title')</div>
        <div class="vest_text">
            <p>@lang('speaker.vest_text')</p>
        </div>
        <div class="contents_box_inner pTB20">
            <p class="contact_submit mB20 sp_none">
                <a class="submit_btn" href="/register"
                    style="cursor:pointer;margin-bottom:0px;">@lang('speaker.register')</a>
            </p>

            <p class="contact_submit mB20 pc_none" style="text-align:center;">
                <a class="submit_btn" href="/register"
                    style="cursor:pointer;width: 240px;">@lang('speaker.register')</a>
            </p>
        </div>
        <div class="vest_text">
            <p>@lang('speaker.inquire_text')</p>
        </div>
    </div>

    <!-- <div class="vest">
        <div class="vest_title">@lang('speaker.vest_title')</div>
        <div class="vest_text">
            <p>@lang('speaker.vest_text')</p>
        </div>
        <div class="contents_box_inner pTB20">
            <p class="contact_submit mB20 sp_none">
                <a class="submit_btn" href="{{route('register')}}" style="cursor:pointer;margin-bottom:0px;">@lang('speaker.register')</a>
            </p>

            <p class="contact_submit mB20 pc_none" style="text-align:center;">
                <a class="submit_btn" href="{{route('register')}}" style="cursor:pointer;width: 240px;">@lang('speaker.register')</a>
            </p>
        </div>
        <div class="vest_text">
            <p>@lang('speaker.inquire_text')</p>
        </div>
    </div>

  </div> -->
    <!-- /#container -->

</div>
@endsection