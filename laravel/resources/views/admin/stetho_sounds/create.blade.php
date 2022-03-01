@extends('admin.layouts.app')

@section('content')
<div class="page-header">
  <h1>聴診音ライブラリ追加</h1>
</div>

<form class="form-horizontal" action="{{ route('admin.stetho_sounds.store') }}" method="POST" enctype="multipart/form-data">
  {{ csrf_field() }}
  <h4>基本情報</h4>
  <div class="well">
    @include('admin.common.form_errors')
    @if(Session::has('error_desc'))
      <div class="alert alert-danger"><ul><li> {!! session('error_desc') !!}</li></ul></div>
    @endif
    <!-- 聴診音ファイル -->
    <div class="form-group @if($errors->has('sound_file')) has-error @endif">
      <label for="sound_file-field" class="col-sm-2 control-label">音源ファイル</label>
      <div class="col-sm-10">
        <input type="hidden" name="lib_type" value="0"/>
        <input type="hidden" name="sound_file"/>
        <input type="file" accept='audio/*,video/*' id="sound_file-field" name="sound_file" class="form-control well well-sm" value="{{ old("sound_file") }}"/>
        <?php
          $sound_path = null;
          if (count($errors)) {
              $sound_path = \Session::has('sound_path') ? \Session::get('sound_path') : null;
          } else {
              Session::forget('sound_path');
          }
        ?>
        <audio src="{{ $sound_path }}" preload controls style="@if(is_null($sound_path)) display:none; @endif"></audio>
        <input type="hidden" name="sound_path" value="@if(!is_null($sound_path)){{ $sound_path }}@endif" />
        @if($errors->has("sound_file"))
        <span class="help-block">{{ $errors->first("sound_file") }}</span>
        @endif
      </div>
      <div id="is_video_show" style="display:none;">
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
    </div>
    <!-- タイトル -->
    <div class="form-group @if($errors->has('title')) has-error @endif">
      <label for="title-field" class="col-sm-2 control-label">タイトル (JP)</label>
      <div class="col-sm-10">
        <input type="text" id="title-field" name="title" class="form-control" value="{{ old("title") }}"/>
        @if($errors->has("title"))
        <span class="help-block">{{ $errors->first("title") }}</span>
        @endif
      </div>
    </div>
    <!-- タイトル (EN) -->
    <div class="form-group @if($errors->has('title_en')) has-error @endif">
      <label for="title-field" class="col-sm-2 control-label">タイトル (EN)</label>
      <div class="col-sm-10">
        <input type="text" id="title-field" name="title_en" class="form-control" value="{{ old("title_en") }}"/>
        @if($errors->has("title_en"))
        <span class="help-block">{{ $errors->first("title_en") }}</span>
        @endif
      </div>
    </div>
    <!-- 聴診音種別 -->
    <div class="form-group @if($errors->has('type')) has-error @endif">
      <label for="type-field" class="col-sm-2 control-label">聴診音種別</label>
      <div class="col-sm-10">
        <select id="type-selector" name="type" class="form-control">
        <?php $type_strings = [ 1 => '肺音', 2 => '心音', 3 => '腸音', 9 => 'その他' ]; ?>
        @foreach($type_strings as $key => $value)
          <option id="type-option" value="{{$key}}" @if($key==old('type')) checked @endif>{{$value}}</option>
        @endforeach
        </select>
        @if($errors->has("type"))
        <span class="help-block">{{ $errors->first("type") }}</span>
        @endif
      </div>
    </div>
    <!-- 聴診部位 -->
    <div class="form-group @if($errors->has('area')) has-error @endif">
      <label for="area-field" class="col-sm-2 control-label">聴診部位 (JP)</label>
      <div class="col-sm-10">
        <input type="text" id="area-field" name="area" class="form-control" value="{{ old("area") }}"/>
        @if($errors->has("area"))
        <span class="help-block">{{ $errors->first("area") }}</span>
        @endif
      </div>
    </div>
    <!-- 聴診部位 (EN) -->
    <div class="form-group @if($errors->has('area_en')) has-error @endif">
      <label for="area-field" class="col-sm-2 control-label">聴診部位 (EN)</label>
      <div class="col-sm-10">
        <input type="text" id="area-field" name="area_en" class="form-control" value="{{ old("area_en") }}"/>
        @if($errors->has("area_en"))
        <span class="help-block">{{ $errors->first("area_en") }}</span>
        @endif
      </div>
    </div>
    <!-- 加工方法 -->
    <div class="form-group @if($errors->has('conversion_type')) has-error @endif">
      <label for="conversion_type-field" class="col-sm-2 control-label">加工方法</label>
      <div class="col-sm-10">
        <select id="conversion_type-selector" name="conversion_type" class="form-control">
        <?php $type_strings = [ 0 => '採取オリジナル', 1 => '加工音', 2 => '人工音' ]; ?>
        @foreach($type_strings as $key => $value)
          <option id="conversion_type-option" value="{{$key}}" @if($key==1) selected @endif @if($key==old('conversion_type')) checked @endif>{{$value}}</option>
        @endforeach
        </select>
        @if($errors->has("conversion_type"))
        <span class="help-block">{{ $errors->first("conversion_type") }}</span>
        @endif
      </div>
    </div>
    <!-- 正常/異常 -->
    <div class="form-group @if($errors->has('is_normal')) has-error @endif">
      <label for="is_normal-field" class="col-sm-2 control-label">正常/異常</label>
      <div class="col-sm-10">
        <div class="radio">
          <label>
            <input type="radio" id="is_normal-field" name="is_normal" value='1' {{ old("is_normal") ? "" : "checked" }}/>正常
          </label>
          <label>
            <input type="radio" id="is_normal-field" name="is_normal" value='0' {{ old("is_normal") ? "checked" : "" }}/>異常
          </label>
        </div>
        @if($errors->has("is_normal"))
        <span class="help-block">{{ $errors->first("is_normal") }}</span>
        @endif
      </div>
    </div>
    <!-- 代表疾患 -->
    <div class="form-group @if($errors->has('disease')) has-error @endif">
      <label for="disease-field" class="col-sm-2 control-label">代表疾患 (JP)</label>
      <div class="col-sm-10">
        <input type="text" id="disease-field" name="disease" class="form-control" value="{{ old("disease") }}"/>
        @if($errors->has("disease"))
        <span class="help-block">{{ $errors->first("disease") }}</span>
        @endif
      </div>
    </div>
    <!-- 代表疾患  (EN)-->
    <div class="form-group @if($errors->has('disease_en')) has-error @endif">
      <label for="disease-field" class="col-sm-2 control-label">代表疾患 (EN)</label>
      <div class="col-sm-10">
        <input type="text" id="disease-field" name="disease_en" class="form-control" value="{{ old("disease_en") }}"/>
        @if($errors->has("disease_en"))
        <span class="help-block">{{ $errors->first("disease_en") }}</span>
        @endif
      </div>
    </div>
    <!-- 音源提供者等 -->
    <div class="form-group @if($errors->has('sub_description')) has-error @endif">
      <label for="sub_description-field" class="col-sm-2 control-label">音源・提供者等 (JP)</label>
      <div class="col-sm-10">
        <input style="max-width: 310px;" type="text" id="sub_description-field" name="sub_description" class="form-control" value="{{ old("sub_description") }}" placeholder="日本語のみの場合は30文字まで"/>
        @if($errors->has("sub_description"))
        <span class="help-block">{{ $errors->first("sub_description") }}</span>
        @endif
      </div>
    </div>
    <!-- 音源提供者等 (EN) -->
    <div class="form-group @if($errors->has('sub_description_en')) has-error @endif">
      <label for="sub_description-field" class="col-sm-2 control-label">音源・提供者等 (EN)</label>
      <div class="col-sm-10">
        <input style="max-width: 310px;" type="text" id="sub_description-field" name="sub_description_en" class="form-control" value="{{ old("sub_description_en") }}" placeholder="日本語のみの場合は30文字まで"/>
        @if($errors->has("sub_description_en"))
        <span class="help-block">{{ $errors->first("sub_description_en") }}</span>
        @endif
      </div>
    </div>
    
    <!-- 音の説明 -->
    <div class="form-group @if($errors->has('description')) has-error @endif">
      <label for="description-field" class="col-sm-2 control-label">音の説明 (JP)</label>
      <div class="col-sm-10">
        <textarea id="description-field" name="description" class="form-control">{{ old("description") }}</textarea>
        @if($errors->has("description"))
        <span class="help-block">{{ $errors->first("description") }}</span>
        @endif
      </div>
    </div>
     <!-- 音の説明(EN) -->
    <div class="form-group @if($errors->has('description_en')) has-error @endif">
      <label for="description-field" class="col-sm-2 control-label">音の説明 (EN)</label>
      <div class="col-sm-10">
        <textarea id="description-en-field" name="description_en" class="form-control">{{ old("description_en") }}</textarea>
        @if($errors->has("description_en"))
        <span class="help-block">{{ $errors->first("description_en") }}</span>
        @endif
      </div>
    </div>
    <!-- 音の説明(画像) -->

    <!-- JP -->
    <div id="stetho_sound_images_form" class="form-group @if($errors->has('image_files')) has-error @endif">
      <label for="image_files-field" class="col-sm-2 control-label">説明画像 (JP)</label>
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
          <a id="add_images__btn" class="btn btn-sm btn-primary" data-field="stetho" data-lang="ja">画像を追加</a>
        </div>
        @if($errors->has("image_files"))
        <span class="help-block">{{ $errors->first("image_files") }}</span>
        @endif
      </div>
    </div>

    <!-- EN -->
    <div id="stetho_sound_images_form" class="form-group @if($errors->has('image_files')) has-error @endif">
      <label for="image_files-field" class="col-sm-2 control-label">説明画像 (EN)</label>
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
                <input type="text" name="image_titles[]" class="form-control" value="{{ $image['title'] or "" }}" />
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
          <a id="add_images__btn" class="btn btn-sm btn-primary" data-field="stetho" data-lang="en">画像を追加</a>
        </div>
        @if($errors->has("image_files"))
        <span class="help-block">{{ $errors->first("image_files") }}</span>
        @endif
      </div>
    </div>
    <!-- 監修者 -->
    <div class="form-group @if($errors->has('user_id')) has-error @endif">
      <label for="user_id-field" class="col-sm-2 control-label">監修者</label>
      <div class="col-sm-10">
        <select id="user_id-selector" name="user_id" class="form-control">
        @foreach($superintendents as $superintendent)
          <option id="user_id-option" value="{{$superintendent->id}}" @if($key==old('user_id')) checked @endif>{{$superintendent->name}}</option>
        @endforeach
        </select>
        @if($errors->has("user_id"))
        <span class="help-block">{{ $errors->first("user_id") }}</span>
        @endif
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
            <input type="radio" id="status-field" name="status" value='0' {{ old("status",0)==0 ? "checked" : "" }}/>監修中
          </label>
          &nbsp;&nbsp;&nbsp;
          <label>
            <input type="radio" id="status-field" name="status" value='1' {{ old("status")==1 ? "checked" : "" }}/>監修済
          </label>
          &nbsp;&nbsp;&nbsp;
          <label>
            <input type="radio" id="status-field" name="status" value='2' {{ old("status")==2 ? "checked" : "" }}/>公開中
          </label>
           &nbsp;&nbsp;&nbsp;
          <label>
            <input type="radio" id="status-field" name="status" value='3' {{ old("status")==3 ? "checked" : "" }}/>公開中(New)
          </label>
          <input style="width: 80px;display:inline" type="hidden" id="disp_order-field" name="disp_order" class="form-control" value="{{ $count+1 }}" placeholder="順番"/>
          {{-- @if($errors->has("disp_order"))
          <span class="help-block">{{ $errors->first("disp_order") }}</span>
          @endif --}}
        </div>
{{-- <div>
【「公開中(New)」の数値入力(順番)について】<br>
・「試聴音」の順番です(ライブラリ等には影響しません)<br>
・数値の小さい順(小さい方が上)になります<br>
・同じ数値の場合は今まで通り(新しい順)になります<br>
・マイナス指定も可能です(全てデフォルトは0)<br>
・指定できる範囲は-99999(5桁)～999999(6桁)です
</div> --}}
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
      <a class="btn btn-default" href="{{ route('admin.stetho_sounds.index') }}">キャンセル</a>
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
