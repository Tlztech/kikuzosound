@extends('layouts.app')

@section('title', trans('application_form.title'))

@section('breadcrumb')
{!! Breadcrumbs::render('appli_form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- サイト使用プラン　お申込み -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form action="{{ route('appli_form_confirm') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="contents_box_inner">
          <h2 class="title_m mT20" style="padding:4px;text-align: center;border: 1px solid #CCCCCC;">@lang('application_form.title')</h2>
          <p style="text-align:center;">@lang('application_form.sub_title')</p>
          <div class="appli_c" style="margin-bottom:0px;">
            <ul class="appli_ul" style="border:none;">
              <div class="appli_row">
                <li class="appli_li_plan"></li>
                <li class="appli_li"></li>
                <li class="appli_li"></li>
                <li class="appli_li"></li>
                <li class="appli_li">@lang('application_form.tax_info')</li>
              </div>
            </ul>
          </div>
          <div class="appli_c">
            <ul class="appli_ul">
              <div class="appli_row">
                <li class="appli_hr">@lang('application_form.table_header.li1')</li>
                <li class="appli_hr">@lang('application_form.table_header.li2')</li>
                <li class="appli_hr">@lang('application_form.table_header.li3')</li>
                <li class="appli_hr">@lang('application_form.table_header.li4')</li>
                <li class="appli_hr_init">@lang('application_form.table_header.li5')</li>
              </div>
              <div class="appli_row">
                <li class="appli_li_plan">@lang('application_form.table_header.row2.li1')</li>
                <li class="appli_li">@lang('application_form.table_header.row2.li2')</li>
                <li class="appli_li">@lang('application_form.table_header.row2.li3')</li>
                <li class="appli_li">@lang('application_form.table_header.row2.li4')</li>
                <li class="appli_li">@lang('application_form.table_header.row2.li5')</li>
              </div>
              <div class="appli_row">
                <li class="appli_li_plan_c">@lang('application_form.table_header.row3.li1')</li>
                <li class="appli_li_c">@lang('application_form.table_header.row3.li2')</li>
                <li class="appli_li_c">@lang('application_form.table_header.row3.li3')</li>
                <li class="appli_li_c">@lang('application_form.table_header.row3.li4')</li>
                <li class="appli_li">@lang('application_form.table_header.row3.li5')</li>
              </div>
              <div class="appli_row">
                <li class="appli_li_plan">@lang('application_form.table_header.row4.li1')</li>
                <li class="appli_li">@lang('application_form.table_header.row4.li2')</li>
                <li class="appli_li">@lang('application_form.table_header.row4.li3')</li>
                <li class="appli_li">@lang('application_form.table_header.row4.li4')</li>
                <li class="appli_li">@lang('application_form.table_header.row4.li5')</li>
              </div>
              <div class="appli_row">
                <li class="appli_li_plan_c">@lang('application_form.table_header.row5.li1')</li>
                <li class="appli_li_c">@lang('application_form.table_header.row5.li2')</li>
                <li class="appli_li_c">@lang('application_form.table_header.row5.li3')</li>
                <li class="appli_li_c">@lang('application_form.table_header.row5.li4')</li>
                <li class="appli_li">@lang('application_form.table_header.row5.li5')</li>
              </div>
            </ul>
          </div>
        </div>

        <div class="contents_box_inner">
          <p class="mB10 t_left">
            ・@lang('application_form.content_box.li1')<br>
            ・@lang('application_form.content_box.li2')<br>
            ・@lang('application_form.content_box.li3')<br>
            ・@lang('application_form.content_box.li4')<br>
          </p>
        </div>

        <div class="contents_box_inner">
          <h2 class="title_m mT20" style="padding:4px;text-align: center;border: 1px solid #CCCCCC;">@lang('application_form.purchase_txt')</h2>
          <p style="text-align:center;">@lang('application_form.price_desc')</p>
        </div>

        <div class="contents_box_inner">
          <p class="mTB20 t_left">
            ・@lang('application_form.purchase_id_info')
          </p>
        </div>
        <?php $lang = (Config::get('app.locale') != "ja") ? "_en" : ""; //get locale ?>
        <ul class="contact_form" style="margin-bottom:40px;">
          <li>
            <label for="" class="">
              <p class="input_name must name" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.name')</p>
              <input name="name" type="text" maxlength="25" value="{{ old('name') }}" id="name"/>
              @if($errors->has('name'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('name') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must facility" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.facility.field')</p>
              <input name="group" type="text" maxlength="25" value="{{ old('group') }}" placeholder= "@lang('application_form.form_fields.facility.placeholder')" id="facility"/>
              @if($errors->has('group'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('group') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must affiliation" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.affiliation.field')</p>
              <input name="affiliation" type="text" maxlength="100" id="affiliation" value="{{ old('affiliation') }}" placeholder= "@lang('application_form.form_fields.affiliation.placeholder')" />
              @if($errors->has('affiliation'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('affiliation') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must zip" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.zip.field')</p>
              <input name="zip" type="text" maxlength="100" id="zip" value="{{ old('zip') }}" placeholder= "@lang('application_form.form_fields.zip.placeholder')" />
              @if($errors->has('zip'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('zip') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must address" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.address.field')</p>
              <input name="address" type="text" maxlength="200" id="address" value="{{ old('address') }}" />
              @if($errors->has('address'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('address') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must tel" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.tel.field')</p>
              <input name="tel" type="text" id="tel" maxlength="20" value="{{ old('tel') }}"/>
              @if($errors->has('tel'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('tel') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must email" style=" background-image: url(../img/contact_must<?php echo $lang; ?>.png">@lang('application_form.form_fields.mail.field')</p>
              <input name="mail" type="text" id="email" maxlength="100" value="{{ old('mail') }}"/>
              @if($errors->has('mail'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('mail') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.dealer.field')</p>
              <input name="dealer" type="text" maxlength="100" value="{{ old('dealer') }}" placeholder= "@lang('application_form.form_fields.dealer.placeholder')" />
              @if($errors->has('dealer'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('dealer') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.area.field')</p>
              <input name="area" type="text" maxlength="100" value="{{ old('area') }}"/>
              @if($errors->has('area'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('area') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.sales.field')</p>
              <input name="sales" type="text" maxlength="100" value="{{ old('sales') }}"/>
              @if($errors->has('sales'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('sales') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.salestel.field')</p>
              <input name="salestel" type="text" maxlength="20" value="{{ old('salestel') }}"/>
              @if($errors->has('salestel'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('salestel') }}</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.salesmail.field')</p>
              <input name="salesmail" type="text" maxlength="100" value="{{ old('salesmail') }}"/>
              @if($errors->has('salesmail'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('salesmail') }}</p>
              @endif
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.plan')</p>

              <div style="height:40px;">
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('application_form.form_fields.plan1')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <select id="plan1" name="plan1" style="width:80px">
                      <option value='0' {{ old('plan1','0') == '0' ? 'selected' : '' }}>0</option>
                      <option value='1' {{ old('plan1') == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('plan1') == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('plan1') == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('plan1') == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('plan1') == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('plan1') == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('plan1') == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('plan1') == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('plan1') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>

              <div style="height:40px;">
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('application_form.form_fields.plan2')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <select id="plan2" name="plan2" style="width:80px">
                      <option value='0' {{ old('plan2','0') == '0' ? 'selected' : '' }}>0</option>
                      <option value='1' {{ old('plan2') == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('plan2') == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('plan2') == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('plan2') == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('plan2') == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('plan2') == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('plan2') == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('plan2') == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('plan2') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>

              <div style="height:40px;">
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('application_form.form_fields.plan3')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <select id="plan3" name="plan3" style="width:80px">
                      <option value='0' {{ old('plan3','0') == '0' ? 'selected' : '' }}>0</option>
                      <option value='1' {{ old('plan3') == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('plan3') == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('plan3') == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('plan3') == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('plan3') == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('plan3') == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('plan3') == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('plan3') == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('plan3') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>

                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('application_form.form_fields.plan4')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <select id="plan4" name="plan4" style="width:80px">
                      <option value='0' {{ old('plan4','0') == '0' ? 'selected' : '' }}>0</option>
                      <option value='1' {{ old('plan4') == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('plan4') == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('plan4') == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('plan4') == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('plan4') == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('plan4') == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('plan4') == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('plan4') == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('plan4') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>

            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.purchase.field')</p>
              <div>
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;font-size:16px;padding:0px 0px 0px 20px;">@lang('application_form.form_fields.purchase.number')</li>
                  <li style="display:table-cell;vertical-align:middle;border-bottom:none;">
                    <select id="purchase" name="purchase" style="width:80px">
                      <option value='0' {{ old('purchase','0') == '0' ? 'selected' : '' }}>0</option>
                      <option value='1' {{ old('purchase') == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('purchase') == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('purchase') == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('purchase') == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('purchase') == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('purchase') == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('purchase') == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('purchase') == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('purchase') == '9' ? 'selected' : '' }}>9</option>
                    </select>
                  </li>
                </ul>
              </div>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.guidance.field')</p>
              <div>
                <ul style="display:table; width:300px;margin:0px;table-layout:fixed;">
                  <li style="display:table-cell;border-bottom:none;font-size:16px;padding:0px 0px 0px 0px;">
                    <input style="width:50px;" type="checkbox" name="way[]" value='0' {{ is_array(old('way')) && in_array('0',old('way')) ? 'checked' : '' }}>@lang('application_form.form_fields.guidance.ch1')
                    <input style="width:50px;" type="checkbox" name="way[]" value='1' {{ is_array(old('way')) && in_array('1',old('way')) ? 'checked' : '' }}>@lang('application_form.form_fields.guidance.ch2')
                  </li>
                </ul>
              </div>
<p style="margin-bottom:10px;padding:0px 20px;">
@lang('application_form.form_fields.guidance.info')</p>
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.kind.field')</p>
              <input name="kind" type="text" maxlength="100" value="{{ old('kind') }}"/>
              @if($errors->has('kind'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('kind') }}</p>
              @endif
<p style="margin-bottom:10px;padding:0px 20px;">
@lang('application_form.form_fields.kind.info')
</p>
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name">@lang('application_form.form_fields.question.field')</p>
              <textarea name="question" type="text" maxlength="500" wrap="soft" placeholder= "@lang('application_form.form_fields.question.placeholder')" >{{ old('question') }}</textarea>
              @if($errors->has('question'))
              <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('question') }}</p>
              @endif
            </label>
          </li>
        </ul>


        <div class="contents_box_inner">
          <h2 class="title_m mT40" style="padding:4px;text-align: center;border: 1px solid #CCCCCC;">@lang('application_form.contract.title')</h2>
          <!-- 約款s -->
          <div class="agreement_text notice_list">
            <div class="appli-form-terms-box">
              <p style="text-align:center;font-size:16px;font-weight:bold;">
                @lang('application_form.contract.agreement_text')
                <span style="font-size:10px;">@lang('application_form.contract.for_individuals')
                <?php $url = env('APP_URL')."appli_form#personal"; /* $url = "http://local3sp.telemedica.jp/appli_form#personal"; */ ?>
                <a href="<?= $url ?>">@lang('application_form.contract.click_here')</a></span>
                <br>
              </p>
              <br>
              @lang('application_form.contract.paragraph_1')
              <br>
              @lang('application_form.contract.paragraph_2')
              <br>
              <br>
              <br>
              @lang('application_form.contract.chapter_1.title')
              <br>
              @lang('application_form.contract.chapter_1.purpose')
              <br>
              @lang('application_form.contract.chapter_1.article_1')
              <br>
              ２．@lang('application_form.contract.chapter_1.purpose_2')
              <br>
              ３．@lang('application_form.contract.chapter_1.purpose_2')
              <br>
              <br>
              @lang('application_form.contract.chapter_1.definition')
              <br>
              @lang('application_form.contract.chapter_1.article_2')
              <br>
          　　（１） @lang('application_form.contract.chapter_1.definitions.services')<br>
          　　（２） @lang('application_form.contract.chapter_1.definitions.usage')<br>
          　　（３） @lang('application_form.contract.chapter_1.definitions.providing')<br>
          　　（４） @lang('application_form.contract.chapter_1.definitions.server')<br>
          　　（５） @lang('application_form.contract.chapter_1.definitions.server_software')<br>
          　　（６） @lang('application_form.contract.chapter_1.definitions.server_data')<br>
          　　（７） @lang('application_form.contract.chapter_1.definitions.server_network')<br>
          　　（７） @lang('application_form.contract.chapter_1.definitions.client')<br>
          　　（８） @lang('application_form.contract.chapter_1.definitions.client_software')<br>
          　　（９） @lang('application_form.contract.chapter_1.definitions.access_line')<br>
              <br>
              <br>
              @lang('application_form.contract.chapter_2.title') @lang('application_form.contract.chapter_2.terms')<br>
             （@lang('application_form.contract.chapter_2.terms')）<br>
              @lang('application_form.contract.chapter_2.article_3')<br>
              ２．@lang('application_form.contract.chapter_2.terms_2')<br>
              ３．@lang('application_form.contract.chapter_2.terms_3')<br>
              <br>
              @lang('application_form.contract.chapter_2.setting_service')<br>
              @lang('application_form.contract.chapter_2.article_4')<br>
              @lang('application_form.contract.chapter_2.setup_services')      
              <br>
              @lang('application_form.contract.chapter_2.confirmation_test')<br>
              @lang('application_form.contract.chapter_2.article_5')    
              <br>
              @lang('application_form.contract.chapter_2.service_start')<br>
              @lang('application_form.contract.chapter_2.article_6')       
              <br>
              <br>
              @lang('application_form.contract.chapter_2.support_services')<br>
              @lang('application_form.contract.chapter_2.article_7')<br>
              <br>
              @lang('application_form.contract.chapter_2.article_8')<br> 
              @lang('application_form.contract.chapter_2.article_9') <br>
              @lang('application_form.contract.chapter_2.article_10')<br>
              @lang('application_form.contract.chapter_2.article_11')<br>
              @lang('application_form.contract.chapter_2.article_12')<br>
              @lang('application_form.contract.chapter_2.article_13')<br>
              @lang('application_form.contract.remaining_articles')<br> 
              <br>
              <br id="personal">
              <br>
              <hr>
              <br>
              <p style="text-align:center;font-size:16px;font-weight:bold;">
              @lang('application_form.contract.for_individuals')<br>
              </p>
              <br>
              @lang('application_form.contract.individual_paragraphs')<br>    
              <br>
              @lang('application_form.contract.chapter_1.title')
              <br>
              @lang('application_form.contract.chapter_1.purpose')
              <br>
              @lang('application_form.contract.chapter_1.article_1')
              <br>
              ２．@lang('application_form.contract.chapter_1.purpose_2')
              <br>
              ３．@lang('application_form.contract.chapter_1.purpose_2')
              <br>
              <br>
              @lang('application_form.contract.chapter_1.definition')
              <br>
              @lang('application_form.contract.chapter_1.article_2')
              <br>
          　　（１） @lang('application_form.contract.chapter_1.definitions.services')<br>
          　　（２） @lang('application_form.contract.chapter_1.definitions.usage')<br>
          　　（３） @lang('application_form.contract.chapter_1.definitions.providing')<br>
          　　（４） @lang('application_form.contract.chapter_1.definitions.server')<br>
          　　（５） @lang('application_form.contract.chapter_1.definitions.server_software')<br>
          　　（６） @lang('application_form.contract.chapter_1.definitions.server_data')<br>
          　　（７） @lang('application_form.contract.chapter_1.definitions.server_network')<br>
          　　（７） @lang('application_form.contract.chapter_1.definitions.client')<br>
          　　（８） @lang('application_form.contract.chapter_1.definitions.client_software')<br>
          　　（９） @lang('application_form.contract.chapter_1.definitions.access_line')<br>
              <br>
              <br>
              @lang('application_form.contract.chapter_2.title') @lang('application_form.contract.chapter_2.terms')<br>
             （@lang('application_form.contract.chapter_2.terms')）<br>
              @lang('application_form.contract.chapter_2.article_3')<br>
              ２．@lang('application_form.contract.chapter_2.terms_2')<br>
              ３．@lang('application_form.contract.chapter_2.terms_3')<br>
              <br>
              @lang('application_form.contract.chapter_2.setting_service')<br>
              @lang('application_form.contract.chapter_2.article_4')<br>
              @lang('application_form.contract.chapter_2.setup_services')      
              <br>
              @lang('application_form.contract.chapter_2.confirmation_test')<br>
              @lang('application_form.contract.chapter_2.article_5')    
              <br>
              @lang('application_form.contract.chapter_2.service_start')<br>
              @lang('application_form.contract.chapter_2.article_6')       
              <br>
              <br>
              @lang('application_form.contract.chapter_2.support_services')<br>
              @lang('application_form.contract.chapter_2.article_7')<br>
              <br>
              @lang('application_form.contract.chapter_2.article_8')<br> 
              @lang('application_form.contract.chapter_2.article_9') <br>
              @lang('application_form.contract.chapter_2.article_10')<br>
              @lang('application_form.contract.chapter_2.article_11')<br>
              @lang('application_form.contract.chapter_2.article_12')<br>
              @lang('application_form.contract.chapter_2.article_13')<br>  
              @lang('application_form.contract.remaining_articles')<br> 
            </div>

        </div>
        <!-- 約款e -->
    </div>

    <p style="text-align:center;">
      <label for="agree1" class="agreement_button">
        <input type="checkbox" name="agree" value="0" id="agree1"> @lang('application_form.contract.agree') 
        @if($errors->has('agree'))
          <p style="text-align:center;color:red;font-size:12px;font-weight:bold;margin:0 0 1em 0;">{{ $errors->first('agree') }}</p>
        @endif
      </label>
    </p>

        <div class="contents_box_inner pTB20">
          <p class="contact_submit mB20"><a href="{{ route('contact') }}" class="submit_backbtn"> @lang('application_form.contract.btn_return')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value=@lang('application_form.contract.btn_purchase')></input></p>
        </div>
      </form>
      <div class="contents_box_inner">
        <p class="mB10 t_left">
          ・@lang('application_form.contract.see_page')「<a href="{{ route('privacy') }}">@lang('application_form.contract.link')</a>」@lang('application_form.contract.personal_info')
        </p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/js/ajaxzip3.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $(".submit_btn").on("click", function() {  // タグ
    var plan1 = $("#plan1").val();
    var plan2 = $("#plan2").val();
    var plan3 = $("#plan3").val();
    var plan4 = $("#plan4").val();

    var purchase = $("#purchase").val();

    var total = plan1 + plan2 + plan3 + plan4 + purchase;

    if(total == 0) {
      alert("<?php echo trans('application_form.no_purchase'); ?>");
      return false;
    }
  });
});
</script>

<script type="text/javascript">
(function($){
    $('#zip').change(function(){
        var zip = $('#zip').val();

        if(zip != "" && !zip.match(/^\d{3}-?\d{4}$/)) {
            alert("<?php echo trans('application_form.zip_valid'); ?>");
            return false;
        }

        var addr = $('#address').val();

        if(addr == "") {
            AjaxZip3.zip2addr(this,'','address','address');
        }
    });
})(jQuery);
</script>
<script>
$("#name, #facility, #affiliation, #zip, #address, #tel, #email").on(
    "keyup",
    function () {
      $("#name").val()
        ? $(".name").css("background-image", "none")
        : $(".name").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
      $("#facility").val()
        ? $(".facility").css("background-image", "none")
        : $(".facility").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
      $("#affiliation").val()
        ? $(".affiliation").css("background-image", "none")
        : $(".affiliation").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
      $("#zip").val()
        ? $(".zip").css("background-image", "none")
        : $(".zip").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
      $("#address").val()
        ? $(".address").css("background-image", "none")
        : $(".address").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
      $("#tel").val()
        ? $(".tel").css("background-image", "none")
        : $(".tel").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
      $("#email").val()
        ? $(".email").css("background-image", "none")
        : $(".email").css(
            "background-image",
            "url(../img/contact_must<?php echo $lang; ?>.png"
          );
    }
  );
</script>
@endsection
