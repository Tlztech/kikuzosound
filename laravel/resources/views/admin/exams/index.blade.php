@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
  <h1 class="pull-left">EXAM一覧</h1>
</div>

<div class="row">
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>試験名 (JP)</th>
          <th>試験名 (EN)</th>
          <th>Examグループ名</th>
          <th>クイズパック名</th>
          <th>公開/非公開</th>
          <th>結果送信先</th>
          <th><a class="btn btn-primary pull-right" href="{{ route('admin.exams.create') }}" style="position: relative;">追加</a></th>
        </tr>
      </thead>
      <input type="hidden" value="{{ $count }}" id="count"/>
      <tbody id="<?php echo isset($_GET['reorder']) ? "exam_list__tbody" : "" ?>">
        <?php $i = $count - 1; ?>
        @foreach($exams as $exam)
        <tr>
          <input type="hidden" value="{{ $exam->id }}" name="exam[{{ $i }}][disp_order]"/>
          <td>{{$exam->id}}</td>
          <td class="text-break">{{$exam->name_jp}}</td>
          <td class="text-break">{{$exam->name}}</td>
          <td class="text-break">
          @foreach($exam->exam_groups()->get() as $exam_group)
            <h6><span class="label label-default">{{$exam_group->name}}</span></h6>
          @endforeach
          </td>
          <td class="text-break">{{$exam->quiz_pack->title_en}}</td>
          <td>
          @if ($exam->is_publish)
            <h6><span class="label label-success">&nbsp&nbsp公開&nbsp&nbsp</span></h6>
          @else
            <h6><span class="label label-danger">非公開</span></h6>
          @endif
          </td>
          <td class="text-break">{{$exam->result_destination_email}}</td>

          <td class="text-right">
            <a class="btn btn-success" href="{{ route('admin.exams.edit', $exam->id) }}">編集</a>
            <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('本当に削除しますか？この操作は取り消しできません。')) { return true } else {return false };">
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
    <?php
            try {
                echo $exams->render();
            } catch (Throwable $e) {
                echo "";
            }
        ?>
        <div class="col-sm-offset-10 col-sm-2">
          <a class="btn btn-primary pull-right create-btn" href="{{ route('admin.exams.create') }}" style="position: relative;">追加</a>
        </div>
        <div class="col-sm-offset-4 col-sm-8" style="margin-top:80px;">
          <a class="btn btn-primary" href="/admin/exams<?php echo isset($_GET['reorder']) ? "" : "?reorder" ?>">
            <?php echo isset($_GET['reorder']) ? "並べ替え完了" : "試験を並べ替え" ?>
          </a>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
  $("#exam_list__tbody").sortable({
    update: function(event, ui) {
      var $td = $('#exam_list__tbody>tr');
      var arr = [];
      var count = $('#count').val();
      //console.log(count);
      $td.children('input[type="hidden"]').each(function(i, e){
        $(e).attr('name','exam[' + (count - i) + '][disp_order]');
        $name = 'exam[' + (count - i) + '][disp_order]';
        arr[i] = { 
          exam_id: $('input[name="' + $name + '"]').val(), 
          disp_order: count - i
        }
      });

      var data = JSON.stringify({"exams": arr});
      $.ajax({
        url : 'exam_reorder',
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