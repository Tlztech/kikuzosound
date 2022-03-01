@extends('customer_admin.layouts.app')
@section('content')
<style type="text/css">
    .link_btn{
        text-decoration: none;
    }
</style>
<div class="container">
    <p style="text-align:center;">現在のアクセス元IPアドレス：172.22.0.1</p>
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">契約状況</h2>
        <div style="text-align: center;margin-bottom:20px;">
            <a style="text-decoration: none;" type="button" id="nothing" name="" class="link_btn" href="{{url('customer_admin/home?status=1')}}">出荷前を非表示</a>
            <a style="text-decoration: none;" type="button" id="all" name="" class="link_btn" href="{{url('customer_admin/home')}}">全件表示</a>
            <div style="min-width:300px;display:inline-block;">
                <input type="text" name="" id="search_in" placeholder="検索文字列を入力">
                <input type="button" id="search_table" name="" class="search_btn" value="検索">
            </div>
        </div>
        <ul class="ultable">
            <li class="first column_no">シリアルNo</li>
            <li class="first">販社</li>
            <li class="first column_name">担当者</li>
            <li class="first">顧客</li>
            <li class="first column_name">契約者</li>
        </ul>
        @foreach($accounts as $key => $account)
        <ul class="ultable" id="ul1">
            <li class="column_no"><a @if($account->aid) href="{{route('customer_admin_accounts_edit',$account->aid)}}@endif">{{sprintf('%08d',++$key)}}</a></li>
            <li>{{$account->d_com}}</li>
            <li class="column_name">{{$account->d_name}}</li>
            <li>{{$account->c_com}}</li>
            <li class="column_name">{{$account->c_name}}</li>
        </ul>
        @endforeach
    </div>
    <!-- おまけ　アバターがニュースを提供 -->
    <div id="avator" class="words1"></div>
    <img class="hito" src="{{url('/img/162Dr_takamura.png')}}">
</div>
@endsection
@section('page-script')
@endsection
