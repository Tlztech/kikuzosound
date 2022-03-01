@if(Session::has('message'))
<?php session(['isRegistered' => 1]); // registered 
?>
<script>
    // var contents = document.createElement("div");
    // contents.innerHTML =
    //     "<h2>Thank you for registering at <br> Kikuzosound.com. <br> Please click the verification link in the email we just sent you to activate your account. <h2><br><h3>@lang('customer_registration.h3')</h3><h4>@lang('customer_registration.h4-1') <br>" +
    //     browserUsed + " <br> </h4>";
    // swal({
    //     content: contents,
    // }).then(function() {
    window.location = "/alert";
    // });
</script>
@endif

@extends('layouts.app')

@section('title', trans('app.registration'))


@section('breadcrumb')
@endsection

@section('content')
<div id="container">
    <div class="container_inner clearfix">
        <div class="contents_box sp_mTB20 mTB20 t_center">
            <form action="customer_registration" method="POST" id="customer_registration" autocomplete="off">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="contents_box_inner">
                    <h2 class="title_m mTB20" style="text-align: center;">@lang('customer_registration.customer_info')
                    </h2>
                </div>
                <ul class="contact_form customer_registration">
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.corp_name')
                                <span class="required1">@lang('customer_registration.required')</span>
                            </p>
                            <input name="company" type="text" value="{{ old('company') }}" id="company" />
                            <p class="msg_err">
                                @if($errors->has('company'))
                                {{$errors->first('company')}}
                                @endif
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.name')
                                <span class="required2">@lang('customer_registration.required')</span>
                            </p>
                            <input name="name" type="text" value="{{ old('name') }}" id="name" />
                            <p class="msg_err">
                                @if($errors->has('name'))
                                {{$errors->first('name')}}
                                @endif
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.user_id')
                                <span class="required3">@lang('customer_registration.required')</span>
                            </p>
                            <p class="upper_note">*@lang('customer_registration.arbitrary_user_id')</p>
                            <input name="userId" type="text" value="{{ old('userId') }}" id="userId" />
                            <p class="msg_err">{!! $msg_error_userID !!}
                                @if($errors->has('userId'))
                                {{$errors->first('userId')}}
                                @endif
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.mail_address')
                                <span class="required4">@lang('customer_registration.required')</span>
                            </p>
                            <input name="cust_mail_address" type="text" value="{{ old('cust_mail_address') }}" id="cust_mail_address" />
                            <p class="invalid_email msg_err" style="display:none">
                                @lang('customer_registration.invalid_email')</p>
                            <p class="msg_err">{!! $msg_error_account_mail !!} @if($errors->has('cust_mail_address'))
                                {{$errors->first('cust_mail_address')}} @endif
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.mail_address_confirm')
                                <span class="required8">@lang('customer_registration.required')</span>
                            </p>
                            <input name="cust_mail_address_confirm" type="text" value="{{ old('cust_mail_address_confirm') }}" id="cust_mail_address_confirm" />
                            <p class="invalid_email_confirm msg_err" style="display:none">
                                @lang('customer_registration.invalid_email')</p>
                            <p class="invalid_email_confirm_match msg_err" style="display:none">
                                @lang('customer_registration.mail_address_match')</p>
                            <p class="msg_err">{!! $msg_error_account_mail !!} @if($errors->has('cust_mail_address_confirm'))
                                {{$errors->first('cust_mail_address_confirm')}} @endif
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.password')
                                <span class="required5">@lang('customer_registration.required')</span>
                            </p>
                            <p class="upper_note">*@lang('customer_registration.please_set_password')</p>
                            <input name="password" type="password" value="{{ old('password') }}" id="password" 
                            onpaste="return false" autocomplete="new-password" pattern="^[a-zA-Z0-9]*$" onkeyup="checkChar(this,'pass')">
                            <span toggle="#password" class="toggle-password eye-img">
                                <img src="/img/eye.png" class="eye">
                                <img src="/img/eye-slash.png" class="eye-slash" style="display:none">
                            </span>
                            <p class="msg_err">{!! $msg_error_pass !!}</p>
                            <p class="pass_err msg_err" style="display:none">
                                @if($errors->has('password'))
                                {{$errors->first('password')}}
                                @endif
                            </p>
                            <p id="double_byte" class="byte_err" style="display:none">
                                @lang('customer_registration.double_byte_pass_err')
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.password_confirm')
                                <span class="required6">@lang('customer_registration.required')</span>
                            </p>
                            <p class="upper_note">@lang('customer_registration.sub_label')</p>
                            <input name="passwordConfirm" type="password" value="{{ old('passwordConfirm') }}" id="passwordConfirm" 
                            onpaste="return false" autocomplete="new-password" pattern="^[a-zA-Z0-9]*$" onkeyup="checkChar(this,'confirm')" />
                            <span toggle="#passwordConfirm" class="toggle-passwordConfirm eye-img">
                                <img src="/img/eye.png" class="eye-2">
                                <img src="/img/eye-slash.png" class="eye-slash-2" style="display:none">
                            </span>
                            <p class="msg_err">{!! $msg_error_confirim_pass !!}</p>
                            <p class="mismatch_pass msg_err" style="display:none">

                                @if($errors->has('passwordConfirm'))
                                {{$errors->first('passwordConfirm')}}
                                @endif
                            </p>
                            <p id="double_byte_confirm" class="byte_err" style="display:none">
                                @lang('customer_registration.double_byte_pass_err')
                            </p>
                        </label>
                    </li>
                </ul>

                <div class="contents_box_inner">
                    <h2 class="title_m mTB20" style="text-align: center;">@lang('customer_registration.kikuzo_info')
                    </h2>
                    @lang('customer_registration.note')
                </div>

                <ul class="contact_form customer_registration">
                    <li>
                        <label for="" class="">
                            <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                @lang('customer_registration.one_time_password')
                                <span class="required7">@lang('customer_registration.required')</span>
                            </p>
                            <p class="upper_note">@lang('customer_registration.token')</p>
                            <input id="oneTimePassword" name="oneTimePassword" value="{{ old('oneTimePassword') }}" type="hidden" />{{-- original onetimepassword input --}}
                            <input type="password" class="numeric-password first" name="otp_1" value="{{ old('otp_1') }}" autocomplete="off" id="otp_1" maxlength="4" /> -
                            <input type="password" class="numeric-password" name="otp_2" value="{{ old('otp_2') }}" autocomplete="off" id="otp_2" maxlength="4" /> -
                            <input type="password" class="numeric-password" name="otp_3" value="{{ old('otp_3') }}" autocomplete="off" id="otp_3" maxlength="4" /> -
                            <input type="password" class="numeric-password" name="otp_4" value="{{ old('otp_4') }}" autocomplete="off" id="otp_4" maxlength="4" />
                            <span toggle=".numeric-password" class="toggle-otp eye-img">
                                <img src="/img/eye.png" class="eye-3">
                                <img src="/img/eye-slash.png" class="eye-slash-3" style="display:none">
                            </span>
                            <p class="msg_err">{!! $msg_error_onetime !!}</p>
                            <p class="invalid_otp msg_err" style="display:none">
                                @lang('customer_registration.valid_license_key')</p>
                        </label>
                    </li>
                </ul>


                <div class="contents_box_inner  for-terms">
                    <p style="font-weight:bold;font-size:20px;">@lang('terms.head')</p>
                    <div class="terms-box">
                        <p>@lang('terms.headText') </p>
                        </br>
                        <div class="little_title">@lang('terms.title1.title') </div>
                        </br>
                        <p>@lang('terms.title1.p')</p>
                        </br>
                        <p>@lang('terms.title1.p1') </p>
                        </br>
                        <p>@lang('terms.title1.p2') </p>
                        </br>
                        <p>@lang('terms.title1.p3') </p>
                        </br>
                        <p>@lang('terms.title1.p4') </p>
                        </br>
                        <p>@lang('terms.title1.p5') </p>
                        </br>
                        <p>@lang('terms.title1.p6') </p>
                        </br>
                        <p>@lang('terms.title1.p7') </p>
                        </br>
                        <p>@lang('terms.title1.p8') </p>
                        </br>
                        <p>@lang('terms.title1.p9') </p>
                        </br>
                        <p>@lang('terms.title1.p10') </p>
                        </br>
                        <p>@lang('terms.title1.p11') </p>
                        </br>
                        <p>@lang('terms.title1.p12') </p>
                        </br>
                        <p>@lang('terms.title1.p13') </p>
                        </br>
                        <p>@lang('terms.title1.p14') </p>

                        </br>
                        <div class="little_title">@lang('terms.title2.title')</div>
                        </br>
                        <p>@lang('terms.title2.p1')</p>
                        </br>
                        <p>@lang('terms.title2.p2')</p>
                        </br>
                        <p>@lang('terms.title2.p3')</p>
                        </br>
                        <div class="little_title">@lang('terms.title3.title')</div>
                        </br>
                        <p>@lang('terms.title3.p1') </p>
                        </br>
                        <p>@lang('terms.title3.p2') </p>
                        </br>
                        <p>@lang('terms.title3.p3') </p>
                        </br>
                        <p>@lang('terms.title3.p4') </p>
                        </br>
                        <p>@lang('terms.title3.p5') </p>
                        </br>
                        <p>@lang('terms.title3.p6') </p>
                        </br>
                        <p>@lang('terms.title3.p7') </p>
                        </br>
                        <p>@lang('terms.title3.p8') </p>
                        </br>
                        <p>@lang('terms.title3.p9') </p>
                        </br>
                        <p>@lang('terms.title3.p10') </p>
                        </br>
                        <p>@lang('terms.title3.p11') </p>
                        </br>
                        <p>@lang('terms.title3.p13') </p>
                        </br>
                        <p>@lang('terms.title3.p22') </p>
                        </br>
                        <div class="little_title">@lang('terms.title4.title')</div>
                        </br>
                        <p>@lang('terms.title4.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title5.title')</div>
                        </br>
                        <p>@lang('terms.title5.p1')</p>
                        </br>
                        <p>@lang('terms.title5.p2')</p>
                        </br>
                        <p>@lang('terms.title5.p3') </p>
                        </br>
                        <p>@lang('terms.title5.p4') </p>
                        </br>
                        <div class="little_title">@lang('terms.title6.title')</div>
                        </br>
                        <p>@lang('terms.title6.p1')</p>
                        </br>
                        <p>@lang('terms.title6.p2')</p>
                        </br>
                        <p>@lang('terms.title6.p3')</p>
                        </br>
                        <p>@lang('terms.title6.p4')</p>
                        </br>
                        <p>@lang('terms.title6.p5')</p>
                        </br>
                        <div class="little_title">@lang('terms.title7.title')</div>
                        </br>
                        <p>@lang('terms.title7.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title8.title')</div>
                        </br>
                        <p>@lang('terms.title8.p1')</p>
                        </br>
                        <p>@lang('terms.title8.p2')</p>
                        </br>
                        <p>@lang('terms.title8.p3')</p>
                        </br>
                        <p>@lang('terms.title8.p4')</p>
                        </br>
                        <p>@lang('terms.title8.p5')</p>
                        </br>
                        <p>@lang('terms.title8.p6')</p>
                        </br>
                        <p>@lang('terms.title8.p7')</p>
                        </br>
                        <p>@lang('terms.title8.p8')</p>
                        </br>
                        <p>@lang('terms.title8.p9')</p>
                        </br>
                        <p>@lang('terms.title8.p10')</p>
                        </br>
                        <p>@lang('terms.title8.p11')</p>
                        </br>
                        <p>@lang('terms.title8.p12')</p>
                        </br>
                        <p>@lang('terms.title8.p13')</p>
                        </br>
                        <p>@lang('terms.title8.p14')</p>
                        </br>
                        <p>@lang('terms.title8.p15')</p>
                        </br>
                        <p>@lang('terms.title8.p16')</p>
                        </br>
                        <div class="little_title">@lang('terms.title9.title')</div>
                        </br>
                        <p>@lang('terms.title9.p1')</p>
                        </br>
                        <p>@lang('terms.title9.p2')</p>
                        </br>
                        <p>@lang('terms.title9.p3')</p>
                        </br>
                        <p>@lang('terms.title9.p4')</p>
                        </br>
                        <p>@lang('terms.title9.p5')</p>
                        </br>
                        <p>@lang('terms.title9.p6')</p>
                        </br>
                        <p>@lang('terms.title9.p7')</p>
                        </br>
                        <div class="little_title">@lang('terms.title10.title')</div>
                        </br>
                        <p>@lang('terms.title10.p1')</p>
                        </br>
                        <p>@lang('terms.title10.p2')</p>
                        </br>
                        <p>@lang('terms.title10.p3')</p>
                        </br>
                        <p>@lang('terms.title10.p4')</p>
                        </br>
                        <div class="little_title">@lang('terms.title11.title')</div>
                        </br>
                        <p>@lang('terms.title11.p1')</p>
                        </br>
                        <p>@lang('terms.title11.p2')</p>
                        </br>
                        <p>@lang('terms.title11.p3')</p>
                        </br>
                        <p>@lang('terms.title11.p4')</p>
                        </br>
                        <div class="little_title">@lang('terms.title12.title')</div>
                        </br>
                        <p>@lang('terms.title12.p1')</p>
                        </br>
                        <p>@lang('terms.title12.p2')</p>
                        </br>
                        <p>@lang('terms.title12.p3')</p>
                        </br>
                        <div class="little_title">@lang('terms.title13.title')</div>
                        </br>
                        <p>@lang('terms.title13.p1')</p>
                        </br>
                        <p>@lang('terms.title13.p2')</p>
                        </br>
                        <p>@lang('terms.title13.p3')</p>
                        </br>
                        <p>@lang('terms.title13.p4')</p>
                        </br>
                        <div class="little_title">@lang('terms.title14.title')</div>
                        </br>
                        <p>@lang('terms.title14.p1')</p>
                        </br>
                        <p>@lang('terms.title14.p2')</p>
                        </br>
                        <p>@lang('terms.title14.p3')</p>
                        </br>
                        <div class="little_title">@lang('terms.title15.title')</div>
                        </br>
                        <p>@lang('terms.title15.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title16.title')</div>
                        </br>
                        <p>@lang('terms.title16.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title17.title')</div>
                        </br>
                        <p>@lang('terms.title17.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title18.title')</div>
                        </br>
                        <p>@lang('terms.title18.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title19.title')</div>
                        </br>
                        <p>@lang('terms.title19.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title20.title')</div>
                        </br>
                        <p>@lang('terms.title20.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title21.title')</div>
                        </br>
                        <p>@lang('terms.title21.p1')</p>
                        </br>
                        <div class="little_title">@lang('terms.title22.title')</div>
                        </br>
                        <p>@lang('terms.title22.p1')</p>
                        </br>
                        <p>@lang('terms.title22.p2')</p>
                    </div>
                </div>
        </div>
        <div class="form-group">
            <input type="checkbox" id="agree" id="agree" name="terms">
            <label for="agree">@lang('customer_registration.agree')</label>
        </div>
        <div class="contents_box_inner pTB20">
            <p class="contact_submit mB20 customer_registration_form" style="margin-top: 40px;">
                <a href="javascript:history.go(-1)" class="submit_backbtn">@lang('customer_registration.back_btn')</a>
                <input class="submit_btn" style="cursor:pointer;" id="save" type="submit" value=@lang('customer_registration.register') disabled />
            </p>
        </div>
        </form>
    </div>
</div>
</div>

<script src="{{asset('js/form-validation.js')}}"></script>
<script src="{{asset('js/jquery-key-restrictions.js')}}"></script>
<script>
    $(document).ready(function(e) {
        $("#password, #passwordConfirm").alphaNumericOnly();
        $('.numeric-password').keyup(function(e) {
            if ($(this).val().length >= 4)
                $(this).next().trigger("focus");
        });
    });

    function Chr(AsciiNum) {
        return String.fromCharCode(AsciiNum)
    }

    function pasteString(pastedText) {
        var parts = pastedText.split('-');
        document.getElementById("otp_1").value = parts[0];
        document.getElementById("otp_2").value = (parts[1] === undefined ? "" : parts[1]);
        document.getElementById("otp_3").value = (parts[2] === undefined ? "" : parts[2]);
        document.getElementById("otp_4").value = (parts[3] === undefined ? "" : parts[3]);
        document.getElementById("otp_4").focus();
    }

    function checkChar(input, field) {
        let regex = /[^a-zA-Z0-9]/gi
        let input_val = input.value;
        let error_msg = field == 'pass' ? document.getElementById('double_byte') : document.getElementById('double_byte_confirm');
        if (regex.test(input_val)) {
            error_msg.style.display = 'block';
        } else {
            error_msg.style.display = 'none';
        }
    }


    function handlePaste(e) {
        var pastedText = undefined;
        if (window.clipboardData && window.clipboardData.getData) { // IE
            pastedText = window.clipboardData.getData('Text');
        } else if (e.clipboardData && e.clipboardData.getData) {
            pastedText = e.clipboardData.getData('text/plain');
        }
        pasteString(pastedText); // Process and handle text...
        return false; // Prevent the default handler from running.
    };
    document.getElementById("otp_1").onpaste = handlePaste;
</script>
@endsection