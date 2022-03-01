@extends('layouts.app')

@section('title', 'Privacy')

@section('breadcrumb')
{!! Breadcrumbs::render('privacy') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container">
  <!-- プライバシーポリシー -->
  <div class="container_inner clearfix">
    <div class="contents">
      <div class="about mTB20">
        <div class="about_title">@lang('privacy_policy.page_title')</div>
        </br>
        <div class="about_text">
          <p>@lang('privacy_policy.telemedica_policy')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.laws_regulations')</div>
          </br>
          <p>@lang('privacy_policy.regulations_content')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.measures_safety')</div>
          </br>
          <p>@lang('privacy_policy.safety_content')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.acquisition')</div>
          </br>
          <p>@lang('privacy_policy.acquisition_content')</p>
          </br>
          <p>@lang('privacy_policy.acquisition_lists')</p>
          </br>
          <p>@lang('privacy_policy.info_provided')</p>
          </br>
          <p>@lang('privacy_policy.info_provided_content')</p>
          </br>
          <p>@lang('privacy_policy.info_collected')</p>
          </br>
          <p>@lang('privacy_policy.info_collected_content')</p>
          </br>
          <p>@lang('privacy_policy.other_info')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.purpose_use')</div>
          </br>
          <p>@lang('privacy_policy.purpose_4_1')</p>
          </br>
          <p>@lang('privacy_policy.registration_procedure')</p>
          </br>
          <p>@lang('privacy_policy.provision_service')</p>
          </br>
          <p>@lang('privacy_policy.usage_status')</p>
          </br>
          <p>@lang('privacy_policy.improve_surveys')</p>
          </br>
          <p>@lang('privacy_policy.ads_delivery')</p>
          </br>
          <p>@lang('privacy_policy.new_service_info')</p>
          </br>
          <p>@lang('privacy_policy.inquiry_response')</p>
          </br>
          <p>@lang('privacy_policy.notification_change')</p>
          </br>
          <p>@lang('privacy_policy.unpaid_dues')</p>
          </br>
          <p>@lang('privacy_policy.other_purpose')</p>
          </br>
          <p>@lang('privacy_policy.purpose_4_2')</p>
          </br>
          <p>@lang('privacy_policy.process_user_info')</p>
          </br>
          <p>@lang('privacy_policy.gender')</p>
          </br>
          <p>@lang('privacy_policy.birthday')</p>
          </br>
          <p>@lang('privacy_policy.residence')</p>
          </br>
          <p>@lang('privacy_policy.attribute_info')</p>
          </br>
          <p>@lang('privacy_policy.terminal_info')</p>
          </br>
          <p>@lang('privacy_policy.history_service')</p>
          </br>
          <p>@lang('privacy_policy.cookie_info')</p>
          </br>
          <p>@lang('privacy_policy.anonymous_info')</p>
          </br>
          <p>@lang('privacy_policy.statistical_data')</p>
          </br>
          <p>@lang('privacy_policy.development')</p>
          </br>
          <p>@lang('privacy_policy.ads_distribution')</p>
          </br>
          <p>@lang('privacy_policy.product_info_services')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.provision')</div>
          </br>
          <p>@lang('privacy_policy.provision_content')</p>
          </br>
          <p>@lang('privacy_policy.provision_list1')</p>
          </br>
          <p>@lang('privacy_policy.provision_list2')</p>
          </br>
          <p>@lang('privacy_policy.provision_list3')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.consignment')</div>
          </br>
          <p>@lang('privacy_policy.consignment_content')</p>
          </br>
          <p>@lang('privacy_policy.consignment_list1')</p>
          </br>
          <p>@lang('privacy_policy.consignment_list2')</p>
          </br>
          <p>@lang('privacy_policy.consignment_list3')</p>
          </br>
          <p>@lang('privacy_policy.consignment_list4')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.disclosure')</div>
          </br>
          <p>@lang('privacy_policy.disclosure_info_description')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.correction')</div>
          </br>
          <p>@lang('privacy_policy.correction_description_1')</p>
          </br>
          <p>@lang('privacy_policy.correction_info_list1')</p>
          </br>
          <p>@lang('privacy_policy.correction_info_list2')</p>
          </br>
          <p>@lang('privacy_policy.correction_description_2')</p>
          </br>
          <p>@lang('privacy_policy.correction_description_3')</p>
          </br>
          <p>@lang('privacy_policy.correction_procedure')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_1')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_2')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_3')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_4')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_5')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_6')</p>
          </br>
          @if(config('app.locale') != 'en')
          <p>@lang('privacy_policy.paragraph_7')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_8')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_9')</p>
          </br>
          <p>&#9312;　@lang('privacy_policy.paragraph_10')</p>
          </br>
          <p>&#9313;　@lang('privacy_policy.paragraph_11')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_12')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_13')</p>
          </br>
          <p>&#9312;　@lang('privacy_policy.paragraph_14')</p>
          </br>
          <p>&#9313;　@lang('privacy_policy.paragraph_11')</p>
          </br>
          <p>&#9314;　@lang('privacy_policy.paragraph_15')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_16')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_17')</p>
          </br>
          <p>&#9315;　@lang('privacy_policy.paragraph_18')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_19')</p>
          </br>
          <p>(c)　@lang('privacy_policy.paragraph_20')</p>
          </br>
          <p>&#9312;　@lang('privacy_policy.paragraph_21')</p>
          </br>
          <p>&#9313;　@lang('privacy_policy.paragraph_11')</p>
          </br>
          <p>&#9314;　@lang('privacy_policy.paragraph_22')</p>
          </br>
          <p>&#9315;　@lang('privacy_policy.paragraph_23')</p>
          </br>
          <p>(3)　@lang('privacy_policy.paragraph_24')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_25')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_26')</p>
          </br>
          <p>(4)　@lang('privacy_policy.paragraph_27')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_28')</p>
          </br>
          <p>(5)　@lang('privacy_policy.paragraph_29')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_30')</p>
          </br>
          <p>(6)　@lang('privacy_policy.paragraph_31')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_32')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_33')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_34')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_35')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_36')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_37')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_38')</p>
          </br>
          <p>@lang('privacy_policy.paragraph_39')</p>
          </br>
          @endif
          <div class="little_title">@lang('privacy_policy.processing')</div>
          </br>
          <p>@lang('privacy_policy.processing_info_description')</p>
          </br>
          <div class="little_title">@lang('privacy_policy.handling_complaints')</div>
          </br>
          <p>@lang('privacy_policy.handling_paragraph_1')</p>
          </br>
          @if(config('app.locale') != 'en')
          <p>@lang('privacy_policy.handling_paragraph_2')</p>
          </br>
          <!-- <p>@lang('privacy_policy.handling_paragraph_3')</p>
          </br>
          <p>@lang('privacy_policy.handling_paragraph_4')</p>
          </br> -->
          <p>@lang('privacy_policy.handling_paragraph_5')</p>
          </br>
          @endif
          <div class="little_title">@lang('privacy_policy.changing_policy')</div>
          </br>
          <p>@lang('privacy_policy.policy_procedure_description')</p>
          </br>
          <p>@lang('privacy_policy.date_created')</p>
        </div>
      </div>
    </div>
<!-- サイドコンテンツ（右） -->
<div class="side_column">
  <!-- Aboutリンクー -->
  @include('layouts.about_menus')
  <!-- 聴診専用スピーカとは？ -->
  @include('layouts.whatspeaker')
</div>

  </div>
</div>
@endsection
