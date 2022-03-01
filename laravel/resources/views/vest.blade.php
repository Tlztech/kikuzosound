@extends('layouts.app')

@section('title', 'vest')

@section('breadcrumb')
{!! Breadcrumbs::render('vest') !!}
@endsection

@section('content')

<!-- メイン聴診音ライブラリ（左） -->
  <div class="vest" style="margin-top:40px;">
    <div class="vest_title">@lang('vest.title')</div>
    <div class="vest_text">
      <p>
        @lang('vest.p1')
      </p>
      <p>
        @lang('vest.p2')
      </p>
    </div>
    <div class="c_container">
      <div class="oneline">
        <ul>
          <li>
            <div class="mat1">
              <img src="{{{asset('img/vest1.jpg')}}}" style="width:100%;height:auto;">
              <p>@lang('vest.pic1')</p>
            </div>
          </li>
          <li>
            <div class="mat2">
              <img src="{{{asset('img/vest2.jpg')}}}" style="width:100%;height:auto;">
              <p>@lang('vest.pic2')</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="vest_text">
      <p style="text-align: center;font-weight:bold;">
        @lang('vest.p3')
      </p>
      <p>
        @lang('vest.p4')
      </p>
      <p>
        @lang('vest.p5')
      </p>
      <br>
      <p style="text-align: center;">
      @if(Config::get('app.locale') == 'en')
        <img src="{{{asset('img/vest3_en.png')}}}" style="width:80%;height:auto;">
      @else
      <img src="{{{asset('img/vest3.png')}}}" style="width:80%;height:auto;">
      @endif

      </p>
      <br>
      <p>
        @lang('vest.p6')
      </p>
      <p>
      <?php 
        $vestFilename_pdf = Config::get('app.locale') == 'en' ? "downloads/vest_en.pdf" : "downloads/vest.pdf";
      ?>
        @lang('vest.p7a')「<a href="{{{asset($vestFilename_pdf)}}}" target="_blank">@lang('vest.p7b')</a>」@lang('vest.p7c')
      </p>
      <p>
        @lang('vest.p8a')<a href="{{route('contact')}}">@lang('vest.p8b')</a>@lang('vest.p8c')
      </p>
    </div>
  </div>

  <?php 
    $vestFilename = Config::get('app.locale') == 'en' ? "downloads/vest_en.docx" : "downloads/vest.docx";
  ?>

  <div id="container">
    <div class="container_inner">
      <div class="contents_box_inner pTB20">
        <p class="contact_submit mB20 sp_none">
          <a class="submit_btn" href="{{{asset($vestFilename)}}}" target="_blank" style="cursor:pointer;">@lang('vest.submit_button')</a>
        </p>

        <p class="contact_submit mB20 pc_none" style="text-align: center;margin-top:10px;">
          <a class="submit_btn" href="{{{asset($vestFilename)}}}" target="_blank" style="cursor:pointer;width: 240px;">@lang('vest.submit_button')</a>
        </p>
      </div>
    </div>
    <!-- /#container_inner -->
  </div>
  <!-- /#container -->

</div>
@endsection
