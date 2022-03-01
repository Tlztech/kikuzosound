@extends('layouts.app')

@section('title', 'use')

@section('breadcrumb')
{!! Breadcrumbs::render('use') !!}
@endsection

@section('content')

<!-- メイン聴診音ライブラリ（左） -->
  <div id="container">

    <div class="vest">
        <div class="vest_title">@lang('use.title')</div>
        <div class="vest_text">
            <p>@lang('use.p1a')<br>@lang('use.p1b')</p>
            <p>@lang('use.p2')</p>
        </div>
    </div>

    <div class="container_inner">
      <div class="youtube_contents">
        <?php $USE_YOUTUBE_URL = env('USE_YOUTUBE_URL'); ?>
        <iframe src="{{ $USE_YOUTUBE_URL }}" frameborder="0" allowfullscreen class="sp_none"></iframe>
        <iframe src="{{ $USE_YOUTUBE_URL }}" frameborder="0" allowfullscreen class="pc_none"></iframe>

        <!--  <p class="link_red"><a href="pdf/3s.pdf" target="_blank">資料はこちらです>>></a></p>
        <p class="link_red"><a href="https://telemedica.jp/kikuzou" target="_blank">ご注文はこちらです>>></a></p> -->
      </div>

      <div class="contents_box_inner pTB20" style="display:none;">
        <p class="contact_submit mB20 sp_none">
          <a href="pdf/3s.pdf" target="_blank" class="submit_backbtn" style="background: #47B8E8;">資料はこちら</a>
          <a class="submit_backbtn" href="https://telemedica.jp/kikuzou" target="_blank" style="background: #47B8E8;width: cursor:pointer;">ご注文はこちら</a>
          <a class="submit_btn" href="http://api.telemedica.jp/download/vest.docx" target="_blank" style="cursor:pointer;">聴くゾウベスト(Word)</a>
        </p>

        <p class="contact_submit mB20 pc_none" style="text-align: center;margin-top:10px;">
          <a href="pdf/3s.pdf" target="_blank" class="submit_backbtn" style="background: #47B8E8;width: 240px;">資料はこちら</a><br><br>
          <a class="submit_backbtn" href="https://telemedica.jp/kikuzou" target="_blank" style="background: #47B8E8;cursor:pointer;width: 240px;">ご注文はこちら</a><br><br>
          <a class="submit_btn" href="http://api.telemedica.jp/download/vest.docx" target="_blank" style="cursor:pointer;width: 240px;">聴くゾウベスト(Word)</a>
        </p>
      </div>
    </div>
    <!-- /#container_inner -->
  </div>
  <!-- /#container -->

</div>
@endsection
