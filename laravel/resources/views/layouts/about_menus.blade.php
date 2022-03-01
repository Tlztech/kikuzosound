<div class="side_box mTB20 about_box">
  <h2 class="side_title sp_none">About</h2>
  <ul class="contents_list">
    <li><a href="{{ route('about') }}" class="{{ Route::currentRouteName()=='about'?'active':''}}" >@lang('about_menus.li1')</a></li>
    <li><a href="{{ route('terms') }}" class="{{ Route::currentRouteName()=='terms'?'active':''}}"> @lang('about_menus.li2')</a></li>
    <li><a href="{{ route('privacy') }}" class="{{ Route::currentRouteName()=='privacy'?'active':''}}"> @lang('about_menus.li3')</a></li>
    <li><a href="{{ route('business_partner') }}" class="{{ Route::currentRouteName()=='business_partner'?'active':''}}">@lang('about_menus.li4')</a></li>
    <li><a href="{{ route('faq') }}" class="{{ Route::currentRouteName()=='faq'?'active':''}}"> @lang('about_menus.li5')</a></li>
    <li><a href="{{ route('news') }}" class="{{ Route::currentRouteName()=='news'?'active':''}}">@lang('about_menus.li6')</a></li>
    <li><a href="{{ route('contact') }}" class="{{ Route::currentRouteName()=='contact'?'active':''}}"> @lang('about_menus.li7')</a></li>
  </ul>
</div>