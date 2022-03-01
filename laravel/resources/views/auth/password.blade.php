@extends('auth.layouts.layout')

@section('content')
<div class="reset_password_container">
    <div class="vest_title">@lang('passwords.title')</div>
    <div>
        @if (session('status'))
        <script>
        var contents = document.createElement("div");
        contents.innerHTML =
            "<h2>{{trans('passwords.h2')}} <br> {{old('email')}} </h2><p>{{trans('passwords.p1')}}<br>{{trans('passwords.p2')}}</p>";

        swal({
            content: contents,
        });
        </script>
        @endif
        <div>
            <form class="reset_pass_form" role="form" method="POST" action="{{ url('/password/email') }}">
                {!! csrf_field() !!}
                <div class="email_field">
                    <label class="label-email">@lang('passwords.label')</label>
                    <div>
                        <input type="email" class="email" name="email" id="email">
                    </div>
                    <div class="errors">
                        <p class="msg_errors valid_email">@lang('passwords.valid_email')</p>
                    </div>
                    @if($errors->has('email'))
                    <div class="errors">
                        <p>@lang('passwords.empty')</p>
                    </div>
                    @endif
                    @if ($message = Session::get('error'))
                    <div class="errors">
                        <p>{{$message}}</p>
                    </div>
                    @endif
                    @if ($message = Session::get('not_activated'))
                    <div class="errors">
                        <p>{{$message}}</p>
                        <p><a href="{{route('email_verification')}}" class="links">@lang('login.resend_email')</a></p>
                    </div>
                    @endif
                </div>

                <div class="for_btn">
                    <button type="submit" class="submit_btn">
                        @lang('passwords.btn')
                    </button>
                </div>
            </form>
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