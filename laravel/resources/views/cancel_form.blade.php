@extends('layouts.app')

@section('title', '解約フォーム')

@section('breadcrumb')
{!! Breadcrumbs::render('cancel_form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('cancel_form_confirm') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">ご解約申請</h2>
          <p class="mB10 t_left">
            ・途中解約の場合の利用料は、契約期間満了するまでの利用料残総額を一括でお支払いいただきます。<br/>
            ・解約は3ヶ月前の申請となる為、実際の解約は3ヶ月後(月単位)となります。<br/>
            ・この処理は取消ができません。十分ご確認の上、解約手続きをして下さい。<br/>
            ・個人情報の取り扱いにつきましては「<a href="{{ route('privacy') }}">プライバシーポリシー</a>」のページをご覧ください。<br/>
<br/>
          </p>
        </div>

<?php 
$user = $params['user'];    // アカウント名
$usage_disp = $params['usage_disp'];    // サイト使用プラン
$c_com = $params['c_com'];  // 顧客施設名
$c_name = $params['c_name'];    // 顧客担当者名
$c_email = $params['c_email'];  // 顧客メール
$c_tel = $params['c_tel'];  // 顧客電話
?>

        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name">ご施設名</p>
              <input name="group" type="hidden" maxlength="25" value="<?= $c_com ?>"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;"><?= $c_com ?></p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">お名前</p>
              <input name="name" type="hidden" maxlength="25" value="<?= $c_name ?>"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;"><?= $c_name ?></p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">ご利用プラン</p>
              <input name="mail" type="hidden" maxlength="100" value="<?= $usage_disp ?>"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;"><?= $usage_disp ?></p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">アカウント名</p>
              <input name="tel" type="hidden" maxlength="20" value="<?= $user ?>"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;"><?= $user ?></p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">解約理由</p>
              <select name="kind" id="kind">
                <option value='0' {{ old('kind','0') == '0' ? 'selected' : '' }}>---解約理由を選択してください---</option>
                <option value='1' {{ old('kind') == '1' ? 'selected' : '' }}>他に良い教材を見つけた</option>
                <option value='2' {{ old('kind') == '2' ? 'selected' : '' }}>利用する必要がなくなった</option>
                <option value='3' {{ old('kind') == '3' ? 'selected' : '' }}>その他</option>
              </select>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">解約理由補足</p>
              <textarea name="question" id="question" type="text" maxlength="500" wrap="soft" placeholder="解約理由が「その他」の場合は必ずご記入ください" />{{ old('question') }}</textarea>
              @if($errors->has('question'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('question') }}</p>
              @endif
            </label>
          </li>
        </ul>
        <div class="contents_box_inner pTB20">
          <p class="mTB20" style="text-align: center;margin-top: 40px;">一度解約しますと「お気に入り」等は削除され復旧はできません。十分ご確認の上、解約手続きをして下さい。</p>
          <p class="contact_submit mB20"><a href="{{ route('contact') }}" class="submit_backbtn">戻る</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value="解約する"></input></p>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
  $(".submit_btn").on("click", function() {
    var etc = 3;
    var min = 3;

    var kind = $("#kind").val();
    var question = $("#question").val();
    var count = question.length;

    if(kind == 0) {
      alert("解約理由を選択してください");
      return false;
    }

    if(kind == etc && count < min) {
      alert("解約理由が「その他」の場合は理由を必ずご記入ください");
      return false;
    }
  });
});
</script>
@endsection
