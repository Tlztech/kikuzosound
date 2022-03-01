@extends('layouts.app')

@section('title', trans(''appli_confirmation.title))

@section('breadcrumb')
{!! Breadcrumbs::render('appli_form_confirm') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('appli_form_send_mail') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('appli_confirmation.msg_confirmation')</h2>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.name')</p>
              <input name="name" type="hidden" maxlength="25" value="{{ $name }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $name }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.facility_name')</p>
              <input name="group" type="hidden" maxlength="25" value="{{ $group }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $group }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.affiliation')</p>
              <input name="affiliation" type="hidden" maxlength="100" value="{{ $affiliation }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $affiliation }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.postal_code')</p>
              <input name="zip" type="hidden" maxlength="100" value="{{ $zip }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $zip }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.address')</p>
              <input name="address" type="hidden" maxlength="200" value="{{ $address }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $address }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.phone_number')</p>
              <input name="tel" type="hidden" maxlength="20" value="{{ $tel }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $tel }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.email_address')</p>
              <input name="mail" type="hidden" maxlength="100" value="{{ $mail }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $mail }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.sales_company')</p>
              <input name="dealer" type="hidden" maxlength="100" value="{{ $dealer }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $dealer }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.branch_office')</p>
              <input name="area" type="hidden" maxlength="100" value="{{ $area }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $area }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.sales_person')</p>
              <input name="sales" type="hidden" maxlength="100" value="{{ $sales }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $sales }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.company_number')</p>
              <input name="salestel" type="hidden" maxlength="20" value="{{ $salestel }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $salestel }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.business_email')</p>
              <input name="salesmail" type="hidden" maxlength="100" value="{{ $salesmail }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{{ $salesmail }}</p>
            </label>
          </li>


          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.plan_number')</p>

              <div style="height:40px;">
                <ul style="display:table; width:<?php echo (Config::get("app.locale") == "ja") ? "300" : "360" ?>px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('appli_confirmation.1year_payment')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <input name="plan1" type="hidden" value="{{ $plan1 }}"/>
                    <select name="" style="width:80px">
                      <option disabled value='0' {{ old('plan1','0') == '0' ? 'selected' : '' }}>0</option>
                      <option disabled value='1' {{ old('plan1') == '1' ? 'selected' : '' }}>1</option>
                      <option disabled value='2' {{ old('plan1') == '2' ? 'selected' : '' }}>2</option>
                      <option disabled value='3' {{ old('plan1') == '3' ? 'selected' : '' }}>3</option>
                      <option disabled value='4' {{ old('plan1') == '4' ? 'selected' : '' }}>4</option>
                      <option disabled value='5' {{ old('plan1') == '5' ? 'selected' : '' }}>5</option>
                      <option disabled value='6' {{ old('plan1') == '6' ? 'selected' : '' }}>6</option>
                      <option disabled value='7' {{ old('plan1') == '7' ? 'selected' : '' }}>7</option>
                      <option disabled value='8' {{ old('plan1') == '8' ? 'selected' : '' }}>8</option>
                      <option disabled value='9' {{ old('plan1') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>

              <div style="height:40px;">
                <ul style="display:table; width:<?php echo (Config::get("app.locale") == "ja") ? "300" : "360" ?>px; margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('appli_confirmation.1year_lumpsum')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <input name="plan2" type="hidden" value="{{ $plan2 }}"/>
                    <select name="" style="width:80px">
                      <option disabled value='0' {{ old('plan2','0') == '0' ? 'selected' : '' }}>0</option>
                      <option disabled value='1' {{ old('plan2') == '1' ? 'selected' : '' }}>1</option>
                      <option disabled value='2' {{ old('plan2') == '2' ? 'selected' : '' }}>2</option>
                      <option disabled value='3' {{ old('plan2') == '3' ? 'selected' : '' }}>3</option>
                      <option disabled value='4' {{ old('plan2') == '4' ? 'selected' : '' }}>4</option>
                      <option disabled value='5' {{ old('plan2') == '5' ? 'selected' : '' }}>5</option>
                      <option disabled value='6' {{ old('plan2') == '6' ? 'selected' : '' }}>6</option>
                      <option disabled value='7' {{ old('plan2') == '7' ? 'selected' : '' }}>7</option>
                      <option disabled value='8' {{ old('plan2') == '8' ? 'selected' : '' }}>8</option>
                      <option disabled value='9' {{ old('plan2') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>

              <div style="height:40px;">
                <ul style="display:table; width:<?php echo (Config::get("app.locale") == "ja") ? "300" : "360" ?>px; margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('appli_confirmation.3years_payment')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <input name="plan3" type="hidden" value="{{ $plan3 }}"/>
                    <select name="" style="width:80px">
                      <option disabled value='0' {{ old('plan3','0') == '0' ? 'selected' : '' }}>0</option>
                      <option disabled value='1' {{ old('plan3') == '1' ? 'selected' : '' }}>1</option>
                      <option disabled value='2' {{ old('plan3') == '2' ? 'selected' : '' }}>2</option>
                      <option disabled value='3' {{ old('plan3') == '3' ? 'selected' : '' }}>3</option>
                      <option disabled value='4' {{ old('plan3') == '4' ? 'selected' : '' }}>4</option>
                      <option disabled value='5' {{ old('plan3') == '5' ? 'selected' : '' }}>5</option>
                      <option disabled value='6' {{ old('plan3') == '6' ? 'selected' : '' }}>6</option>
                      <option disabled value='7' {{ old('plan3') == '7' ? 'selected' : '' }}>7</option>
                      <option disabled value='8' {{ old('plan3') == '8' ? 'selected' : '' }}>8</option>
                      <option disabled value='9' {{ old('plan3') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>

                <ul style="display:table; width:<?php echo (Config::get("app.locale") == "ja") ? "300" : "360" ?>px; margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('appli_confirmation.3years_lumpsum')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <input name="plan4" type="hidden" value="{{ $plan4 }}"/>
                    <select name="" style="width:80px">
                      <option disabled value='0' {{ old('plan4','0') == '0' ? 'selected' : '' }}>0</option>
                      <option disabled value='1' {{ old('plan4') == '1' ? 'selected' : '' }}>1</option>
                      <option disabled value='2' {{ old('plan4') == '2' ? 'selected' : '' }}>2</option>
                      <option disabled value='3' {{ old('plan4') == '3' ? 'selected' : '' }}>3</option>
                      <option disabled value='4' {{ old('plan4') == '4' ? 'selected' : '' }}>4</option>
                      <option disabled value='5' {{ old('plan4') == '5' ? 'selected' : '' }}>5</option>
                      <option disabled value='6' {{ old('plan4') == '6' ? 'selected' : '' }}>6</option>
                      <option disabled value='7' {{ old('plan4') == '7' ? 'selected' : '' }}>7</option>
                      <option disabled value='8' {{ old('plan4') == '8' ? 'selected' : '' }}>8</option>
                      <option disabled value='9' {{ old('plan4') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>

            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.elephant_purchase')</p>

              <div>
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('appli_confirmation.number')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <input name="purchase" type="hidden" value="{{ $purchase }}"/>
                    <select name="" style="width:80px">
                      <option disabled value='0' {{ old('purchase','0') == '0' ? 'selected' : '' }}>0</option>
                      <option disabled value='1' {{ old('purchase') == '1' ? 'selected' : '' }}>1</option>
                      <option disabled value='2' {{ old('purchase') == '2' ? 'selected' : '' }}>2</option>
                      <option disabled value='3' {{ old('purchase') == '3' ? 'selected' : '' }}>3</option>
                      <option disabled value='4' {{ old('purchase') == '4' ? 'selected' : '' }}>4</option>
                      <option disabled value='5' {{ old('purchase') == '5' ? 'selected' : '' }}>5</option>
                      <option disabled value='6' {{ old('purchase') == '6' ? 'selected' : '' }}>6</option>
                      <option disabled value='7' {{ old('purchase') == '7' ? 'selected' : '' }}>7</option>
                      <option disabled value='8' {{ old('purchase') == '8' ? 'selected' : '' }}>8</option>
                      <option disabled value='9' {{ old('purchase') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.guidance_method')</p>
              <div>
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;border-bottom:none;font-size:16px;padding:0px 0px 0px 0px;">
                    <input disabled style="width:50px;" type="checkbox" name="" {{ is_array(old('way')) && in_array('0',old('way')) ? 'checked' : '' }}>@lang('appli_confirmation.phone')
                    <input disabled style="width:50px;" type="checkbox" name="" {{ is_array(old('way')) && in_array('1',old('way')) ? 'checked' : '' }}>@lang('appli_confirmation.email')

<?php
if(array_key_exists("way",$_POST)) {
  foreach ($_POST['way'] as $way) {
    print '<input type="hidden" name="way[]" value="'.$way.'">'.PHP_EOL;
  }
}
?>
                  </li>
                </ul>
              </div>
<p style="margin-bottom:10px;padding:0px 20px;">
@lang('appli_confirmation.email_guide')
</p>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.coupon_code')</p>
              <input name="kind" type="hidden" maxlength="100" value="{{ $kind }}"/>
              <p class="input_name" style="background:white;width:100%;text-align:left;">{{ $kind == '' ? trans('appli_confirmation.none') : $kind}}<br>
              @lang('appli_confirmation.payment_price')
<?php
/* クーポンが有効でない場合は警告を表示 */
if($warning != "") {echo "<br><span style='color:red;'>$warning</span>";}
?>
            </p>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('appli_confirmation.opinion')</p>
              <input name="question" type="hidden" maxlength="500" wrap="soft" value="{{ $question }}"/>
              <p class="input_name" style="background:white;width:70%;text-align:left;">{!! nl2br($question, false) !!}</p>
            </label>
          </li>
        </ul>

<?php
/*
「契約書と同意する」のチェックも渡さないとエラーになるので以降使用しないが追加
*/
?>
        <input type="hidden" name="agree" value="0" style="">

        <div class="contents_box_inner pTB20" style="margin-top:20px;">
          <p class="contact_submit mB20"><a href="{{ route('appli_form') }}" class="submit_backbtn">@lang('appli_confirmation.button_return')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value=@lang('appli_confirmation.button_purchase')></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
