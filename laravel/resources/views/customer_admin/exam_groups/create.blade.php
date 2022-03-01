@extends('customer_admin.layouts.app')
@section('content')
<!-- universities -->
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">Examグループ新規追加</h2><br>
        <form action="{{ route('customer_admin.exam_groups.store') }}" method="POST" enctype="multipart/form-data"
            class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">Examグループ名<br></p>
                        <input type="text" name="exam_group_name" value="{{ old("exam_group_name") }}" 
                            style="box-shadow:none;">
                        @if($errors->has("exam_group_name"))
                        <p class="error_wrapper">
                            <span class="help-block">{{ $errors->first("exam_group_name") }}</span>
                        </p>
                        @endif
                    </label>
                </li>
            </ul>
            <p class="contact_submit" style="padding:30px 0px 50px 0px;">
                <button type="submit" class="submit_btn">新規追加</button>
            </p>
        </form>

    </div>
</div>

@endsection