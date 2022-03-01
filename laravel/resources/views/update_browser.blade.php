@extends('layouts.app')

@section('content')
<div class="reapply_browser_container">
    @if(isset($status))
        @if($status == "confirm")
            <script>
                var contents = document.createElement("div");
                contents.innerHTML =
                    "@lang('update_browser.confirm_title')";
                swal({
                    content: contents,
                }).then(function() {
                    window.location = "/update_browser_success";
                });
            </script>
        @elseif($status == "success")
            <div class="alert alert-success alert_container">
                <h1><a href="/member_login">@lang('update_browser.title_success')</a></h1>
                <p><a href="/contact">@lang('reapply_browser.alert_link2')</a></p>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = "/member_login";
                }, 5000);
            </script>
        @elseif ($status == "token_ok")
        <div class="reset_password_container reapply_browser">
            <div class="vest_title">@lang('update_browser.update_browser_title')</div>
            <div class="note" style=@if (config('app.locale')=='en' ) {{'width:670px'}} @endif>
                <p>@lang('update_browser.note1')</p>
                <p>@lang('update_browser.note2')</p>
            </div>
            <div>
                <div>
                    <form class="reset_pass_form" role="form" method="POST" action="{{ url('/update_browser') }}">
                        {!! csrf_field() !!}
                        <br>
                        <ul class="contact_form">
                            <li>
                                <label for="" class="">
                                    <p class="input_name must" style=@if (config('app.locale')=='en' ) {{'width:40%'}} @endif>
                                        @lang('update_browser.license_key')
                                    </p>
                                    <input name="oneTimePassword" type="text" placeholder="0000-0000-0000-0000" value="{{ old('oneTimePassword') }}" id="oneTimePassword" />
                                    @if($errors->any())
                                        <div class="errors">
                                            <ul>
                                                <li> {{$errors->first()}} </li>
                                            </ul>
                                        </div>
                                    @endif
                                </label>
                            </li>
                        </ul>
                        
                        <div class="for_btn">
                            <button type="submit" class="submit_btn">
                                @lang('reapply_browser.btn_text')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
            <script>
                window.location.href = "/reapply_browser";
            </script>
        @endif
    @else
        <script>
            window.location.href = "/reapply_browser";
        </script>
    @endif
</div>
@endsection