@extends('layouts.app')

@section('title', trans('app.register-form-confirm'))

@section('breadcrumb')
{!! Breadcrumbs::render('register_form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container">
    <!-- 登録フォーム -->
    <div class="container_inner clearfix">
        <div class="contents_box sp_mTB20 mTB20 t_center">
            <form action="{{ route('register_form_send_mail') }}" method="POST" id="form" class="confirm_registration_form" data-flag="0" onsubmit="submitonce()">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="browser_used" id="browser_used" value="">
                <div class="contents_box_inner">
                    <p class="title_m mTB20" style="text-align: center;">@lang('register_form.reg_confirm.title')</p>
                    <h2 class="title_m mTB20" style="text-align: center;">@lang('register_form.div1.h2')</h2>
                </div>
                <ul class="contact_form">
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.corp_name')</p>
                            <input name="company" type="hidden" maxlength="25" value="{{ $company }}"
                                placeholder="個人の場合は「個人」とご入力ください" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $company }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.name')</p>
                            <input name="name" type="hidden" maxlength="25" value="{{ $name }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $name }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.user_id')</p>
                            <input name="userId" type="hidden" maxlength="100" value="{{ $userId }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $userId }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.mail_address')</p>
                            <input name="cust_mail_address" type="hidden" maxlength="20"
                                value="{{ $cust_mail_address }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">
                                {{ $cust_mail_address }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.password')</p>
                            <input name="password" type="password" maxlength="20" value="{{ $password }}" id="password"
                                placeholder="4　の様に数値のみを入力して下さい" style="background:white;width:70%;text-align:left;" readonly/>
                                <span toggle="#password" class="toggle-password eye-img">
                                <img src="/img/eye.png" class="eye"> 
                                <img src="/img/eye-slash.png" class="eye-slash" style="display:none">
                            </span>
                            </p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.password_confirm')</p>
                            <input name="passwordConfirm" type="password" maxlength="200" id="passwordConfirm"
                                value="{{ $passwordConfirm }}" style="background:white;width:70%;text-align:left;" readonly/>
                            <span toggle="#passwordConfirm" class="toggle-passwordConfirm eye-img">
                                <img src="/img/eye.png" class="eye-2"> 
                                <img src="/img/eye-slash.png" class="eye-slash-2" style="display:none">
                            </span>
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
                            <p class="input_name">購入種類</p>
                            <div style="vertical-align:middle;font-size:1.3em;margin:7px;">
                                <label style="display:inline;"><input type="radio" name="kind" value="buy"
                                        style="display:inline;width:20px;"><span>購入</span></label>
                                <br>
                                <label style="display:inline;"><input type="radio" name="kind" value="rent1"
                                        style="display:inline;width:20px;"><span>年間利用１年(月払い)</span></label>
                                <label style="display:inline;"><input type="radio" name="kind" value="rent2"
                                        style="display:inline;width:20px;"><span>年間利用１年(年払い)</span></label>
                                <br>
                                <label style="display:inline;"><input type="radio" name="kind" value="rent3"
                                        style="display:inline;width:20px;"><span>年間利用３年(月払い)</span></label>
                                <label style="display:inline;"><input type="radio" name="kind" value="rent4"
                                        style="display:inline;width:20px;"><span>年間利用３年(年払い)</span></label>
                            </div>

                            <div class="rental" style="display:none;">
                                <input name="year" type="hidden" value="" />
                                <input name="month" type="hidden" value="" />
                                <input name="date" type="hidden" value="" />

                                <select name="" class="year" style="display:inline-block;width:70px;margin-right:2px;">
                                    <option disabled value='2018'>2018</option>
                                    <option disabled value='2019'>2019</option>
                                    <option disabled value='2020'>2020</option>
                                    <option disabled value='2021'>2021</option>
                                    <option disabled value='2022'>2022</option>
                                </select>年

                                <select name="" class="month" style="display:inline-block;width:60px;margin-right:2px;">
                                    <option disabled value='01'>01</option>
                                    <option disabled value='02'>02</option>
                                    <option disabled value='03'>03</option>
                                    <option disabled value='04'>04</option>
                                    <option disabled value='05'>05</option>
                                    <option disabled value='06'>06</option>
                                    <option disabled value='07'>07</option>
                                    <option disabled value='08'>08</option>
                                    <option disabled value='09'>09</option>
                                    <option disabled value='10'>10</option>
                                    <option disabled value='11'>11</option>
                                    <option disabled value='12'>12</option>
                                </select>月

                                <select name="" class="date" style="display:inline-block;width:60px;margin-right:2px;">
                                    <option disabled value='01'>01</option>
                                    <option disabled value='02'>02</option>
                                    <option disabled value='03'>03</option>
                                    <option disabled value='04'>04</option>
                                    <option disabled value='05'>05</option>
                                    <option disabled value='06'>06</option>
                                    <option disabled value='07'>07</option>
                                    <option disabled value='08'>08</option>
                                    <option disabled value='09'>09</option>
                                    <option disabled value='10'>10</option>
                                    <option disabled value='11'>11</option>
                                    <option disabled value='12'>12</option>
                                    <option disabled value='13'>13</option>
                                    <option disabled value='14'>14</option>
                                    <option disabled value='15'>15</option>
                                    <option disabled value='16'>16</option>
                                    <option disabled value='17'>17</option>
                                    <option disabled value='18'>18</option>
                                    <option disabled value='19'>19</option>
                                    <option disabled value='20'>20</option>
                                    <option disabled value='21'>21</option>
                                    <option disabled value='22'>22</option>
                                    <option disabled value='23'>23</option>
                                    <option disabled value='24'>24</option>
                                    <option disabled value='25'>25</option>
                                    <option disabled value='26'>26</option>
                                    <option disabled value='27'>27</option>
                                    <option disabled value='28'>28</option>
                                    <option disabled value='29'>29</option>
                                    <option disabled value='30'>30</option>
                                    <option disabled value='31'>31</option>
                                </select>日
                            </div>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.one_time_password')</p>
                            <input name="oneTimePassword" type="password" maxlength="500" wrap="soft" id="numeric-password"
                                value="{{ $oneTimePassword }}" style="background:white;width:70%;text-align:left;" readonly/>
                            <span toggle="#numeric-password" class="toggle-otp eye-img">
                                <img src="/img/eye.png" class="eye-3"> 
                                <img src="/img/eye-slash.png" class="eye-slash-3" style="display:none">
                            </span>
                        </label>
                    </li>
                </ul>
<!-- 
                <div class="contents_box_inner">
                    <h2 class="title_m mTB20" style="text-align: center;">@lang('register_form.div3.h2')</h2>
                </div>
                <ul class="contact_form confirm_registration_form">
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.sales_name')</p>
                            <input name="dealer" type="hidden" maxlength="25" value="{{ $dealer }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $dealer }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.contact_name')</p>
                            <input name="contact" type="hidden" maxlength="25" value="{{ $contact }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $contact }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.mail_address')</p>
                            <input name="distr_mail_address" type="hidden" maxlength="100"
                                value="{{ $distr_mail_address }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">
                                {{ $distr_mail_address }}</p>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name">@lang('customer_registration.phone_number')</p>
                            <input name="phone_number" type="hidden" maxlength="20" value="{{ $phone_number }}" />
                            <p class="input_name" style="background:white;width:70%;text-align:left;">
                                {{ $phone_number }}</p>
                        </label>
                    </li>
                </ul> -->

                <div class="contents_box_inner pTB20">
                    <p class="contact_submit mB20 customer_registration_form" style="margin-top: 40px;">
                        <a href="{{ route('customer_registration') }}"
                            class="submit_backbtn">@lang('register_form.submit_back')</a>
                        <input class="submit_btn" style="cursor:pointer;" type="submit" id="btn_submit" name="submit"
                            value="@lang('register_form.submit')"></input>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://unpkg.com/bowser@2.7.0/es5.js"></script>
<script>
    var userAgent = bowser.getParser(window.navigator.userAgent);
    var browserUsed = userAgent.parsedResult.browser.name + " on " + userAgent.parsedResult.os.name;
    $("#browser_used").val(browserUsed);
</script>
<script>
    $(".toggle-password").click(function() {
      $(this).toggleClass("eye eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
          input.attr("type", "text"); 
          $(".eye").hide()
          $(".eye-slash").show()
      } else {
          input.attr("type", "password");
          $(".eye").show()
          $(".eye-slash").hide()
      }
  });
  
  $(".toggle-passwordConfirm").click(function() {
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
          input.attr("type", "text"); 
          $(".eye-2").hide()
          $(".eye-slash-2").show()
      } else {
          input.attr("type", "password");
          $(".eye-2").show()
          $(".eye-slash-2").hide()
      }
  });
  
  $(".toggle-otp").click(function() {
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
          input.attr("type", "text"); 
          $(".eye-3").hide()
          $(".eye-slash-3").show()
      } else {
          input.attr("type", "password");
          $(".eye-3").show()
          $(".eye-slash-3").hide()
      }
  });
    function submitonce(){
        //disables submit button after clicking once
        form.submit.disabled = true
    }
</script>
@endsection