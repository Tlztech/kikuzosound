@extends('admin.layouts.app')

@section('content')
<div class="page-header">
  <h1>監修者編集</h1>
</div>

<div class="row">
  <div class="col-md-12">
    @include('admin.common.form_errors')

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
      <input type="hidden" name="_method" value="PUT">
      {{ csrf_field() }}
      <input type="hidden" name="updated_at" value="{{old('updated_at',$user->updated_at)}}"/>
      {{-- <input type="datetime-local" name="updated_at" value="{{old('updated_at',$user->updated_at)}}"/> --}}

      {{-- 強制更新 --}}
      @if($errors->has("is_force_update"))
        <div class="form-group has-error">
          <label for="is_force_update-field" class="col-sm-2 control-label">強制更新</label>
          <div class="col-sm-10">
            <input type="checkbox" name="is_force_update" class="well well-sm" value="1"/>
          </div>
        </div>
      @endif

      {{-- ID --}}
      <div class="form-group">
        <label for="id" class="col-sm-2 control-label">ID</label>
        <div class="col-sm-10">
          <p class="form-control-static">{{$user->id}}</p>
        </div>
      </div>

      {{-- 氏名 --}}
      <div class="form-group @if($errors->has('name')) has-error @endif">
        <label for="name-field" class="col-sm-2 control-label">氏名</label>
        <div class="col-sm-10">
          <input type="text" id="name-field" name="name" class="form-control" value="{{ $user->name }}"/>
          @if($errors->has("name"))
          <span class="help-block">{{ $errors->first("name") }}</span>
          @endif
        </div>
      </div>

      {{-- メールアドレス --}}
      <div class="form-group @if($errors->has('email')) has-error @endif">
        <label for="email-field" class="col-sm-2 control-label">メールアドレス</label>
        <div class="col-sm-10">
          <input type="text" id="email-field" name="email" class="form-control" value="{{ $user->email }}"/>
          @if($errors->has("email"))
          <span class="help-block">{{ $errors->first("email") }}</span>
          @endif
        </div>
      </div>

      {{-- パスワード --}}
      <div class="form-group @if($errors->has('password')) has-error @endif">
        <label for="password-field" class="col-sm-2 control-label">パスワード</label>
        <div class="col-sm-10">
          <input type="password" id="password-field" name="password" class="form-control" value="{{ $user->password }}"/>
          @if($errors->has("password"))
          <span class="help-block">{{ $errors->first("password") }}</span>
          @endif
        </div>
      </div>

      {{-- 有効/無効 --}}
      <div class="form-group @if($errors->has('enabled')) has-error @endif">
        <label for="enabled-field" class="col-sm-2 control-label">有効/無効</label>
        <div class="col-sm-10">
          <label class="radio-inline">
            <input type="radio" name="enabled" id="enabled" value="1" @if($user->enabled) checked @endif> 有効
          </label>
          <label class="radio-inline">
            <input type="radio" name="enabled" id="disabled" value="0" @unless($user->enabled) checked @endif> 無効
          </label>
          @if($errors->has("enabled"))
          <span class="help-block">{{ $errors->first("enabled") }}</span>
          @endif
        </div>
      </div>

      <div class="clearfix">
        <button class="btn btn-primary pull-right" type="submit">保存</button>
        <a class="btn btn-default pull-right" href="{{ route('admin.users.index') }}" style="margin-right: .5em;">キャンセル</a>
      </div>
   </form>
 </div>
</div>


@endsection