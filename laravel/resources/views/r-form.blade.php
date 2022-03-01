@extends('layouts.app')

@section('title', trans('app.r-form'))

@section('breadcrumb')
{!! Breadcrumbs::render('r-form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
<?php
/*
<?=$urltoken ?><br>
*/
?>
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">聴診会員ライブラリ利用ご登録の完了</h2>
          <p class="mB10 t_left">
            ・登録完了致しました。下記「Home」よりログインしてご利用ください。<br/>
          </p>
        </div>
        <div class="contents_box_inner pTB20">
          <p class="contact_submit mB20"><a href="{{ route('home') }}" class="submit_btn">Home</a></p>
        </div>
    </div>
  </div>
</div>
@endsection
