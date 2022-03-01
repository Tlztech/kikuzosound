@extends('customer_admin.layouts.app')
@section('content')
<!-- Customer Management -->
<div id="container">
  <div class="container_inner clearfix">
    <!--*********************************** .contents ***********************************-->
    <div class="contents">
        <div class="customer-bottom">
            <h2>新しいライセンスキーを作成する</h2>
            <div style="margin: auto 100px;">
                <h4>Customer name: {{ $name }}</h4>
                <h4 class="break-all">Email: {{ $email }}</h4>
                <h4>New license key: {{ $newLicense }}</h4>
                <h4 class="break-all">URL: {{ $url }}</h4>
            </div>
            <div class="contents_box_inner pTB20">
                <p class="contact_submit mB20">
                    <a href="{{url('customer_admin')}}" class="btn btn-info " style="cursor:pointer;">戻る</a>
                </p>
            </div>
        </div>
        <div class="update_note">
            <h4>新しいライセンスキーを発行する担当者へのメッセージ </h4>
            <br>
            <h4>New license keyとURLをユーザに通知してください。</h4>
            <h4>ユーザが、このURLを開くとライセンスキー登録画面が表示されます。</h4>
            <h4>新しいブラウザ／端末でURLを開いて登録を済ませるように通知してください。</h4>
        </div>
    </div>
  </div>
</div>
@endsection
