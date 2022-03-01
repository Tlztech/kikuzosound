@extends('layouts.app')

@section('title', 'Contact Form')

@section('breadcrumb')
{!! Breadcrumbs::render('contact_form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('contact_form_confirm') }}" method="GET" novalidate>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('quote.enter_inquiry')</h2>
          <p class="mB10 t_left">
          <ul class="contact_form_inquiry">
            <li>@lang('quote.li1')</li>
            <li>@lang('quote.li2') <a href="{{ route('faq') }}">@lang('quote.li2link')</a> @lang('quote.li2last')</li>
            @if(config('app.locale') != 'en')
              <li>@lang('quote.li3')「 <a href="{{ route('privacy') }}">@lang('quote.li3last')</a> 」@lang('quote.li3link')</li>
            @else 
              <li>@lang('quote.li3') @lang('quote.li3last') <a href="{{ route('privacy') }}">@lang('quote.li3link')</a>.</li>
            @endif
            <br/>
            <li>@lang('quote.li4')</li>
            <li>@lang('quote.li5')</li>
            <li>@lang('quote.li6')</li>
          </p>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('quote.corporation')</p>
              <input name="group" type="text" maxlength="25" value="{{ old('group') }}" placeholder="@lang('quote.placeholder')"/>
              @if($errors->has('group'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('group') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('quote.name')</p>
              <input name="name" type="text" maxlength="25" value="{{ old('name') }}"/>
              @if($errors->has('name'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('name') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('quote.mail')</p>
              <input name="mail" type="text" maxlength="100" value="{{ old('mail') }}"/>
              @if($errors->has('mail'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('mail') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('quote.phone')</p>
              <input name="tel" maxlength="15" value="{{ old('tel') }}" type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
              @if($errors->has('tel'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('tel') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('quote.inquiry_type')</p>
              <select name="kind">
                <option value='4' {{ old('kind','4') == '4' ? 'selected' : '' }}>@lang('quote.quote')</option>
                <option value='1' {{ old('kind') == '1' ? 'selected' : '' }}>@lang('quote.demonstration')</option>
                <option value='2' {{ old('kind') == '2' ? 'selected' : '' }}>@lang('quote.purchase')</option>
                <option value='3' {{ old('kind') == '3' ? 'selected' : '' }}>@lang('quote.other')</option>
              </select>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('quote.inquiry_contents')</p>
              <textarea name="question" type="text" maxlength="500" wrap="soft" placeholder="@lang('quote.placeholder2')" />{{ old('question') }}</textarea>
              @if($errors->has('question'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('question') }}</p>
              @endif
            </label>
          </li>
        </ul>
        <div class="contents_box_inner pTB20">
          <p class="mTB20" style="text-align: center;margin-top: 40px;">
          @if(config('app.locale') != 'en')
          「 <a href="{{ route('privacy') }}">@lang('quote.privacy')</a>
          @endif
          @lang('quote.aggrement')
          @if(config('app.locale') == 'en')
          "<a href="{{ route('privacy') }}">@lang('quote.privacy')</a>"
          @endif
          @lang('quote.aggrement_2')
          </p>
          <p class="contact_submit mB20"><a href="{{ route('contact') }}" class="submit_backbtn">@lang('quote.return')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value="@lang('quote.send')"></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
