@extends('layouts.app')

@section('title', trans('app.r-form-confirm'))

@section('breadcrumb')
{!! Breadcrumbs::render('r-mail-form-confirm') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('r-mail-form-send_mail') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="edit" value="{{ old('edit',$edit) }}">
        <input type="hidden" name="language" value=<?php echo (Config::get("app.locale"))?>>
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('r-mail_confirmation.msg_confirm')</h2>
          <p class="mB10 t_left">
            @lang('r-mail_confirmation.note_1')<br/>
            @lang('r-mail_confirmation.note_2')<a id="policy_privacy1" href="{{ route('privacy') }}"> @lang('r-mail_confirmation.privacy_policy') </a>@lang('r-mail_confirmation.note_2s')<br/>
          </p>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.email_address')</p>
              <input name="mail" type="hidden" maxlength="100" value="{{ $mail }}"/>
              <p class="input_name confirm_p">{{ $mail }}</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.password')</p>
            <?php
            $password_hide = str_repeat('*', strlen($password));

            // パスワードは表示したくない為、セッションで保持しているが、
            // バリデーションでのエラー回避の為、ダミーのパスワードを表示用に設定
            // なので、valueに表示されているパスワードに意味はない(ランダム)
            $seed = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $password_disp = substr(str_shuffle($seed),0,strlen($password));
            $locale = Session::get('lang');
            ?>
              <p class="input_name confirm_p"><?= $password_hide ?></p>
              <input name="password" type="hidden" value="{{ $password_disp }}" />
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.full_name')</p>
                <p class="input_name confirm_p">{{ $name1 }}　{{ $name2 }}</p>
                <input name="name1" type="hidden" maxlength="25" value="{{ $name1 }}"/>
                <input name="name2" type="hidden" maxlength="25" value="{{ $name2 }}" />
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.name')</p>
                <p class="input_name confirm_p">{{ $kana1 }}　{{ $kana2 }}</p>
                <input name="kana1" type="hidden" maxlength="25" value="{{ $kana1 }}"/>
                <input name="kana2" type="hidden" maxlength="25" value="{{ $kana2 }}" />
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.birthday')</p>
                <p class="input_name confirm_p">
                  @if($locale == 'ja') 
                    {{ $yearSelect }}年{{ $monthSelect }}月{{ $daySelect }}日 
                  @else 
                    {{ $monthSelect }}-{{ $daySelect }}-{{ $yearSelect }} 
                  @endif
                </p>
                <input name="yearSelect" type="hidden" maxlength="25" value="{{ $yearSelect }}"/>
                <input name="monthSelect" type="hidden" maxlength="25" value="{{ $monthSelect }}" />
                <input name="daySelect" type="hidden" maxlength="25" value="{{ $daySelect }}" />
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.gender')</p>
<?php
if($gender == "female") {
    $dispGender = trans('r-mail-form.gender_female');
} else {
    $dispGender = trans('r-mail-form.gender_male');
}
?>
                <p class="input_name confirm_p"><?= $dispGender ?></p>
                <input name="gender" type="hidden" value="{{ $gender }}"/>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.occupation')</p>
              <input name="kind" type="hidden" maxlength="20" value="{{ $kind }}"/>
              <select name="">
                <option disabled value='1' {{ old('kind') == '1' ? 'selected' : '' }}>@lang('r-mail-form.occupation_1')</option>
                <option disabled value='2' {{ old('kind') == '2' ? 'selected' : '' }}>@lang('r-mail-form.occupation_2')</option>
                <option disabled value='3' {{ old('kind') == '3' ? 'selected' : '' }}>@lang('r-mail-form.occupation_3')</option>
                <option disabled value='4' {{ old('kind') == '4' ? 'selected' : '' }}>@lang('r-mail-form.occupation_4')</option>
                <option disabled value='5' {{ old('kind') == '5' ? 'selected' : '' }}>@lang('r-mail-form.occupation_5')</option>
                <option disabled value='6' {{ old('kind') == '6' ? 'selected' : '' }}>@lang('r-mail-form.occupation_6')</option>
                <option disabled value='7' {{ old('kind') == '7' ? 'selected' : '' }}>@lang('r-mail-form.occupation_7')</option>
                <option disabled value='8' {{ old('kind') == '8' ? 'selected' : '' }}>@lang('r-mail-form.occupation_8')</option>
                <option disabled value='9' {{ old('kind') == '9' ? 'selected' : '' }}>@lang('r-mail-form.occupation_9')</option>
                <option disabled value='10' {{ old('kind') == '10' ? 'selected' : '' }}>@lang('r-mail-form.occupation_10')</option>
                <option disabled value='11' {{ old('kind') == '11' ? 'selected' : '' }}>@lang('r-mail-form.occupation_11')</option>
                <option disabled value='12' {{ old('kind') == '12' ? 'selected' : '' }}>@lang('r-mail-form.occupation_12')</option>
                <option disabled value='13' {{ old('kind') == '13' ? 'selected' : '' }}>@lang('r-mail-form.occupation_13')</option>
                <option disabled value='14' {{ old('kind') == '14' ? 'selected' : '' }}>@lang('r-mail-form.occupation_14')</option>
                <option disabled value='15' {{ old('kind') == '15' ? 'selected' : '' }}>@lang('r-mail-form.occupation_15')</option>
                <option disabled value='16' {{ old('kind') == '16' ? 'selected' : '' }}>@lang('r-mail-form.occupation_16')</option>
                <option disabled value='17' {{ old('kind') == '17' ? 'selected' : '' }}>@lang('r-mail-form.occupation_17')</option>
                <option disabled value='18' {{ old('kind') == '18' ? 'selected' : '' }}>@lang('r-mail-form.occupation_18')</option>
                <option disabled value='19' {{ old('kind') == '19' ? 'selected' : '' }}>@lang('r-mail_confirmation.occupation_19')</option>
                <option disabled value='20' {{ old('kind') == '20' ? 'selected' : '' }}>@lang('r-mail_confirmation.occupation_20')</option>
                <option disabled value='21' {{ old('kind') == '21' ? 'selected' : '' }}>@lang('r-mail-form.occupation_21')</option>
                <option disabled value='22' {{ old('kind') == '22' ? 'selected' : '' }}>@lang('r-mail-form.occupation_22')</option>
                <option disabled value='23' {{ old('kind') == '23' ? 'selected' : '' }}>@lang('r-mail-form.occupation_23')</option>
                <option disabled value='24' {{ old('kind') == '24' ? 'selected' : '' }}>@lang('r-mail-form.occupation_24')</option>
                <option disabled value='25' {{ old('kind') == '25' ? 'selected' : '' }}>@lang('r-mail-form.occupation_25')</option>
                <option disabled value='26' {{ old('kind') == '26' ? 'selected' : '' }}>@lang('r-mail_confirmation.occupation_26')</option>
              </select>
              <div class="li_caremane_1" style="display:none;">
                <input disabled class="caremane_input" type="checkbox" name="caremane[]" value='1' {{ is_array(old('caremane')) && in_array('1',old('caremane')) ? 'checked' : '' }}>@lang('r-mail-form.occupation_note')
              </div>

<?php
if(array_key_exists("caremane",$_POST)) {
  foreach ($_POST['caremane'] as $caremane) {
    print '<input type="hidden" name="caremane[]" value="'.$caremane.'">'.PHP_EOL;
  }
}
?>
            </label>
          </li>

          <li class="li_group_1" style="display:none;">
            <label for="" class="">
              <p id="group_name" class="input_name"
                data-employment_name="@lang('r-mail-form.employment_name')"
                data-employer_field="@lang('r-mail-form.employer_field')"
                data-schoolname="@lang('r-mail-form.school_name')" 
                data-trans="@lang('r-mail-form.employer_field')">
                  @lang('r-mail-form.employer_field')</p>
              <input name="group" type="hidden" maxlength="25" value="{{ $group }}"/>
              <p class="input_name confirm_p">{{ $group }}</p>
            </label>
          </li>

          <li class="li_school_1" style="display:none;">
            <label for="" class="">
              <p class="input_name">@lang('r-mail-form.graduation_year')</p>
              <p class="input_name confirm_p">
                @if($locale == 'ja') 
                  {{ $graduationYear . "年"}}
                  @else 
                  {{ $graduationYear }}
                @endif
              </p>
                <input name="graduationYear" type="hidden" maxlength="25" value="{{ $graduationYear }}"/>
            </label>
          </li>

<?php
/*
    下記データは診療科目1～5で使用

    oldの使用に不安があるので、下記、手書きの場合を参考に残す
 
<option disabled value='19' {{ $services1 == '19' ? 'selected' : '' }}>一般外科</option>
    あるいは
<option disabled value='19' {{ old('services2') == '19' ? 'selected' : '' }}>一般外科</option>

    この書き方で全ての科目を記述すればoldが機能する
    その場合、コントローラ[RMailFormController.php]でのoldの処理は外してOK
*/
// 外科
$services_gs = [19=>trans('r-mail-form.service_19'),20=>trans('r-mail-form.service_20'),22=>trans('r-mail-form.service_22'),23=>trans('r-mail-form.service_23'),24=>trans('r-mail-form.service_24'),25=>trans('r-mail-form.service_25'),26=>trans('r-mail-form.service_26'),27=>trans('r-mail-form.service_27')];
// 内科
$services_im = [1=>trans('r-mail-form.service_1'),2=>trans('r-mail-form.service_2'),4=>trans('r-mail-form.service_4'),49=>trans('r-mail-form.service_49'),5=>trans('r-mail-form.service_5'),7=>trans('r-mail-form.service_7'),8=>trans('r-mail-form.service_8'),10=>trans('r-mail-form.service_10'),11=>trans('r-mail-form.service_11'),12=>trans('r-mail-form.service_12'),47=>trans('r-mail-form.service_47'),13=>trans('r-mail-form.service_13'),14=>trans('r-mail-form.service_14'),16=>trans('r-mail-form.service_16'),44=>trans('r-mail-form.service_44'),18=>trans('r-mail-form.service_18')];
// その他
$services_etc = [3=>trans('r-mail-form.service_3'),40=>trans('r-mail-form.service_40'),38=>trans('r-mail-form.service_38'),42=>trans('r-mail-form.service_42'),35=>trans('r-mail-form.service_35'),6=>trans('r-mail-form.service_6'),21=>trans('r-mail-form.service_21'),50=>trans('r-mail-form.service_50'),32=>trans('r-mail-form.service_32'),9=>trans('r-mail-form.service_9'),43=>trans('r-mail-form.service_43'),33=>trans('r-mail-form.service_33'),34=>trans('r-mail-form.service_34'),39=>trans('r-mail-form.service_39'),29=>trans('r-mail-form.service_29'),48=>trans('r-mail-form.service_48'),51=>trans('r-mail-form.service_51'),15=>trans('r-mail-form.service_15'),52=>trans('r-mail-form.service_52'),53=>trans('r-mail-form.service_53'),28=>trans('r-mail-form.service_28'),31=>trans('r-mail-form.service_31'),37=>trans('r-mail-form.service_37'),54=>trans('r-mail-form.service_54'),36=>trans('r-mail-form.service_36'),45=>trans('r-mail-form.service_45'),46=>trans('r-mail-form.service_46'),41=>trans('r-mail-form.service_41'),17=>trans('r-mail-form.service_17'),30=>trans('r-mail-form.service_30')];
?>
          <li class="li_rowitem_1" style="display:none;">
            <label for="" class="">
              <p class="input_name">@lang('r-mail_confirmation.medical_subject')</p>
              <input name="services1" type="hidden" maxlength="20" value="{{ $services1 }}"/>
              <select name="">
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services1 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_im as $s_no => $s_name) {
  $selected = $services1 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_etc as $s_no => $s_name) {
  $selected = $services1 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
              </select>

              <input name="services2" type="hidden" maxlength="20" value="{{ $services2 }}"/>
              <select name="">
                <option disabled value='' {{ old('services2') == '' ? 'selected' : '' }}>@lang('r-mail_confirmation.no_selection')</option>
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services2 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_im as $s_no => $s_name) {
  $selected = $services2 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_etc as $s_no => $s_name) {
  $selected = $services2 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
              </select>

              <input name="services3" type="hidden" maxlength="20" value="{{ $services3 }}"/>
              <select name="">
                <option disabled value='' {{ old('services3') == '' ? 'selected' : '' }}>@lang('r-mail_confirmation.no_selection')</option>
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services3 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_im as $s_no => $s_name) {
  $selected = $services3 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_etc as $s_no => $s_name) {
  $selected = $services3 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
              </select>

              <input name="services4" type="hidden" maxlength="20" value="{{ $services4 }}"/>
              <select name="">
                <option disabled value='' {{ old('services4') == '' ? 'selected' : '' }}>@lang('r-mail_confirmation.no_selection')</option>
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services4 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_im as $s_no => $s_name) {
  $selected = $services4 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_etc as $s_no => $s_name) {
  $selected = $services4 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
              </select>

              <input name="services5" type="hidden" maxlength="20" value="{{ $services5 }}"/>
              <select name="">
                <option disabled value='' {{ old('services5') == '' ? 'selected' : '' }}>@lang('r-mail_confirmation.no_selection')</option>
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services5 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_im as $s_no => $s_name) {
  $selected = $services5 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
foreach($services_etc as $s_no => $s_name) {
  $selected = $services5 == $s_no  ? 'selected' : '';
  echo '<option disabled value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
              </select>

            </label>
          </li>

          <li class="li_id_1" style="display:none;">
            <label for="" class="">
              <p class="input_name">@lang('r-mail-form.identity_verification')</p>
<?php
if($identification == "none") {
    $dispID = trans('r-mail-form.verification_choice_1');
} else if($identification == "image") {
    $dispID = "画像による確認";
} else if($identification == "tel") {
    $dispID = "勤務先へのお電話による確認";
}
?>
              <input name="identification" type="hidden" value="{{ $identification }}"/>
              <p class="id_confirm" style=""><?= $dispID ?></p>
<?php
if($identification == "none") {
} else if($identification == "image") {
    $imagesrc = $image_path.$current; // 画像パス
    echo '<img src="'.$imagesrc.'" id="file-preview" style="width: 300px;"/>';
    echo '<input type="hidden" id="show-file" name="image_files[]" accept=".jpg,.png,.gif,image/jpeg,image/png,image/gif" value="" />';
    echo '<input type="hidden" name="image_path" value="'.$image_path.'" />';
    echo '<input type="hidden" name="current" value="'.$current.'" />';
    echo '<img id="file-preview" style="height:0px;" />';
} else if($identification == "tel") {
    echo '<input name="tel" type="hidden" maxlength="20" value="'.$tel.'"/>';
    echo '<p class="id_confirm" style="">'.$tel.'</p>';
}
?>
            </label>
          </li>
        </ul>
        <div class="contents_box_inner pTB20">
          <p class="mTB20" style="text-align: center;margin-top: 40px;"><a id="policy_privacy2"  href="{{ route('privacy') }}"> @lang('r-mail_confirmation.privacy_policy') </a>@lang('r-mail_confirmation.privacy_policy_note')</p>
          <p class="contact_submit mB20"><a href="{{ route('r-mail-form') }}" class="submit_backbtn">@lang('r-mail-form.return_button')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value=@lang('r-mail-form.send_button')></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{asset('js/r-mail-form-confirm.min.js?v=1.2.0.20190422')}}"></script>
@endsection
