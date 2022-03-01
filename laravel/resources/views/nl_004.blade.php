@extends('layouts.app')

@section('title', 'nl_004')

@section('breadcrumb')
{!! Breadcrumbs::render('nl_004') !!}
@endsection

@section('content')       

<!-- ニュースレター -->
<div class="nl" style="margin-top:40px;">
   <div class="nl_abstract_outline">
     <div class="nl_abstract"> @lang('nl_004.elephant') News Letter <span class="nl_volume">Vol.4</span>
     </div>
   </div>
   <div class="nl_title_outline">
     <div class="nl_title4">@lang('nl_004.title')</div>
   </div>
   <div class="c_container">
     <div class="oneline">
       <ul>
         <li>
           <div class="mat1">
             <img src="{{{asset('img/kudou2.png')}}}" style="width:70%;height:auto;">
             <p style="text-align:center;font-weight: bold;">@lang('nl_004.kenji')</p>
             <p style="font-size:12px;font-weight: bold;"> @lang('nl_004.description')
             </p>
             <p class="nl_sub_title4">@lang('nl_004.desc_subtitle') </p>
             <hr class="nl_hr4">
             <p>@lang('nl_004.paragraph_1') </p>
             <p class="nl_sub_title4">@lang('nl_004.subtitle_1')  </p>
             <hr class="nl_hr4">
             <p>@lang('nl_004.paragraph_2') </p>
           </div>
         </li>
         <li>
           <div class="mat4">
             <p class="nl_sub_title4"> @lang('nl_004.subtitle_2') </p>
             <hr class="nl_hr4">
             <p> @lang('nl_004.paragraph_3')</p>
             <p class="nl_sub_title4"> @lang('nl_004.subtitle_3') </p>
             <hr class="nl_hr4">
             <p style="margin:0px 0px 0px;"> @lang('nl_004.paragraph_4')</p>
             <div class="clearfix">
               <div class="lmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.subtitle_4')</p>
               </div>
               <div class="rmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.paragraph_5')</p>
               </div>
             </div>
             <div class="clearfix">
               <div class="lmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.method_1')</p>
               </div>
               <div class="rmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.method_2')</p>
               </div>
             </div>
           </div>
         </li>
         <li>
           <div class="mat5">
             <div class="clearfix">
               <div class="lmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.method_3')</p>
               </div>
               <div class="rmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.paragraph_6')</p>
               </div>
             </div>
             <div class="clearfix">
               <div class="lmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.image_caption')</p>
               </div>
               <div class="rmat">
                 <p style="margin-bottom:0px;">@lang('nl_004.btn_text')</p>
               </div>
             </div>
             <p class="nl_sub_title4"> @lang('nl_004.subtitle_5') </p>
             <hr class="nl_hr4">
             <p> @lang('nl_004.paragraph_7')</p>
             <img src="{{{asset('img/kikuzou.jpg')}}}" style="width:90%;height:auto;">
           </div>
         </li>
       </ul>
     </div>
   </div>
   <div class="nl_text">
     <p class="sp_none" style="text-align:center;">
       <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;margin-bottom:0px;">@lang('nl_004.a1')</a>
     </p>
     <p class="pc_none" style="text-align:center;">
       <a class="submit_btn" href="{{route('nl_backnumber')}}" style="cursor:pointer;width: 240px;"> @lang('nl_004.a2')</a>
     </p>
   </div>
 </div>
  <!-- /#container -->

@endsection