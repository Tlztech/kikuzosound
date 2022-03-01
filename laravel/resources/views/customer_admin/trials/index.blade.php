@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">聴診会員（暫定版）</h2>
        <p style="margin:0px 0px 10px;text-align:center;">・新規登録(登録の代行)は
            <a href="{{route('customer_admin_trials_register')}}">
                こちら
            </a>から行えます<br>
            ・削除は「No」をクリックすると行えます(確認ダイアログ有り)
        </p>
        <ul class="ultable">
            <li class="first column_tno">No</li>
            <li class="first" style="width: 25%;">メールアドレス</li>
            <li class="first">氏名</li>
            <li class="first">職種</li>
            <li class="first">勤務先</li>
            <li class="first">登録日</li>
            <li style="width: 7%;" class="first column_gender">登録</li>
        </ul>
        @foreach($trials as $trial)
            <ul class="ultable" id="ul0">
                <li class="column_tno c_delete" data-id="{{$trial->id}}">{{$trial->id}}</li>
                <li style="width: 25%;">{{$trial->mail}}</li>
                <li>{{$trial->name1}} {{$trial->name2}}</li>
                <li>{{ $trial->professions->first()->profession_name }}</li>
                <li>{{$trial->groups->group_name}}</li>
                <li>{{date_format(date_create($trial->created_at),"Y-m-d")}}</li>
                <li style="width: 7%;" class="column_gender">
                    @if($trial->status_flag == 1)
                    本
                    @else
                    仮
                    @endif
                </li>
            </ul>
        @endforeach

    </div>
    <div style="margin:0px 0px 20px;text-align:center;">
        <button id="csv_download_btn" class="customer-admin">CSVダウンロード</button>
        <button id="xlsx_download_btn" class="customer-admin">XLSXダウンロード</button>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="color: red;">
                <h5 class="modal-title">削除</h5>
            </div>
            <div class="modal-body" style="color: red;">
                <p>該当聴診会員を削除しますか？</p>
            </div>
            <div class="modal-footer">
                <form  id="deleteform" action="" method="POST" style="display: inline;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="btn btn-danger" type="submit">削除</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('page-script')
<script type="text/javascript">
    $(function() {
        $('.c_delete').on('click', function () {
            var url = "{{url('/')}}/customer_admin/trials/"+$(this).data('id')+"/destroy";
            $('.modal').modal('show')
            $('#deleteform').attr('action', url);
        })
        $("#csv_download_btn").on("click", function() {
            document.location.href = "{{route('customer_admin_trials_csv')}}";
        })
        $("#xlsx_download_btn").on("click", function() {
            document.location.href = "{{route('customer_admin_trials_xlsx')}}";
        })
    });
</script>
@endsection
