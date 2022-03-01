@extends('layouts.app')

@section('title','Reapply Browser')

@section('content')
<div class="reapply_browser_container">
    <br><br><br><br><br><br><br><br><br><br>
    <div class="vest_title">
    @lang('register.email_not_found')
    </div>
    <div class="vest_text">
        <a href="{{ url('/customer_registration') }}" class="submit_btn">@lang('register.go_to_registration')</a>
    </div>
</div>
@endsection