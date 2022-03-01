@extends('layouts.app')

@section('title', 'list')

@section('breadcrumb')
{!! Breadcrumbs::render('list') !!}
@endsection

@section('content')

<!-- メイン聴くゾウライブラリ（左） -->
  <div class="bn" style="margin-top:40px;">
    <div class="list_abstract_outline">
        <div class="list_abstract">
            @lang('list.abstract')
        </div>
    </div>
    <div class="list_title_outline">
      <div class="list_title">@lang('list.title')　<?php echo $params['total_all_lib']; ?> @lang('list.back')</div>
    </div>

    <?php $is_normal_strings = [ 1 => '正常音', 0 => '異常音']; ?>
    <?php $is_normal_strings_en = [ 1 => 'Normal sound', 0 => 'Abnormal sound']; ?>

    <div class="container_list_page">
      <div class="one_list">
        <p class="one_tag"><span>@lang('list.heart')</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($list['cardiac'] as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $params['cardiac']; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag"><span>@lang('list.lung')</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($list['pulmonary'] as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td id="{{$stetho_sound->id}}" width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $params['pulmonary']; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag"><span>@lang('list.bowel')</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($list['bowel'] as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="40%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $params['bowel']; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag"><span>@lang('list.other')</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($list['etc'] as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $params['etc']; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag" style="background-image: url('../img/tag-aus.png');"><span>iPax</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($ausc as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="40%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $total_ausc; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag" style="background-image: url('../img/tag-palp.png');"><span>Palp</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($palpation as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $total_palpation; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag" style="background-image: url('../img/tag-ecg.png');"><span>ECG</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($ecg as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $total_ecg; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag" style="background-image: url('../img/tag-ins.png');"><span>Insp</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($inspection as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $total_inspection; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag" style="background-image: url('../img/tag-xray.png');"><span>X-ray</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($xray as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $total_xray; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="one_list">
        <p class="one_tag" style="background-image: url('../img/tag-echo.png');"><span>UCG</span></p>
        <div class="row list-table">
          <table>
            <tr>
              <td>Number</td>
              <td>Title</td>
              <td>File format</td>
            </tr>
            @foreach($ucg as $stetho_sound)
            <?php
              $libType = $stetho_sound->lib_type;
              if ($libType == 1) {
                  $s = json_decode($stetho_sound->sound_path);

                  $a_sound_path = !empty($s->a_sound_path)? "A: ".pathinfo(public_path($s->a_sound_path))['extension'].", " : "";
                  $p_sound_path = !empty($s->p_sound_path)? "P: ".pathinfo(public_path($s->p_sound_path))['extension'].", " : "";
                  $t_sound_path = !empty($s->t_sound_path)? "T: ".pathinfo(public_path($s->t_sound_path))['extension'].", " : "";
                  $m_sound_path = !empty($s->m_sound_path)? "M: ".pathinfo(public_path($s->m_sound_path))['extension'] : "";

                  $extension = $a_sound_path.$p_sound_path.$t_sound_path.$m_sound_path;
              } else {
                  $infoPath = pathinfo(public_path($stetho_sound->sound_path));
                  $extension = !empty($infoPath['extension']) ? $infoPath['extension'] : "File Not Found";
              }
            ?>
            <tr>
              <td width="40%">@if(config('app.locale') == 'en') {{ $stetho_sound->title_en }} @else {{ $stetho_sound->title }}@endif</td>
              <td width="40%">@if(config('app.locale') == 'en') {{$stetho_sound->sub_description_en}} @else {{$stetho_sound->sub_description}} @endif</td>
              <td width="20%" class="file_format">{{$extension}}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3">@lang('list.meter')：<?php echo $total_ucg; ?>@lang('list.pcs')</td>
            </tr>
          </table>
        </div>
      </div>


    </div>
  </div>



  <!-- /#container -->

<script type="text/javascript">
$(document).ready(function(){
  $(".one_tag").each( function() {  // タグ
    var part = $(this).text();  // 心音　肺音　腸音　その他

    if(part.indexOf('<?php echo trans('list.lung')?>') !== -1) {
      imgurl = "url(../img/tag-pulm.png)"; // タグのイメージ
      $(this).css('background-image',imgurl);  // イメージ変更
    } else if(part.indexOf('<?php echo trans('list.bowel')?>') !== -1) {
      imgurl = "url(../img/tag-bowel.png)"; // タグのイメージ
      $(this).css('background-image',imgurl);  // イメージ変更
    } else if(part.indexOf('<?php echo trans('list.other')?>') !== -1) {
      imgurl = "url(../img/tag-etc.png)"; // タグのイメージ
      $(this).css('background-image',imgurl);  // イメージ変更
    }
  });
});
</script>

@endsection
