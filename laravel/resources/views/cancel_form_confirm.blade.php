@extends('layouts.app')

@section('title', 'ご解約フォーム 確認')

@section('breadcrumb')
{!! Breadcrumbs::render('cancel_form_confirm') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('cancel_form_send_mail') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">ご解約申請の確認</h2>
          <p class="mB10 t_left">
            ・途中解約の場合の利用料は、契約期間満了するまでの利用料残総額を一括でお支払いいただきます。<br/>
            ・解約は3ヶ月前の申請となる為、実際の解約は3ヶ月後(月単位)となります。<br/>
            ・この処理は取消ができません。十分ご確認の上、解約手続きをして下さい。<br/>
            ・個人情報の取り扱いにつきましては「<a href="{{ route('privacy') }}">プライバシーポリシー</a>」のページをご覧ください。<br/>
<br/>
          </p>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name">ご施設名</p>
              <input name="group" type="hidden" maxlength="25" value="{{ $group }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $group }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">お名前</p>
              <input name="name" type="hidden" maxlength="25" value="{{ $name }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $name }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">ご利用プラン</p>
              <input name="mail" type="hidden" maxlength="100" value="{{ $mail }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $mail }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">アカウント名</p>
              <input name="tel" type="hidden" maxlength="20" value="{{ $tel }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $tel }}</p>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">解約理由</p>
              <input name="kind" type="hidden" maxlength="20" value="{{ $kind }}"/>
              <select name="">
                <option disabled value='1' {{ old('kind') == '1' ? 'selected' : '' }}>他に良い教材を見つけた</option>
                <option disabled value='2' {{ old('kind') == '2' ? 'selected' : '' }}>利用する必要がなくなった</option>
                <option disabled value='3' {{ old('kind') == '3' ? 'selected' : '' }}>その他</option>
              </select>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">解約理由補足</p>
              <input name="question" type="hidden" maxlength="500" wrap="soft" value="{{ $question }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{!! nl2br($question, false) !!}</p>
            </label>
          </li>
        </ul>
        <div class="contents_box_inner pTB20">
          <p class="mTB20" style="text-align: center;margin-top: 40px;">一度解約しますと「お気に入り」等は削除され復旧はできません。十分ご確認の上、解約手続きをして下さい。</p>
          <p class="contact_submit mB20"><a href="{{ route('cancel_form') }}" class="submit_backbtn">戻る</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value="解約実行"></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
