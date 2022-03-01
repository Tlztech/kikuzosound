@extends('customer_admin.layouts.app')
@section('content')
    <div class="container">
        <div class="mat">
        <h2 class="title_m" style="text-align: center;">{{$db}}に変更しました</h2><br>
            <div style="text-align:center;">
                <a href="{{route('customer_admin')}}" class="submit_btn">トップに戻る</a>
            </div>
        </div>
    </div>
@endsection
@section('page-script')