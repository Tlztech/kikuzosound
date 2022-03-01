@extends('layouts.app')

@section('title', 'nl_backnumber')

@section('breadcrumb')
{!! Breadcrumbs::render('nl_backnumber') !!}
@endsection

@section('content')

<!-- バックナンバー -->
  <div class="bn" style="margin-top:40px;">
    <ul class="ultable">
      <li class="first column_title" style="padding:6px;">@lang('nl_backnumber.title')</li>
    </ul>

    <?php $lang = (Config::get('app.locale') != "ja") ? "_en" : ""; //get locale ?>

    <ul class="ultable" style="background-color: #eeeeee;">
      <li class="column_vol">@lang('nl_backnumber.date_5')</li>
      <li>Vol.5 <a href="pdf/KKZ-NL-005<?php echo $lang; ?>.pdf" target="_blank">(@lang('nl_backnumber.pdf'))</a></li>
    </ul>
    <ul class="ultable">
      <li class="column_photo" style="padding:0px 0px 0px 0px;"><img src="{{{asset('img/kudou.jpg')}}}" style="width:100%;height:auto;"></li>
      <li class="column_name">@lang('nl_backnumber.kenji')</li>
      <li><a href="{{route('nl_005')}}">@lang('nl_backnumber.filename_5')</a></li>
    </ul>

    <ul class="ultable" style="background-color: #eeeeee;">
      <li class="column_vol">@lang('nl_backnumber.date_1')</li>
      <li>Vol.4 <a href="pdf/KKZ-NL-004<?php echo $lang; ?>.pdf" target="_blank">(@lang('nl_backnumber.pdf'))</a></li>
    </ul>
    <ul class="ultable">
      <li class="column_photo" style="padding:0px 0px 0px 0px;"><img src="{{{asset('img/kudou2.png')}}}" style="width:100%;height:auto;"></li>
      <li class="column_name">@lang('nl_backnumber.shoji')</li>
      <li><a href="{{route('nl_004')}}">@lang('nl_backnumber.filename_1')</a></li>
    </ul>

    <ul class="ultable" style="background-color: #eeeeee;">
      <li class="column_vol">@lang('nl_backnumber.date_2')</li>
      <li>Vol.3 <a href="pdf/KKZ-NL-003<?php echo $lang; ?>.pdf" target="_blank">(@lang('nl_backnumber.pdf'))</a></li>
    </ul>
    <ul class="ultable">
      <li class="column_photo" style="padding:0px 0px 0px 0px;"><img src="{{{asset('img/saraya.jpg')}}}" style="width:100%;height:auto;"></li>
      <li class="column_name">@lang('nl_backnumber.ken')</li>
      <li><a href="{{route('nl_003')}}">@lang('nl_backnumber.filename_2')</a></li>
    </ul>

    <ul class="ultable" style="background-color: #eeeeee;">
      <li class="column_vol">@lang('nl_backnumber.date_3')</li>
      <li>Vol.2 <a href="pdf/KKZ-NL-002<?php echo $lang; ?>.pdf" target="_blank">(@lang('nl_backnumber.pdf'))</a></li>
    </ul>
    <ul class="ultable">
      <li class="column_photo" style="padding:0px 0px 0px 0px;"><img src="{{{asset('img/hazama.jpg')}}}" style="width:100%;height:auto;"></li>
      <li class="column_name">@lang('nl_backnumber.kenzama')</li>
      <li><a href="{{route('nl_002')}}">@lang('nl_backnumber.filename_3')</a></li>
    </ul>

    <ul class="ultable" style="background-color: #eeeeee;">
      <li class="column_vol">@lang('nl_backnumber.date_4')</li>
      <li>Vol.1 <a href="pdf/KKZ-NL-001<?php echo $lang; ?>.pdf" target="_blank">(@lang('nl_backnumber.pdf'))</a></li>
    </ul>
    <ul class="ultable">
      <li class="column_photo" style="padding:0px 0px 0px 0px;"><img src="{{{asset('img/takashina.jpg')}}}" style="width:100%;height:auto;"></li>
      <li class="column_name">@lang('nl_backnumber.jixing')</li>
      <li><a href="{{route('nl_001')}}">@lang('nl_backnumber.filename_4')</a></li>
    </ul>

  </div>

@endsection
