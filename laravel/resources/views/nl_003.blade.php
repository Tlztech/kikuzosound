@extends('layouts.app')

@section('title', 'nl_003')

@section('breadcrumb')
{!! Breadcrumbs::render('nl_003') !!}
@endsection

@section('content')

<!-- ニュースレター -->
  <div class="nl" style="margin-top:40px;">
    <div class="nl_abstract_outline">
        <div class="nl_abstract">
          @lang('nl_003.elephant') News Letter <span class="nl_volume">Vol.3</span>
        </div>
    </div>
    <div class="nl_title_outline">
      <div class="nl_title3">@lang('nl_003.title')</div>
    </div>
    <div class="c_container">
      <div class="oneline">
        <ul>
          <li>
            <div class="mat1">
              <img src="{{{asset('img/saraya.jpg')}}}" style="width:70%;height:auto;">
              <p style="text-align:center;font-weight: bold;">@lang('nl_003.tanigu')</p>
              <p style="font-size:12px;font-weight: bold;">
                @lang('nl_003.description')
                <br>
              </p>
              <p class="nl_sub_title3">@lang('nl_003.subtitle_1')</p>
              <hr class="nl_hr3">
              <p>
                @lang('nl_003.paragraph_1_1')
                <img src="{{{asset('img/laennec.png')}}}" style="width:150px;height:auto;float:right;margin:6px;">
                @lang('nl_003.paragraph_1_2')
              </p>
              <p>@lang('nl_003.paragraph_2')</p>
            </div>
          </li>
          <li>
            <div class="mat2">
              <p class="nl_sub_title3">@lang('nl_003.subtitle_2')</p>
              <hr class="nl_hr3">
              <p>@lang('nl_003.paragraph_3')</p>
              <p class="nl_sub_title3">@lang('nl_003.subtitle_3')</p>
              <hr class="nl_hr3">

              <ol style="margin-left:14px;">
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  @lang('nl_003.list_1_1')
                </li>
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  @lang('nl_003.list_1_2')
                </li>
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  @lang('nl_003.list_1_3')
                </li>
              </ol>
            </div>
          </li>
          <li>
            <div class="mat3">
              <p class="nl_sub_title3">@lang('nl_003.subtitle_4')</p>
              <hr class="nl_hr3">
              <p>@lang('nl_003.paragraph_4')</p>
              <p class="nl_sub_title3">@lang('nl_003.subtitle_5')</p>
              <hr class="nl_hr3">
              <p>@lang('nl_003.paragraph_5')</p>
              <img src="{{{asset('img/kikuzou.jpg')}}}" style="width:90%;height:auto;">
              <ol style="margin-left:14px;">
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  @lang('nl_003.list_2_1')
                </li>
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  Sakula A. R T H Laennec 1781--1826 his life and work: a bicentenary appreciation. Thorax. 1981;36(2):81-90.
                </li>
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  Mikami R, Murao M, Cugell DW, Chretien J, Cole P, Meier-Sydow J, et al. International Symposium on Lung Sounds. Synopsis of proceedings. Chest. 1987;92(2):342-5.
                </li>
                <li style="left:0px;text-align:left;font-size:14px;line-height:140%;">
                  Saraya T, Ohkuma K, Tsukahara Y, Watanabe T, Kurai D, Ishii H,et al.<br>
                  Correlation between clinical features, high-resolution computed tomography findings, and a visual scoring system in patients with pneumonia due to Mycoplasma pneumoniae. Respir Investig. 2018 Jul;56(4):320-325
                </li>
              </ol>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="nl_text">
      <p class="sp_none" style="text-align:center;">
        <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;margin-bottom:0px;">@lang('nl_003.btn_text')</a>
      </p>
      <p class="pc_none" style="text-align:center;">
        <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;width: 240px;">@lang('nl_003.btn_text')</a>
      </p>
    </div>
  </div>
  <!-- /#container -->

@endsection
