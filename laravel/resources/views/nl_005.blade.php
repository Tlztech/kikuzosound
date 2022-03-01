@extends('layouts.app')

@section('title', 'nl_005')

@section('breadcrumb')
{!! Breadcrumbs::render('nl_005') !!}
@endsection

@section('content')

<!-- ニュースレター -->
<div class="nl" style="margin-top:40px;">
  <div class="nl_abstract_outline">
    <div class="nl_abstract"> @lang('nl_005.elephant') News Letter <span class="nl_volume">Vol.5</span>
    </div>
  </div>
  <div class="nl_title_outline">
    <div class="nl_title4">@lang('nl_005.title')</div>
  </div>
  <div class="c_container">
    <div class="oneline">
      <ul>
        <li>
          <div class="mat1">
            <img src="{{{asset('img/kudou.jpg')}}}" style="width:70%;height:auto;">
            <p style="text-align:center;font-weight: bold;"> @lang('nl_005.nojiri')</p>
            <p style="font-size:12px;font-weight: bold;"> @lang('nl_005.description') <br>
            </p>
            <!-- <p class="nl_sub_title4">
               フィジカル・アセスメントの基本<br>「肺聴診」
             </p>
             <hr class="nl_hr4"> -->
            <p> @lang('nl_005.paragraph_1') </p>
            
            <!-- I 聴診専用スピーカ 聴くゾウ -->
            <p class="nl_sub_title4"> @lang('nl_005.subtitle_2') </p>
            <hr class="nl_hr4">
            <img src="{{{asset('img/image--006.jpg')}}}" style="width:100%;height:auto;">
            <p> @lang('nl_005.paragraph_3') </p>
            <img src="{{{asset('img/image--007.jpg')}}}" style="width:100%;height:auto;" />
            
            <!-- II 聴くゾウの聴診音源  -->
            <!-- ① 聴診ポータルWeb サイ  -->
            <p class="nl_sub_title4" style="margin-top:10px;"> @lang('nl_005.subtitle_5') </p>
            <hr class="nl_hr4">
            <p> @lang('nl_005.paragraph_6') </p>
            <!-- ② オースカレイド（スマホアプリ） -->
            <p class="nl_sub_title4"> @lang('nl_005.subtitle_6') </p>
            <hr class="nl_hr4">
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_7') </p>
            
          </div>
        </li>
        <li>
          <div class="mat4">
            <!-- <p class="nl_sub_title4">
            @lang('nl_005.subtitle_2')
            </p>
            <hr class="nl_hr4">
            <img src="{{{asset('img/image--006.jpg')}}}"
              style="width:100%;height:auto;">
            <p>
            @lang('nl_005.paragraph_3')
            </p>
            <img src="{{{asset('img/image--007.jpg')}}}"
              style="width:100%;height:auto;"/> -->
            <!-- <br><br> -->

            <!-- ② オースカレイド（スマホアプリ） -->
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_7_2') </p>
            <!-- III 聴くゾウを使用した学習効果  -->
            <p class="nl_sub_title4" style="margin-top:10px;"> @lang('nl_005.subtitle_1') </p>
            <hr class="nl_hr4">
            <p> @lang('nl_005.paragraph_2') </p>
            <img src="{{{asset('img/image.png')}}}" style="width:70%;height:auto;">
            
            <!-- IV 聴くゾウを使用した学習の効率化  -->
            <p class="nl_sub_title4" style=" margin-top:<?php echo (Config::get("app.locale") == "ja") ? "20" : "122";?>px;"> @lang('nl_005.subtitle_3') </p>
            <hr class="nl_hr4">
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_4') </p>
            
            <!-- <div class="clearfix">
               <div class="lmat">
                 <p style="margin-bottom:0px;">(a)</p>
               </div>
               <div class="rmat">
                 <p style="margin-bottom:0px;">実際に音を聴いて、外部雑音が入らずよく聴こえること。</p>
               </div>
             </div> -->
            <!-- <div class="clearfix">
               <div class="lmat">
                 <p style="margin-bottom:0px;">(b)</p>
               </div>
               <div class="rmat">
                 <p style="margin-bottom:0px;">チェストピースを数回、膜型とベル型に変換させてみて、ガタつきが感じられないこと。ガタつきがあると感度は低下し、外部音が入りやすい。</p>
               </div>
             </div> -->
            </br>
          </div>
        </li>
        <li>
          <div class="mat5">
            <!-- <div class="clearfix">
              <div class="lmat">
                <p style="margin-bottom:0px;">(c)</p>
              </div>
              <div class="rmat">
                <p style="margin-bottom:0px;">導管が長すぎないこと。長すぎると導管が患者や自分の体・衣類・腕にぶつかり雑音を拾う。目安としては、聴診器を耳にかけた際にチェストピースが自分の“へそ”のあたりになる長さが良い。</p>
              </div>
            </div>

            <div class="clearfix">
              <div class="lmat">
                <p style="margin-bottom:0px;">(d)</p>
              </div>
              <div class="rmat">
                <p style="margin-bottom:0px;">イヤーピースの大きさが耳にぴったりしていること。イヤーピースが自分の耳に合わず少しでも隙間があれば、感度は著しく低下し雑音混入が増える。</p>
              </div>
            </div> -->
            
            <!-- IV 聴くゾウを使用した学習の効率化  -->
            <img src="{{{asset('img/Page-2-Image-6.jpg')}}}" style="width:100%;height:auto;">
            <p style="margin:0px 0px 0px; font-size:12px;"> @lang('nl_005.caption')</p>

             <!-- V 聴くゾウを使用した -->
              <!--シミュレータの機能拡張  -->
            <p class="nl_sub_title4" style="margin-top:10px;"> @lang('nl_005.subtitle_4') </p>
            <hr class="nl_hr4">
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_5') </p>
            <img src="{{{asset('img/image--010.jpg')}}}" style="width:90%;height:auto;">
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_8') </p>
            <img src="{{{asset('img/image--013.jpg')}}}" style="width:90%;height:auto;">
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_9') </p>
            
            <!-- VI まとめ -->
            <p class="nl_sub_title4"> @lang('nl_005.subtitle_7')</p>
            <hr class="nl_hr4">
            <p style="margin:0px 0px 0px;"> @lang('nl_005.paragraph_10') </p>
            </br>
            <p style="font-size:11px"> @lang('nl_005.references') </br>  @lang('nl_005.mailto'): 3sp@telemedica.co.jp <br> @lang('nl_005.last') </br> 045-875-19 </p>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <div class="nl_text">
    <p class="sp_none" style="text-align:center;">
      <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;margin-bottom:0px;">@lang('nl_005.btn_text')</a>
    </p>
    <p class="pc_none" style="text-align:center;">
      <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;width: 240px;">@lang('nl_005.btn_text')</a>
    </p>
  </div>
</div>
  <!-- /#container -->

@endsection
