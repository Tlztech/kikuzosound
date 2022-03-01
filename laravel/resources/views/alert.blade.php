@extends('layouts.app')

@section('content')
<div class="alert alert-success">

        <h1 style="color:red;">{{trans('alert.not_complete') }}</h1>
        <p>
        {{trans('alert.p1') }}<br>
        {{trans('alert.p2') }}<br>
        {{trans('alert.p3') }}
        </p>
        <p>
            <span class="msg_err"> {{trans('alert.p4') }}</span><br>
            {{trans('alert.p5') }}<br>
            <?php echo Session::get('browser_used'); ?>
        </p>
        <p>
        {{trans('alert.p7') }}<br>
        {{trans('alert.p8') }}
        </p>
        <form method="GET" action="{{ url('/email_verification') }}">
            <button class="submit_btn"> {{trans('alert.p9') }}</button><br><br><br>
        </form>
        <p>
        {{trans('alert.p10') }}<br>
        {{trans('alert.p11') }}<br>
        {{trans('alert.p12') }}<br>
        {{trans('alert.p13') }}
        </p>
    </div>
@endsection
