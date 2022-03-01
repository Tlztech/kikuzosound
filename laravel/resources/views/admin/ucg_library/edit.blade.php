@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <h1>心エコーライブラリ編集</h1>
</div>

<form class="form-horizontal" action="{{ route('admin.ucg_library.update', $stetho_sound->id) }}" method="POST"
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
         <div class="form-group @if($errors->has('lib_video_file')) has-error @endif">
            <label for="lib-image-file-field" class="col-sm-2 control-label">動画ファイル</label>
            <div class="col-sm-10">
                <input type="hidden" name="lib_type" value="6" />
                <input type="hidden" name="lib_video_file" />
                <input type="file" id="lib_video-file-input" name="lib_video_file" class="form-control well well-sm"
                    accept="video/mp4,video/x-m4v,video/*" value="{{ old("lib_video_file") }}" />
                    <?php
                $video_path = \Session::has('lib_video_file_path') ? \Session::get('lib_video_file_path') : $stetho_sound->video_path;
                \Session::forget('lib_video_file_path');
                ?>
                @if (file_exists(public_path($video_path)))
                    <video id="lib_video_file-video" controls src="@if($video_path){{ url('/').$video_path.'?v='.session('version') }} @endif" style="@if(empty($video_path)) display:none; @endif">
                    <input type="hidden" name="lib_video_file_path" value="{{$video_path}}"/>
                @else
                    <video id="lib_video_file-video" controls src=""></video>
                    <p id="audio-error"><strong>ファイルが存在しません</strong> </p>
                    <input type="hidden" name="lib_video_file_path" value="{{$video_path}}"/>
                @endif

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
        <!-- 心電図説明 (JP) -->
        <div class="form-group @if($errors->has('description')) has-error @endif">
            <label for="description-field" class="col-sm-2 control-label">心エコーの説明 (JP)</label>
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
            <label for="description-field" class="col-sm-2 control-label">心エコーの説明 (EN)</label>
            <div class="col-sm-10">
                <textarea id="description-en-field" name="description_en"
                    class="form-control">{{ old('description_en',$stetho_sound->description_en) }}</textarea>
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
            <a class="btn btn-default" href="{{ route('admin.ucg_library.index') }}?page={{ $page }}">キャンセル</a>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div style="padding: 3em;">&nbsp;</div>

</form>
@endsection