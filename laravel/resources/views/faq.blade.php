@extends('layouts.app')

@section('title', 'FAQ')

@section('breadcrumb')
{!! Breadcrumbs::render('faq') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container">
  <!-- お知らせ -->
  <div class="container_inner clearfix">
    <div class="contents">
      <div class="contents_search mTB20">
        <div class="search_box">
          <p class="search_box_inner clearfix">
            <input class="search_keyword" placeholder="@lang('faq.search_placeholder')" type="text" name="keyword">
            <button class="search_btn"></button>
          </p>
        </div>
        <!-- /.search_box -->
      </div>
      <!-- /.contents_search -->
<div class="contents_box clearboth">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_1')</h2>
    <dl class="faq_list first">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_1')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_1')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_2')</span></dt>
      <dd class="faq_A accordion_moreconts">
       @lang('faq.answer_2')

      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_3')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_3')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_4')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_4')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_5')</span></dt>
      <dd class="faq_A accordion_moreconts">
      @lang('faq.answer_5')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_6')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_6')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_7')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_7')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_8')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_8')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_9')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_9')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_10')</span></dt>

      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_10')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_11')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_11')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_51')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_51')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_52')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_52')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_53')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_53')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_54')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_54')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_55')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_55')
      </dd>
    </dl>
  </div>
</div>
<div class="contents_box mTB20">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_2')</h2>
    <dl class="faq_list first">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_12')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_12')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_13')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_13')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_14')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_14')
      </dd>
    </dl>
  </div>
</div>
<div class="contents_box mTB20">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_3')</h2>
    <dl class="faq_list first">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_15')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_15')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_16')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_16')
      </dd>
    </dl>
  </div>
</div>
<div class="contents_box mTB20">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_4')</h2>
    <dl class="faq_list first">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_17')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_17')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_18')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_18')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_19')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_19')
      </dd>
    </dl>
  </div>
</div>
<div class="contents_box mTB20">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_5')</h2>
    <dl class="faq_list first">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_20')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_20')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_21')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_21')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_22')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_22')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_23')</span></dt>
      <dd class="faq_A accordion_moreconts">
       @lang('faq.answer_23')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_24')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_24')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_25')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_25')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>
        @lang('faq.question_26')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_26')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_27')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_27')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_28')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_28')
     </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_29')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_29')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_30')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_30')
      </dd>
    </dl>
  </div>
</div>
<div class="contents_box mTB20">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_7')</h2>
    <dl class="faq_list first" style="border-top: 2px dotted #ccc;">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_32')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_32')
      </dd>
    </dl>
    <!-- <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_33')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_33')
      </dd>
    </dl> -->
  </div>
</div>
<div class="contents_box mTB20">
  <div class="contents_box_inner pTB20">
    <h2 class="title_m mTB10">@lang('faq.title_8')</h2>
    <dl class="faq_list first">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_34')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_34')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_35')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_35')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_36')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_36')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_37')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_37')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_38')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_38')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_39')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_39')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_40')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_40')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_41')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_41')
      </dd>
    </dl>
    @if(config('app.locale') != 'en')
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_42')</span></dt>
      <dd class="faq_A accordion_moreconts">@lang('faq.answer_42')
      </dd>
    </dl>
    @endif
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_43')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_43')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_44')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_44')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_45')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_45')
      </dd>
    </dl>
<!--
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>ペースメーカーに対する影響が心配です。電波は出ますか？</span></dt>
      <dd class="faq_A accordion_moreconts">
        本製品はスピーカーであり、電波の発生はほとんどありません。従ってペースメーカーに対する影響はほとんどないと考えられますが、ペースメーカーへの影響は試験していません。ペースメーカーに対する影響を心配される場合は、ご利用にならないでください。
      </dd>
    </dl>
-->
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_46')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_46')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_47')</span></dt>
      <dd class="faq_A accordion_moreconts"> @lang('faq.answer_47')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_48')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_48')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_49')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_49')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_50')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_50')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn"><span>@lang('faq.question_56')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_56')
      </dd>
    </dl>
    <dl class="faq_list">
      <dt class="faq_Q accordion_btn anchor" id="IpaxSharedPc"><span>@lang('faq.question_57')</span></dt>
      <dd class="faq_A accordion_moreconts">
        @lang('faq.answer_57')
      </dd>
    </dl>
  </div>
</div>


<!-- /.contents_box -->
<!----------------------------------- /faq_2 --------------------------------- -->

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
<script type="text/javascript" src="/js/jquery.sss_portal.faq.js?v=1.1.6.20170323"></script>
<script type="text/javascript">
  $(document).ready(function(){
  var hash = window.location.hash;
  if (hash) {
    var element = $(hash);    
    if (element.length) {
     element.next(".accordion_moreconts").slideToggle();
     element.toggleClass("accordion_more");
    }
  }
  });
 (function($){
  // FAQ検索を有効にする。
  $('.contents').sss_portal.faq();
})(jQuery);

</script>

@endsection
