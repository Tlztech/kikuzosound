@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
  <h1 class="pull-left">クイズパック一覧</h1>
</div>

@include('admin.common.api_errors')

<div class="row">
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>タイトル (JP)</th>
          <th>タイトル (EN)</th>
          <th>説明 (JP)</th>
          <th>説明 (EN)</th>
          <th>出題形式</th>
          <th>問題数</th>
          <th>公開/非公開</th>
          <th><a class="btn btn-primary pull-right" href="{{ route('admin.quiz_packs.create') }}" style="position: relative;">追加</a></th>
        </tr>
      </thead>
      <tbody id="<?php echo isset($_GET['reorder']) ? "quiz_packs__tbody" : "" ?>">
        <?php $i = 0; ?>
        @foreach($quiz_packs as $quiz_pack)
        <tr>
          <input type="hidden" value="{{ $quiz_pack->id }}" name="quiz_packs[{{ $i }}][disp_order]"/>
          <td>{{$quiz_pack->id}}</td>
          <td class="text-break">{{$quiz_pack->title}}</td>
          <td class="text-break">{{$quiz_pack->title_en}}</td>
          <td class="text-break"><div class="<?php if(strlen($quiz_pack->description)>500) echo "desc-td"; ?>">{{$quiz_pack->description}}</div></td>
          <td class="text-break"><div class="<?php if(strlen($quiz_pack->description_en)>500) echo "desc-td"; ?>">{{$quiz_pack->description_en}}</div></td>
          <td>{{$quiz_pack->quiz_order_type ? "ランダム" : "固定"}}</td>
          <td>{{$quiz_pack->max_quiz_count}}</td>
          <td>
          @if ($quiz_pack->is_public)
            <span class="label label-success">あり</span>
          @else
            <span class="label label-danger">なし</span>
          @endif
          </td>

          <td class="text-right">
            <a class="btn btn-success" href="{{ route('admin.quiz_packs.edit', $quiz_pack->id) }}">編集</a>
            <form action="{{ route('admin.quiz_packs.destroy', $quiz_pack->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('本当に削除しますか？この操作は取り消しできません。')) { return true } else {return false };">
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <button class="btn btn-danger" type="submit">削除</button>
            </form>
            @if(isset($_GET['reorder']))
              <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
            @endif
          </td>
        </tr>
        <?php $i++; ?>
        @endforeach
      </tbody>
    </table>

    {!! $quiz_packs->render() !!}
  </div>
  <div class="col-sm-offset-10 col-sm-2">
    <a class="btn btn-primary pull-right" href="{{ route('admin.quiz_packs.create') }}" style="position: relative;">追加</a>
  </div>

  <div class="col-sm-offset-4 col-sm-8 sorting-button">
      <a class="btn btn-primary" href="/admin/quiz_packs<?php echo isset($_GET['reorder']) ? "" : "?reorder" ?>">
        <?php echo isset($_GET['reorder']) ? "並べ替え完了" : "クイズパックを並び替え" ?>
      </a>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#quiz_packs__tbody").sortable({
      update: function(event, ui) {
        var $td = $('#quiz_packs__tbody>tr');
        var arr = [];
        var count = 0;

        $td.children('input[type="hidden"]').each(function(i, e){
          $(e).attr('name','quiz_packs[' + (count + i) + '][disp_order]');
          $name = 'quiz_packs[' + (count + i) + '][disp_order]';
          arr[i] = { 
            quiz_pack_id: $('input[name="' + $name + '"]').val(), 
            disp_order: count + i
          }
        });
        //console.log(arr);
        var data = JSON.stringify({"quiz_packs": arr});
        $.ajax({
          url : 'quiz_packs_reorder',
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
            console.log( "error : " + errorThrown );
          },
        });
      }
    });
    $('#quiz_packs__tbody').disableSelection();
  });
</script>

@endsection