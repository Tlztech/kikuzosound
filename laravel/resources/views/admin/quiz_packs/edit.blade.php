@extends('admin.layouts.app')

@section('content')
<div class="page-header">
  <h1>クイズパック編集</h1>
</div>

<div class="row well">
  <div class="col-md-12">
    @include('admin.common.form_errors')
    <form class="form-horizontal" action="{{ route('admin.quiz_packs.update', $quiz_pack->id) }}" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="_method" value="PUT">
      {{ csrf_field() }}

      <!-- 強制更新 -->
      @if($errors->has("is_force_update"))
        <div class="form-group has-error">
          <label for="is_force_update-field" class="col-sm-2 control-label">強制更新</label>
          <div class="col-sm-10">
            <input type="checkbox" name="is_force_update" class="well well-sm" value="1"/>
          </div>
        </div>
      @endif
      <input type="hidden" name="updated_at" value="{{old('updated_at',$quiz_pack->updated_at)}}"/>

      <!-- タイトル -->
      <div class="form-group @if($errors->has('title')) has-error @endif">
        <label for="title-field" class="col-sm-2 control-label" >タイトル (JP)</label>
        <div class="col-sm-10"> 
          <input type="text" id="title-field" name="title" class="form-control" value="{{ old('title',$quiz_pack->title) }}"/>
          @if($errors->has("title"))
          <span class="help-block">{{ $errors->first("title") }}</span>
          @endif
        </div>
      </div>

      <!-- タイトル(EN) -->
      <div class="form-group @if($errors->has('title_en')) has-error @endif">
        <label for="title_en-field" class="col-sm-2 control-label" >タイトル (EN)</label>
        <div class="col-sm-10"> 
          <input type="text" id="title_en-field" name="title_en" class="form-control" value="{{ old('title_en',$quiz_pack->title_en) }}"/>
          @if($errors->has("title_en"))
          <span class="help-block">{{ $errors->first("title_en") }}</span>
          @endif
        </div>
      </div>

      <!-- タイトルの色 -->
      <div class="form-group @if($errors->has('title_color')) has-error @endif">
        <label for="title_color-field" class="col-sm-2 control-label">タイトルの色</label>
        <div class="col-sm-10"> 
          <input type="text" id="title_color-field" name="title_color" class="jscolor" value="{{ old('title_color',$quiz_pack->title_color) }}"/>
          @if($errors->has("title_color"))
          <span class="help-block">{{ $errors->first("title_color") }}</span>
          @endif
        </div>
      </div>

      <!-- 説明 -->
      <div class="form-group @if($errors->has('description')) has-error @endif">
       <label for="description-field" class="col-sm-2 control-label">説明 (JP)</label>
       <div class="col-sm-10">
        <input type="text" id="description_en-field" name="description" class="form-control" value= "{{ old('description',$quiz_pack->description) }}"/>
          @if($errors->has("description"))
          <span class="help-block">{{ $errors->first("description") }}</span>
          @endif
        </div>
      </div>

      <!-- 説明(EN) -->
      <div class="form-group @if($errors->has('description_en')) has-error @endif">
       <label for="description_en-field" class="col-sm-2 control-label">説明 (EN)</label>
       <div class="col-sm-10">
        <input type="text" id="description_en-field" name="description_en" class="form-control" value= "{{ old('description_en',$quiz_pack->description_en) }}"/>
          @if($errors->has("description_en"))
          <span class="help-block">{{ $errors->first("description_en") }}</span>
          @endif
        </div>
      </div>


      <!-- アイコン -->
      <div class="form-group @if($errors->has('bg_img') || $errors->has('icon_path')) has-error @endif">
        <label for="bg_img-field" class="col-sm-2 control-label">アイコン</label>
        <div class="col-sm-10">
          <table>
            <tr>
              <td>
                <input type="file" id="quiz_pack_image-file-input" class="well well-sm" id="bg_img-field" name="bg_img" accept=".jpg,.png,image/jpeg,image/png" value=""/>
                <?php
                  $icon_path = \Session::has('tmp_quiz_pack_image_path') ? \Session::get('tmp_quiz_pack_image_path') : $quiz_pack->icon_path;
                  \Session::forget('tmp_quiz_pack_image_path');
                ?>
                <img id="quiz_pack_image-img" src="{{ $icon_path.'?v='.date('YmdHis', strtotime($quiz_pack->updated_at)) }}" style="height:220px;@if(empty($icon_path))display:none;@endif"/>
                <input type="hidden" name="icon_path" value="@if(!empty($icon_path)){{ $icon_path }}@endif" />
                @if($errors->has("bg_img"))
                  <span class="help-block">{{ $errors->first("bg_img") }}</span>
                @endif
                @if($errors->has("icon_path"))
                  <span class="help-block">{{ $errors->first("icon_path") }}</span>
                @endif
                <br /><input id="quiz_pack_image_remove-btn" type="button" value="画像を削除" class="btn btn-default" style="@if(empty($icon_path))display:none;@endif" />
              </td>
            </tr>
          </table>
        </div>
      </div>

      <!-- 出題形式 -->
      <div class="form-group @if($errors->has('quiz_order_type')) has-error @endif">
        <label for="quiz_order_type-field" class="col-sm-2 control-label">出題形式</label>
        <div class="col-sm-10"> 
          <select class="form-control quiz_order_type" id="fixed_status"  name="quiz_order_type">
            <option id="quiz_order_type-field_1" value="0"{{ old('quiz_order_type',$quiz_pack->quiz_order_type) ? "" : "selected" }}>固定</option>
            <option id="quiz_order_type-field_2" value="1"{{ old('quiz_order_type',$quiz_pack->quiz_order_type) ? "selected" : "" }}>ランダム</option>
          </select>
          @if($errors->has("quiz_order_type"))
          <span class="help-block">{{ $errors->first("quiz_order_type") }}</span>
          @endif
        </div>
      </div>

      <!-- 出題数 -->
      <div class="form-group @if($errors->has('max_quiz_count')) has-error @endif">
        <label for="max_quiz_count-field" class="col-sm-2 control-label">出題数</label>
        <div class="col-sm-10"> 
          <input type="number" id="max_quiz_count-field" name="max_quiz_count" class="form-control max_quiz_count" value="{{ old('max_quiz_count',$quiz_pack->max_quiz_count) }}"/>
          @if($errors->has("max_quiz_count"))
          <span class="help-block">{{ $errors->first("max_quiz_count") }}</span>
          @endif
        </div>
      </div>

      <!-- クイズ -->
      <div class="form-group @if($errors->has('quizzes')) has-error @endif">
        <label for="quizzes-field" class="col-sm-2 control-label" >クイズ</label>
        <div class="col-sm-10"> 
          <table class="table">   
            <tbody id="quizzes__tbody">
              <?php //$old_quiz_ids = old('quizzes'); ?>
              <?php //$old_quizzes = $old_quiz_ids ? App\Quiz::whereIn('id', old('quizzes'))->get() : $quiz_pack->quizzes; ?>
              @foreach( $quiz_pack_quizzes as $quiz)
              <tr>
                <td>{{$quiz->title_en}}</td>
                <td class="text-right">
                  <input type="hidden" name="quizzes[]" value="{{$quiz->id}}"/>
                  <a id="remove_quiz__btn" class="btn btn-danger" data-id="{{$quiz->id}}" data-title="{{$quiz->title_en}}">削除</a>
                  <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- クイズ -->
      <div class="form-group add_quiz"> 
        <div class="col-sm-offset-8 col-sm-4"> 
          <div class="col-sm-12"> 
            <div class="col-sm-8"> 
             <select id="quiz_add__selector" class="form-control">
              @foreach($select_quizzes as $quiz)
                <option id="quiz_add__opt" value="{{$quiz->id}}">{{$quiz->title_en}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-sm-4"> 
            <button id="add_quiz__btn" class="btn btn-primary" type="button" >追加</button>
          </div>
        </div>
      </div> 

      <div class="form-group @if($errors->has('lang')) has-error @endif">
        <label for="lang-field" class="col-sm-2 control-label">公開</label>
        <div class="col-sm-10"> 
          <div class="radio">
            <label>
              <input type="radio" id="lang-field" name="lang" value="JP" {{ old("lang", $quiz_pack->lang)=="JP" ? "checked" : "" }}/>JP
            </label>
            <label>
              <input type="radio" id="lang-field" name="lang" value="EN" {{ old("lang", $quiz_pack->lang)=="JP" ? "" : "checked" }}/>EN
            </label>
          </div>
        </div>
        @if($errors->has("lang"))
        <span class="help-block">{{ $errors->first("lang") }}</span>
        @endif
      </div>

      <!-- 公開 -->
      <div class="form-group @if($errors->has('is_public')) has-error @endif">
        <label for="is_public-field" class="col-sm-2 control-label">公開</label>
        <div class="col-sm-10"> 
          <div class="radio">
            <label>
              <input type="radio" id="is_public-field" name="is_public" value="1" {{ old("is_public", $quiz_pack->is_public)==1 ? "checked" : "" }}/>公開
            </label>
            <label>
              <input type="radio" id="is_public-field" name="is_public" value="0" {{ old("is_public", $quiz_pack->is_public)==1 ? "" : "checked" }}/>非公開
            </label>
          </div>
        </div>
        @if($errors->has("is_public"))
        <span class="help-block">{{ $errors->first("is_public") }}</span>
        @endif
      </div>
      <!-- グループ属性 -->
      <?php
          $old_exam_groups = $quiz_pack->exam_groups()->get()->pluck("id")->toArray();
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
                      <p style="color:#B8B7B7;font-family:inherit;">グループ属性の削除の際は”Ctrl+F”検索で該当箇所の特定が出来ます。</p>
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
      <div class="form-group"> 
        <div class="col-sm-offset-5 col-sm-10"> 
          <a class="btn btn-default" href="{{ route('admin.quiz_packs.index') }}">キャンセル</a>
          <button class="btn btn-primary" type="submit">保存</button>
        </div>
      </div>  
    </form>
  </div>
</div>

<script src="/js/jscolor/jscolor.min.js"></script>
<script type="text/javascript">
  $('#quizzes__tbody').sortable();
  $('#quizzes__tbody').disableSelection();
</script>

@endsection
