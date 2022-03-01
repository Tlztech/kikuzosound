@extends('admin.layouts.app')
@section('content')
<div class="page-header">
    <h1>お知らせ管理</h1>
</div>
<div class="row">
    <div class="col-md-10">
        @include('admin.common.form_errors')
        <form class="form-horizontal" action="{{ route('admin.informations.update', $information->id) }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            {{-- <div class="form-group @if($errors->has('title_en')) has-error @endif">
                <label for="title-field" class="col-sm-2 control-label">日付 (EN)</label>
                <div class="col-sm-10">
                    <input type="text" id="title-field" name="title_en" class="form-control" value="{{$information->title_en}}" placeholder="日付(EN)"/>
                    @if($errors->has("title_en"))
                    <span class="help-block">{{ $errors->first("title_en") }}</span>
                    @endif
                </div>
            </div> --}}
            <div class="form-group @if($errors->has('description')) has-error @endif">
                <label for="description_en-field" class="col-sm-2 control-label">お知らせ (JP)</label>
                <div class="col-sm-10">
                    <textarea type="text" id="description_en-field" name="description" class="form-control" placeholder="お知らせ (JP)" >{{$information->description}}</textarea>
                    @if($errors->has("description"))
                    <span class="help-block">{{ $errors->first("description") }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group @if($errors->has('description_en')) has-error @endif">
                <label for="description_en-field" class="col-sm-2 control-label">お知らせ (EN)</label>
                <div class="col-sm-10">
                    <textarea type="text" id="description_en-field" name="description_en" class="form-control" placeholder="お知らせ (EN)" >{{$information->description_en}}</textarea>
                    @if($errors->has("description_en"))
                    <span class="help-block">{{ $errors->first("description_en") }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group @if($errors->has('status')) has-error @endif">
                <label for="status-field" class="col-sm-2 control-label">ステータス</label>
                <div class="col-sm-10">
                    <label class="radio-inline">
                        <input type="radio" name="status"  value="1" @if($information->status == 1) checked @else @endif>公開
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" @if($information->status == 0) checked @else @endif>非公開
                    </label>
                    @if($errors->has("status"))
                    <span class="help-block">{{ $errors->first("status") }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <a class="btn btn-danger" href="{{ route('admin.informations.index') }}">キャンセル</a>
                    <button class="btn btn-primary" type="submit">保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
