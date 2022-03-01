@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
  <h1 class="pull-left">お知らせ管理</h1>
</div>

<div class="row">
  <div class="col-md-12 table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>作成日</th>
          <th class="col-lg-3">お知らせ (JP)</th>
          <th class="col-lg-2">お知らせ (EN)</th>
          <th class="col-lg-1">ステータス</th>
          <th>更新日時</th>
          <th><a class="btn btn-primary pull-right" href="{{ route('admin.informations.create') }}" style="position: relative;">追加</a></th>
        </tr>
      </thead>

      <tbody>

        @foreach($informations as $information)
        <tr>
          <td>{{$information->id}}</td>
          <td>{{$information->created_at}}</td>
          <td class="text-break">{{$information->description}}</td>
          <td class="text-break">{{$information->description_en}}</td>
          <td>
          @if ($information->status)
            <h4><span class="label label-success">&nbsp&nbsp公開&nbsp&nbsp</span></h4>
          @else
            <h4><span class="label label-danger">非公開</span></h4>
          @endif
          </td>
          <td>{{$information->updated_at}}</td>

          <td class="text-right">
            <a class="btn btn-success" href="{{ route('admin.informations.edit', $information->id) }}">編集</a>
            <form action="{{ route('admin.informations.destroy', $information->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('本当に削除しますか？この操作は取り消しできません。')) { return true } else {return false };">
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <button class="btn btn-danger" type="submit">削除</button>
            </form>
          </td>
        </tr>

        @endforeach
      </tbody>
    </table>

    {!! $informations->render() !!}
  </div>
  <div class="col-md-12">
    <a class="btn btn-primary pull-right" href="{{ route('admin.informations.create') }}" style="position: relative;">追加</a>
  </div>
</div>

<script type="text/javascript">
  $('#sortable_tbody').sortable();
  $('#sortable_tbody').disableSelection();
</script>
@endsection
