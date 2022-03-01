@extends('auth.layouts.reset_layout')

@section('content')
<div class="reset_password_container">
    <div class="vest_title">@lang('passwords.pass_reset')</div>
    <div>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div>

            <form class="form-horizontal a" role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}
                <ul class="contact_form">
                    <li>
                        <label for="">
                            <p class="input_name must">@lang('passwords.new_pass')</p>
                            <input id="password" type="password" class="form-control" name="password" onpaste="return false"><br/>
                                @if ($errors->has('password'))
                                    <span class="help-block error-pass">
                                        <strong>@lang('passwords.required')</strong>
                                    </span>
                                @endif
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must">@lang('passwords.confirm_pass')</p>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" onpaste="return false"><br>
                            @if ($errors->has('password_confirmation'))
                                    <span class="help-block error-pass">
                                        <strong>@lang('passwords.confirmation')</strong>
                                    </span>
                                @endif
                        </label>
                    </li>
                </ul> 
                @if(Session::has('confirmation_err'))
                    <div class="text-center">
                        <span class="help-block error-pass">                
                            <strong>@lang('passwords.match')</strong>                    
                        </span>
                    </div>
                @endif               
                <div class="for_btn">
                    <button type="submit" class="submit_btn">
                        @lang('passwords.btn_done')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{asset('js/jquery-key-restrictions.js')}}"></script>
<script>
$(document).ready(function(e) {
    $("#password, #password_confirmation").alphaNumericOnly();
});
</script>
@endsection