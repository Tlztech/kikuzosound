@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
  <h1 class="pull-left">監修者一覧</h1>
</div>

<div class="row">
  <div class="col-md-12 table-responsive">
    <table class="table table-striped users-table">
      <thead>
        <tr>
          <th>ID</th>
          <th class="for-name">氏名</th>
          <th>メールアドレス</th>
          <th>有効/無効</th>
          <th><a class="btn btn-primary pull-right" href="{{ route('admin.users.create') }}">追加</a></th>
        </tr>
      </thead>

      <tbody>

        @foreach($users as $user)
        <tr>
          <td>{{$user->id}}</td>
          <td>{{$user->name}}</td>
          <td>{{$user->email}}</td>
          <td>
            @if ($user->enabled)
              <span class="label label-success">有効</span>
            @else
              <span class="label label-danger">無効</span>
            @endif
          </td>

          <td class="text-right">
            <a class="btn btn-success " href="{{ route('admin.users.edit', $user->id) }}">編集</a>
          </td>
        </tr>

        @endforeach
      </tbody>
    </table>

    {!! $users->render() !!}
  </div>
</div>

<div class="page-footer clearfix">
  <a class="btn btn-primary pull-right" href="{{ route('admin.users.create') }}">追加</a>
</div>

@endsection