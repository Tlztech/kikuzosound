@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <h1>触診ライブラリ追加</h1>
</div>

<form class="form-horizontal" action="{{ route('admin.palpation_library.store') }}" method="POST"
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
        <div class="form-group @if($errors->has('sound_file')) has-error @endif" style="margin-bottom: 0px;">
            <label for="sound_file-field" class="col-sm-2 control-label">音源ファイル</label>
            <div class="col-sm-10">
                <input type="hidden" name="lib_type" value="2" />
                <input type="hidden" name="sound_file" />
                <input type="file" accept='audio/*' id="sound_file-field" name="sound_file"
                    class="form-control well well-sm" value="{{ old("sound_file") }}" />
                <?php
                $sound_path = null;
                if ( count($errors) ) {
                    $sound_path = \Session::has('sound_path') ? \Session::get('sound_path') : null;
                }
                else {
                    Session::forget('sound_path');
                }
                ?>
                <audio src="{{ $sound_path }}" preload controls
                    style="@if(is_null($sound_path)) display:none; @endif"></audio>
                <input type="hidden" name="sound_path" value="@if(!is_null($sound_path)){{ $sound_path }}@endif" />
                @if($errors->has("sound_file"))
                <span class="help-block">{{ $errors->first("sound_file") }}</span>
                @endif
            </div>
        </div>
        <div id="is_video_show" style="margin-bottom:50px; display:none;">
            <label class="col-sm-2 control-label">ビデオ</label>
            <div class="col-sm-10">
                <div class="radio">
                    <label>
                        <input type="radio" id="is_video_show_1" name="is_video_show" value='1'/>公開
                    </label>
                    <label>
                        <input type="radio" id="is_video_show_0" name="is_video_show" value='0' checked/>非公開
                    </label>
                </div>
            </div>
        </div>
        <!-- タイトル -->
        <!-- 聴診音ファイル -->
        <div class="form-group @if($errors->has('lib_video_file')) has-error @endif">
            <label for="lib-image-file-field" class="col-sm-2 control-label">動画ファイル</label>
            <div class="col-sm-10">
                <input type="hidden" name="lib_video_file" />
                <input type="file" id="lib_video-file-input" name="lib_video_file" class="form-control well well-sm"
                    accept="video/mp4,video/x-m4v,video/*" value="{{ old("lib_video_file") }}" />
                <?php
                  $lib_video_file_path = null;
                  if ( count($errors) ) {
                    $lib_video_file_path = \Session::has('lib_video_file_path') ? \Session::get('lib_video_file_path') : null;
                  }
                  else {
                    Session::forget('lib_video_file_path');
                  }
                ?>
                <video id="lib_video_file-video" controls src="@if($lib_video_file_path){{$lib_video_file_path }} @endif" style="@if(is_null($lib_video_file_path)) display:none; @endif"></video>
                <input type="hidden" name="lib_video_file_path"
                    value="@if(!is_null($lib_video_file_path)){{ $lib_video_file_path }}@endif" />
                @if($errors->has("lib_video_file"))
                <span class="help-block">{{ $errors->first("lib_video_file") }}</span>
                @endif
                @if($errors->has("lib_video_file_path"))
                <span class="help-block">{{ $errors->first("lib_video_file_path") }}</span>
                @endif
            </div>
        </div>
        <!-- タイトル -->
        <div class="form-group @if($errors->has('title')) has-error @endif">
            <label for="title-field" class="col-sm-2 control-label">タイトル（JP)</label>
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
        <!-- 心電図説明 (JP) -->
        <div class="form-group @if($errors->has('description')) has-error @endif">
            <label for="description-field" class="col-sm-2 control-label">触診説明 (JP)</label>
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
            <label for="description-field" class="col-sm-2 control-label">触診説明 (EN)</label>
            <div class="col-sm-10">
                <textarea id="description-en-field" name="description_en"
                    class="form-control">{{ old("description_en") }}</textarea>
                @if($errors->has("description_en"))
                <span class="help-block">{{ $errors->first("description_en") }}</span>
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
            <a class="btn btn-default" href="{{ route('admin.palpation_library.index') }}">キャンセル</a>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div style="padding: 3em;">&nbsp;</div>

</form>

<script type="text/javascript">
$('#sortable_tbody').sortable();
$('#sortable_tbody').disableSelection();
</script>

@endsection