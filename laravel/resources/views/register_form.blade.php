@extends('layouts.app')

@section('title', trans('app.register-form'))

@section('breadcrumb')
{!! Breadcrumbs::render('register_form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container">
  <!-- 登録フォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('register_form_confirm') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('register_form.div1.h2')</h2>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div1.p1')</p>
              <input name="company" type="text" maxlength="25" value="{{ old('company') }}" placeholder="@lang('register_form.div1.placeholder')"/>
              @if($errors->has('company'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('company') }} @else Please enter your corporation (facility) name. @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div1.p2')</p>
              <input name="name" type="text" maxlength="25" value="{{ old('name') }}"/>
              @if($errors->has('name'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('name') }} @else 
              Please enter your name. @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div1.p3')</p>
              <input name="mail" type="text" maxlength="100" value="{{ old('mail') }}"/>
              @if($errors->has('mail'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('mail') }} @else 
              Please enter your e-mail address @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div1.p4')</p>
              <input name="tel" type="text" maxlength="20" value="{{ old('tel') }}"/>
              @if($errors->has('tel'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('tel') }} @else Please enter a phone number @endif</p>
              @endif
            </label>
          </li>
        </ul>

        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('register_form.div2.h2')</h2>
        </div>
        <ul class="contact_form">
<?php
// 一旦、非表示とする
// フォームのデータは下記のhiddenで送っておく
// なので、非表示->表示の際は下記のechoも削除する事
echo "<input type='hidden' name='kind' value='buy'>";
?>
          <li style="display:none;">
            <label for="" class="">
              <p class="input_name must">購入種類</p>
              <div style="vertical-align:middle;font-size:1.3em;margin:7px;">
                <label style="display:inline;"><input type="radio" name="kind" value="buy" {{ old('kind') == 'buy' ? 'checked' : '' }} style="display:inline;width:20px;"><span>購入</span></label>
<br>
                <label style="display:inline;"><input type="radio" name="kind" value="rent1" {{ old('kind') == 'rent1' ? 'checked' : '' }} style="display:inline;width:20px;"><span>年間利用１年(月払い)</span></label>
                <label style="display:inline;"><input type="radio" name="kind" value="rent2" {{ old('kind') == 'rent2' ? 'checked' : '' }} style="display:inline;width:20px;"><span>年間利用１年(年払い)</span></label>
<br>
                <label style="display:inline;"><input type="radio" name="kind" value="rent3" {{ old('kind') == 'rent3' ? 'checked' : '' }} style="display:inline;width:20px;"><span>年間利用３年(月払い)</span></label>
                <label style="display:inline;"><input type="radio" name="kind" value="rent4" {{ old('kind') == 'rent4' ? 'checked' : '' }} style="display:inline;width:20px;"><span>年間利用３年(年払い)</span></label>
              </div>
              @if($errors->has('kind'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('kind') }}</p>
              @endif

              <div class="rental" style="display:none;">
<?php
// 2022年以降を追加する場合はregister_form_confirm.blade.phpと
// common.jsのmaxDateも併せて変更する必要有
$year = date("Y");  // 今の年(デフォルト値)
$month = date("m"); // 今の月(デフォルト値)
$date = date("d");  // 今の日(デフォルト値)
?>

                <p style="font-size:1.0em;margin:10px 0px 0px 10px;">※年間利用の場合は「契約日」を入力して下さい</p>
                <p style="font-size:1.0em;margin:10px 0px 0px 10px;">※申請日から2ヶ月以内での開始となります<p>
                <select name="year" class="year" style="display:inline-block;width:70px;margin-right:2px;">
                  <option value='2018' {{ old('year',$year) == '2018' ? 'selected' : '' }}>2018</option>
                  <option value='2019' {{ old('year',$year) == '2019' ? 'selected' : '' }}>2019</option>
                  <option value='2020' {{ old('year',$year) == '2020' ? 'selected' : '' }}>2020</option>
                  <option value='2021' {{ old('year',$year) == '2021' ? 'selected' : '' }}>2021</option>
                  <option value='2022' {{ old('year',$year) == '2022' ? 'selected' : '' }}>2022</option>
                </select>年

                <select name="month" class="month" style="display:inline-block;width:60px;margin-right:2px;">
                  <option value='01' {{ old('month',$month) == '01' ? 'selected' : '' }}>01</option>
                  <option value='02' {{ old('month',$month) == '02' ? 'selected' : '' }}>02</option>
                  <option value='03' {{ old('month',$month) == '03' ? 'selected' : '' }}>03</option>
                  <option value='04' {{ old('month',$month) == '04' ? 'selected' : '' }}>04</option>
                  <option value='05' {{ old('month',$month) == '05' ? 'selected' : '' }}>05</option>
                  <option value='06' {{ old('month',$month) == '06' ? 'selected' : '' }}>06</option>
                  <option value='07' {{ old('month',$month) == '07' ? 'selected' : '' }}>07</option>
                  <option value='08' {{ old('month',$month) == '08' ? 'selected' : '' }}>08</option>
                  <option value='09' {{ old('month',$month) == '09' ? 'selected' : '' }}>09</option>
                  <option value='10' {{ old('month',$month) == '10' ? 'selected' : '' }}>10</option>
                  <option value='11' {{ old('month',$month) == '11' ? 'selected' : '' }}>11</option>
                  <option value='12' {{ old('month',$month) == '12' ? 'selected' : '' }}>12</option>
                </select>月

                <select name="date" class="date" style="display:inline-block;width:60px;margin-right:2px;">
                  <option value='01' {{ old('date',$date) == '01' ? 'selected' : '' }}>01</option>
                  <option value='02' {{ old('date',$date) == '02' ? 'selected' : '' }}>02</option>
                  <option value='03' {{ old('date',$date) == '03' ? 'selected' : '' }}>03</option>
                  <option value='04' {{ old('date',$date) == '04' ? 'selected' : '' }}>04</option>
                  <option value='05' {{ old('date',$date) == '05' ? 'selected' : '' }}>05</option>
                  <option value='06' {{ old('date',$date) == '06' ? 'selected' : '' }}>06</option>
                  <option value='07' {{ old('date',$date) == '07' ? 'selected' : '' }}>07</option>
                  <option value='08' {{ old('date',$date) == '08' ? 'selected' : '' }}>08</option>
                  <option value='09' {{ old('date',$date) == '09' ? 'selected' : '' }}>09</option>
                  <option value='10' {{ old('date',$date) == '10' ? 'selected' : '' }}>10</option>
                  <option value='11' {{ old('date',$date) == '11' ? 'selected' : '' }}>11</option>
                  <option value='12' {{ old('date',$date) == '12' ? 'selected' : '' }}>12</option>
                  <option value='13' {{ old('date',$date) == '13' ? 'selected' : '' }}>13</option>
                  <option value='14' {{ old('date',$date) == '14' ? 'selected' : '' }}>14</option>
                  <option value='15' {{ old('date',$date) == '15' ? 'selected' : '' }}>15</option>
                  <option value='16' {{ old('date',$date) == '16' ? 'selected' : '' }}>16</option>
                  <option value='17' {{ old('date',$date) == '17' ? 'selected' : '' }}>17</option>
                  <option value='18' {{ old('date',$date) == '18' ? 'selected' : '' }}>18</option>
                  <option value='19' {{ old('date',$date) == '19' ? 'selected' : '' }}>19</option>
                  <option value='20' {{ old('date',$date) == '20' ? 'selected' : '' }}>20</option>
                  <option value='21' {{ old('date',$date) == '21' ? 'selected' : '' }}>21</option>
                  <option value='22' {{ old('date',$date) == '22' ? 'selected' : '' }}>22</option>
                  <option value='23' {{ old('date',$date) == '23' ? 'selected' : '' }}>23</option>
                  <option value='24' {{ old('date',$date) == '24' ? 'selected' : '' }}>24</option>
                  <option value='25' {{ old('date',$date) == '25' ? 'selected' : '' }}>25</option>
                  <option value='26' {{ old('date',$date) == '26' ? 'selected' : '' }}>26</option>
                  <option value='27' {{ old('date',$date) == '27' ? 'selected' : '' }}>27</option>
                  <option value='28' {{ old('date',$date) == '28' ? 'selected' : '' }}>28</option>
                  <option value='29' {{ old('date',$date) == '29' ? 'selected' : '' }}>29</option>
                  <option value='30' {{ old('date',$date) == '30' ? 'selected' : '' }}>30</option>
                  <option value='31' {{ old('date',$date) == '31' ? 'selected' : '' }}>31</option>
                </select>日
                <input type="text" value="" id="calendar" style="display:none;" />
              </div>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div2.p1')</p>
              <input name="number" type="text" maxlength="20" value="{{ old('number') }}" placeholder="@lang('register_form.div2.placeholder')"/>
              @if($errors->has('number'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('number') }} @else 
              Please enter the purchase quantity. @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div2.p2')</p>
<p style="font-size:1.0em;margin:10px 0px 0px 10px;">@lang('register_form.div2.p4')<p>
<p style="font-size:1.0em;margin:10px 0px 0px 10px;">@lang('register_form.div2.p5')<p>
<p style="font-size:1.0em;margin:10px 0px 0px 10px;">@lang('register_form.div2.p6')</p>
<p style="font-size:1.0em;margin:10px 0px 0px 10px;">@lang('register_form.div2.p7')</p>
<p style="font-size:1.0em;margin:10px 0px 0px 10px;">@lang('register_form.div2.p8')</p>
              <input name="serial1" type="text" maxlength="200" value="{{ old('serial1') }}"/>
              @if($errors->has('serial1'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;"> @if(Config::get('app.locale') == 'ja') {{ $errors->first('serial1') }} @else Please enter your serial number @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('register_form.div2.p3')</p>
              <textarea name="question" type="text" maxlength="500" wrap="soft" />{{ old('question') }}</textarea>
              @if($errors->has('question'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('question') }}</p>
              @endif
            </label>
          </li>
        </ul>

        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('register_form.div3.h2')</h2>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div3.p1')</p>
              <input name="dealer" type="text" maxlength="25" value="{{ old('dealer') }}"/>
              @if($errors->has('dealer'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;"> @if(Config::get('app.locale') == 'ja') {{ $errors->first('dealer') }} @else Please enter your distributor name. @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div3.p2')</p>
              <input name="contact" type="text" maxlength="25" value="{{ old('contact') }}"/>
              @if($errors->has('contact'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('contact') }} @else Please enter a contact name. @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('register_form.div3.p3')</p>
              <input name="cmail" type="text" maxlength="100" value="{{ old('cmail') }}"/>
              @if($errors->has('cmail'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('cmail') }} @else Please enter your e-mail address. @endif</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p style="" class="input_name must">@lang('register_form.div3.p4')</p>
              <input name="ctel" type="text" maxlength="20" value="{{ old('ctel') }}"/>
              @if($errors->has('ctel'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">@if(Config::get('app.locale') == 'ja') {{ $errors->first('ctel') }} @else Please enter a phone number. @endif</p>
              @endif
            </label>
          </li>
        </ul>

        <div class="contents_box_inner pTB20">
          <p class="contact_submit mB20" style="margin-top: 40px;"><a href="{{ route('register') }}" class="submit_backbtn">@lang('register_form.submit_back')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value="@lang('register_form.submit')"></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    // 購入種類　最初に来た時の設定
    var radiochecked = $('input[name="kind"]:checked').val();
    if(radiochecked == 'rent1' || radiochecked == 'rent2' || radiochecked == 'rent3' || radiochecked == 'rent4') {  // レンタルの場合
        $(".rental").css('display','block');    // 表示
    }

    // 購入種類　ラジオボタンが変更された場合
    $('input[name="kind"]:radio').change( function() {
        var radio = $(this).val();
        if(radio == 'buy') {    // 購入の場合
            $(".rental").css('display','none'); // 非表示
        } else {    // レンタルの場合
            $(".rental").css('display','block');    // 表示
        }
    });
});
</script>
@endsection
