@extends('customer_admin.layouts.app')
@section('content')

<style>
    a{
        color:#00C8FF;
    }
</style>
<!-- exams -->
<div class="container">
    <div style="text-align: center;font-size: 12px;">
        <a href="http://kkzcontrol.telemedica.co.jp/login.php" target="_blank">講師側サイト</a>&nbsp&nbsp&nbsp
        <a href="http://kkzspeakers.telemedica.co.jp/login.php" target="_blank">受講側サイト</a>
    </div>
    <div class="mat" style="text-align: center;">
        <h2 class="title_m"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">コンダクタ登録</font></font></h2>
        <ul class="link-ul">
            @foreach($exams as $exam)
            <li>
                <a href="{{route('customer_admin_exams.edit', $exam->id)}}">
                    {{$exam->id}}: {{$exam->user}} ({{$exam->unit}})
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div style="text-align: center;">
        <a href="{{route('customer_admin_exams.create')}}">
            <button class="customer-admin">新規作成</button>
        </a>
    </div>
    </div>
@endsection
@section('page-script')
<script type="text/javascript">
</script>
@endsection
