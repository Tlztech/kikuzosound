<!-- 広告 -->
<div class="side_advertisement" style="display:none;">
<?php
/*
  <div class="side_box_inner side_advertisement_text clearfix">
     <a href="{{ route('vest') }}"><img src="/img/bnr_vest.png"></a>
  </div>
  <div class="side_box_inner side_advertisement_text clearfix">
     <a href="http://pbl.telemedica.jp/" target="_blank" class="outlink"><img src="/img/bnr_pbl_maker.png"></a>
  </div>
*/
?>
  <div class="side_box_inner side_advertisement_text clearfix">
    <!-- <p>広告表示枠</p> -->
    <a href="https://telemedica.jp/" target="_blank" class="outlink">
    @if(Config::get('app.locale') == 'en')
      <img src="/img/TMN_BNR_en.png"></a>
      @else 
      <img src="/img/TMN_BNR.png"></a>
      @endif
  </div>
  <div class="side_box_inner side_advertisement_text clearfix">
     <a href="http://telemedica.co.jp/" target="_blank" class="outlink">
     @if(Config::get('app.locale') == 'en')
         <img src="/img/bnr_telemedica_co_en.png"></a>
      @else 
      <img src="/img/bnr_telemedica_co_jp.png"></a>
      @endif
  </div>
  <div class="side_box_inner side_advertisement_text clearfix">
     <a href="http://pasono.telemedica.jp/" target="_blank" class="outlink">
     @if(Config::get('app.locale') == 'en')
     <img src="/img/bnr_pharmacy_en.png"></a>
      @else 
      <img src="/img/bnr_pharmacy.png"></a>
      @endif
  </div>
 
</div>
