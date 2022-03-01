@extends('layouts.app')

@section('title', 'nl_001')

@section('breadcrumb')
{!! Breadcrumbs::render('nl_001') !!}
@endsection

@section('content')

<!-- ニュースレター -->
  <div class="nl" style="margin-top:40px;">
    <div class="nl_abstract_outline">
        <div class="nl_abstract">
            @lang('nl_001.elephant') News Letter <span class="nl_volume">Vol.1</span>
        </div>
    </div>
    <div class="nl_title_outline">
      <div class="nl_title">@lang('nl_001.title')</div>
    </div>
    <div class="c_container">
      <div class="oneline">
        <ul>
          <li>
            <div class="mat1">
              <img src="{{{asset('img/takashina.jpg')}}}" style="width:70%;height:auto;">
              <p style="text-align:center;font-weight: bold;">@lang('nl_001.jijie')</p>
              <p style="font-size:12px;font-weight: bold;">
                @lang('nl_001.description_1')<br>
                @lang('nl_001.description_2')<br>
                @lang('nl_001.description_3')<br>
                @lang('nl_001.description_4')<br>
                @lang('nl_001.description_5')<br>
              </p>
              <p>
                @lang('nl_001.paragraph_1')
              </p>

              <hr>
              <p class="nl_sub_title">
                @lang('nl_001.subtitle_1')
              </p>

              <p>
                @lang('nl_001.paragraph_2')
                <br>
                @lang('nl_001.paragraph_3')
              </p>
            </div>
          </li>
          <li>
            <div class="mat2">
              <p>
                @lang('nl_001.paragraph_4')
              <br>
                @lang('nl_001.paragraph_5')
              </p>
              <hr>
              <p class="nl_sub_title">
                @lang('nl_001.subtitle_2')
              </p>
              <p>
                @lang('nl_001.paragraph_6')
              </p>

              <hr>
              <p class="nl_sub_title">
                @lang('nl_001.subtitle_3')
              </p>
              <p>
                @lang('nl_001.paragraph_7')
              </p>
              <hr>
              <p class="nl_sub_title">
                @lang('nl_001.subtitle_4')
              </p>
              <p>
                @lang('nl_001.paragraph_8')
              </p>
            </div>
          </li>
          <li>
            <div class="mat3">
              <p>
                @lang('nl_001.paragraph_9')
              </p>
              <br>
              <p>
                @lang('nl_001.paragraph_10')
              </p>
              <img src="{{{asset('img/kikuzou.jpg')}}}" style="width:90%;height:auto;">
              <p style="font-size:12px;font-weight: bold;">
                @lang('nl_001.image_caption')
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="nl_text">
      <p class="sp_none" style="text-align:center;">
        <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;margin-bottom:0px;">@lang('nl_001.btn_text')</a>
      </p>
      <p class="pc_none" style="text-align:center;">
        <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;width: 240px;">@lang('nl_001.btn_text')</a>
      </p>
    </div>
  </div>
  <!-- /#container -->

@endsection
