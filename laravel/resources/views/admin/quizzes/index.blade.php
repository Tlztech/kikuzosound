@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
  <h1 class="pull-left">クイズ一覧</h1>
</div>

<div class="row">
  <div class="col-md-12">

    @include('admin.common.form_errors',['title'=>'エラーが発生しました。'])

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>タイトル</th>
          @if(!isset($_GET['reorder']))
            <th>イラスト</th>
          @endif
          <th>ライブラリ</th>
          <th>コンテンツ</th>
          <th>回答選択肢</th>
          <th>制限時間</th>
          <th><a class="btn btn-primary pull-right" href="{{ route('admin.quizzes.create') }}">追加</a></th>
        </tr>
      </thead>

      <input type="hidden" value="{{ $count }}" id="count"/>
      <tbody id="<?php echo isset($_GET['reorder']) ? "quizzes_list__tbody" : "" ?>">

      <?php $i = $count - 1; ?>
      @foreach($quizzes as $q)
      <tr>
        <input type="hidden" value="{{ $q->id }}" name="quizzes[{{ $i }}][disp_order]"/>
        <td>{{$q->id}}</td>
        <td class="text-break">{{$q->title_en}}</td>
        @if(!isset($_GET['reorder']))
          <td>
            <img src="{{ asset($q->image_path).'?v='.session('version') }}" class="ad-table__img" 
              onerror="this.src = '/img/no_image.png?v={{session('version')}}';">
          </td>
        @endif
        <?php
          $lib_count = $q->stetho_sounds()->get()->groupBy('lib_type')->count();
          $single_lib =  $q->stetho_sounds()->first();
          $choices_count = 0;
          $choices_count = ($q->is_optional == 1) ? $q->quiz_choices()->where('is_fill_in', null)->count() : 0;
          // if ($lib_count > 1) {
          //     $choices_count = $q->quiz_choices()->where('is_fill_in', null)->count();
          // } else {
          //     $choices_count = $q->quiz_choices()->where('lib_type', $single_lib['lib_type'])->where('is_fill_in', null)->count();
          // }
          
        ?>
        <td>{{$lib_count}}</td>
        <td>{{$q->stetho_sounds()->count()}}</td>
        <td>{{$choices_count}}</td>
        <td>{{$q->limit_seconds == 0 ? '無制限' : $q->limit_seconds}}</td>

        <td class="text-right">
          <a class="btn btn-success " href="{{ route('admin.quizzes.edit', $q->id) }}">編集</a>
          <form action="{{ route('admin.quizzes.destroy', $q->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('本当に削除しますか？この操作は取り消しできません。')) { return true } else {return false };">
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <button class="btn btn-danger" type="submit">削除</button>
          </form>
          @if(isset($_GET['reorder']))
            <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
          @endif
        </td>
      </tr>
      <?php $i--; ?>
      @endforeach

      </tbody>
    </table>

    {!! $quizzes->render() !!}
    </div>
    <div class="col-sm-offset-10 col-sm-2">
      <a class="btn btn-primary pull-right" href="{{ route('admin.quizzes.create') }}">追加</a>
    </div>
    <div class="col-sm-offset-4 col-sm-8 sorting-button">
      <a class="btn btn-primary" href="/admin/quizzes<?php echo isset($_GET['reorder']) ? "" : "?reorder" ?>">
        <?php echo isset($_GET['reorder']) ? "並べ替え完了" : "クイズを並べ替え" ?>
      </a>
    </div>
  </div>


<script type="text/javascript">
  $(document).ready(function(){
    $("#quizzes_list__tbody").sortable({
      update: function(event, ui) {
        var $td = $('#quizzes_list__tbody>tr');
        var arr = [];
        var count = $('#count').val();
        //console.log(count);
        $td.children('input[type="hidden"]').each(function(i, e){
          $(e).attr('name','quizzes[' + (count - i) + '][disp_order]');
          $name = 'quizzes[' + (count - i) + '][disp_order]';
          arr[i] = { 
            quizzes_id: $('input[name="' + $name + '"]').val(), 
            disp_order: count - i
          }
        });
  
        var data = JSON.stringify({"quizzes": arr});
        $.ajax({
          url : 'quizzes_reorder',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data : data,
          type : 'POST',
          contentType : 'application/json',
          success: function(res){
            //if(res == 1) console.log("reorderd success");
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            // alert( "error : " + XMLHttpRequest );
          },
        });
      }
    });
  });
</script>

@endsection
