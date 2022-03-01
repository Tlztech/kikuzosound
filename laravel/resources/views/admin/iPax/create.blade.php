@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <h1>iPaxライブラリ追加</h1>
</div>

<form class="form-horizontal" action="{{ route('admin.iPax.store') }}" method="POST"
    enctype="multipart/form-data">
    {{ csrf_field() }}
    <h4>基本情報</h4>
    <div class="well">
        @include('admin.common.form_errors')
        @if(Session::has('error_desc'))
        <div class="alert alert-danger">
            <ul>
                <li> {!! session('error_desc') !!}</li>
            </ul>
        </div>
        @endif
        <!-- 聴診音ファイル -->
        <input type="hidden" name="lib_type" value="1" />
        <!-- タイトル -->
        <div class="form-group @if($errors->has('title')) has-error @endif">
            <label for="title-field" class="col-sm-2 control-label">タイトル (JP)</label>
            <div class="col-sm-10">
                <input type="text" id="title-field" name="title" class="form-control" value="{{ old("title") }}" />
                @if($errors->has("title"))
                <span class="help-block">{{ $errors->first("title") }}</span>
                @endif
            </div>
        </div>
        <!-- タイトル (EN) -->
        <div class="form-group @if($errors->has('title_en')) has-error @endif">
            <label for="title-field" class="col-sm-2 control-label">タイトル (EN)</label>
            <div class="col-sm-10">
                <input type="text" id="title-field" name="title_en" class="form-control"
                    value="{{ old("title_en") }}" />
                @if($errors->has("title_en"))
                <span class="help-block">{{ $errors->first("title_en") }}</span>
                @endif
            </div>
        </div>
        <!-- 心電図説明 (JP) -->
        <div class="form-group @if($errors->has('description')) has-error @endif">
            <label for="description-field" class="col-sm-2 control-label">説明 (JP)</label>
            <div class="col-sm-10">
                <textarea id="description-field" name="description"
                    class="form-control">{{ old("description") }}</textarea>
                @if($errors->has("description"))
                <span class="help-block">{{ $errors->first("description") }}</span>
                @endif
            </div>
        </div>
        <!-- 心電図説明（EN）-->
        <div class="form-group @if($errors->has('description_en')) has-error @endif">
            <label for="description-field" class="col-sm-2 control-label">説明 (EN)</label>
            <div class="col-sm-10">
                <textarea id="description-en-field" name="description_en"
                    class="form-control">{{ old("description_en") }}</textarea>
                @if($errors->has("description_en"))
                <span class="help-block">{{ $errors->first("description_en") }}</span>
                @endif
            </div>
        </div>
        <!-- 正常/異常 -->
        <div class="form-group @if($errors->has('is_normal')) has-error @endif">
            <label for="is_normal-field" class="col-sm-2 control-label">正常/異常</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" id="is_normal-field" name="is_normal" value='1'
                            {{ old("is_normal") ? "" : "checked" }} />正常

                    </label>
                    <label>
                        <input type="radio" id="is_normal-field" name="is_normal" value='0'
                            {{ old("is_normal") ? "checked" : "" }} />異常
                    </label>
                </div>
                @if($errors->has("is_normal"))
                <span class="help-block">{{ $errors->first("is_normal") }}</span>
                @endif
            </div>
        </div>
        <!-- 説明画像 (JP)-->
        <div class="form-group @if($errors->has('explanatory_image_file')) has-error @endif">
            <label for="lib-image-file-field" class="col-sm-2 control-label">説明画像 (JP)</label>
            <div class="col-sm-10 explanatory_file_wrapper">

                <div id="stetho_sound_images_form" class="form-group @if($errors->has('image_files')) has-error @endif">
                    <div class="col-sm-10">
                        <table class="table table-striped">
                        <tbody id="sortable_tbody_ja">
                        <?php
                            $images = [];
                            if (count($errors)) {
                                $images = \Session::has('stetho_sound_images') ? \Session::get('stetho_sound_images') : [];
                            } else {
                                \Session::forget('stetho_sound_images');
                            }
                        ?>
                            @foreach($images as $image)
                            <tr>
                            <td>
                                <input type="hidden" name="image_ids[]" value="" />
                                <input type="hidden" name="image_paths[]" value="{{ $image['image_path'] }}" />
                                <input type="file" id="image_files-fields" name="image_files[]" accept=".jpg,.png,image/jpeg,image/png,video/mp4" value="" />
                                <img src="{{ $image['image_path'] }}" style="height: 220px;" />
                            </td>
                            <td>
                                <input type="text" name="image_titles[]" class="form-control" value="{{ $image['title'] or "" }}" />
                            </td>
                            <td>
                                <div class="pull-right">
                                <a class="btn btn-sm btn-danger" id="remove_images__btn">削除</a>
                                <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                                </div>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <div class="pull-left">
                        <a id="add_images__btn" class="btn btn-sm btn-primary" data-field="explanatory" data-lang="ja">画像を追加</a>
                        </div>
                        @if($errors->has("image_files"))
                        <span class="help-block">{{ $errors->first("image_files") }}</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <!-- 説明画像 (EN) -->
        <div class="form-group @if($errors->has('explanatory_image_file_en')) has-error @endif">
            <label for="lib-image-file-field" class="col-sm-2 control-label">説明画像 (EN)</label>
            <div class="col-sm-10 explanatory_file_wrapper">

                <div id="stetho_sound_images_form" class="form-group @if($errors->has('image_files')) has-error @endif">
                    <div class="col-sm-10">
                        <table class="table table-striped">
                        <tbody id="sortable_tbody_en">
                        <?php
                            $images = [];
                            if (count($errors)) {
                                $images = \Session::has('stetho_sound_images') ? \Session::get('stetho_sound_images') : [];
                            } else {
                                \Session::forget('stetho_sound_images');
                            }
                        ?>
                            @foreach($images as $image)
                            <tr>
                            <td>
                                <input type="hidden" name="image_ids[]" value="" />
                                <input type="hidden" name="image_paths[]" value="{{ $image['image_path'] }}" />
                                <input type="file" id="image_files-fields" name="image_files[]" accept=".jpg,.png,image/jpeg,image/png,video/mp4" value="" />
                                <img src="{{ $image['image_path'] }}" style="height: 220px;" />
                            </td>
                            <td>
                                <input type="hidden" name="image_titles[]" class="form-control" value="{{ $image['title'] or "" }}" />
                                <input type="hidden" name="lang[]" class="form-control" value="{{ $image['lang'] or "" }}" />
                            </td>
                            <td>
                                <div class="pull-right">
                                <a class="btn btn-sm btn-danger" id="remove_images__btn">削除</a>
                                <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                                </div>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <div class="pull-left">
                        <a id="add_images__btn" class="btn btn-sm btn-primary" data-field="explanatory" data-lang="en">画像を追加</a>
                        </div>
                        @if($errors->has("image_files"))
                        <span class="help-block">{{ $errors->first("image_files") }}</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <!-- 身体画像 -->
        <div class="form-group @if($errors->has('body_image_file')) has-error @endif">
            <label for="lib-image-file-field" class="col-sm-2 control-label">身体画像(前面)</label>
            <div class="col-sm-10">
                <input type="hidden" name="body_image_file" />
                <input type="file" id="body_image-file-input" name="body_image_file" class="form-control well well-sm"
                    accept=".jpg,.png,image/jpeg,image/png" value="{{ old("body_image_file") }}" />
                <?php
                  $body_image_file_path = null;
                  if ( count($errors) ) {
                    $body_image_file_path = \Session::has('body_image_file_path') ? \Session::get('body_image_file_path') : null;
                  }
                  else {
                    Session::forget('body_image_file_path');
                  }
                ?>
                <img id="body_image_file-image-img"
                    src="@if(!is_null($body_image_file_path)){{ $body_image_file_path }}@endif"
                    style="@if(!is_null($body_image_file_path))height:220px;@endif @if(is_null($body_image_file_path))display:none;@endif" />
                <input type="hidden" name="body_image_file_path"
                    value="@if(!is_null($body_image_file_path)){{ $body_image_file_path }}@endif" />
                @if($errors->has("bg_img"))
                <span class="help-block">{{ $errors->first("body_image_file") }}</span>
                @endif
                @if($errors->has("icon_path"))
                <span class="help-block">{{ $errors->first("body_image_file_path") }}</span>
                @endif
            </div>

        </div>

        <div class="form-group @if($errors->has('body_image_back_file')) has-error @endif">
            <label for="lib-image-file-field" class="col-sm-2 control-label">身体画像(背面)</label>
            <div class="col-sm-10">
                <input type="hidden" name="body_image_back_file" />
                <input type="file" id="body_image_back-file-input" name="body_image_back_file" class="form-control well well-sm"
                    accept=".jpg,.png,image/jpeg,image/png" value="{{ old("body_image_back_file") }}" />
                <?php
                  $body_image_back_file_path = null;
                  if ( count($errors) ) {
                    $body_image_back_file_path = \Session::has('body_image_back_file_path') ? \Session::get('body_image_back_file_path') : null;
                  }
                  else {
                    Session::forget('body_image_back_file_path');
                  }
                ?>
                <img id="body_image_back_file-image-img"
                    src="@if(!is_null($body_image_back_file_path)){{ $body_image_back_file_path }}@endif"
                    style="@if(!is_null($body_image_back_file_path))height:220px;@endif @if(is_null($body_image_back_file_path))display:none;@endif" />
                <input type="hidden" name="body_image_back_file_path"
                    value="@if(!is_null($body_image_back_file_path)){{ $body_image_back_file_path }}@endif" />
                @if($errors->has("bg_img"))
                <span class="help-block">{{ $errors->first("body_image_back_file") }}</span>
                @endif
                @if($errors->has("icon_path"))
                <span class="help-block">{{ $errors->first("body_image_back_file_path") }}</span>
                @endif
            </div>

        </div>

        <!-- 心電図説明 (JP) -->
        <div class="form-group @if($errors->has('img_description')) has-error @endif">
            <label for="img_description-field" class="col-sm-2 control-label">画像説明 (JP)</label>
            <div class="col-sm-10">
                <textarea id="img_description-field" name="img_description"
                    class="form-control">{{ old("img_description") }}</textarea>
                @if($errors->has("img_description"))
                <span class="help-block">{{ $errors->first("img_description") }}</span>
                @endif
            </div>
        </div>
        <!-- 心電図説明（EN）-->
        <div class="form-group @if($errors->has('img_description_en')) has-error @endif">
            <label for="description-field" class="col-sm-2 control-label">画像説明 (EN)</label>
            <div class="col-sm-10">
                <textarea id="img_description-en-field" name="img_description_en"
                    class="form-control">{{ old("img_description_en") }}</textarea>
                @if($errors->has("img_description_en"))
                <span class="help-block">{{ $errors->first("img_description_en") }}</span>
                @endif
            </div>
        </div>
        <!-- 設定-->
        <div class="form-group @if($errors->has('configuration')) has-error @endif">
            <label for="configuration-field" class="col-sm-2 control-label">設定</label>
            <div class="col-sm-10">
                {{-- <textarea id="configuration-field" name="configuration"
                    class="form-control">{{ old("configuration") }}</textarea>
                @if($errors->has("configuration"))
                <span class="help-block">{{ $errors->first("configuration") }}</span>
                @endif --}}

                <label for="">A </label>
                <input type="number" name="a_x" step=any value="{{ old("a_x") }}"> (px)
                <input type="number" name="a_y" step=any value="{{ old("a_y") }}"> (px)
                <input type="number" name="a_r" step=any value="{{ old("a_r") }}"> (r)
                <!-- a sound -->
                <br>
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="a_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="a_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("a_sound_file") }}" />
                        <?php
                        $a_sound_path = null;
                        if ( count($errors) ) {
                            $a_sound_path = \Session::has('a_sound_path') ? \Session::get('a_sound_path') : null;
                        }
                        else {
                            Session::forget('a_sound_path');
                        }
                        ?>
                        <audio src="{{ $a_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($a_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="a_sound_path" value="@if(!is_null($a_sound_path)){{ $a_sound_path }}@endif" />
                        @if($errors->has("a_sound_file"))
                            <span class="help-block">{{ $errors->first("a_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <!-- pulse a sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">脈波</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="pa_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="pa_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("pa_sound_file") }}" />
                        <?php
                        $pa_sound_path = null;
                        if ( count($errors) ) {
                            $pa_sound_path = \Session::has('pa_sound_path') ? \Session::get('pa_sound_path') : null;
                        }
                        else {
                            Session::forget('pa_sound_path');
                        }
                        ?>
                        <audio src="{{ $pa_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($pa_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="pa_sound_path" value="@if(!is_null($pa_sound_path)){{ $pa_sound_path }}@endif" />
                        @if($errors->has("pa_sound_file"))
                            <span class="help-block">{{ $errors->first("pa_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <br>
                <label for="">P</label>
                <input type="number" name="p_x" step=any value="{{ old("p_x") }}"> (px)
                <input type="number" name="p_y" step=any value="{{ old("p_y") }}"> (px)
                <input type="number" name="p_r" step=any value="{{ old("p_r") }}"> (r)
                <!-- p sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="p_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="p_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("p_sound_file") }}" />
                        <?php
                        $p_sound_path = null;
                        if ( count($errors) ) {
                            $p_sound_path = \Session::has('p_sound_path') ? \Session::get('p_sound_path') : null;
                        }
                        else {
                            Session::forget('p_sound_path');
                        }
                        ?>
                        <audio src="{{ $p_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($p_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="p_sound_path" value="@if(!is_null($p_sound_path)){{ $p_sound_path }}@endif" />
                        @if($errors->has("p_sound_file"))
                            <span class="help-block">{{ $errors->first("p_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <!-- pulse p sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">脈波</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="pp_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="pp_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("pp_sound_file") }}" />
                        <?php
                        $pp_sound_path = null;
                        if ( count($errors) ) {
                            $pp_sound_path = \Session::has('pp_sound_path') ? \Session::get('pp_sound_path') : null;
                        }
                        else {
                            Session::forget('pp_sound_path');
                        }
                        ?>
                        <audio src="{{ $pp_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($pp_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="pp_sound_path" value="@if(!is_null($pp_sound_path)){{ $pp_sound_path }}@endif" />
                        @if($errors->has("pp_sound_file"))
                            <span class="help-block">{{ $errors->first("pp_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <br>
                <label for="">T</label>
                <input type="number" name="t_x" step=any value="{{ old("t_x") }}"> (px)
                <input type="number" name="t_y" step=any value="{{ old("t_y") }}"> (px)
                <input type="number" name="t_r" step=any value="{{ old("t_r") }}"> (r)
                <!-- t sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="t_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="t_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("t_sound_file") }}" />
                        <?php
                        $t_sound_path = null;
                        if ( count($errors) ) {
                            $t_sound_path = \Session::has('t_sound_path') ? \Session::get('t_sound_path') : null;
                        }
                        else {
                            Session::forget('t_sound_path');
                        }
                        ?>
                        <audio src="{{ $t_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($t_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="t_sound_path" value="@if(!is_null($t_sound_path)){{ $t_sound_path }}@endif" />
                        @if($errors->has("t_sound_file"))
                            <span class="help-block">{{ $errors->first("t_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <!-- pulse t sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">脈波</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="pt_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="pt_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("pt_sound_file") }}" />
                        <?php
                        $pt_sound_path = null;
                        if ( count($errors) ) {
                            $pt_sound_path = \Session::has('pt_sound_path') ? \Session::get('pt_sound_path') : null;
                        }
                        else {
                            Session::forget('pt_sound_path');
                        }
                        ?>
                        <audio src="{{ $pt_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($pt_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="pt_sound_path" value="@if(!is_null($pt_sound_path)){{ $pt_sound_path }}@endif" />
                        @if($errors->has("pt_sound_file"))
                            <span class="help-block">{{ $errors->first("pt_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <br>
                <label for="">M</label>
                <input type="number" name="m_x" step=any value="{{ old("m_x") }}"> (px)
                <input type="number" name="m_y" step=any value="{{ old("m_y") }}"> (px)
                <input type="number" name="m_r" step=any value="{{ old("m_r") }}"> (r)
                <!-- m sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="m_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="m_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("m_sound_file") }}" />
                        <?php
                        $m_sound_path = null;
                        if ( count($errors) ) {
                            $m_sound_path = \Session::has('m_sound_path') ? \Session::get('m_sound_path') : null;
                        }
                        else {
                            Session::forget('m_sound_path');
                        }
                        ?>
                        <audio src="{{ $m_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($m_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="m_sound_path" value="@if(!is_null($m_sound_path)){{ $m_sound_path }}@endif" />
                        @if($errors->has("m_sound_file"))
                            <span class="help-block">{{ $errors->first("m_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <!-- pulse m sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">脈波</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="pm_sound_file" />
                        <input type="file" accept='audio/*' id="sound_file-field" name="pm_sound_file"
                            style="margin-left:14px; margin-top:8px;" value="{{ old("pm_sound_file") }}" />
                        <?php
                        $pm_sound_path = null;
                        if ( count($errors) ) {
                            $pm_sound_path = \Session::has('pm_sound_path') ? \Session::get('pm_sound_path') : null;
                        }
                        else {
                            Session::forget('pm_sound_path');
                        }
                        ?>
                        <audio src="{{ $pm_sound_path }}" preload controls
                            style="margin-left:15px; @if(is_null($pm_sound_path)) display:none; @endif"></audio>
                        <input type="hidden" name="pm_sound_path" value="@if(!is_null($pm_sound_path)){{ $pm_sound_path }}@endif" />
                        @if($errors->has("pm_sound_file"))
                            <span class="help-block">{{ $errors->first("pm_sound_file") }}</span>
                        @endif
                    </div>
                </div>
                
                <br>
                <br>
                <br>
                <!-- Heart 1 -->
                <br>
            </div>
                <label for="" class="col-sm-2 control-label">その他聴診音</label>
            <div class="col-sm-10">
                <?php 
                    for($i=1; $i <= 4; $i++) {
                ?>
                    <label for="" style="margin-right: 12px;">{{$i}}</label>
                    <input type="number" name="h{{ $i }}_x" step=any value="{{ old('h'.$i.'_x') }}"> (px)
                    <input type="number" name="h{{ $i }}_y" step=any value="{{ old('h'.$i.'_y') }}"> (px)
                    <input type="number" name="h{{ $i }}_r" step=any value="{{ old('h'.$i.'_r') }}"> (r)
                    <!-- h1-h4 sound -->
                    <div class="row">
                        <div class="col-sm-1">
                            <label style="margin-top: 10px;">心音</label>
                        </div>
                        <div class="col-sm-11" style="margin-left: -30px;">
                            <input type="hidden" name="h{{ $i }}_sound_file" />
                            <input type="file" accept='audio/*' id="sound_file-field" name="h{{ $i }}_sound_file"
                                style="margin-left:14px; margin-top:8px;" value="{{ old('h'.$i.'_sound_file') }}" />
                            <?php
                            $h_sound_path = null;
                            if ( count($errors) ) {
                                $h_sound_path = \Session::has('h'.$i.'_sound_path') ? \Session::get('h'.$i.'_sound_path') : null;
                            }
                            else {
                                Session::forget('h'.$i.'_sound_path');
                            }
                            ?>
                            <audio src="{{ $h_sound_path }}" preload controls
                                style="margin-left:15px; @if(is_null($h_sound_path)) display:none; @endif"></audio>
                            <input type="hidden" name="h{{ $i }}_sound_path" value="@if(!is_null($h_sound_path)){{ $h_sound_path }}@endif" />
                            @if($errors->has("h".$i."_sound_file"))
                            <span class="help-block">{{ $errors->first("h".$i."_sound_file") }}</span>
                            @endif
                        </div>
                    </div>
                    <!-- p1-p4 sound -->
                    <div class="row">
                        <div class="col-sm-1">
                            <label style="margin-top: 10px;">脈波</label>
                        </div>
                        <div class="col-sm-11" style="margin-left: -30px;">
                            <input type="hidden" name="p{{ $i }}_sound_file" />
                            <input type="file" accept='audio/*' id="sound_file-field" name="p{{ $i }}_sound_file"
                                style="margin-left:14px; margin-top:8px;" value="{{ old('p'.$i.'_sound_file') }}" />
                            <?php
                            $p_sound_path = null;
                            if ( count($errors) ) {
                                $p_sound_path = \Session::has('p'.$i.'_sound_path') ? \Session::get('p'.$i.'_sound_path') : null;
                            }
                            else {
                                Session::forget('p'.$i.'_sound_path');
                            }
                            ?>
                            <audio src="{{ $p_sound_path }}" preload controls
                                style="margin-left:15px; @if(is_null($p_sound_path)) display:none; @endif"></audio>
                            <input type="hidden" name="p{{ $i }}_sound_path" value="@if(!is_null($p_sound_path)){{ $p_sound_path }}@endif" />
                            @if($errors->has("p".$i."_sound_file"))
                            <span class="help-block">{{ $errors->first("p".$i."_sound_file") }}</span>
                            @endif
                        </div>
                    </div>
                    <br>
                <?php } ?>

                <br>
                <br>

                <!-- Tracheal -->
                <label for="" style="margin-right: 20px;">気管呼吸音 </label>
                <br>
                <label for="" style="margin-right: 90px;"></label>
                <label for="" style="margin-left: -12px;">1</label>
                <input type="number" name="tr1_x" step=any value="{{ old("tr1_x") }}"> (px)
                <input type="number" name="tr1_y" step=any value="{{ old("tr1_y") }}"> (px)
                <input type="number" name="tr1_r" step=any value="{{ old("tr1_r") }}"> (r)
                <br>
                
                <!-- tr1 sound -->
                <input type="hidden" name="tr1_sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="tr1_sound_file"
                    style="margin-left:94px; margin-top:8px;" value="{{ old("tr1_sound_file") }}" />
                <?php
                $tr1_sound_path = null;
                if ( count($errors) ) {
                    $tr1_sound_path = \Session::has('tr1_sound_path') ? \Session::get('tr1_sound_path') : null;
                }
                else {
                    Session::forget('tr1_sound_path');
                }
                ?>
                <audio src="{{ $tr1_sound_path }}" preload controls
                    style="margin-left:94px; @if(is_null($tr1_sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="tr1_sound_path" value="@if(!is_null($tr1_sound_path)){{ $tr1_sound_path }}@endif" />
                @if($errors->has("tr1_sound_file"))
                <span class="help-block">{{ $errors->first("tr1_sound_file") }}</span>
                @endif
                <br>

                <label for="" style="margin-left: 82px;">2</label>
                <input type="number" name="tr2_x" step=any value="{{ old("tr2_x") }}"> (px)
                <input type="number" name="tr2_y" step=any value="{{ old("tr2_y") }}"> (px)
                <input type="number" name="tr2_r" step=any value="{{ old("tr2_r") }}"> (r)
                
                <!-- tr2 sound -->
                <input type="hidden" name="tr2_sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="tr2_sound_file"
                    style="margin-left:94px; margin-top:8px;" value="{{ old("tr2_sound_file") }}" />
                <?php
                $tr2_sound_path = null;
                if ( count($errors) ) {
                    $tr2_sound_path = \Session::has('tr2_sound_path') ? \Session::get('tr2_sound_path') : null;
                }
                else {
                    Session::forget('tr2_sound_path');
                }
                ?>
                <audio src="{{ $tr2_sound_path }}" preload controls
                    style="margin-left:94px; @if(is_null($tr2_sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="tr2_sound_path" value="@if(!is_null($tr2_sound_path)){{ $tr2_sound_path }}@endif" />
                @if($errors->has("tr2_sound_file"))
                <span class="help-block">{{ $errors->first("tr2_sound_file") }}</span>
                @endif
                <br>

                <!-- Bronchial Front -->
                <br>
                <label for="">気管支呼吸音 </label>
                <br>
                <label for="">(正面） </label>
                <br>
                <label for="" style="margin-right: 92px;"></label>
                <label for="" style="margin-left: -12px;">1</label>
                <input type="number" name="br1_x" step=any value="{{ old("br1_x") }}"> (px)
                <input type="number" name="br1_y" step=any value="{{ old("br1_y") }}"> (px)
                <input type="number" name="br1_r" step=any value="{{ old("br1_r") }}"> (r)
                <br>

                <!-- br1 sound -->
                <input type="hidden" name="br1_sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="br1_sound_file"
                    style="margin-left:95px; margin-top:8px;" value="{{ old("br1_sound_file") }}" />
                <?php
                $br1_sound_path = null;
                if ( count($errors) ) {
                    $br1_sound_path = \Session::has('br1_sound_path') ? \Session::get('br1_sound_path') : null;
                }
                else {
                    Session::forget('br1_sound_path');
                }
                ?>
                <audio src="{{ $br1_sound_path }}" preload controls
                    style="margin-left:95px; @if(is_null($br1_sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="br1_sound_path" value="@if(!is_null($br1_sound_path)){{ $br1_sound_path }}@endif" />
                @if($errors->has("br1_sound_file"))
                <span class="help-block">{{ $errors->first("br1_sound_file") }}</span>
                @endif

                <br>

                <label for="" style="margin-left: 82px;">2</label>
                <input type="number" name="br2_x" step=any value="{{ old("br2_x") }}"> (px)
                <input type="number" name="br2_y" step=any value="{{ old("br2_y") }}"> (px)
                <input type="number" name="br2_r" step=any value="{{ old("br2_r") }}"> (r)
                

                <!-- br2 sound -->
                <input type="hidden" name="br2_sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="br2_sound_file"
                    style="margin-left:95px; margin-top:8px;" value="{{ old("br2_sound_file") }}" />
                <?php
                $br2_sound_path = null;
                if ( count($errors) ) {
                    $br2_sound_path = \Session::has('br2_sound_path') ? \Session::get('br2_sound_path') : null;
                }
                else {
                    Session::forget('br2_sound_path');
                }
                ?>
                <audio src="{{ $br2_sound_path }}" preload controls
                    style="margin-left:95px; @if(is_null($br2_sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="br2_sound_path" value="@if(!is_null($br2_sound_path)){{ $br2_sound_path }}@endif" />
                @if($errors->has("br2_sound_file"))
                <span class="help-block">{{ $errors->first("br2_sound_file") }}</span>
                @endif

                <!-- Bronchial back -->
                <br>
                <label for="">(背面）</label>
                <br>
                <label for="" style="margin-right: 92px;"></label>
                <label for="" style="margin-left: -12px;">1</label>
                <input type="number" name="br3_x" step=any value="{{ old("br3_x") }}"> (px)
                <input type="number" name="br3_y" step=any value="{{ old("br3_y") }}"> (px)
                <input type="number" name="br3_r" step=any value="{{ old("br3_r") }}"> (r)
                <br>

                <!-- br3 sound -->
                <input type="hidden" name="br3_sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="br3_sound_file"
                    style="margin-left:95px; margin-top:8px;" value="{{ old("br3_sound_file") }}" />
                <?php
                $br3_sound_path = null;
                if ( count($errors) ) {
                    $br3_sound_path = \Session::has('br3_sound_path') ? \Session::get('br3_sound_path') : null;
                }
                else {
                    Session::forget('br3_sound_path');
                }
                ?>
                <audio src="{{ $br3_sound_path }}" preload controls
                    style="margin-left:95px; @if(is_null($br3_sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="br3_sound_path" value="@if(!is_null($br3_sound_path)){{ $br3_sound_path }}@endif" />
                @if($errors->has("br3_sound_file"))
                <span class="help-block">{{ $errors->first("br3_sound_file") }}</span>
                @endif

                <br>

                <label for="" style="margin-left: 82px;">2</label>
                <input type="number" name="br4_x" step=any value="{{ old("br4_x") }}"> (px)
                <input type="number" name="br4_y" step=any value="{{ old("br4_y") }}"> (px)
                <input type="number" name="br4_r" step=any value="{{ old("br4_r") }}"> (r)
                

                <!-- br4 sound -->
                <input type="hidden" name="br4_sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="br4_sound_file"
                    style="margin-left:95px; margin-top:8px;" value="{{ old("br4_sound_file") }}" />
                <?php
                $br4_sound_path = null;
                if ( count($errors) ) {
                    $br4_sound_path = \Session::has('br4_sound_path') ? \Session::get('br4_sound_path') : null;
                }
                else {
                    Session::forget('br4_sound_path');
                }
                ?>
                <audio src="{{ $br4_sound_path }}" preload controls
                    style="margin-left:95px; @if(is_null($br4_sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="br4_sound_path" value="@if(!is_null($br4_sound_path)){{ $br4_sound_path }}@endif" />
                @if($errors->has("br4_sound_file"))
                <span class="help-block">{{ $errors->first("br4_sound_file") }}</span>
                @endif

                <br>

                <!-- Vesicular -->
                <br>
                <label for="">肺胞呼吸音 </label>
                <br>
                <!-- Front body -->
                <label for="">(正面）</label>
                <br>
                <?php 
                    for($i=1; $i <= 6; $i++) {
                ?>
                    <label for="" style="margin-right: 92px;"></label>
                    <label for="" style="margin-left: -12px;">{{$i}}</label>
                    <input type="number" name="ve{{ $i }}_x" step=any value="{{ old('ve'.$i.'_x') }}"> (px)
                    <input type="number" name="ve{{ $i }}_y" step=any value="{{ old('ve'.$i.'_y') }}"> (px)
                    <input type="number" name="ve{{ $i }}_r" step=any value="{{ old('ve'.$i.'_r') }}"> (r)
                    <!-- ve sound -->
                    <input type="hidden" name="ve{{ $i }}_sound_file" />
                    <input type="file" accept='audio/*' id="sound_file-field" name="ve{{ $i }}_sound_file"
                        style="margin-left:95px; margin-top:8px;" value="{{ old('ve'.$i.'_sound_file') }}" />
                    <?php
                    $ve_sound_path = null;
                    if ( count($errors) ) {
                        $ve_sound_path = \Session::has('ve'.$i.'_sound_path') ? \Session::get('ve'.$i.'_sound_path') : null;
                    }
                    else {
                        Session::forget('ve'.$i.'_sound_path');
                    }
                    ?>
                    <audio src="{{ $ve_sound_path }}" preload controls
                        style="margin-left:95px; @if(is_null($ve_sound_path)) display:none; @endif"></audio>
                    <input type="hidden" name="ve{{ $i }}_sound_path" value="@if(!is_null($ve_sound_path)){{ $ve_sound_path }}@endif" />
                    @if($errors->has("ve".$i."_sound_file"))
                    <span class="help-block">{{ $errors->first("ve".$i."_sound_file") }}</span>
                    @endif
                    <br>
                <?php } ?>
                <br>

                <!-- Back body -->
                <label for="">(背面）</label>
                <br>
                <?php 
                    for($i=7; $i <= 12; $i++) {
                ?>
                    <label for="" style="margin-right: 92px;"></label>
                    <label for="" style="margin-left: -12px;">{{$i-6}}</label>
                    <input type="number" name="ve{{ $i }}_x" step=any value="{{ old('ve'.$i.'_x') }}"> (px)
                    <input type="number" name="ve{{ $i }}_y" step=any value="{{ old('ve'.$i.'_y') }}"> (px)
                    <input type="number" name="ve{{ $i }}_r" step=any value="{{ old('ve'.$i.'_r') }}"> (r)
                    <br>
                    <!-- ve sound -->
                    <input type="hidden" name="ve{{ $i }}_sound_file" />
                    <input type="file" accept='audio/*' id="sound_file-field" name="ve{{ $i }}_sound_file"
                        style="margin-left:95px; margin-top:8px;" value="{{ old('ve'.$i.'_sound_file') }}" />
                    <?php
                    $ve_sound_path = null;
                    if ( count($errors) ) {
                        $ve_sound_path = \Session::has('ve'.$i.'_sound_path') ? \Session::get('ve'.$i.'_sound_path') : null;
                    }
                    else {
                        Session::forget('ve'.$i.'_sound_path');
                    }
                    ?>
                    <audio src="{{ $ve_sound_path }}" preload controls
                        style="margin-left:95px; @if(is_null($ve_sound_path)) display:none; @endif"></audio>
                    <input type="hidden" name="ve{{ $i }}_sound_path" value="@if(!is_null($ve_sound_path)){{ $ve_sound_path }}@endif" />
                    @if($errors->has("ve".$i."_sound_file"))
                    <span class="help-block">{{ $errors->first("ve".$i."_sound_file") }}</span>
                    @endif
                    <br>
                <?php } ?>
                <br>
                
            </div>
        </div>

        <!-- コンテンツグループ -->
        <div class="form-group @if($errors->has('content_group')) has-error @endif">
            <label for="content_group-field" class="col-sm-2 control-label">コンテンツグループ</label>
            <div class="col-sm-10">
                <select id="type-selector" name="type" class="form-control">
                    <?php $type_strings = [ 1 => '肺音', 2 => '心音' ]; ?>
                    @foreach($type_strings as $key => $value)
                    <option id="type-option" value="{{$key}}" @if($key==old('content_group')) checked @endif>{{$value}}
                    </option>
                    @endforeach
                </select>
                @if($errors->has("content_group"))
                <span class="help-block">{{ $errors->first("content_group") }}</span>
                @endif
            </div>
        </div>
        <!-- ソート -->
        <div class="form-group @if($errors->has('sort')) has-error @endif">
            <label for="sort-field" class="col-sm-2 control-label">ソート</label>
            <div class="col-sm-10">
                <input type="number" id="sort-field" name="sort" class="form-control" value="{{ old("sort") }}" />
                @if($errors->has("sort"))
                <span class="help-block">{{ $errors->first("sort") }}</span>
                @endif
            </div>
        </div>
        <!-- 座標 -->
        <div class="form-group @if($errors->has('coordinate')) has-error @endif">
            <label for="coordinate-field" class="col-sm-2 control-label">座標</label>
            <div class="col-sm-10">
                <input type="text" id="coordinate-field" name="coordinate" class="form-control"
                    value="{{ old("coordinate") }}" />
                @if($errors->has("sort"))
                <span class="help-block">{{ $errors->first("coordinate") }}</span>
                @endif
            </div>
        </div>

        <!-- 監修者 -->
        <div class="form-group @if($errors->has('user_id')) has-error @endif">
            <label for="user_id-field" class="col-sm-2 control-label">監修者</label>
            <div class="col-sm-10">
                <select id="user_id-selector" name="user_id" class="form-control">
                    @foreach($superintendents as $key => $superintendent)
                    <option id="user_id-option" value="{{$superintendent->id}}" @if($key==old('user_id')) checked
                        @endif>{{$superintendent->name}}</option>
                    @endforeach
                </select>
                @if($errors->has("user_id"))
                <span class="help-block">{{ $errors->first("user_id") }}</span>
                @endif
            </div>
        </div>
        <!-- disp_order -->
        <div class="form-group">
            <div class="col-sm-10">
                <input style="width: 80px;display:inline" type="hidden" id="disp_order-field" name="disp_order"
                    class="form-control" value="{{ $count+1 }}" placeholder="順番" />
                {{-- @if($errors->has("disp_order"))
          <span class="help-block">{{ $errors->first("disp_order") }}</span>
                @endif --}}
            </div>
        </div>
    </div><!-- END: 上部フォーム -->
    <hr />
    <!-- ステータス -->
    <div class="well">
        <div class="form-group @if($errors->has('status')) has-error @endif">
            <label for="status-field" class="col-sm-2 control-label">ステータス</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" id="status-field" name="status" value='0'
                            {{ old("status",0)==0 ? "checked" : "" }} />監修中
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="status-field" name="status" value='1'
                            {{ old("status")==1 ? "checked" : "" }} />監修済
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="status-field" name="status" value='2'
                            {{ old("status")==2 ? "checked" : "" }} />公開中
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="status-field" name="status" value='3'
                            {{ old("status")==3 ? "checked" : "" }} />公開中(New)
                    </label>
                    <input style="width: 80px;display:inline" type="hidden" id="disp_order-field" name="disp_order"
                        class="form-control" value="{{ $count+1 }}" placeholder="順番" />
                    {{-- @if($errors->has("disp_order"))
          <span class="help-block">{{ $errors->first("disp_order") }}</span>
                    @endif --}}
                </div>
                @if($errors->has("status"))
                <span class="help-block">{{ $errors->first("status") }}</span>
                @endif
            </div>
        </div>
        <!-- グループ属性 -->
        <div class="form-group @if($errors->has('exam_group')) has-error @endif">
            <label for="exam_group-field" class="col-sm-2 control-label">グループ属性</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label class="col-sm-2">
                        <input type="radio" id="" name="group_attr" value='0'
                            {{ old("group_attr",0)==0 ? "checked" : "" }} />あり
                    </label>
                    <div class="col-sm-6">
                        <select class="form-control" name="exam_group[]" id="exam_group-field" multiple>
                            @foreach($exam_groups as $exam_group)
                            <option value="{{$exam_group->id}}" @if(old("exam_group")){{ (in_array($exam_group->id, old("exam_group")) ? "selected":"") }}@endif>{{$exam_group->name}}</option>
                            @endforeach
                        </select>
                        <p style="color:#B8B7B7;font-family:inherit;white-space:nowrap;">グループ属性の削除の際は”Ctrl+F”検索で該当箇所の特定が出来ます。</p>
                    </div>
                    <label class="col-sm-3 col-3 pl-60">
                        <input type="radio" id="" name="group_attr" value='1'
                            {{ old("group_attr")==1 ? "checked" : "" }} />なし
                    </label>

                </div>
                @if($errors->has("exam_group"))
                <span class="help-block">{{ $errors->first("exam_group") }}</span>
                @endif
            </div>
        </div>
    </div><!-- END: ステータス -->
    <!-- キャンセル・保存 -->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <a class="btn btn-default" href="{{ route('admin.iPax.index') }}">キャンセル</a>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div style="padding: 3em;">&nbsp;</div>

</form>

<script type="text/javascript">
$('#sortable_tbody_ja, #sortable_tbody_en').sortable();
$('#sortable_tbody_ja, #sortable_tbody_en').disableSelection();
</script>

@endsection