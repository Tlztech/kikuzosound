@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
    <h1 class="pull-left">視診ライブラリ一覧</h1>
</div>

@include('admin.common.api_errors')

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>聴診音種別</th>
                    <th>聴診部位</th>
                    <th>正常/異常</th>
                    <th>ステータス</th>
                    @if(Auth::user()->role == App\User::$ROLE_ADMIN)
                    <th>監修者</th>
                    @endif
                    <th>公開/非公開</th>
                    <th>更新日時</th>
                    @if(Auth::user()->role == App\User::$ROLE_ADMIN)
                    <th><a class="btn btn-primary pull-right"
                            href="{{ route('admin.inspection_library.create') }}">追加</a></th>
                    @endif
                </tr>
            </thead>
            <input type="hidden" value="{{ $count }}" id="count" />
            <tbody id="<?php echo isset($_GET['reorder']) ? "stetho_sound_list__tbody" : "" ?>">
                <?php $i = $count - 1; ?>
                @foreach($stetho_sounds as $stetho_sound)
                <tr>
                    <input type="hidden" value="{{ $stetho_sound->id }}" name="stetho_sound[{{ $i }}][disp_order]" />
                    <td>{{$stetho_sound->id}}</td>
                    <td class="text-break"><a
                            href="{{ route('admin.inspection_library.show', $stetho_sound->id) }}">{{$stetho_sound->title_en}}</a>
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <?php $type_strings = [ 1 => '肺音', 2 => '心音', 3 => '腸音', 9 => 'その他' ]; ?>
                    <?php $normal_strings = [ 0 => '異常', 1 => '正常' ]; ?>
                    <td>{{$normal_strings[$stetho_sound->is_normal] or '指定なし'}}</td>
                    <?php 
                        $status_strings = [
                        0 => '<span class="label label-danger">監修中</span>',
                        1 => '<span class="label label-warning">監修済</span>',
                        2 => '<span class="label label-success">公開中</span>',
                        3 => '<span class="label label-primary">公開中(New)</span>',
                        ];
                    ?>
                                <td>{!! $status_strings[$stetho_sound->status] or '指定なし' !!}</td>
                                <?php 
                        $public_strings = [
                        0 => '<span class="label label-danger">非公開</span>',
                        1 => '<span class="label label-success">公開</span>',
                        ];
                    ?>
                    @if(Auth::user()->role == App\User::$ROLE_ADMIN)
                        <td class="text-break">
                            @if($stetho_sound->user)
                                {{ $stetho_sound->user->name }}
                            @else
                                -
                            @endif
                        </td>
                    @endif
                    <td>{!! $public_strings[$stetho_sound->is_public] or '指定なし' !!}</td>
                    <td>{{$stetho_sound->updated_at}}</td>

                    @if(Auth::user()->role == App\User::$ROLE_ADMIN)
                    <td class="text-right">
                        <a class="btn btn-success" href="{{ url('admin/inspection_library/'.$stetho_sound->id.'/edit?page='.$stetho_sounds->currentPage()) }}">編集</a>
                        <form action="{{ route('admin.inspection_library.destroy', $stetho_sound->id) }}" method="POST"
                            style="display: inline;"
                            onsubmit="if(confirm('本当に削除しますか？この操作は取り消しできません。')) { return true } else {return false };">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-danger" type="submit">削除</button>
                        </form>
                        @if(isset($_GET['reorder']))
                        <img src="/img/drag_and_drop.png" class="drag_and_drop__img" />
                        @endif
                    </td>
                    @endif
                </tr>
                <?php $i--; ?>
                @endforeach
            </tbody>
        </table>
        <?php
            try {
                echo $stetho_sounds->render();
            } catch (Throwable $e) {
                echo "";
            }
        ?>
        <div class="col-sm-offset-10 col-sm-2">
            <a class="btn btn-primary pull-right" href="{{ route('admin.inspection_library.create') }}" style="position: relative;">追加</a>
        </div>
        <div class="col-sm-offset-4 col-sm-8" style="margin-top:80px;">
            <a class="btn btn-primary"
                href="/admin/inspection_library<?php echo isset($_GET['reorder']) ? "" : "?reorder" ?>">
                <?php echo isset($_GET['reorder']) ? "並べ替え完了" : "ライブラリを並べ替え" ?>
            </a>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#stetho_sound_list__tbody").sortable({
        update: function(event, ui) {
            var $td = $('#stetho_sound_list__tbody>tr');
            var arr = [];
            var count = $('#count').val();
            //console.log(count);
            $td.children('input[type="hidden"]').each(function(i, e) {
                $(e).attr('name', 'stetho_sound[' + (count - i) + '][disp_order]');
                $name = 'stetho_sound[' + (count - i) + '][disp_order]';
                arr[i] = {
                    stetho_sound_id: $('input[name="' + $name + '"]').val(),
                    disp_order: count - i
                }
            });

            var data = JSON.stringify({
                "stetho_sounds": arr
            });
            $.ajax({
                url: 'stetho_sounds_reorder',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                type: 'POST',
                contentType: 'application/json',
                success: function(res) {
                    //if (res == 1) console.log("reorderd success");
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    // alert("error : " + XMLHttpRequest);
                },
            });
        }
    });
});
</script>

@endsection