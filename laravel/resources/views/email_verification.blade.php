@extends('layouts.app')

@section('title','Reapply Browser')

@section('content')
<div class="reapply_browser_container">
    <div class="reset_password_container reapply_browser">
        <div class="vest_title">@lang('register.resend_email_title')</div>
        <div class="vest_text">
        @lang('register.resend_email_text')
        </div>
        <div>
            <div>
                <form class="reset_pass_form" role="form" method="POST" action="{{ url('/email_verification') }}">
                    {!! csrf_field() !!}
                    <div class="email_field">
                        <label class="label-email">@lang('register.resend_reenter')</label>
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
                        @lang('register.next')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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