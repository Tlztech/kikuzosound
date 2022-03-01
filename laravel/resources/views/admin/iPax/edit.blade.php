@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <h1>iPaxライブラリ編集</h1>
</div>

<form class="form-horizontal" action="{{ route('admin.iPax.update', $stetho_sound->id) }}" method="POST"
    enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="updated_at" value="{{old('updated_at',$stetho_sound->updated_at)}}" />
    <h4>基本情報</h4>
    <div class="well">
        @include('admin.common.form_errors')
        <!-- 強制更新 -->
        @if($errors->has("is_force_update"))
        <div class="form-group has-error">
            <label for="is_force_update-field" class="col-sm-2 control-label">強制更新</label>
            <div class="col-sm-10">
                <input type="checkbox" name="is_force_update" class="well well-sm" value="1" />
            </div>
        </div>
        @endif
        <!-- 聴診音ファイル -->
        <div class="form-group">
            <input type="hidden" name="lib_type" value="1" />
        </div>
        <!-- タイトル -->
        <div class="form-group @if($errors->has('title')) has-error @endif">
            <label for="title-field" class="col-sm-2 control-label">タイトル (JP)</label>
            <div class="col-sm-10">
                <input type="text" id="title-field" name="title" class="form-control"
                    value="{{ old('title',$stetho_sound->title) }}" />
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
                    value="{{ old('title_en',$stetho_sound->title_en) }}" />
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
                    class="form-control">{{ old('description',$stetho_sound->description) }}</textarea>
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
                    class="form-control">{{ old('description_en',$stetho_sound->description_en) }}</textarea>
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
                            {{ old('is_normal',$stetho_sound->is_normal) == 1 ? "checked" : "" }} />正常
                    </label>
                    <label>
                        <input type="radio" id="is_normal-field" name="is_normal" value='0'
                            {{ old('is_normal',$stetho_sound->is_normal) == 0 ? "checked" : "" }} />異常
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
                    <table class="table table-striped stetho_sounds_table">
                        <tbody id="sortable_tbody_ja">
                            <?php
                            $images = [];
                            if (count($errors)) {
                                $images = $stetho_sound->images_ja->toArray();
                            } else {
                                \Session::forget('stetho_sound_images');
                                $images = $stetho_sound->images_ja->toArray();
                            }
                            ?>
                            @foreach($images as $image)
                            <tr>
                                <td>
                                    <input type="hidden" name="image_paths[]" value="{{ $image['image_path'] }}" />
                                    <input type="hidden" name="image_ids[]" value="{{ $image['id'] }}" />
                                    <input type="file" id="image_files-fields" name="image_files[]" accept=".jpg,.png,image/jpeg,image/png,image/gif,video/mp4" value="" />
                                    @if (strpos($image['image_path'], 'mp4'))
                                        <img src="" style="height: 220px;display:none;" />
                                        <video style="height: 220px;" controls src="{{ $image['image_path'].'?v='.session('version') }}"></video>
                                    @else
                                        <img src="{{ $image['image_path'].'?v='.session('version') }}" style="height: 220px;" class="stetho-image"/>
                                        <video controls src="" style="display:none;"></video>
                                    @endif
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
                        <a id="add_images__btn" class="btn btn-sm btn-primary" data-field="explanatory" data-lang="ja">画像を追加</a>
                    </div>
                    <!-- @if($errors->has("image_files"))
                    <span class="help-block">{{ $errors->first("image_files") }}</span>
                    @endif -->
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
                        <table class="table table-striped stetho_sounds_table">
                            <tbody id="sortable_tbody_en">
                                <?php
                                $images = [];
                                if (count($errors)) {
                                    $images = $stetho_sound->images_en->toArray();
                                } else {
                                    \Session::forget('stetho_sound_images');
                                    $images = $stetho_sound->images_en->toArray();
                                }
                                ?>
                                @foreach($images as $image)
                                <tr>
                                    <td>
                                        <input type="hidden" name="image_paths[]" value="{{ $image['image_path'] }}" />
                                        <input type="hidden" name="image_ids[]" value="{{ $image['id'] }}" />
                                        <input type="file" id="image_files-fields" name="image_files[]" accept=".jpg,.png,image/jpeg,image/png,image/gif,video/mp4" value="" />
                                        @if (strpos($image['image_path'], 'mp4'))
                                            <img src="" style="height: 220px;display:none;" />
                                            <video id="lib_video_file-video" controls src="{{ $image['image_path'].'?v='.session('version') }}"></video>
                                        @else
                                            <img src="{{ $image['image_path'].'?v='.session('version')}}" style="height: 220px;" class="stetho-image"/>
                                            <video id="lib_video_file-video" controls src="" style="display:none;"></video>
                                        @endif
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
                        <!-- @if($errors->has("image_files"))
                        <span class="help-block">{{ $errors->first("image_files") }}</span>
                        @endif -->
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
                    $body_image_file_path = $stetho_sound->body_image;
                  }
                  else {
                    \Session::forget('body_image_file_path');
                    $body_image_file_path = $stetho_sound->body_image;
                  }
                ?>
                <img id="body_image_file-image-img"
                    src="@if(!is_null($body_image_file_path)){{ $body_image_file_path.'?v='.session('version') }}@endif"
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
                    $body_image_back_file_path = $stetho_sound->body_image_back;
                  }
                  else {
                    \Session::forget('body_image_back_file_path');
                    $body_image_back_file_path = $stetho_sound->body_image_back;
                  }
                ?>
                <img id="body_image_back_file-image-img"
                    src="@if(!is_null($body_image_back_file_path)){{ $body_image_back_file_path.'?v='.session('version')}}@endif"
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
                    class="form-control">{{ old('img_description',$stetho_sound->image_description) }}</textarea>
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
                    class="form-control">{{ old('img_description_en',$stetho_sound->image_description_en) }}</textarea>
                @if($errors->has("img_description_en"))
                <span class="help-block">{{ $errors->first("img_description_en") }}</span>
                @endif
            </div>
        </div>
        <!-- 設定-->
        <div class="form-group @if($errors->has('configuration')) has-error @endif">
            <label for="configuration-field" class="col-sm-2 control-label">設定</label>
            <div class="col-sm-10">
                {{-- <textarea id="configuration-field" name="configuration" rows="10"
                    class="form-control">{{ old("configuration",$stetho_sound->configuration) }}</textarea>
                @if($errors->has("configuration"))
                <span class="help-block">{{ $errors->first("configuration") }}</span>
                @endif --}}

                <?php 
                    $config = json_decode($stetho_sound->configuration);
                ?>
              
                <label for="">A </label>
                <input type="number" name="a_x" step=any value="{{ old("a_x",!empty($config->a->x)? $config->a->x : '') }}"> (px)
                <input type="number" name="a_y" step=any value="{{ old("a_y",!empty($config->a->y)? $config->a->y : '') }}"> (px)
                <input type="number" name="a_r" step=any value="{{ old("a_r",!empty($config->a->r)? $config->a->r : '') }}"> (r)
                <!-- a sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="a_sound_file" />
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $a_sound_path = \Session::has('a_sound_path') ? \Session::get('a_sound_path') : !empty($s->a_sound_path)? $s->a_sound_path : '';
                            \Session::forget('a_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="a_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($a_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$a_sound_path.'?v='.session('version')}}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="a_sound_path" value="{{$a_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($a_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="a_sound_path" value="{{$a_sound_path}}"/>
                        @endif
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
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $pa_sound_path = \Session::has('pa_sound_path') ? \Session::get('pa_sound_path') : !empty($s->pa_sound_path)? $s->pa_sound_path : '';
                            \Session::forget('pa_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="pa_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($pa_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$pa_sound_path.'?v='.session('version') }}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="pa_sound_path" value="{{$pa_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($pa_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="pa_sound_path" value="{{$pa_sound_path}}"/>
                        @endif
                        @if($errors->has("pa_sound_file"))
                            <span class="help-block">{{ $errors->first("pa_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <br>
                <label for="">P</label>
                <input type="number" name="p_x" step=any value="{{ old("p_x",!empty($config->p->x)? $config->p->x : '') }}"> (px)
                <input type="number" name="p_y" step=any value="{{ old("p_y",!empty($config->p->y)? $config->p->y : '') }}"> (px)
                <input type="number" name="p_r" step=any value="{{ old("p_r",!empty($config->p->r)? $config->p->r : '') }}"> (r)
                
                <!-- p sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="p_sound_file" />
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $p_sound_path = \Session::has('p_sound_path') ? \Session::get('p_sound_path') : !empty($s->p_sound_path)? $s->p_sound_path : '';
                            \Session::forget('p_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="p_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($p_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$p_sound_path.'?v='.session('version') }}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="p_sound_path" value="{{$p_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($p_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="p_sound_path" value="{{$p_sound_path}}"/>
                        @endif
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
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $pp_sound_path = \Session::has('pp_sound_path') ? \Session::get('pp_sound_path') : !empty($s->pp_sound_path)? $s->pp_sound_path : '';
                            \Session::forget('pp_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="pp_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($pp_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$pp_sound_path.'?v='.session('version') }}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="pp_sound_path" value="{{$pp_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($pp_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="pp_sound_path" value="{{$pp_sound_path}}"/>
                        @endif
                        @if($errors->has("pp_sound_file"))
                            <span class="help-block">{{ $errors->first("pp_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <br>
                <label for="">T</label>
                <input type="number" name="t_x" step=any value="{{ old("t_x",!empty($config->t->x)? $config->t->x : '') }}"> (px)
                <input type="number" name="t_y" step=any value="{{ old("t_y",!empty($config->t->y)? $config->t->y : '') }}"> (px)
                <input type="number" name="t_r" step=any value="{{ old("t_r",!empty($config->t->r)? $config->t->r : '') }}"> (r)
                <!-- t sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="t_sound_file" />
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $t_sound_path = \Session::has('t_sound_path') ? \Session::get('t_sound_path') : !empty($s->t_sound_path)? $s->t_sound_path : '';
                            \Session::forget('t_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="t_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($t_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$t_sound_path.'?v='.session('version') }}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="t_sound_path" value="{{$t_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($t_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="t_sound_path" value="{{$t_sound_path}}"/>
                        @endif
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
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $pt_sound_path = \Session::has('pt_sound_path') ? \Session::get('pt_sound_path') : !empty($s->pt_sound_path)? $s->pt_sound_path : '';
                            \Session::forget('pt_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="pt_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($pt_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$pt_sound_path.'?v='.session('version') }}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="pt_sound_path" value="{{$pt_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($pt_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="pt_sound_path" value="{{$pt_sound_path}}"/>
                        @endif
                        @if($errors->has("pt_sound_file"))
                            <span class="help-block">{{ $errors->first("pt_sound_file") }}</span>
                        @endif
                    </div>
                </div>

                <br>
                <label for="">M</label>
                <input type="number" name="m_x" step=any value="{{ old("m_x",!empty($config->m->x)? $config->m->x : '') }}"> (px)
                <input type="number" name="m_y" step=any value="{{ old("m_y",!empty($config->m->y)? $config->m->y : '') }}"> (px)
                <input type="number" name="m_r" step=any value="{{ old("m_r",!empty($config->m->r)? $config->m->r : '') }}"> (r)
                <!-- m sound -->
                <div class="row">
                    <div class="col-sm-1">
                        <label style="margin-top: 10px;">心音</label>
                    </div>
                    <div class="col-sm-11" style="margin-left: -30px;">
                        <input type="hidden" name="m_sound_file" />
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $m_sound_path = \Session::has('m_sound_path') ? \Session::get('m_sound_path') : !empty($s->m_sound_path)? $s->m_sound_path : '';
                            \Session::forget('m_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="m_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($m_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$m_sound_path.'?v='.session('version')}}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="m_sound_path" value="{{$m_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($m_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="m_sound_path" value="{{$m_sound_path}}"/>
                        @endif
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
                        <?php
                            $s = json_decode($stetho_sound->sound_path);
                            $pm_sound_path = \Session::has('pm_sound_path') ? \Session::get('pm_sound_path') : !empty($s->pm_sound_path)? $s->pm_sound_path : '';
                            \Session::forget('pm_sound_path');
                            ?>
                            <input type="file" accept='audio/*' id="sound_file-field" name="pm_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                        @if (file_exists(public_path($pm_sound_path)))
                            <audio style="margin-left:15px;" src="{{ url('/').$pm_sound_path.'?v='.session('version') }}"
                                preload controls>
                            </audio>
                            <input type="hidden" name="pm_sound_path" value="{{$pm_sound_path}}"/>
                        @else
                            <audio src=""
                                preload controls style="@if(is_null($pm_sound_path)) display:none; @endif">
                            </audio>
                            <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                            <input type="hidden" name="pm_sound_path" value="{{$pm_sound_path}}"/>
                        @endif
                        @if($errors->has("pm_sound_file"))
                            <span class="help-block">{{ $errors->first("pm_sound_file") }}</span>
                        @endif
                    </div>
                </div>
                <br>
                <br>
                <br>
            </div>
                <label for="" class="col-sm-2 control-label">その他聴診音</label>
            <div class="col-sm-10">
                <?php 
                    for($i=1; $i <= 4; $i++) {
                ?>
                    <label for="" style="margin-right: 12px;">{{$i}}</label>
                    <input type="number" name="h{{ $i }}_x" step=any value="{{ old('h'.$i.'_x', !empty($config->{'h'.$i}->x)? $config->{'h'.$i}->x : '') }}"> (px)
                    <input type="number" name="h{{ $i }}_y" step=any value="{{ old('h'.$i.'_y', !empty($config->{'h'.$i}->y)? $config->{'h'.$i}->y : '') }}"> (px)
                    <input type="number" name="h{{ $i }}_r" step=any value="{{ old('h'.$i.'_r', !empty($config->{'h'.$i}->r)? $config->{'h'.$i}->r : '') }}"> (r)
                    <br>

                    <!-- h1-h4 sound -->
                    <div class="row">
                        <div class="col-sm-1">
                            <label style="margin-top: 10px;">心音</label>
                        </div>
                        <div class="col-sm-11" style="margin-left: -30px;">
                            <!-- h sound -->
                            <input type="hidden" name="h{{$i}}_sound_file" />
                            <?php
                                $s = json_decode($stetho_sound->sound_path);
                                $h_sound_path = \Session::has('h'.$i.'_sound_path') ? \Session::get('h'.$i.'_sound_path') : !empty($s->{'h'.$i.'_sound_path'})? $s->{'h'.$i.'_sound_path'} : '';
                                \Session::forget('h'.$i.'_sound_path');
                                ?>
                                <input type="file" accept='audio/*' id="sound_file-field" name="h{{$i}}_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                            @if (file_exists(public_path($h_sound_path)))
                                <audio src="{{ url('/').$h_sound_path.'?v='.session('version') }}"
                                    preload controls style="margin-left:15px;">
                                </audio>
                                <input type="hidden" name="h{{$i}}_sound_path" value="{{$h_sound_path}}"/>
                            @else
                                <audio src=""1
                                    preload controls style="margin-left:15px; @if(is_null($h_sound_path)) display:none; @endif">
                                </audio>
                                <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                                <input type="hidden" name="h{{$i}}_sound_path" value="{{$h_sound_path}}"/>
                            @endif
                            @if($errors->has("h".$i."_sound_file"))
                                <span class="help-block">{{ $errors->first("h".$i."_sound_file") }}</span>
                            @endif
                            <br>
                        </div>
                    </div>

                    <!-- p1-p4 sound -->
                    <div class="row">
                        <div class="col-sm-1">
                            <label style="margin-top: 10px;">脈波</label>
                        </div>
                        <div class="col-sm-11" style="margin-left: -30px;">
                            <!-- p sound -->
                            <input type="hidden" name="p{{$i}}_sound_file" />
                            <?php
                                $s = json_decode($stetho_sound->sound_path);
                                $p_sound_path = \Session::has('p'.$i.'_sound_path') ? \Session::get('p'.$i.'_sound_path') : !empty($s->{'p'.$i.'_sound_path'})? $s->{'p'.$i.'_sound_path'} : '';
                                \Session::forget('p'.$i.'_sound_path');
                                ?>
                                <input type="file" accept='audio/*' id="sound_file-field" name="p{{$i}}_sound_file" style="margin-left:14px; margin-top:8px;" value="" />

                            @if (file_exists(public_path($p_sound_path)))
                                <audio src="{{ url('/').$p_sound_path.'?v='.session('version') }}"
                                    preload controls style="margin-left:15px;">
                                </audio>
                                <input type="hidden" name="p{{$i}}_sound_path" value="{{$p_sound_path}}"/>
                            @else
                                <audio src=""1
                                    preload controls style="margin-left:15px; @if(is_null($p_sound_path)) display:none; @endif">
                                </audio>
                                <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                                <input type="hidden" name="p{{$i}}_sound_path" value="{{$p_sound_path}}"/>
                            @endif
                            @if($errors->has("p".$i."_sound_file"))
                                <span class="help-block">{{ $errors->first("p".$i."_sound_file") }}</span>
                            @endif
                            <br>
                        </div>
                    </div>

                <?php } ?>


                <br>
                <br>

                <!-- Tracheal -->
                <label for="" style="margin-right: 20px;">気管呼吸音 </label>
                <br>
                <label for="" style="margin-right: 90px;"></label>
                <label for="" style="margin-left: -12px;">1</label>
                <input type="number" name="tr1_x" step=any value="{{ old("tr1_x",!empty($config->tr1->x)? $config->tr1->x : '') }}"> (px)
                <input type="number" name="tr1_y" step=any value="{{ old("tr1_y",!empty($config->tr1->y)? $config->tr1->y : '') }}"> (px)
                <input type="number" name="tr1_r" step=any value="{{ old("tr1_r",!empty($config->tr1->r)? $config->tr1->r : '') }}"> (r)

                <br>

                <!-- tr1 sound -->
                <input type="hidden" name="tr1_sound_file" />
                <?php
                    $s = json_decode($stetho_sound->sound_path);
                    $tr1_sound_path = \Session::has('tr1_sound_path') ? \Session::get('tr1_sound_path') : !empty($s->tr1_sound_path)? $s->tr1_sound_path : '';
                    \Session::forget('tr1_sound_path');
                    ?>
                    <input type="file" accept='audio/*' id="sound_file-field" name="tr1_sound_file" style="margin-left:94px; margin-top:8px;" value="" />

                @if (file_exists(public_path($tr1_sound_path)))
                    <audio src="{{ url('/').$tr1_sound_path.'?v='.session('version') }}"
                        preload controls style="margin-left:95px;">
                    </audio>
                    <input type="hidden" name="tr1_sound_path" value="{{$tr1_sound_path}}"/>
                @else
                    <audio src=""
                        preload controls style="margin-left:95px; @if(is_null($tr1_sound_path)) display:none; @endif">
                    </audio>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="tr1_sound_path" value="{{$tr1_sound_path}}"/>
                @endif
                @if($errors->has("tr1_sound_file"))
                    <span class="help-block">{{ $errors->first("tr1_sound_file") }}</span>
                @endif

                <br>
                <label for="" style="margin-left: 90px;"></label>
                <label for="" style="margin-left: -12px;">2</label>
                <input type="number" name="tr2_x" step=any value="{{ old("tr2_x",!empty($config->tr2->x)? $config->tr2->x : '') }}"> (px)
                <input type="number" name="tr2_y" step=any value="{{ old("tr2_y",!empty($config->tr2->y)? $config->tr2->y : '') }}"> (px)
                <input type="number" name="tr2_r" step=any value="{{ old("tr2_r",!empty($config->tr2->r)? $config->tr2->r : '') }}"> (r)
                
                <br>

                <!-- tr2 sound -->
                <input type="hidden" name="tr2_sound_file" />
                <?php
                    $s = json_decode($stetho_sound->sound_path);
                    $tr2_sound_path = \Session::has('tr2_sound_path') ? \Session::get('tr2_sound_path') : !empty($s->tr2_sound_path)? $s->tr2_sound_path : '';
                    \Session::forget('tr2_sound_path');
                    ?>
                    <input type="file" accept='audio/*' id="sound_file-field" name="tr2_sound_file" style="margin-left:94px; margin-top:8px;" value="" />

                @if (file_exists(public_path($tr2_sound_path)))
                    <audio src="{{ url('/').$tr2_sound_path.'?v='.session('version') }}"
                        preload controls style="margin-left:95px;">
                    </audio>
                    <input type="hidden" name="tr2_sound_path" value="{{$tr2_sound_path}}"/>
                @else
                    <audio src=""
                        preload controls style="margin-left:95px; @if(is_null($tr2_sound_path)) display:none; @endif">
                    </audio>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="tr2_sound_path" value="{{$tr2_sound_path}}"/>
                @endif
                @if($errors->has("tr2_sound_file"))
                    <span class="help-block">{{ $errors->first("tr2_sound_file") }}</span>
                @endif

                <!-- Bronchial Front -->
                <br>
                <label for="">気管支呼吸音 </label>
                <br>
                <label for="">(正面） </label>
                <br>
                <label for="" style="margin-right: 92px;"></label>
                <label for="" style="margin-left: -12px;">1</label>
                <input type="number" name="br1_x" step=any value="{{ old("br1_x",!empty($config->br1->x)? $config->br1->x : '') }}"> (px)
                <input type="number" name="br1_y" step=any value="{{ old("br1_y",!empty($config->br1->y)? $config->br1->y : '') }}"> (px)
                <input type="number" name="br1_r" step=any value="{{ old("br1_r",!empty($config->br1->r)? $config->br1->r : '') }}"> (r)

                <br>

                <!-- br1 sound -->
                <input type="hidden" name="br1_sound_file" />
                <?php
                    $s = json_decode($stetho_sound->sound_path);
                    $br1_sound_path = \Session::has('br1_sound_path') ? \Session::get('br1_sound_path') : !empty($s->br1_sound_path)? $s->br1_sound_path : '';
                    \Session::forget('br1_sound_path');
                    ?>
                    <input type="file" accept='audio/*' id="sound_file-field" name="br1_sound_file" style="margin-left:95px; margin-top:8px;" value="" />

                @if (file_exists(public_path($br1_sound_path)))
                    <audio src="{{ url('/').$br1_sound_path.'?v='.session('version') }}"
                        preload controls style="margin-left:95px;">
                    </audio>
                    <input type="hidden" name="br1_sound_path" value="{{$br1_sound_path}}"/>
                @else
                    <audio src=""
                        preload controls style="margin-left:95px; @if(is_null($br1_sound_path)) display:none; @endif">
                    </audio>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="br1_sound_path" value="{{$br1_sound_path}}"/>
                @endif
                @if($errors->has("br1_sound_file"))
                    <span class="help-block">{{ $errors->first("br1_sound_file") }}</span>
                @endif

                <br>
                <label for="" style="margin-left: 92px;"></label>
                <label for="" style="margin-left: -12px;">2</label>
                <input type="number" name="br2_x" step=any value="{{ old("br2_x",!empty($config->br2->x)? $config->br2->x : '') }}"> (px)
                <input type="number" name="br2_y" step=any value="{{ old("br2_y",!empty($config->br2->y)? $config->br2->y : '') }}"> (px)
                <input type="number" name="br2_r" step=any value="{{ old("br2_r",!empty($config->br2->r)? $config->br2->r : '') }}"> (r)
                
                <br>

                <!-- br2 sound -->
                <input type="hidden" name="br2_sound_file" />
                <?php
                    $s = json_decode($stetho_sound->sound_path);
                    $br2_sound_path = \Session::has('br2_sound_path') ? \Session::get('br2_sound_path') : !empty($s->br2_sound_path)? $s->br2_sound_path : '';
                    \Session::forget('br2_sound_path');
                    ?>
                    <input type="file" accept='audio/*' id="sound_file-field" name="br2_sound_file" style="margin-left:95px; margin-top:8px;" value="" />

                @if (file_exists(public_path($br2_sound_path)))
                    <audio src="{{ url('/').$br2_sound_path.'?v='.session('version') }}"
                        preload controls style="margin-left:95px;">
                    </audio>
                    <input type="hidden" name="br2_sound_path" value="{{$br2_sound_path}}"/>
                @else
                    <audio src=""
                        preload controls style="margin-left:95px; @if(is_null($br2_sound_path)) display:none; @endif">
                    </audio>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="br2_sound_path" value="{{$br2_sound_path}}"/>
                @endif
                @if($errors->has("br2_sound_file"))
                    <span class="help-block">{{ $errors->first("br2_sound_file") }}</span>
                @endif

                <!-- Bronchial Back -->
                <br>
                <label for="">(背面） </label>
                <br>
                <label for="" style="margin-right: 92px;"></label>
                <label for="" style="margin-left: -12px;">1</label>
                <input type="number" name="br3_x" step=any value="{{ old("br3_x",!empty($config->br3->x)? $config->br3->x : '') }}"> (px)
                <input type="number" name="br3_y" step=any value="{{ old("br3_y",!empty($config->br3->y)? $config->br3->y : '') }}"> (px)
                <input type="number" name="br3_r" step=any value="{{ old("br3_r",!empty($config->br3->r)? $config->br3->r : '') }}"> (r)

                <br>

                <!-- br3 sound -->
                <input type="hidden" name="br3_sound_file" />
                <?php
                    $s = json_decode($stetho_sound->sound_path);
                    $br3_sound_path = \Session::has('br3_sound_path') ? \Session::get('br3_sound_path') : !empty($s->br3_sound_path)? $s->br3_sound_path : '';
                    \Session::forget('br3_sound_path');
                    ?>
                    <input type="file" accept='audio/*' id="sound_file-field" name="br3_sound_file" style="margin-left:95px; margin-top:8px;" value="" />

                @if (file_exists(public_path($br3_sound_path)))
                    <audio src="{{ url('/').$br3_sound_path.'?v='.session('version') }}"
                        preload controls style="margin-left:95px;">
                    </audio>
                    <input type="hidden" name="br3_sound_path" value="{{$br3_sound_path}}"/>
                @else
                    <audio src=""
                        preload controls style="margin-left:95px; @if(is_null($br3_sound_path)) display:none; @endif">
                    </audio>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="br3_sound_path" value="{{$br3_sound_path}}"/>
                @endif
                @if($errors->has("br3_sound_file"))
                    <span class="help-block">{{ $errors->first("br3_sound_file") }}</span>
                @endif

                <br>
                <label for="" style="margin-left: 92px;"></label>
                <label for="" style="margin-left: -12px;">2</label>
                <input type="number" name="br4_x" step=any value="{{ old("br4_x",!empty($config->br4->x)? $config->br4->x : '') }}"> (px)
                <input type="number" name="br4_y" step=any value="{{ old("br4_y",!empty($config->br4->y)? $config->br4->y : '') }}"> (px)
                <input type="number" name="br4_r" step=any value="{{ old("br4_r",!empty($config->br4->r)? $config->br4->r : '') }}"> (r)
                
                <br>

                <!-- br4 sound -->
                <input type="hidden" name="br4_sound_file" />
                <?php
                    $s = json_decode($stetho_sound->sound_path);
                    $br4_sound_path = \Session::has('br4_sound_path') ? \Session::get('br4_sound_path') : !empty($s->br4_sound_path)? $s->br4_sound_path : '';
                    \Session::forget('br4_sound_path');
                    ?>
                    <input type="file" accept='audio/*' id="sound_file-field" name="br4_sound_file" style="margin-left:95px; margin-top:8px;" value="" />

                @if (file_exists(public_path($br4_sound_path)))
                    <audio src="{{ url('/').$br4_sound_path.'?v='.session('version') }}"
                        preload controls style="margin-left:95px;">
                    </audio>
                    <input type="hidden" name="br4_sound_path" value="{{$br4_sound_path}}"/>
                @else
                    <audio src=""
                        preload controls style="margin-left:95px; @if(is_null($br4_sound_path)) display:none; @endif">
                    </audio>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="br4_sound_path" value="{{$br4_sound_path}}"/>
                @endif
                @if($errors->has("br4_sound_file"))
                    <span class="help-block">{{ $errors->first("br4_sound_file") }}</span>
                @endif


                <!-- Vesicular -->
                <br><br>
                <label for="">肺胞呼吸音 </label>
                <br>
                <!-- Front body -->
                <label for="" style="margin-right: 24px;">(正面）</label>
                <br>
                <?php 
                    for($i=1; $i <= 6; $i++) {
                ?>
                    <label for="" style="margin-right: 92px;"></label>
                    <label for="" style="margin-left: -12px;">{{$i}}</label>
                    <input type="number" name="ve{{ $i }}_x" step=any value="{{ old('ve'.$i.'_x', !empty($config->{'ve'.$i}->x)? $config->{'ve'.$i}->x : '') }}"> (px)
                    <input type="number" name="ve{{ $i }}_y" step=any value="{{ old('ve'.$i.'_y', !empty($config->{'ve'.$i}->y)? $config->{'ve'.$i}->y : '') }}"> (px)
                    <input type="number" name="ve{{ $i }}_r" step=any value="{{ old('ve'.$i.'_r', !empty($config->{'ve'.$i}->r)? $config->{'ve'.$i}->r : '') }}"> (r)
                    <br>

                    <!-- ve sound -->
                    <input type="hidden" name="ve{{$i}}_sound_file" />
                    <?php
                        $s = json_decode($stetho_sound->sound_path);
                        $ve_sound_path = \Session::has('ve'.$i.'_sound_path') ? \Session::get('ve'.$i.'_sound_path') : !empty($s->{'ve'.$i.'_sound_path'})? $s->{'ve'.$i.'_sound_path'} : '';
                        \Session::forget('ve'.$i.'_sound_path');
                        ?>
                        <input type="file" accept='audio/*' id="sound_file-field" name="ve{{$i}}_sound_file" style="margin-left:95px; margin-top:8px;" value="" />

                    @if (file_exists(public_path($ve_sound_path)))
                        <audio src="{{ url('/').$ve_sound_path.'?v='.session('version') }}"
                            preload controls style="margin-left:95px;">
                        </audio>
                        <input type="hidden" name="ve{{$i}}_sound_path" value="{{$ve_sound_path}}"/>
                    @else
                        <audio src=""1
                            preload controls style="margin-left:95px; @if(is_null($ve_sound_path)) display:none; @endif">
                        </audio>
                        <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                        <input type="hidden" name="ve{{$i}}_sound_path" value="{{$ve_sound_path}}"/>
                    @endif
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
                    <input type="number" name="ve{{ $i }}_x" step=any value="{{ old('ve'.$i.'_x', !empty($config->{'ve'.$i}->x)? $config->{'ve'.$i}->x : '') }}"> (px)
                    <input type="number" name="ve{{ $i }}_y" step=any value="{{ old('ve'.$i.'_y', !empty($config->{'ve'.$i}->y)? $config->{'ve'.$i}->y : '') }}"> (px)
                    <input type="number" name="ve{{ $i }}_r" step=any value="{{ old('ve'.$i.'_r', !empty($config->{'ve'.$i}->r)? $config->{'ve'.$i}->r : '') }}"> (r)
                    <br>

                    <!-- ve sound -->
                    <input type="hidden" name="ve{{$i}}_sound_file" />
                    <?php
                        $s = json_decode($stetho_sound->sound_path);
                        $ve_sound_path = \Session::has('ve'.$i.'_sound_path') ? \Session::get('ve'.$i.'_sound_path') : !empty($s->{'ve'.$i.'_sound_path'})? $s->{'ve'.$i.'_sound_path'} : '';
                        \Session::forget('ve'.$i.'_sound_path');
                        ?>
                        <input type="file" accept='audio/*' id="sound_file-field" name="ve{{$i}}_sound_file" style="margin-left:95px; margin-top:8px;" value="" />

                    @if (file_exists(public_path($ve_sound_path)))
                        <audio src="{{ url('/').$ve_sound_path.'?v='.session('version')}}"
                            preload controls style="margin-left:95px;">
                        </audio>
                        <input type="hidden" name="ve{{$i}}_sound_path" value="{{$ve_sound_path}}"/>
                    @else
                        <audio src=""1
                            preload controls style="margin-left:95px; @if(is_null($ve_sound_path)) display:none; @endif">
                        </audio>
                        <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                        <input type="hidden" name="ve{{$i}}_sound_path" value="{{$ve_sound_path}}"/>
                    @endif
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
                    <option id="type-option" value="{{$key}}" @if($key ==old('content_group',$stetho_sound->type)) selected @endif>
                        {{$value}}
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
                <input type="number" id="sort-field" name="sort" class="form-control" value="{{ old("sort",$stetho_sound->sort) }}" />
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
                    value="{{ old("coordinate",$stetho_sound->coordinate) }}" />
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
                    @foreach($superintendents as $s)
                    <option id="user_id-option" value="{{$s->id}}" @if($s->id==old('user_id',$stetho_sound->user_id))
                        selected @endif>{{$s->name}}</option>
                    @endforeach
                </select>
                @if($errors->has("user_id"))
                <span class="help-block">{{ $errors->first("user_id") }}</span>
                @endif
            </div>
        </div>
    </div><!-- END: 上部フォーム -->
    <!-- 監修コメント -->
    <hr />
    <h4>監修コメント</h4>
    <div class="well">
        <input type="hidden" id="stetho_sound_id" value="{{ $stetho_sound->id }}" />
        <div id="comments_area">
            @foreach($stetho_sound->comments()->get() as $comment)
            <div class="comment_item" style="padding-bottom: 1em; margin-bottom: 1em; border-bottom: 1px solid gray;"
                data-id="{{$comment->id}}">
                <p>
                <div style="float:left;">{{ $comment->created_at}}&nbsp;&nbsp;<strong>{{ $comment->user->name}}</strong>
                </div>
                @if(\Auth::user()->role == App\User::$ROLE_ADMIN || (\Auth::user()->role != App\User::$ROLE_ADMIN &&
                \Auth::user()->id == $comment->id))
                <div style="float:right;"><a class="btn btn-sm btn-default edit_comment__btn">編集</a>&nbsp;<a
                        class="btn btn-sm btn-danger remove_comment__btn">削除</a></div>
                @endif
                <div style="clear: both;"></div>
                </p>
                <p class="comment_text" data-id="{{$comment->id}}">{{ $comment->text }}</p>
                <textarea name="comment" class="form-control" style="display: none;">{{ $comment->text }}</textarea>
                <button type="button" class="btn btn-sm btn-primary update_comment__btn"
                    style="margin-top: 4px; display: none;">更新</button>
            </div>
            @endforeach
        </div>
        <textarea id="comments__textarea" name="comment" class="form-control"></textarea>
        <button type="button" id="add_comments__btn" class="btn btn-primary" style="margin-top: 4px;"
            disabled>コメント保存</button>
        <span>※コメントはすぐに保存されます</span>
    </div><!-- END: 監修コメント -->
    <hr />
    <!-- ステータス -->
    <div class="well">
        <div class="form-group @if($errors->has('status')) has-error @endif">
            <label for="status-field" class="col-sm-2 control-label">ステータス</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" id="status-field" name="status" value='0'
                            {{ old('status',$stetho_sound->status)==0 ? "checked" : "" }} />監修中
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="status-field" name="status" value='1'
                            {{ old('status',$stetho_sound->status)==1 ? "checked" : "" }} />監修済
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="status-field" name="status" value='2'
                            {{ old('status',$stetho_sound->status)==2 ? "checked" : "" }} />公開中
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" id="status-field" name="status" value='3'
                            {{ old('status',$stetho_sound->status)==3 ? "checked" : "" }} />公開中(New)
                    </label>
                    <input style="width: 80px;display:inline" type="hidden" id="disp_order-field" name="disp_order"
                        class="form-control" value="{{ old('disp_order',$stetho_sound->disp_order) }}"
                        placeholder="順番" />
                    {{-- @if($errors->has("disp_order"))
                    <span class="help-block">{{ $errors->first("disp_order") }}</span>
                    @endif --}}
                </div>
            </div>
        </div>
        <!-- グループ属性 -->
        <?php
            $old_exam_groups = $stetho_sound->exam_groups()->get()->pluck("id")->toArray();
        ?>
        <div class="form-group @if($errors->has('exam_group')) has-error @endif">
            <label for="exam_group-field" class="col-sm-2 control-label">グループ属性</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label class="col-sm-2">
                        <input type="radio" id="" name="group_attr" value='0'
                            {{ old("group_attr",$old_exam_groups?0:"")==0 ? "checked" : "" }} />あり
                    </label>
                    <div class="col-sm-6">
                        <select class="form-control" name="exam_group[]" id="exam_group-field" multiple>
                            @foreach($exam_groups as $exam_group)
                            <option value="{{$exam_group->id}}" @if(old("exam_group",$old_exam_groups)){{ (in_array($exam_group->id, old("exam_group",$old_exam_groups)) ? "selected":"") }}@endif>{{$exam_group->name}}</option>
                            @endforeach
                        </select>
                        <p style="color:#B8B7B7;font-family:inherit;white-space:nowrap;">グループ属性の削除の際は”Ctrl+F”検索で該当箇所の特定が出来ます。</p>
                    </div>
                    <label class="col-sm-3 col-3 pl-60">
                        <input type="radio" id="" name="group_attr" value='1'
                            {{ old("group_attr",!$old_exam_groups?1:"")==1 ? "checked" : "" }} />なし
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
            <a class="btn btn-default" href="{{ route('admin.iPax.index') }}?page={{ $page }}">キャンセル</a>
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