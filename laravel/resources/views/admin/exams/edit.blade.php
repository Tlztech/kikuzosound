@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <h1>EXAM編集する</h1>
</div>

<div class="row">
    <div class="col-md-12">
        @include('admin.common.form_errors')

        <form action="{{ route('admin.exams.update', $exams->id) }}" method="POST" enctype="multipart/form-data"
            class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group @if($errors->has('exam_name_jp')) has-error @endif">
                <label for="exam_name_jp-field" class="col-sm-2 control-label">試験名 (JP)</label>
                <div class="col-sm-10">
                    <input type="text" id="exam_name_jp-field" name="exam_name_jp" class="form-control"
                        value="{{old('exam_name_jp',$exams->name_jp)}}" />
                    @if($errors->has("exam_name_jp"))
                    <span class="help-block">{{ $errors->first("exam_name_jp") }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group @if($errors->has('exam_name')) has-error @endif">
                <label for="exam_name-field" class="col-sm-2 control-label">試験名 (EN)</label>
                <div class="col-sm-10">
                    <input type="text" id="exam_name-field" name="exam_name" class="form-control"
                        value="{{old('exam_name',$exams->name)}}" />
                    @if($errors->has("exam_name"))
                    <span class="help-block">{{ $errors->first("exam_name") }}</span>
                    @endif
                </div>
            </div>
            <?php
                $old_exam_groups = $exams->exam_groups()->get()->pluck("id")->toArray();
            ?>
            <div class="form-group">
                <label for="combi-field" class="col-sm-2 control-label">組合せ</label>
                <div class="col-sm-5">
                    <select class="form-control" name="exam_group[]" id="exam_group-field" multiple>
                        @foreach($exam_groups as $exam_group)
                        <option value="{{$exam_group->id}}" @if(old("exam_group",$old_exam_groups)){{ (in_array($exam_group->id, old("exam_group",$old_exam_groups)) ? "selected":"") }}@endif>{{$exam_group->name}}</option>
                        @endforeach
                    </select>
                    <p style="color:#B8B7B7;font-family:inherit;font-size: 12px;word-break: break-all;">グループ属性の削除の際は”Ctrl+F”検索で該当箇所の特定が出来ます。</p>
                    @if($errors->has("exam_group"))
                    <span class="help-block">{{ $errors->first("exam_group") }}</span>
                    @endif
                </div>
                <div class="col-sm-5">
                    <select class="form-control" name="quiz_pack">
                        <option value="">クイズパック</option>
                        @foreach($quiz_packs as $quiz_pack)
                        <option value="{{$quiz_pack->id}}" data-description="{{$quiz_pack->title}}" @if(old('quiz_pack',$exams->quiz_pack_id)==$quiz_pack->
                            id) selected @endif>
                            {{$quiz_pack->title_en}}</option>
                        @endforeach
                    </select>
                    @if($errors->has("quiz_pack"))
                    <span class="help-block">{{ $errors->first("quiz_pack") }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group @if($errors->has('destination_email')) has-error @endif">
                <label for="destination-field" class="col-sm-2 control-label">結果送付先</label>
                <div class="col-sm-10">
                    <input type="text" id="destination_email-field" name="destination_email" class="form-control"
                        value="{{old('destination_email',$exams->result_destination_email)}}" />
                    @if($errors->has("destination_email"))
                    <span class="help-block">{{ $errors->first("destination_email") }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group @if($errors->has('enabled')) has-error @endif">
                <label for="enabled-field" class="col-sm-2 control-label">EXAM公開</label>
                <div class="col-sm-10">
                    <label class="radio-inline">
                        <input type="radio" name="enabled" id="enabled" value="1" @if(old('enabled',$exams->is_publish)==1) checked
                        @endif> 公開
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="enabled" id="disabled" value="0" @if(old('enabled',$exams->is_publish)==0) checked
                        @endif> 非公開
                    </label>
                    @if($errors->has("enabled"))
                    <span class="help-block">{{ $errors->first("enabled") }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group for-buttons">
                <div class="col-sm-8 col-sm-offset-2">
                    <a class="btn btn-default pull-left" href="{{ route('admin.exams.index') }}"
                        style="margin-right: .5em;">キャンセル</a>
                        <button class="btn btn-primary pull-left" type="submit">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection