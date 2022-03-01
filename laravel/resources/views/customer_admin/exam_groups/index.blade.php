@extends('customer_admin.layouts.app')
@section('content')
<!-- Customer Management -->
<div id="container">
    <div class="container_inner clearfix">
        <!--*********************************** .contents ***********************************-->
        <div class="contents">
            <div class="customer-top">
                <a href="{{url('customer_admin/exam_groups/create')}}"><button class="customer-admin">新規追加</button></a>
                <form method="GET" action="">
                    <input name="search_key" type="customer-input" placeholder="" value="{{$_GET['search_key'] or ''}}" />
                    <button type="submit" class="search-input">
                        検索
                    </button>
                </form>
            </div>
            <div class="customer-bottom exam-groups">
                <h2> EXAMグループ一覧</h2>
                <table data-toggle="table" id="eventsTable" class="exam_group_table">
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-visible="false"></th>
                            <th data-sortable="true">Examグループ名</th>
                            <th data-sortable="true">作成日</th>
                            <th data-sortable="true">更新日</th>
                            <th data-sortable=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exam_groups as $exam_group)
                        <tr>
                            <td></td>
                            <td>{{$exam_group->id}}</td>
                            <td>{{$exam_group->name}}</td>
                            <td>{{$exam_group->created_at}}</td>
                            <td>{{$exam_group->updated_at}}</td>
                            <td class="text-right">
                                <a class="btn btn-success"
                                    href="{{ route('customer_admin.exam_groups.edit', $exam_group->id) }}">編集</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="for-pagination">
                {!! $exam_groups->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
@endsection
