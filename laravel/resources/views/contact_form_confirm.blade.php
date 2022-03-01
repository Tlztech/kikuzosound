@extends('layouts.app')

@section('title', 'お問合わせフォーム 確認')

@section('breadcrumb')
{!! Breadcrumbs::render('contact_form_confirm') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('contact_form_send_mail') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('contact_confirmation.msg_confrimation')</h2>
          <p class="mB10 t_left">
            @lang('contact_confirmation.note_1')<br/>
            @lang('contact_confirmation.note_2_1') <a href="{{ route('faq') }}">@lang('contact_confirmation.faq')</a> @lang('contact_confirmation.note_2_2')<br/>
            @lang('contact_confirmation.note_3_1') <a href="{{ route('privacy') }}">@lang('contact_confirmation.privacy_policy')</a> @lang('contact_confirmation.note_3_2')<br/>
<br/>
            @lang('contact_confirmation.note_4')<br/>
            @lang('contact_confirmation.note_5')<br/>
            @lang('contact_confirmation.note_6')<br/>
          </p>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name">@lang('contact_confirmation.corporation')</p>
              <input name="group" type="hidden" maxlength="25" value="{{ $group }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $group }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('contact_confirmation.name')</p>
              <input name="name" type="hidden" maxlength="25" value="{{ $name }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $name }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('contact_confirmation.email_address')</p>
              <input name="mail" type="hidden" maxlength="100" value="{{ $mail }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $mail }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('contact_confirmation.phone_number')</p>
              <input name="tel" type="hidden" maxlength="20" value="{{ $tel }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $tel }}</p>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('contact_confirmation.inquiry_type')</p>
              <input name="kind" type="hidden" maxlength="20" value="{{ $kind }}"/>
              <select name="">
                <option disabled value='4' {{ old('kind') == '4' ? 'selected' : '' }}>@lang('contact_confirmation.kind_4')</option>
                <option disabled value='1' {{ old('kind') == '1' ? 'selected' : '' }}>@lang('contact_confirmation.kind_1')</option>
                <option disabled value='2' {{ old('kind') == '2' ? 'selected' : '' }}>@lang('contact_confirmation.kind_2')</option>
                <option disabled value='3' {{ old('kind') == '3' ? 'selected' : '' }}>@lang('contact_confirmation.kind_3')</option>
              </select>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('contact_confirmation.inquiry_content')</p>
              <input name="question" type="hidden" maxlength="500" wrap="soft" value="{{ $question }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{!! nl2br($question, false) !!}</p>
            </label>
          </li>
        </ul>
        <div class="contents_box_inner pTB20">
          <p class="mTB20" style="text-align: center;margin-top: 40px;">
          @if(config('app.locale') != 'en')
          「 <a href="{{ route('privacy') }}">@lang('contact_confirmation.privacy')</a>
          @endif
          @lang('contact_confirmation.aggrement')
          @if(config('app.locale') == 'en')
          "<a href="{{ route('privacy') }}">@lang('contact_confirmation.privacy')</a>"
          @endif
          @lang('contact_confirmation.aggrement_2')
          </p>
          </p>
          <p class="contact_submit mB20"><a href="{{ route('contact_form') }}" class="submit_backbtn">@lang('contact_confirmation.button_return')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value=@lang('contact_confirmation.button_send')></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
