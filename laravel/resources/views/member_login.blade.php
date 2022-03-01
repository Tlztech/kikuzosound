@extends('layouts.app')

@section('title', trans('app.login'))

@section('breadcrumb')
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container">
    <!-- ログインフォーム -->
    <div class="container_inner clearfix">
        <div class="contents_box sp_mTB20 mTB20 t_center">
            <form action="member_login" method="POST" id="loginForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="contents_box_inner">
                    <h2 class="title_m mTB20" style="text-align: center;">@lang('login.title')</h2>
                    <div style="max-width:650px;margin:0px auto 20px;text-align: left;">
                        <p style="margin:0px 0px 0px; text-align:center;">
                            @lang('login.info.l1')
                        </p>
                    </div>
                </div>
                <ul class="contact_form login_form">
                    <li>
                        <label for="" class="">
                            <p class="input_name must">@lang('login.id') @if($errors->has('user'))
                            <br class="pc_none">
                                <span class="required1">@lang('customer_registration.required')</span>
                                @else {{ $errors->first('user') }} @endif</p>
                            <input name="user" type="text" maxlength="1000" value="{{ old('user') }}" />
                            <!-- @if($errors->has('user'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;"> @if (Config::get('app.locale') == 'en')
                            Please enter your user ID. @else {{ $errors->first('user') }} @endif</p>
                            @endif -->

                            @if( $id_err !== 0)
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0px 0px 10px 10px;width: 372px;text-align: center;">
                                {{ $id_err }}</p>
                            @endif
                        </label>
                    </li>
                    <li>
                        <label for="" class="email" id="email">
                            <p class="input_name must">@lang('login.mail') @if($errors->has('mail_address'))
                            <br class="pc_none">
                                <span class="required1">@lang('customer_registration.required')</span>
                                @else {{ $errors->first('mail_address') }} @endif</p>
                            <input name="mail_address" type="text" maxlength="255" value="{{ old('mail_address') }}" />
                            <!-- @if($errors->has('mail_address'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'en')
                            Please enter your mail address . @else {{ $errors->first('mail_address') }} @endif</p>
                            @endif -->
                            @if( $mail_err !== 0)
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0px 0px 10px 10px;width: 372px;text-align: center;">
                                {{ $mail_err }}</p>
                            @endif
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must">@lang('login.pass') @if($errors->has('password'))
                                <br class="pc_none">
                                <span class="required1">@lang('customer_registration.required')</span>
                                @else {{ $errors->first('password') }} @endif</p>
                            <input name="password" type="password" maxlength="1000" value="{{ old('password') }}" />
                            <!-- @if($errors->has('password'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if (Config::get('app.locale') == 'en')
                            Enter your password. @else {{ $errors->first('password') }} @endif</p>
                            @endif -->
                            @if( $pass_err !== 0)
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0px 0px 10px 10px;width: 372px;text-align: center;">
                                {{ $pass_err }}</p>
                            @endif
                        </label>
                    </li>
                    <!-- <li>
                        <label for="" class="">
                            <p class="input_name must"></p>
                            <input name="product_no" type="text" maxlength="20" value="{{ old('product_no') }}"/>
                            @if($errors->has('product_no'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'en')
                            Please enter your serial number. @else {{ $errors->first('product_no') }} @endif</p>
                            @endif
                            <p class="mB10 t_left">
                            @lang('login.sn_info')<br/>
                            </p>
                        </label>
                    </li> -->
                </ul>
                <div class="contents_box_inner pTB20">
                    <div class="msg_area" style="width:90%">
                        <p class="msg_err">{!! $msg !!}</p><br>
                        @if(env('APP_DEBUG'))
                        <!-- <span class="msg_err">{!! $msg_err !!}</span><br> -->
                        @endif
                    </div>
                    <p class="contact_submit mB20">
                        <input class="submit_btn" style="cursor:pointer;" type="button" onclick="submitForm()"
                            value=@lang('login.login')></input>
                    </p>
                    <div class="links_container">
                        <div>
                        <p><a href="{{route('reset_password')}}" class="links">@lang('login.link_1')</a></span></p>
                        <p><a href="{{route('reapply_browser')}}" class="links">@lang('login.link_2')</a></p>
                        <p><a href="{{route('email_verification')}}" class="links">@lang('login.resend_email')</a></p>
                        </div>
                    </div>
                </div>
            </form>

            <div class="vest">
                <div class="vest_title">@lang('login.register_info')</div>
                <div class="vest_text">
                    <p>@lang('login.register_msg')</p>
                </div>
                <div class="sharedPC_text">
                    <a href="{{route('faq')}}#IpaxSharedPc">@lang('login.shared_pc_note')</href>
                </div>
                <div class="contents_box_inner pTB20 login-page">
                    <p class="contact_submit mB20 sp_none">
                        <a type="button" class="submit_btn" style="cursor:pointer;margin-bottom:0px;"
                            onclick="openModal()">@lang('login.go_register_btn')</a>
                    </p>

                    <p class="contact_submit mB20 pc_none" style="text-align:center;">
                        <a type="button" class="submit_btn" onclick="openModal()" style="cursor:pointer;width: 240px;"
                            id="open-modal">@lang('login.go_register_btn')</a>
                    </p>
                </div>
            </div>
            <div class="vest_text">
                <p>※ @lang('login.inquire_msg')</p>
            </div>
        </div>
    </div>
</div>
<script>
function submitForm() {
    var html = '<input type="hidden" name="bwtk" value="' + localStorage.getItem('bwtk') + '">'
    $("#email").append(html);
    document.getElementById("loginForm").submit();
}

function openModal() {
    var contents = document.createElement("div");
    contents.innerHTML =
        "<h2 class='h2-title'> @lang('login.register_modal_heading')</h2><p>Google Chrome, Safari.<br>※@lang('login.browser_not_supported')<br><br> @lang('login.register_modal_body')</p>";
    swal({ 
        content: contents,
    }).then(function(isConfirm) {
        if (isConfirm) {
            window.location = "/customer_registration";
        }
    })
}
</script>

@endsection