@extends('admin.layouts.app')

@section('content')
<div class="page-header clearfix">
    <h1 class="pull-left">コンテンツ権限一覧</h1>
</div>
<div class="row group_management">
    <div class="col-md-12">
        <table class="table table-responsive header_table"> 
            <tr>
                <td class="text-center">Group Select
                    <b><span style="font-size: 14px; color: red;">&#9312;</span></b>
                </td>
                <td width="500">
                    <select class="form-select form-control" name="group_sel" id="group_sel" >
                        <option value="0" selected disabled>Select Destination Group</option>
                        @foreach($exam_groups as $index=>$group)
                            <option value="{{$group->id}}" >{{$group->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td class="pull-right"><button class="btn btn-primary btn-save-changes">Save</button></td>
            <tr>
            <tr>
                <td class="text-center">Group Select
                    <b><span style="font-size: 14px; color: red;">&#9313;</span></b>
                </td>
                <td width="500">
                    <select class="form-select form-control" name="" id="original_sel">
                        <option value="0" selected disabled>Select Original Group</option>
                        @foreach($exam_groups as $index=>$group)
                            <option value="{{$group->id}}" >{{$group->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td><button class="btn btn-warning" id="reflect_btn" disabled>Reflect</button>
                <b><span style="font-size: 14px; color: red;">&nbsp;&nbsp; &#9312; &nbsp; &#8592; &nbsp; &#9313;</span></b>
                </td>
            <tr>
        </table>
        <table data-toggle="table" class="table-responsive" id="group_management_table" data-checkbox-header="false" data-show-select-title="true"> 
        <thead>
            <th data-width="5" data-field="key" data-visible="false"></th>
            <th data-width="5" data-field="groups" data-visible="false"></th>
            <th data-width="200" data-field="category">Category</th>
            <th data-align="center" data-width="5" data-field="id">ID</th>
            <th data-width="600" data-field="name">Name</th>
            <th data-align="center" data-width="100" data-field='permission_des'><b><span style="font-size: 14px; color: red;">&#9312;</span></b></th>
            <th data-align="center" data-width="100" data-field='permission_original' ><b><span style="font-size: 14px; color: red;">&#9313;</span></b></th>
        </thead>
        <tbody>
        @foreach($exams as $index=>$exam)
            <tr>
                <td>exam</td>
                <td>{{json_encode(array_pluck($exam->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "Exams" : ""}}</b></td>
                <td>{{$exam->id}}</td>
                <td>{{ ($exam->name) ? $exam->name : $exam->name_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($quiz_packs as $index=>$quiz_pack)
            <tr>
                <td>quiz_pack</td>
                <td>{{json_encode(array_pluck($quiz_pack->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "Quiz pack（quiz）" : ""}}</b></td>
                <td>{{$quiz_pack->id}}</td>
                <td>{{($quiz_pack->title) ? $quiz_pack->title : $quiz_pack->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($quizzes as $index=>$quiz)
            <tr>
                <td>quiz</td>
                <td>{{json_encode(array_pluck($quiz->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "Quiz（Question）" : ""}}</b></td>
                <td>{{$quiz->id}}</td>
                <td>{{($quiz->title) ? $quiz->title : $quiz->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($ipax as $index=>$lib)
            <tr>
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "iPax library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($stetho as $index=>$lib)
            <tr>
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "Stetoho library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($palpation as $index=>$lib)
            <tr>
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "Palpation library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($ecg as $index=>$lib)
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "ECG library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($inspection as $index=>$lib)
            <tr>
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "Inspection library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($xray as $index=>$lib)
            <tr>
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "X-ray library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        @foreach($ucg as $index=>$lib)
            <tr>
                <td>lib</td>
                <td>{{json_encode(array_pluck($lib->exam_groups,'id'))}}</td>
                <td><b>{{($index===0)? "UCG library" : ""}}</b></td>
                <td>{{$lib->id}}</td>
                <td>{{($lib->title) ? $lib->title : $lib->title_en}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
        </table>
        <br/>
        <button class="btn btn-primary pull-right btn-save-changes">Save</button>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
  var $table = $('#group_management_table');
  //custom script
  $( document ).ready(function() {
    var tableData = $table.bootstrapTable('getData');
    $('#group_sel').on('change', function() {
        var original = parseInt($('#original_sel').val());
        if(original){
            $("#reflect_btn").attr("disabled", false);
        }
        
        var destination_group = parseInt($(this).val());        
        var filterData= tableData.filter(function(item){
            var groups;
            try {
                groups = JSON.parse(item.groups);
                if(groups.includes(destination_group)){
                    item.permission_des = "&#10003;";
                }else{
                    item.permission_des = "";
                }
            } catch (e) {
            } 
            return true;
        });
        $table.bootstrapTable('load', filterData);
       
    });
    
    $('#original_sel').on('change', function() {
        var destination = parseInt($('#group_sel').val());
        if(destination){
            $("#reflect_btn").attr("disabled", false);
        }
        var original_group = parseInt($(this).val());
        //var tableData = $table.bootstrapTable('getData');
        var filterData= tableData.filter(function(item){
            var groups;
            try {
                groups = JSON.parse(item.groups);
                if(groups.includes(original_group)){
                    item.permission_original = "&#10003;";
                }else{
                    item.permission_original = "";
                }
            } catch (e) {
            } 
            return true;
        });
        $table.bootstrapTable('load', filterData);
    });

    $("#reflect_btn").on('click', function() {
        //var tableData = $table.bootstrapTable('getData');
        var filterData= tableData.filter(function(item){
        var groups = JSON.parse(item.groups);
        var destination = parseInt($('#group_sel').val());
            try {
                if(item.permission_original){
                    item.permission_des = "&#10003;";
                }
            } catch (e) {
            } 
            return true;
        });
        $table.bootstrapTable('load', filterData);
    });

    $(".btn-save-changes").on('click', function() {
        var original_group_id = parseInt($('#original_sel').val());
        var destination_group_id = parseInt($('#group_sel').val());
        if(!destination_group_id) return;

        var exam_ids=[]; quiz_pack_ids=[]; quiz_ids=[]; library_ids=[]; 
        var exam_original_ids=[]; quiz_pack_original_ids=[]; quiz_original_ids=[]; library_original_ids=[];

        var new_tableData = $table.bootstrapTable('getData');
        var filterData= new_tableData.filter(function(item){
            return (item.permission_des)
        }).map(function (row) {
            var groups = JSON.parse(row.groups);
            if(row.key=="exam"){
                exam_ids.push(row.id) 
            }
            if(row.key=="quiz_pack"){
                quiz_pack_ids.push(row.id) 
            }
            if(row.key=="quiz"){
                quiz_ids.push(row.id) 
            }
            if(row.key=="lib"){
                library_ids.push(row.id) 
            }
        }); 
        var groupData= new_tableData.filter(function(item){
            var groups = JSON.parse(item.groups);
            return (groups.includes(destination_group_id))
        }).map(function (row) {
            if(row.key=="exam"){
                exam_original_ids.push(row.id) 
            }
            if(row.key=="quiz_pack"){
                quiz_pack_original_ids.push(row.id) 
            }
            if(row.key=="quiz"){
                quiz_original_ids.push(row.id) 
            }
            if(row.key=="lib"){
                library_original_ids.push(row.id) 
            }
        }); 
        $.ajaxSetup({
        dataType: 'html',
        timeout: 10000,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            }
        });
        $.ajax({
        url: window.location.protocol + '//' + location.host + "/admin/group_management",
        type: 'POST',
        data: {
            des_group_id:destination_group_id,
            group_id:original_group_id,
            exam_ids:exam_ids,
            quiz_pack_ids: quiz_pack_ids,
            quiz_ids:quiz_ids,
            library_ids:library_ids,
            exam_original_ids:exam_original_ids, 
            quiz_pack_original_ids:quiz_pack_original_ids,
            quiz_original_ids:quiz_original_ids,
            library_original_ids:library_original_ids,
        },
        success: function (data) {
            sessionStorage.setItem("destination_group_id",destination_group_id);
            sessionStorage.setItem("original_group_id",original_group_id);
            location.reload();
        },
        error: function (xhr, textStatus, errorThrown) {
            $.error([xhr, textStatus, errorThrown]);
            location.reload();
        }
        });
    });
    if(sessionStorage.getItem("destination_group_id") && sessionStorage.getItem("original_group_id")){
        $('#group_sel').val(sessionStorage.getItem("destination_group_id")).change();
        $('#original_sel').val(sessionStorage.getItem("original_group_id")).change();
        sessionStorage.removeItem('destination_group_id');
        sessionStorage.removeItem('original_group_id');
    }

  });

  $table.bootstrapTable({
    onClickCell: function (field, value, row, element ) {
            var destination = parseInt($('#group_sel').val());
            var original = $('#original_sel').val();
            var tableData = $table.bootstrapTable('getData');
            var index = tableData.findIndex(item => (item.id == row.id && item.key == row.key));
            var groups = JSON.parse(row.groups);
            if(field=="permission_des" && destination && (groups.includes(destination) || row.permission_original)){
                var checked = value ? "" : "&#10003;";
                $table.bootstrapTable('updateCell', {index: index, field: 'permission_des', value: checked});
            }
        }
    })
</script>
@endsection