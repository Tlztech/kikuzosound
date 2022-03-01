@extends('layouts.app')

@section('title','Reapply Browser')

@section('content')
<div class="reapply_browser_container">
    @if (session('status'))
    <div class="alert alert-success alert_container">
        <h1>@lang('reapply_browser.alert_title')</h1>
        <p><a href="/member_login">@lang('reapply_browser.alert_link1')</a></p>
        <p>@lang('reapply_browser.text_1')</p>
        <p><a href="/contact">@lang('reapply_browser.alert_link2')</a></p>
    </div>
    @else
    <div class="reset_password_container reapply_browser">
        <div class="vest_title">@lang('reapply_browser.title')</div>
        <div class="note" style=@if (config('app.locale')=='en' ) {{'width:670px'}} @endif>
            @lang('reapply_browser.note')
        </div>
        <div>
            <div>
                <form class="reset_pass_form" role="form" method="POST" action="{{ url('/reapply_browser') }}">
                    {!! csrf_field() !!}
                    <div class="email_field">
                        <label class="label-email">@lang('reapply_browser.label')</label>
                        <div>
                            <input type="email" class="email" name="email" value="{{ old('email') }}" id="email">
                        </div>
                        <div class="errors">
                            <p class="msg_errors valid_email">@lang('passwords.valid_email')</p>
                        </div>
                        @if($errors->any())
                        <div class="errors">
                            <ul>
                                <li> {{$errors->first()}} </li>
                            </ul>
                        </div>
                        @endif

                    </div>

                    <div class="for_btn">
                        <button type="submit" class="submit_btn">
                            @lang('reapply_browser.btn_text')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
<script>
var isEmpty = $.trim($("#email").val());
if (isEmpty.length == 0) {
    $(".valid_email").hide();
    $('#email').on('keyup', function() {
        var isEmpty = $.trim($("#email").val());
        var email = $('#email').val();
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if (testEmail.test(email) || isEmpty.length == 0) {
            $(".valid_email").hide();
            $(".submit_btn").attr("disabled", false);
        } else {
            $(".valid_email").show();
            $(".submit_btn").attr("disabled", true);
        }
    });
}
</script>
@endsection