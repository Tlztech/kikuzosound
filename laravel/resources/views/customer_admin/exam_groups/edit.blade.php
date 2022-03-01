
@extends('customer_admin.layouts.app')
@section('content')
<!-- universities -->
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">Exam group 編集</h2><br>
        <form action="{{ route('customer_admin.exam_groups.update', $exam_group->id) }}" method="POST" enctype="multipart/form-data"
            class="form-horizontal">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name" style="width:25%;">Examグループ名<br></p>
                        <input type="text" name="exam_group_name" value="{{$exam_group->name}}" required="required" style="box-shadow:none;">
                        @if($errors->has("exam_group_name"))
                        <p class="error_wrapper">
                            <span class="help-block">{{ $errors->first("exam_group_name") }}</span>
                        </p>
                        @endif
                    </label>
                </li>
            </ul>
            <p class="contact_submit" style="padding:30px 0px 50px 0px;">
                <button type="submit" class="submit_btn" >保存</button>
            </p>
        </form>
        
    </div>
</div>

@endsection