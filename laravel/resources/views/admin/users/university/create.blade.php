@extends('admin.layouts.app')

@section('content')
<div class="page-header">
  <h1>大学管理者追加</h1>
</div>

<div class="row">
  <div class="col-md-12">
    @include('admin.common.form_errors')

    <form action="{{ route('admin.users.university.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group @if($errors->has('name')) has-error @endif">
        <label for="name-field" class="col-sm-2 control-label">氏名</label>
        <div class="col-sm-10">
          <input type="text" id="name-field" name="name" class="form-control" value="{{ old("name") }}"/>
          @if($errors->has("name"))
          <span class="help-block">{{ $errors->first("name") }}</span>
          @endif
        </div>
      </div>
      <div class="form-group @if($errors->has('email')) has-error @endif">
        <label for="email-field" class="col-sm-2 control-label">メールアドレス</label>
        <div class="col-sm-10">
          <input type="text" id="email-field" name="email" class="form-control" value="{{ old("email") }}"/>
          @if($errors->has("email"))
          <span class="help-block">{{ $errors->first("email") }}</span>
          @endif
        </div>
      </div>
      <div class="form-group @if($errors->has('password')) has-error @endif">
        <label for="password-field" class="col-sm-2 control-label">パスワード</label>
        <div class="col-sm-10">
          <input type="password" id="password-field" name="password" class="form-control" value="{{ old("password") }}"/>
          @if($errors->has("password"))
          <span class="help-block">{{ $errors->first("password") }}</span>
          @endif
        </div>
      </div>
      <div class="form-group @if($errors->has('enabled')) has-error @endif">
        <label for="enabled-field" class="col-sm-2 control-label">有効/無効</label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <input type="radio" name="enabled" id="enabled" value="1" checked> 有効
          </label>
          <label class="radio-inline">
            <input type="radio" name="enabled" id="disabled" value="0"> 無効
          </label>
          @if($errors->has("enabled"))
          <span class="help-block">{{ $errors->first("enabled") }}</span>
          @endif
        </div>
      </div>

      <div class="form-group @if($errors->has('enabled')) has-error @endif">
        <label for="enabled-field" class="col-sm-2 control-label">属性</label>
        <div class="col-sm-10">
          <select name="university_id" class="form-control">
            @foreach($exam_groups as $university)
              <option id="univ_add__opt" value="{{$university->id}}">{{$university->name}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="clearfix">
        <button class="btn btn-primary pull-right" type="submit">保存</button>
        <a class="btn btn-default pull-right" href="{{ route('admin.users.university.index') }}" style="margin-right: .5em;">キャンセル</a>
      </div>
    </form>
  </div>
</div>


@endsection