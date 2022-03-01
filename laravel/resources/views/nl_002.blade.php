@extends('layouts.app')

@section('title', 'nl_002')

@section('breadcrumb')
{!! Breadcrumbs::render('nl_002') !!}
@endsection

@section('content')

<!-- ニュースレター -->
  <div class="nl" style="margin-top:40px;">
    <div class="nl_abstract_outline">
        <div class="nl_abstract">
            @lang('nl_002.elephant') News Letter <span class="nl_volume">Vol.2</span>
        </div>
    </div>
    <div class="nl_title_outline">
      <div class="nl_title2">@lang('nl_002.title')</div>
    </div>
    <div class="c_container">
      <div class="oneline">
        <ul>
          <li>
            <div class="mat1">
              <img src="{{{asset('img/hazama.jpg')}}}" style="width:70%;height:auto;">
              <p style="text-align:center;font-weight: bold;">@lang('nl_002.kenji')</p>
              <p style="font-size:12px;font-weight: bold;">@lang('nl_002.description')<br></p>
              <p>@lang('nl_002.paragraph_1')</p>
              <p class="nl_sub_title2">@lang('nl_002.subtitle_1')</p>
              <hr class="nl_hr2">
              <p>@lang('nl_002.paragraph_2')</p>
            </div>
          </li>
          <li>
            <div class="mat2">
              <p class="nl_sub_title2">@lang('nl_002.subtitle_2')</p>
              <hr class="nl_hr2">
              <p>@lang('nl_002.paragraph_3')</p>
              <p class="nl_sub_title2">@lang('nl_002.subtitle_3')</p>
              <hr class="nl_hr2">
              <p>@lang('nl_002.paragraph_4')</p>
            </div>
          </li>
          <li>
            <div class="mat3">
              <p class="nl_sub_title2">@lang('nl_002.subtitle_4')</p>
              <hr class="nl_hr2">
              <p>@lang('nl_002.paragraph_5')</p>
              <p>@lang('nl_002.method_1')</p>
              <br>
              <p>@lang('nl_002.method_2')</p>
              <br>
              <p>@lang('nl_002.method_3')</p>
              <br>
              <p>@lang('nl_002.paragraph_6')</p>
              <img src="{{{asset('img/kikuzou.jpg')}}}" style="width:90%;height:auto;">
              <p style="font-size:12px;font-weight: bold;">
                @lang('nl_002.image_caption')
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="nl_text">
      <p class="sp_none" style="text-align:center;">
        <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;margin-bottom:0px;">@lang('nl_002.btn_text')</a>
      </p>
      <p class="pc_none" style="text-align:center;">
        <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;width: 240px;">@lang('nl_002.btn_text')</a>
      </p>
    </div>
  </div>
  <!-- /#container -->

@endsection
