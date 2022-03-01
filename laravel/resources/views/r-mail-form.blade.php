@extends('layouts.app')

@section('title', 'Authentication for auscultation member library registration email authentication')

@section('breadcrumb')
{!! Breadcrumbs::render('r-mail-form') !!}
@endsection

@section('content')
<!-- メインコンテンツ（左） -->
<div id="container" style="overflow:hidden;">
  <!-- お問い合わせフォーム -->
  <div class="container_inner clearfix">
    <div class="contents_box sp_mTB20 mTB20 t_center">
      <form id="r-mail-form" action="{{ route('r-mail-form-confirm') }}" method="GET" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="edit" value="{{ old('edit',$edit) }}">
        <div class="contents_box_inner">
          <h2 class="title_m mTB20" style="text-align: center;">@lang('r-mail-form.title')</h2>
          <p class="mB10 t_left">
            ・@lang('r-mail-form.message_1')<br/>
            ・@lang('r-mail-form.message_2')<br/>
          </p>
        </div>
        <ul class="contact_form">
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.mail_field')</p>
              <input name="mail" type="text" maxlength="200" value="{{ old('mail',$mail) }}"/>
              @if($errors->has('mail'))
              <p class="error_p">{{ $errors->first('mail') }}</p>
              @endif
              <?php // 同一メールアドレスがある場合 ?>
              @if(Session::has('ls'))
              <div class="error_p">{{ session('ls') }}</div>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.password_field')</p>
              <input name="password" type="password" maxlength="100" readonly onfocus="this.removeAttribute('readonly');" />
              @if($errors->has('password'))
              <p class="error_p">{{ $errors->first('password') }}</p>
              @endif
<?php
/*
フォームに入った時にコントローラでセッションを削除するようにした為、
今、下記は表示されない
*/
?>
              @if(Session::has('SAMPLE-USER-PASSWORD'))
              <p class="error_p">パスワードは再度入力して下さい</p>
              @endif
            </label>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.full_name_field')</p>
              <div class="rowitem">
                <ul class="name_ul">
                  <li class="name_li">
                    <input name="name1" type="text" maxlength="25" value="{{ old('name1',$name1) }}" class="name_input" placeholder="@lang('r-mail-form.full_name_surname_placeholder')" />
                  </li>
                  <li class="name_li">
                    <input name="name2" type="text" maxlength="25" value="{{ old('name2',$name2) }}" class="name_input" placeholder="@lang('r-mail-form.full_name_name_placeholder')" />
                  </li>
                </ul>
              </div>
              @if($errors->has('name1'))
              <p class="error_p">{{ $errors->first('name1') }}</p>
              @endif
              @if($errors->has('name2'))
              <p class="error_p">{{ $errors->first('name2') }}</p>
              @endif
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.name_field')</p>
              <div class="rowitem">
                <ul class="name_ul">
                  <li class="name_li">
                    <input name="kana1" type="text" maxlength="25" value="{{ old('kana1',$kana1) }}" class="name_input" placeholder="@lang('r-mail-form.name_surname_placeholder')" />
                  </li>
                  <li class="name_li">
                    <input name="kana2" type="text" maxlength="25" value="{{ old('kana2',$kana2) }}" class="name_input" placeholder="@lang('r-mail-form.name_2nd_placeholder')" />
                  </li>
                </ul>
              </div>
              @if($errors->has('kana1'))
              <p class="error_p">{{ $errors->first('kana1') }}</p>
              @endif
              @if($errors->has('kana2'))
              <p class="error_p">{{ $errors->first('kana2') }}</p>
              @endif
            </label>
          </li>

          <li>
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.birthday_field')</p>
              <div class="rowitem_birth">
                <ul class="birthday_ul">
                  <?php
                    $locale = Session::get('lang');
                  ?>
                  <li class="yearSelect1_li">
                  @if($locale == 'ja')
                    <select id="yearSelect" name="yearSelect" class="yearSelect_input">
                  @else 
                    <select id="yearSelect" name="yearSelect" class="yearSelect_input yearSelect_en" >
                  @endif
                      <option value='1901' {{ old('yearSelect',$yearSelect) == '1901' ? 'selected' : '' }}>1901 @if($locale == 'ja')  (@lang('r-mail-form.meiji')34) @endif </option>
                      <option value='1902' {{ old('yearSelect',$yearSelect) == '1902' ? 'selected' : '' }}>1902 @if($locale == 'ja') (@lang('r-mail-form.meiji')35) @endif </option>
                      <option value='1903' {{ old('yearSelect',$yearSelect) == '1903' ? 'selected' : '' }}>1903 @if($locale == 'ja')(@lang('r-mail-form.meiji')36) @endif </option>
                      <option value='1904' {{ old('yearSelect',$yearSelect) == '1904' ? 'selected' : '' }}>1904 @if($locale == 'ja') (@lang('r-mail-form.meiji')37) @endif </option>
                      <option value='1905' {{ old('yearSelect',$yearSelect) == '1905' ? 'selected' : '' }}>1905 @if($locale == 'ja') (@lang('r-mail-form.meiji')38) @endif</option>
                      <option value='1906' {{ old('yearSelect',$yearSelect) == '1906' ? 'selected' : '' }}>1906 @if($locale == 'ja') (@lang('r-mail-form.meiji')39) @endif</option>
                      <option value='1907' {{ old('yearSelect',$yearSelect) == '1907' ? 'selected' : '' }}>1907 @if($locale == 'ja') (@lang('r-mail-form.meiji')40) @endif</option>
                      <option value='1908' {{ old('yearSelect',$yearSelect) == '1908' ? 'selected' : '' }}>1908 @if($locale == 'ja') (@lang('r-mail-form.meiji')41) @endif</option>
                      <option value='1909' {{ old('yearSelect',$yearSelect) == '1909' ? 'selected' : '' }}>1909 @if($locale == 'ja') (@lang('r-mail-form.meiji')42) @endif</option>
                      <option value='1910' {{ old('yearSelect',$yearSelect) == '1910' ? 'selected' : '' }}>1910 @if($locale == 'ja') (@lang('r-mail-form.meiji')43) @endif</option>
                      <option value='1911' {{ old('yearSelect',$yearSelect) == '1911' ? 'selected' : '' }}>1911 @if($locale == 'ja') (@lang('r-mail-form.meiji')44) @endif</option>
                      <option value='1912' {{ old('yearSelect',$yearSelect) == '1912' ? 'selected' : '' }}>1912 @if($locale == 'ja') (@lang('r-mail-form.taisho')1) @endif</option>
                      <option value='1913' {{ old('yearSelect',$yearSelect) == '1913' ? 'selected' : '' }}>1913 @if($locale == 'ja') (@lang('r-mail-form.taisho')2) @endif</option>
                      <option value='1914' {{ old('yearSelect',$yearSelect) == '1914' ? 'selected' : '' }}>1914 @if($locale == 'ja') (@lang('r-mail-form.taisho')3) @endif</option>
                      <option value='1915' {{ old('yearSelect',$yearSelect) == '1915' ? 'selected' : '' }}>1915 @if($locale == 'ja') (@lang('r-mail-form.taisho')4) @endif</option>
                      <option value='1916' {{ old('yearSelect',$yearSelect) == '1916' ? 'selected' : '' }}>1916 @if($locale == 'ja') (@lang('r-mail-form.taisho')5) @endif</option>
                      <option value='1917' {{ old('yearSelect',$yearSelect) == '1917' ? 'selected' : '' }}>1917 @if($locale == 'ja') (@lang('r-mail-form.taisho')6) @endif</option>
                      <option value='1918' {{ old('yearSelect',$yearSelect) == '1918' ? 'selected' : '' }}>1918 @if($locale == 'ja') (@lang('r-mail-form.taisho')7) @endif</option>
                      <option value='1919' {{ old('yearSelect',$yearSelect) == '1919' ? 'selected' : '' }}>1919 @if($locale == 'ja') (@lang('r-mail-form.taisho')8) @endif</option>
                      <option value='1920' {{ old('yearSelect',$yearSelect) == '1920' ? 'selected' : '' }}>1920 @if($locale == 'ja') (@lang('r-mail-form.taisho')9) @endif</option>
                      <option value='1921' {{ old('yearSelect',$yearSelect) == '1921' ? 'selected' : '' }}>1921 @if($locale == 'ja') (@lang('r-mail-form.taisho')10) @endif</option>
                      <option value='1922' {{ old('yearSelect',$yearSelect) == '1922' ? 'selected' : '' }}>1922 @if($locale == 'ja') (@lang('r-mail-form.taisho')11) @endif</option>
                      <option value='1923' {{ old('yearSelect',$yearSelect) == '1923' ? 'selected' : '' }}>1923 @if($locale == 'ja') (@lang('r-mail-form.taisho')12) @endif</option>
                      <option value='1924' {{ old('yearSelect',$yearSelect) == '1924' ? 'selected' : '' }}>1924 @if($locale == 'ja') (@lang('r-mail-form.taisho')13) @endif</option>
                      <option value='1925' {{ old('yearSelect',$yearSelect) == '1925' ? 'selected' : '' }}>1925 @if($locale == 'ja') (@lang('r-mail-form.taisho')14) @endif</option>
                      <option value='1926' {{ old('yearSelect',$yearSelect) == '1926' ? 'selected' : '' }}>1926 @if($locale == 'ja') (@lang('r-mail-form.showa')1) @endif</option>
                      <option value='1927' {{ old('yearSelect',$yearSelect) == '1927' ? 'selected' : '' }}>1927 @if($locale == 'ja') (@lang('r-mail-form.showa')2) @endif</option>
                      <option value='1928' {{ old('yearSelect',$yearSelect) == '1928' ? 'selected' : '' }}>1928 @if($locale == 'ja') (@lang('r-mail-form.showa')3) @endif</option>
                      <option value='1929' {{ old('yearSelect',$yearSelect) == '1929' ? 'selected' : '' }}>1929 @if($locale == 'ja') (@lang('r-mail-form.showa')4) @endif</option>
                      <option value='1930' {{ old('yearSelect',$yearSelect) == '1930' ? 'selected' : '' }}>1930 @if($locale == 'ja') (@lang('r-mail-form.showa')5) @endif</option>
                      <option value='1931' {{ old('yearSelect',$yearSelect) == '1931' ? 'selected' : '' }}>1931 @if($locale == 'ja') (@lang('r-mail-form.showa')6) @endif</option>
                      <option value='1932' {{ old('yearSelect',$yearSelect) == '1932' ? 'selected' : '' }}>1932 @if($locale == 'ja') (@lang('r-mail-form.showa')7) @endif</option>
                      <option value='1933' {{ old('yearSelect',$yearSelect) == '1933' ? 'selected' : '' }}>1933 @if($locale == 'ja') (@lang('r-mail-form.showa')8) @endif</option>
                      <option value='1934' {{ old('yearSelect',$yearSelect) == '1934' ? 'selected' : '' }}>1934 @if($locale == 'ja') (@lang('r-mail-form.showa')9) @endif</option>
                      <option value='1935' {{ old('yearSelect',$yearSelect) == '1935' ? 'selected' : '' }}>1935 @if($locale == 'ja') (@lang('r-mail-form.showa')10) @endif</option>
                      <option value='1936' {{ old('yearSelect',$yearSelect) == '1936' ? 'selected' : '' }}>1936 @if($locale == 'ja') (@lang('r-mail-form.showa')11) @endif</option>
                      <option value='1937' {{ old('yearSelect',$yearSelect) == '1937' ? 'selected' : '' }}>1937 @if($locale == 'ja') (@lang('r-mail-form.showa')12) @endif</option>
                      <option value='1938' {{ old('yearSelect',$yearSelect) == '1938' ? 'selected' : '' }}>1938 @if($locale == 'ja') (@lang('r-mail-form.showa')13) @endif</option>
                      <option value='1939' {{ old('yearSelect',$yearSelect) == '1939' ? 'selected' : '' }}>1939 @if($locale == 'ja') (@lang('r-mail-form.showa')14) @endif</option>
                      <option value='1940' {{ old('yearSelect',$yearSelect) == '1940' ? 'selected' : '' }}>1940 @if($locale == 'ja') (@lang('r-mail-form.showa')15) @endif</option>
                      <option value='1941' {{ old('yearSelect',$yearSelect) == '1941' ? 'selected' : '' }}>1941 @if($locale == 'ja') (@lang('r-mail-form.showa')16) @endif</option>
                      <option value='1942' {{ old('yearSelect',$yearSelect) == '1942' ? 'selected' : '' }}>1942 @if($locale == 'ja') (@lang('r-mail-form.showa')17) @endif</option>
                      <option value='1943' {{ old('yearSelect',$yearSelect) == '1943' ? 'selected' : '' }}>1943 @if($locale == 'ja') (@lang('r-mail-form.showa')18) @endif</option>
                      <option value='1944' {{ old('yearSelect',$yearSelect) == '1944' ? 'selected' : '' }}>1944 @if($locale == 'ja') (@lang('r-mail-form.showa')19) @endif</option>
                      <option value='1945' {{ old('yearSelect',$yearSelect) == '1945' ? 'selected' : '' }}>1945 @if($locale == 'ja') (@lang('r-mail-form.showa')20) @endif</option>
                      <option value='1946' {{ old('yearSelect',$yearSelect) == '1946' ? 'selected' : '' }}>1946 @if($locale == 'ja') (@lang('r-mail-form.showa')21) @endif</option>
                      <option value='1947' {{ old('yearSelect',$yearSelect) == '1947' ? 'selected' : '' }}>1947 @if($locale == 'ja') (@lang('r-mail-form.showa')22) @endif</option>
                      <option value='1948' {{ old('yearSelect',$yearSelect) == '1948' ? 'selected' : '' }}>1948 @if($locale == 'ja') (@lang('r-mail-form.showa')23) @endif</option>
                      <option value='1949' {{ old('yearSelect',$yearSelect) == '1949' ? 'selected' : '' }}>1949 @if($locale == 'ja') (@lang('r-mail-form.showa')24) @endif</option>
                      <option value='1950' {{ old('yearSelect',$yearSelect) == '1950' ? 'selected' : '' }}>1950 @if($locale == 'ja') (@lang('r-mail-form.showa')25) @endif</option>
                      <option value='1951' {{ old('yearSelect',$yearSelect) == '1951' ? 'selected' : '' }}>1951 @if($locale == 'ja') (@lang('r-mail-form.showa')26) @endif</option>
                      <option value='1952' {{ old('yearSelect',$yearSelect) == '1952' ? 'selected' : '' }}>1952 @if($locale == 'ja') (@lang('r-mail-form.showa')27) @endif</option>
                      <option value='1953' {{ old('yearSelect',$yearSelect) == '1953' ? 'selected' : '' }}>1953 @if($locale == 'ja') (@lang('r-mail-form.showa')28) @endif</option>
                      <option value='1954' {{ old('yearSelect',$yearSelect) == '1954' ? 'selected' : '' }}>1954 @if($locale == 'ja') (@lang('r-mail-form.showa')29) @endif</option>
                      <option value='1955' {{ old('yearSelect',$yearSelect) == '1955' ? 'selected' : '' }}>1955 @if($locale == 'ja') (@lang('r-mail-form.showa')30) @endif</option>
                      <option value='1956' {{ old('yearSelect',$yearSelect) == '1956' ? 'selected' : '' }}>1956 @if($locale == 'ja') (@lang('r-mail-form.showa')31) @endif</option>
                      <option value='1957' {{ old('yearSelect',$yearSelect) == '1957' ? 'selected' : '' }}>1957 @if($locale == 'ja') (@lang('r-mail-form.showa')32) @endif</option>
                      <option value='1958' {{ old('yearSelect',$yearSelect) == '1958' ? 'selected' : '' }}>1958 @if($locale == 'ja') (@lang('r-mail-form.showa')33) @endif</option>
                      <option value='1959' {{ old('yearSelect',$yearSelect) == '1959' ? 'selected' : '' }}>1959 @if($locale == 'ja') (@lang('r-mail-form.showa')34) @endif</option>
                      <option value='1960' {{ old('yearSelect',$yearSelect) == '1960' ? 'selected' : '' }}>1960 @if($locale == 'ja')(@lang('r-mail-form.showa')35) @endif</option>
                      <option value='1961' {{ old('yearSelect',$yearSelect) == '1961' ? 'selected' : '' }}>1961 @if($locale == 'ja') (@lang('r-mail-form.showa')36) @endif</option>
                      <option value='1962' {{ old('yearSelect',$yearSelect) == '1962' ? 'selected' : '' }}>1962 @if($locale == 'ja') (@lang('r-mail-form.showa')37) @endif</option>
                      <option value='1963' {{ old('yearSelect',$yearSelect) == '1963' ? 'selected' : '' }}>1963 @if($locale == 'ja') (@lang('r-mail-form.showa')38) @endif</option>
                      <option value='1964' {{ old('yearSelect',$yearSelect) == '1964' ? 'selected' : '' }}>1964 @if($locale == 'ja') (@lang('r-mail-form.showa')39) @endif</option>
                      <option value='1965' {{ old('yearSelect',$yearSelect) == '1965' ? 'selected' : '' }}>1965 @if($locale == 'ja')(@lang('r-mail-form.showa')40) @endif</option>
                      <option value='1966' {{ old('yearSelect',$yearSelect) == '1966' ? 'selected' : '' }}>1966 @if($locale == 'ja') (@lang('r-mail-form.showa')41) @endif</option>
                      <option value='1967' {{ old('yearSelect',$yearSelect) == '1967' ? 'selected' : '' }}>1967 @if($locale == 'ja') (@lang('r-mail-form.showa')42) @endif</option>
                      <option value='1968' {{ old('yearSelect',$yearSelect) == '1968' ? 'selected' : '' }}>1968 @if($locale == 'ja') (@lang('r-mail-form.showa')43) @endif</option>
                      <option value='1969' {{ old('yearSelect',$yearSelect) == '1969' ? 'selected' : '' }}>1969 @if($locale == 'ja') (@lang('r-mail-form.showa')44) @endif</option>
                      <option value='1970' {{ old('yearSelect',$yearSelect) == '1970' ? 'selected' : '' }}>1970 @if($locale == 'ja') (@lang('r-mail-form.showa')45) @endif</option>
                      <option value='1971' {{ old('yearSelect',$yearSelect) == '1971' ? 'selected' : '' }}>1971 @if($locale == 'ja') (@lang('r-mail-form.showa')46) @endif</option>
                      <option value='1972' {{ old('yearSelect',$yearSelect) == '1972' ? 'selected' : '' }}>1972 @if($locale == 'ja')(@lang('r-mail-form.showa')47) @endif</option>
                      <option value='1973' {{ old('yearSelect',$yearSelect) == '1973' ? 'selected' : '' }}>1973 @if($locale == 'ja') (@lang('r-mail-form.showa')48) @endif</option>
                      <option value='1974' {{ old('yearSelect',$yearSelect) == '1974' ? 'selected' : '' }}>1974 @if($locale == 'ja') (@lang('r-mail-form.showa')49) @endif</option>
                      <option value='1975' {{ old('yearSelect',$yearSelect) == '1975' ? 'selected' : '' }}>1975 @if($locale == 'ja') (@lang('r-mail-form.showa')50) @endif</option>
                      <option value='1976' {{ old('yearSelect',$yearSelect) == '1976' ? 'selected' : '' }}>1976 @if($locale == 'ja') (@lang('r-mail-form.showa')51) @endif</option>
                      <option value='1977' {{ old('yearSelect',$yearSelect) == '1977' ? 'selected' : '' }}>1977 @if($locale == 'ja') (@lang('r-mail-form.showa')52) @endif</option>
                      <option value='1978' {{ old('yearSelect',$yearSelect) == '1978' ? 'selected' : '' }}>1978 @if($locale == 'ja') (@lang('r-mail-form.showa')53) @endif</option>
                      <option value='1979' {{ old('yearSelect',$yearSelect) == '1979' ? 'selected' : '' }}>1979 @if($locale == 'ja') (@lang('r-mail-form.showa')54) @endif</option>
                      <option value='1980' {{ old('yearSelect',$yearSelect) == '1980' ? 'selected' : '' }}>1980 @if($locale == 'ja')(@lang('r-mail-form.showa')55) @endif</option>
                      <option value='1981' {{ old('yearSelect',$yearSelect) == '1981' ? 'selected' : '' }}>1981 @if($locale == 'ja') (@lang('r-mail-form.showa')56) @endif</option>
                      <option value='1982' {{ old('yearSelect',$yearSelect) == '1982' ? 'selected' : '' }}>1982 @if($locale == 'ja') (@lang('r-mail-form.showa')57) @endif</option>
                      <option value='1983' {{ old('yearSelect',$yearSelect) == '1983' ? 'selected' : '' }}>1983 @if($locale == 'ja') (@lang('r-mail-form.showa')58) @endif</option>
                      <option value='1984' {{ old('yearSelect',$yearSelect) == '1984' ? 'selected' : '' }}>1984 @if($locale == 'ja') (@lang('r-mail-form.showa')59) @endif </option>
                      <option value='1985' {{ old('yearSelect',$yearSelect) == '1985' ? 'selected' : '' }}>1985 @if($locale == 'ja') (@lang('r-mail-form.showa')60) @endif</option>
                      <option value='1986' {{ old('yearSelect',$yearSelect) == '1986' ? 'selected' : '' }}>1986 @if($locale == 'ja') (@lang('r-mail-form.showa')61) @endif</option>
                      <option value='1987' {{ old('yearSelect',$yearSelect) == '1987' ? 'selected' : '' }}>1987 @if($locale == 'ja') (@lang('r-mail-form.showa')62) @endif</option>
                      <option value='1988' {{ old('yearSelect',$yearSelect) == '1988' ? 'selected' : '' }}>1988 @if($locale == 'ja') (@lang('r-mail-form.showa')63) @endif</option>
                      <option value='1989' {{ old('yearSelect',$yearSelect) == '1989' ? 'selected' : '' }}>1989 @if($locale == 'ja') (@lang('r-mail-form.heisei')1) @endif</option>
                      <option value='1990' {{ old('yearSelect',$yearSelect) == '1990' ? 'selected' : '' }}>1990 @if($locale == 'ja') (@lang('r-mail-form.heisei')2) @endif</option>
                      <option value='1991' {{ old('yearSelect',$yearSelect) == '1991' ? 'selected' : '' }}>1991 @if($locale == 'ja')(@lang('r-mail-form.heisei')3) @endif</option>
                      <option value='1992' {{ old('yearSelect',$yearSelect) == '1992' ? 'selected' : '' }}>1992 @if($locale == 'ja') (@lang('r-mail-form.heisei')4) @endif</option>
                      <option value='1993' {{ old('yearSelect',$yearSelect) == '1993' ? 'selected' : '' }}>1993 @if($locale == 'ja') (@lang('r-mail-form.heisei')5) @endif</option>
                      <option value='1994' {{ old('yearSelect',$yearSelect) == '1994' ? 'selected' : '' }}>1994 @if($locale == 'ja') (@lang('r-mail-form.heisei')6) @endif</option>
                      <option value='1995' {{ old('yearSelect',$yearSelect) == '1995' ? 'selected' : '' }}>1995 @if($locale == 'ja') (@lang('r-mail-form.heisei')7) @endif</option>
                      <option value='1996' {{ old('yearSelect',$yearSelect) == '1996' ? 'selected' : '' }}>1996 @if($locale == 'ja') (@lang('r-mail-form.heisei')8) @endif</option>
                      <option value='1997' {{ old('yearSelect',$yearSelect) == '1997' ? 'selected' : '' }}>1997 @if($locale == 'ja') (@lang('r-mail-form.heisei')9) @endif</option>
                      <option value='1998' {{ old('yearSelect',$yearSelect) == '1998' ? 'selected' : '' }}>1998 @if($locale == 'ja') (@lang('r-mail-form.heisei')10) @endif</option>
                      <option value='1999' {{ old('yearSelect',$yearSelect) == '1999' ? 'selected' : '' }}>1999 @if($locale == 'ja') (@lang('r-mail-form.heisei')11) @endif</option>
                      <option value='2000' {{ old('yearSelect',$yearSelect) == '2000' ? 'selected' : '' }}>2000 @if($locale == 'ja') (@lang('r-mail-form.heisei')12) @endif</option>
                      <option value='2001' {{ old('yearSelect',$yearSelect) == '2001' ? 'selected' : '' }}>2001 @if($locale == 'ja') (@lang('r-mail-form.heisei')13) @endif</option>
                      <option value='2002' {{ old('yearSelect',$yearSelect) == '2002' ? 'selected' : '' }}>2002 @if($locale == 'ja') (@lang('r-mail-form.heisei')14) @endif</option>
                      <option value='2003' {{ old('yearSelect',$yearSelect) == '2003' ? 'selected' : '' }}>2003 @if($locale == 'ja') (@lang('r-mail-form.heisei')15) @endif</option>
                      <option value='2004' {{ old('yearSelect',$yearSelect) == '2004' ? 'selected' : '' }}>2004 @if($locale == 'ja') (@lang('r-mail-form.heisei')16) @endif</option>
                      <option value='2005' {{ old('yearSelect',$yearSelect) == '2005' ? 'selected' : '' }}>2005 @if($locale == 'ja') (@lang('r-mail-form.heisei')17) @endif</option>
                      <option value='2006' {{ old('yearSelect',$yearSelect) == '2006' ? 'selected' : '' }}>2006 @if($locale == 'ja') (@lang('r-mail-form.heisei')18) @endif</option>
                      <option value='2007' {{ old('yearSelect',$yearSelect) == '2007' ? 'selected' : '' }}>2007 @if($locale == 'ja') (@lang('r-mail-form.heisei')19) @endif</option>
                      <option value='2008' {{ old('yearSelect',$yearSelect) == '2008' ? 'selected' : '' }}>2008 @if($locale == 'ja') (@lang('r-mail-form.heisei')20) @endif</option>
                      <option value='2009' {{ old('yearSelect',$yearSelect) == '2009' ? 'selected' : '' }}>2009 @if($locale == 'ja') (@lang('r-mail-form.heisei')21) @endif</option>
                      <option value='2010' {{ old('yearSelect',$yearSelect) == '2010' ? 'selected' : '' }}>2010 @if($locale == 'ja') (@lang('r-mail-form.heisei')22) @endif</option>
                      <option value='2011' {{ old('yearSelect',$yearSelect) == '2011' ? 'selected' : '' }}>2011 @if($locale == 'ja') (@lang('r-mail-form.heisei')23) @endif</option>
                      <option value='2012' {{ old('yearSelect',$yearSelect) == '2012' ? 'selected' : '' }}>2012 @if($locale == 'ja') (@lang('r-mail-form.heisei')24) @endif</option>
                      <option value='2013' {{ old('yearSelect',$yearSelect) == '2013' ? 'selected' : '' }}>2013 @if($locale == 'ja') (@lang('r-mail-form.heisei')25) @endif</option>
                      <option value='2014' {{ old('yearSelect',$yearSelect) == '2014' ? 'selected' : '' }}>2014 @if($locale == 'ja') (@lang('r-mail-form.heisei')26) @endif</option>
                      <option value='2015' {{ old('yearSelect',$yearSelect) == '2015' ? 'selected' : '' }}>2015 @if($locale == 'ja') (@lang('r-mail-form.heisei')27) @endif</option>
                      <option value='2016' {{ old('yearSelect',$yearSelect) == '2016' ? 'selected' : '' }}>2016 @if($locale == 'ja') (@lang('r-mail-form.heisei')28) @endif</option>
                      <option value='2017' {{ old('yearSelect',$yearSelect) == '2017' ? 'selected' : '' }}>2017 @if($locale == 'ja') (@lang('r-mail-form.heisei')29) @endif</option>
                      <option value='2018' {{ old('yearSelect',$yearSelect) == '2018' ? 'selected' : '' }}>2018 @if($locale == 'ja') (@lang('r-mail-form.heisei')30) @endif</option>
                      <option value='2019' {{ old('yearSelect',$yearSelect) == '2019' ? 'selected' : '' }}>2019 @if($locale == 'ja') (@lang('r-mail-form.heisei')31) @endif</option>
                    </select>
                  </li>
                  <li class="yearSelect2_li">@lang('r-mail-form.birthday_year')</li>
                  <li class="monthSelect1_li">
                    <select id="monthSelect" name="monthSelect" class="monthSelect_input">
                      <option value='1' {{ old('monthSelect',$monthSelect) == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('monthSelect',$monthSelect) == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('monthSelect',$monthSelect) == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('monthSelect',$monthSelect) == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('monthSelect',$monthSelect) == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('monthSelect',$monthSelect) == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('monthSelect',$monthSelect) == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('monthSelect',$monthSelect) == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('monthSelect',$monthSelect) == '9' ? 'selected' : '' }}>9</option>
                      <option value='10' {{ old('monthSelect',$monthSelect) == '10' ? 'selected' : '' }}>10</option>
                      <option value='11' {{ old('monthSelect',$monthSelect) == '11' ? 'selected' : '' }}>11</option>
                      <option value='12' {{ old('monthSelect',$monthSelect) == '12' ? 'selected' : '' }}>12</option>
                    </select>
                  </li>
                  <li class="yearSelect2_li">@lang('r-mail-form.birthday_month')</li>
                  <li class="monthSelect1_li">
                    <select id="daySelect" name="daySelect" class="monthSelect_input">
                      <option value='1' {{ old('daySelect',$daySelect) == '1' ? 'selected' : '' }}>1</option>
                      <option value='2' {{ old('daySelect',$daySelect) == '2' ? 'selected' : '' }}>2</option>
                      <option value='3' {{ old('daySelect',$daySelect) == '3' ? 'selected' : '' }}>3</option>
                      <option value='4' {{ old('daySelect',$daySelect) == '4' ? 'selected' : '' }}>4</option>
                      <option value='5' {{ old('daySelect',$daySelect) == '5' ? 'selected' : '' }}>5</option>
                      <option value='6' {{ old('daySelect',$daySelect) == '6' ? 'selected' : '' }}>6</option>
                      <option value='7' {{ old('daySelect',$daySelect) == '7' ? 'selected' : '' }}>7</option>
                      <option value='8' {{ old('daySelect',$daySelect) == '8' ? 'selected' : '' }}>8</option>
                      <option value='9' {{ old('daySelect',$daySelect) == '9' ? 'selected' : '' }}>9</option>
                      <option value='10' {{ old('daySelect',$daySelect) == '10' ? 'selected' : '' }}>10</option>
                      <option value='11' {{ old('daySelect',$daySelect) == '11' ? 'selected' : '' }}>11</option>
                      <option value='12' {{ old('daySelect',$daySelect) == '12' ? 'selected' : '' }}>12</option>
                      <option value='13' {{ old('daySelect',$daySelect) == '13' ? 'selected' : '' }}>13</option>
                      <option value='14' {{ old('daySelect',$daySelect) == '14' ? 'selected' : '' }}>14</option>
                      <option value='15' {{ old('daySelect',$daySelect) == '15' ? 'selected' : '' }}>15</option>
                      <option value='16' {{ old('daySelect',$daySelect) == '16' ? 'selected' : '' }}>16</option>
                      <option value='17' {{ old('daySelect',$daySelect) == '17' ? 'selected' : '' }}>17</option>
                      <option value='18' {{ old('daySelect',$daySelect) == '18' ? 'selected' : '' }}>18</option>
                      <option value='19' {{ old('daySelect',$daySelect) == '19' ? 'selected' : '' }}>19</option>
                      <option value='20' {{ old('daySelect',$daySelect) == '20' ? 'selected' : '' }}>20</option>
                      <option value='21' {{ old('daySelect',$daySelect) == '21' ? 'selected' : '' }}>21</option>
                      <option value='22' {{ old('daySelect',$daySelect) == '22' ? 'selected' : '' }}>22</option>
                      <option value='23' {{ old('daySelect',$daySelect) == '23' ? 'selected' : '' }}>23</option>
                      <option value='24' {{ old('daySelect',$daySelect) == '24' ? 'selected' : '' }}>24</option>
                      <option value='25' {{ old('daySelect',$daySelect) == '25' ? 'selected' : '' }}>25</option>
                      <option value='26' {{ old('daySelect',$daySelect) == '26' ? 'selected' : '' }}>26</option>
                      <option value='27' {{ old('daySelect',$daySelect) == '27' ? 'selected' : '' }}>27</option>
                      <option value='28' {{ old('daySelect',$daySelect) == '28' ? 'selected' : '' }}>28</option>
                      <option value='29' {{ old('daySelect',$daySelect) == '29' ? 'selected' : '' }}>29</option>
                      <option value='30' {{ old('daySelect',$daySelect) == '30' ? 'selected' : '' }}>30</option>
                      <option value='31' {{ old('daySelect',$daySelect) == '31' ? 'selected' : '' }}>31</option>
                    </select>
                  </li>
                  <li class="yearSelect2_li">@lang('r-mail-form.birthday_day')</li>
                </ul>
              </div>
            </label>
          </li>

          <li>
<?php //IIE11でlabel内でclickイベントは伝播しない対応 ?>
            <div class="insted_label">
              <p class="input_name must">@lang('r-mail-form.gender_field')</p>
              <div class="gender_test">
                <input name="gender" type="radio" value="male" {{ old('gender',$gender) == "male" ? "checked" : ""}} class="gender_input" /><span id="g_male">@lang('r-mail-form.gender_male')</span>　
                <input name="gender" type="radio" value="female" {{ old('gender',$gender) == "female" ? "checked" : ""}} class="gender_input" /><span id="g_female">@lang('r-mail-form.gender_female')</span>
              </div>
              @if($errors->has('gender'))
              <p class="error_p">{{ $errors->first('gender') }}</p>
              @endif
            </div>
          </li>
          <li>
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.occupation_field')</p>
              <select name="kind">
                <option value='1' {{ old('kind',$kind) == '1' ? 'selected' : '' }}>@lang('r-mail-form.occupation_1')</option>
                <option value='2' {{ old('kind',$kind) == '2' ? 'selected' : '' }}>@lang('r-mail-form.occupation_2')</option>
                <option value='3' {{ old('kind',$kind) == '3' ? 'selected' : '' }}>@lang('r-mail-form.occupation_3')</option>
                <option value='4' {{ old('kind',$kind) == '4' ? 'selected' : '' }}>@lang('r-mail-form.occupation_4')</option>
                <option value='5' {{ old('kind',$kind) == '5' ? 'selected' : '' }}>@lang('r-mail-form.occupation_5')</option>
                <option value='6' {{ old('kind',$kind) == '6' ? 'selected' : '' }}>@lang('r-mail-form.occupation_6')</option>
                <option value='7' {{ old('kind',$kind) == '7' ? 'selected' : '' }}>@lang('r-mail-form.occupation_7')</option>
                <option value='8' {{ old('kind',$kind) == '8' ? 'selected' : '' }}>@lang('r-mail-form.occupation_8')</option>
                <option value='9' {{ old('kind',$kind) == '9' ? 'selected' : '' }}>@lang('r-mail-form.occupation_9')</option>
                <option value='10' {{ old('kind',$kind) == '10' ? 'selected' : '' }}>@lang('r-mail-form.occupation_10')</option>
                <option value='11' {{ old('kind',$kind) == '11' ? 'selected' : '' }}>@lang('r-mail-form.occupation_11')</option>
                <option value='12' {{ old('kind',$kind) == '12' ? 'selected' : '' }}>@lang('r-mail-form.occupation_12')</option>
                <option value='13' {{ old('kind',$kind) == '13' ? 'selected' : '' }}>@lang('r-mail-form.occupation_13')</option>
                <option value='14' {{ old('kind',$kind) == '14' ? 'selected' : '' }}>@lang('r-mail-form.occupation_14')</option>
                <option value='15' {{ old('kind',$kind) == '15' ? 'selected' : '' }}>@lang('r-mail-form.occupation_15')</option>
                <option value='16' {{ old('kind',$kind) == '16' ? 'selected' : '' }}>@lang('r-mail-form.occupation_16')</option>
                <option value='17' {{ old('kind',$kind) == '17' ? 'selected' : '' }}>@lang('r-mail-form.occupation_17')</option>
                <option value='18' {{ old('kind',$kind) == '18' ? 'selected' : '' }}>@lang('r-mail-form.occupation_18')</option>
<?php
/*
                <option value='19' {{ old('kind',$kind) == '19' ? 'selected' : '' }}>医学研究者</option>
                <option value='20' {{ old('kind',$kind) == '20' ? 'selected' : '' }}>医療事務</option>
*/
?>
                <option value='21' {{ old('kind',$kind) == '21' ? 'selected' : '' }}>@lang('r-mail-form.occupation_21')</option>
                <option value='22' {{ old('kind',$kind) == '22' ? 'selected' : '' }}>@lang('r-mail-form.occupation_22')</option>
                <option value='23' {{ old('kind',$kind) == '23' ? 'selected' : '' }}>@lang('r-mail-form.occupation_23')</option>
                <option value='24' {{ old('kind',$kind) == '24' ? 'selected' : '' }}>@lang('r-mail-form.occupation_24')</option>
                <option value='25' {{ old('kind',$kind) == '25' ? 'selected' : '' }}>@lang('r-mail-form.occupation_25')</option>
<?php
/*
                <option value='26' {{ old('kind',$kind) == '26' ? 'selected' : '' }}>その他医療関係者</option>
*/
?>
              </select>
              <div class="li_caremane_1" style="display:none;">
                <input class="caremane_input" type="checkbox" name="caremane[]" value='1' {{ is_array(old('caremane',$caremane)) && in_array('1',old('caremane',$caremane)) ? 'checked' : '' }}>@lang('r-mail-form.occupation_note')
              </div>
            </label>
          </li>

          <li class="li_group_1" style="display:none;">
            <label for="" class="">
              <p id="group_name" class="input_name must"
                data-employment_name="@lang('r-mail-form.employment_name')"
                data-employer_field="@lang('r-mail-form.employer_field')"
                data-schoolname="@lang('r-mail-form.school_name')" 
                data-trans="@lang('r-mail-form.employer_field')">
                  @lang('r-mail-form.employer_field')
              </p>
              <input name="group" type="text" maxlength="25" value="{{ old('group',$group) }}" placeholder="@lang('r-mail-form.employer_field_placeholder')"/>
              @if($errors->has('group'))
              <p class="error_p">{{ $errors->first('group') }}</p>
              @endif
            </label>
          </li>

          <li class="li_school_1" style="display:none;">
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.graduation_year')</p>
                    <select id="graduationYear" name="graduationYear" class="graduationYear">
                      <option value='2019' {{ old('graduationYear',$graduationYear) == '2019' ? 'selected' : '' }}>2019 @if($locale == 'ja')(平成31) @endif</option>
                      <option value='2020' {{ old('graduationYear',$graduationYear) == '2020' ? 'selected' : '' }}>2020 @if($locale == 'ja')(令和2) @endif</option>
                      <option value='2021' {{ old('graduationYear',$graduationYear) == '2021' ? 'selected' : '' }}>2021 @if($locale == 'ja')(令和3) @endif</option>
                      <option value='2022' {{ old('graduationYear',$graduationYear) == '2022' ? 'selected' : '' }}>2022 @if($locale == 'ja')(令和4) @endif</option>
                      <option value='2023' {{ old('graduationYear',$graduationYear) == '2023' ? 'selected' : '' }}>2023 @if($locale == 'ja')(令和5) @endif</option>
                      <option value='2024' {{ old('graduationYear',$graduationYear) == '2024' ? 'selected' : '' }}>2024 @if($locale == 'ja')(令和6) @endif</option>
                      <option value='2025' {{ old('graduationYear',$graduationYear) == '2025' ? 'selected' : '' }}>2025 @if($locale == 'ja')(令和7) @endif</option>
                      <option value='2026' {{ old('graduationYear',$graduationYear) == '2026' ? 'selected' : '' }}>2026 @if($locale == 'ja')(令和8) @endif</option>
                    </select>
            </label>
          </li>

<?php
/*
    下記データは診療科目1～5で使用

    oldの使用に不安があるので、下記、手書きの場合を参考に残す

    <option value='19' {{ old('services5',$services5) == '19' ? 'selected' : '' }}>一般外科</option>

    この書き方で全ての科目を記述すればoldが機能する
    その場合、コントローラ[RMailFormController.php]でのoldの処理は外してOK
*/
// 外科
$service_1 = trans('r-mail-form.service_1');
$service_2 = trans('r-mail-form.service_2');
$service_3 = trans('r-mail-form.service_3');
$service_4 = trans('r-mail-form.service_4');
$service_5 = trans('r-mail-form.service_5');
$service_6 = trans('r-mail-form.service_6');
$service_7 = trans('r-mail-form.service_7');
$service_8 = trans('r-mail-form.service_8');
$service_9 = trans('r-mail-form.service_9');
$service_10 = trans('r-mail-form.service_10');
$service_11 = trans('r-mail-form.service_11');
$service_12 = trans('r-mail-form.service_12');
$service_13 = trans('r-mail-form.service_13');
$service_14 = trans('r-mail-form.service_14');
$service_15 = trans('r-mail-form.service_15');
$service_16 = trans('r-mail-form.service_16');
$service_17 = trans('r-mail-form.service_17');
$service_18 = trans('r-mail-form.service_18');
$service_19 = trans('r-mail-form.service_19');
$service_20 = trans('r-mail-form.service_20');
$service_21 = trans('r-mail-form.service_21');
$service_21 = trans('r-mail-form.service_21');
$service_22 = trans('r-mail-form.service_22');
$service_22 = trans('r-mail-form.service_22');
$service_23 = trans('r-mail-form.service_23');
$service_24 = trans('r-mail-form.service_24');
$service_25 = trans('r-mail-form.service_25');
$service_26 = trans('r-mail-form.service_26');
$service_27 = trans('r-mail-form.service_27');
$service_28 = trans('r-mail-form.service_28');
$service_29 = trans('r-mail-form.service_29');
$service_30 = trans('r-mail-form.service_30');
$service_31 = trans('r-mail-form.service_31');
$service_32 = trans('r-mail-form.service_32');
$service_33 = trans('r-mail-form.service_33');
$service_34 = trans('r-mail-form.service_34');
$service_35 = trans('r-mail-form.service_35');
$service_36 = trans('r-mail-form.service_36');
$service_37 = trans('r-mail-form.service_37');
$service_38 = trans('r-mail-form.service_38');
$service_39 = trans('r-mail-form.service_39');
$service_40 = trans('r-mail-form.service_40');
$service_41 = trans('r-mail-form.service_41');
$service_42 = trans('r-mail-form.service_42');
$service_43 = trans('r-mail-form.service_43');
$service_44 = trans('r-mail-form.service_44');
$service_45 = trans('r-mail-form.service_45');
$service_46 = trans('r-mail-form.service_46');
$service_47 = trans('r-mail-form.service_47');
$service_48 = trans('r-mail-form.service_48');
$service_49 = trans('r-mail-form.service_49');
$service_50 = trans('r-mail-form.service_50');
$service_51 = trans('r-mail-form.service_51');
$service_52 = trans('r-mail-form.service_52');
$service_53 = trans('r-mail-form.service_53');
$service_54 = trans('r-mail-form.service_54');

$services_gs = [19=> $service_19,20=>$service_20,22=>$service_22,23=>$service_23,24=>$service_24,25=>$service_25,26=>$service_26,27=>$service_27];
// 内科

$services_im = [1=>$service_1,2=>$service_2,4=>$service_4,49=>$service_49,5=>$service_5,7=>$service_7,8=>$service_8,10=>$service_10,11=>$service_11,12=>$service_12,47=>$service_47,13=>$service_13,14=>$service_14,16=>$service_16,44=>$service_44,18=>$service_18];
// その他
$services_etc = [3=>$service_3,40=>$service_40,38=>$service_38,42=>$service_42,35=>$service_35,6=>$service_6,21=>$service_21,50=>$service_50,32=>$service_32,9=>$service_9,43=>$service_43,33=>$service_33,34=>$service_34,39=>$service_39,29=>$service_29,48=>$service_48,51=>$service_51,15=>$service_15,52=>$service_52,53=>$service_53,28=>$service_28,31=>$service_31,37=>$service_37,54=>$service_54,36=>$service_36,45=>$service_45,46=>$service_46,41=>$service_41,17=>$service_17,30=>$service_30];
?>
          <li class="li_rowitem_1" style="display:none;">
            <label for="" class="">
              <p class="input_name must">@lang('r-mail-form.medical_departments_field')</p>
              <div class="rowitem">
                <ul class="services_ul">
                  <li class="services_li1">@lang('r-mail-form.have_to_field')</li>
                  <li class="services_li2">
                    <select name="services1" class="services_select" >
                      <option value=''>@lang('r-mail-form.choice_label')</option>
                      <optgroup label="@lang('r-mail-form.department_label_1')">
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services1 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
                      <optgroup label="@lang('r-mail-form.department_label_2')">
<?php
foreach($services_im as $s_no => $s_name) {
  $selected = $services1 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
<?php
foreach($services_etc as $s_no => $s_name) {
  $selected = $services1 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                    </select>
                  </li>
                </ul>
                <?php // 必須が入力されていない場合 ?>
                @if(Session::has('noservices1'))
                <div class="error_p">{{ session('noservices1') }}</div>
                @endif

                <ul class="services_ul">
                  <li class="services_li1">@lang('r-mail-form.arbitrary_field')</li>
                  <li class="services_li2">
                    <select name="services2" class="services_select">
                      <option value=''>@lang('r-mail-form.choice_label')</option>
                      <optgroup label="@lang('r-mail-form.department_label_1')">
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services2 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
                      <optgroup label="@lang('r-mail-form.department_label_2')">
<?php
foreach($services_im as $s_no => $s_name) {
  $selected = $services2 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
<?php
foreach($services_etc as $s_no => $s_name) {
  $selected = $services2 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                    </select>
                  </li>
                </ul>

                <ul class="services_ul">
                  <li class="services_li1">@lang('r-mail-form.arbitrary_field')</li>
                  <li class="services_li2">
                    <select name="services3" class="services_select">
                      <option value=''>@lang('r-mail-form.choice_label')</option>
                      <optgroup label="@lang('r-mail-form.department_label_1')">
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services3 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
                      <optgroup label="@lang('r-mail-form.department_label_2')">
<?php
foreach($services_im as $s_no => $s_name) {
  $selected = $services3 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
<?php
foreach($services_etc as $s_no => $s_name) {
  $selected = $services3 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                    </select>
                  </li>
                </ul>

                <ul class="services_ul">
                  <li class="services_li1">@lang('r-mail-form.arbitrary_field')</li>
                  <li class="services_li2">
                    <select name="services4" class="services_select">
                      <option value=''>@lang('r-mail-form.choice_label')</option>
                      <optgroup label="@lang('r-mail-form.department_label_1')">
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services4 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
                      <optgroup label="@lang('r-mail-form.department_label_2')">
<?php
foreach($services_im as $s_no => $s_name) {
  $selected = $services4 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
<?php
foreach($services_etc as $s_no => $s_name) {
  $selected = $services4 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                    </select>
                  </li>
                </ul>

                <ul class="services_ul">
                  <li class="services_li1">@lang('r-mail-form.arbitrary_field')</li>
                  <li class="services_li2">
                    <select name="services5" class="services_select">
                      <option value=''>@lang('r-mail-form.choice_label')</option>
                      <optgroup label="@lang('r-mail-form.department_label_1')">
<?php
foreach($services_gs as $s_no => $s_name) {
  $selected = $services5 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
                      <optgroup label="@lang('r-mail-form.department_label_2')">
<?php
foreach($services_im as $s_no => $s_name) {
  $selected = $services5 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                      </optgroup>
<?php
foreach($services_etc as $s_no => $s_name) {
  $selected = $services5 == $s_no  ? 'selected' : '';
  echo '<option value="'.$s_no.'" '.$selected.'>'.$s_name.'</option>';
}
?>
                    </select>
                  </li>
                </ul>
              </div>
            </label>
          </li>

          <?php // ご本人確認 ?>
          <li class="li_id_1" style="display:none;">
<?php //IIE11でlabel内でclickイベントは伝播しない対応 ?>
            <div class="insted_label">
              <p class="input_name must">@lang('r-mail-form.identity_verification')</p>
              <div class="idtext">@lang('r-mail-form.identity_confirmation')</div>
              <div class="identification">
                <div>
                  <input name="identification" type="radio" value="none" {{ old('identification',$identification) == "none" ? "checked" : ""}} style="" /><span id="i_none" >@lang('r-mail-form.verification_choice_1')</span>
                </div>
                <div>
                  <input name="identification" type="radio" value="image" {{ old('identification',$identification) == "image" ? "checked" : ""}} style="" /><span id="i_image" >@lang('r-mail-form.verification_choice_2')</span>
                </div>
                <div class="li_id_2">
                  <input name="identification" type="radio" value="tel" {{ old('identification',$identification) == "tel" ? "checked" : ""}} style="" /><span id="i_tel" >@lang('r-mail-form.verification_choice_3')</span>
                </div>
              </div>

              <div class="idpart valuenone" style="display:none;">
                <div class="idparttext" style="margin-bottom:14px;">@lang('r-mail-form.identity_message_1')</div>
              </div>

              <div class="idpart valueimage" style="display:none;">
                <div class="idparttext">@lang('r-mail-form.attach_license')</div>
<?php
if($editimage == 1) { // 編集の場合で画像がDBにある場合
?>
<div class="idparttext">※前回の画像をそのまま使用する場合は、選択の必要はありません。</div>
<?php
}
?>
                <input type="file" id="show-file" name="image_files[]" accept=".jpg,.png,.gif,image/jpeg,image/png,image/gif" value="" />
                <div>
                  <img id="file-preview" style="width:0px;" />
                  <?php // 画像が入力されていない場合 ?>
                  @if(Session::has('noimage'))
                  <div id="noimage" class="error_p">{{ session('noimage') }}</div>
                  @endif
                </div>
              </div>

              <div class="idpart valuetel" style="display:none;">
                <div class="idparttext">@lang('r-mail-form.secretary_call')</div>
                <input name="tel" type="text" maxlength="20" value="{{ old('tel',$tel) }}" placeholder="@lang('r-mail-form.tel_placeholder')"/>
                @if($errors->has('tel'))
                <p class="error_p">{{ $errors->first('tel') }}</p>
                @endif
                <?php // 電話番号が入力されていない場合 ?>
                @if(Session::has('notel'))
                <div class="error_p">{{ session('notel') }}</div>
                @endif
              </div>
          </li>
        </ul>
        <div class="contents_box_inner pTB20">
          <p class="mTB20" style="text-align: center;margin-top: 40px;">@lang('r-mail-form.message_3')</p>
          <p class="contact_submit mB20"><a href="{{ route('home') }}" class="submit_backbtn">@lang('r-mail-form.return_button')</a><input class="submit_btn"  style="cursor:pointer;" type="submit" value="@lang('r-mail-form.send_button')"></input></p>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{asset('js/r-mail-form.min.js?v=1.2.0.20190415')}}"></script>
<script type="text/javascript" src="/js/jquery.calendar.js"></script>
<script type="text/javascript">
(function($){
    $("#yearSelect").calendar({});
    $("#monthSelect").calendar({});
})(jQuery);
</script>
@endsection
