@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">ライセンスキー 一覧</h2>
        <form class="form" method="post" action="{{route('customer_admin_onetime_key_update', $key->id)}}">
            {{csrf_field()}}

            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">代理店<span id="error"></span></p>
                        <input type="text" name="agency" value="{{$key->agency}}" required="required">
                        @if($errors->has('agency'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('agency') }}</p>
                        @endif
                    </label>
                </li>
                <li>
                    <label for="">
                        <p class="input_name must">Examグループ名</p>
                        <select id="university_id" name="university_id">
                            <option value="" <?php if($key->university_id == NULL) echo "selected"; ?>>None</option>
                            @foreach ($universities as $university)
                                <option value="{{$university->id}}" 
                                    <?php
                                        if ($key->university_id == $university->id) echo "selected";
                                    ?>
                                >
                                    {{$university->name}}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">ステータス<span id="error"></span></p>
                        <select id="auth" name="status" disabled>
                            <option value="{{$key->status}}" selected="selected">
                                <?php
                                    if ($key->status == 0) { 
                                        echo "発行済"; // issued
                                    } else if ($key->status == 1) {
                                        echo "使用済"; // used
                                    } else if ($key->status == 2) {
                                        echo "変更済"; // changed
                                    } else if ($key->status == 3) {
                                        echo "停止中"; // stopped
                                    }
                                ?>
                            </option>
                        </select>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">作成日<span id="error"></span></p>
                        <input style="background-color: #e7e7e7;" type="date" id="created_at" value="{{Carbon\Carbon::parse($key->created_at)->format('Y-m-d')}}" name="created_at" disabled>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">更新日<span id="error"></span></p>
                        <input style="background-color: #e7e7e7;" type="date" id="updated_at" value="{{Carbon\Carbon::parse($key->updated_at)->format('Y-m-d')}}" name="updated_at" disabled>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">法人名<span id="error"></span></p>
                        <input type="text" name="company" value="{{$key->company}}" id="company">
                        @if($errors->has('company'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('company') }}</p>
                        @endif
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">ユーザーID<span id="error"></span></p>
                        <input type="text" name="user" value="{{$key->user}}" required="required" id="user">
                        @if($errors->has('user'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('user') }}</p>
                        @endif
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">お客様名<span id="error"></span></p>
                        <input type="text" name="name" value="{{$key->name}}" required="required">
                        @if($errors->has('name'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('name') }}</p>
                        @endif
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">登録者 email<span id="error"></span></p>
                        <input type="text" name="email" value="{{$key->email}}" required="required">
                        @if($errors->has('email'))
                            <p style="color:red;font-size:12px;font-weight:bold;margin:0 0 1em 1em;">{{ $errors->first('email') }}</p>
                        @endif
                    </label>
                </li>
                <input type="hidden" name="company_id" value="{{$key->company_id}}">
                <input type="hidden" name="account_id" value="{{$key->account_id}}">
            </ul>
            <p class="contact_submit">
                <br>
                <button type="submit" class="submit_btn">登録</button>
            </p>
        </form>
    </div>
</div>
@endsection
@section('page-script')
<script src="/js/customer-admin-validations/contact-form.js"></script>
@endsection
