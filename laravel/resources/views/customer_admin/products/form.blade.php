@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
            <div class="mat">
                <h2 class="title_m" style="text-align: center;">製品登録</h2>
                @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <form id="product" class="form" method="post" action="{{route('customer_admin_products_register_store')}}">
                    {{csrf_field()}}
                    <ul class="contact_form">
                    <li>
                        <label for="" class="">
                        <p class="input_name">開始シリアル番号<span id="error1"></span></p>
                        <input type="number" id="product_no_s" name="product_no_s" min="1" required="required"><br>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                        <p class="input_name">終了シリアル番号<span id="error2"></span></p>
                        <input type="number" id="product_no_e" name="product_no_e" min="1" required="required"><br>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                        <p class="input_name">状態</p>
                        <select id="p_status" name="p_status">
                            <option value="0" selected="selected">出荷前</option>
                            <option value="1">出荷済み</option>
                            <option value="2">故障</option>
                            <option value="3">予約</option>
                        </select><br>
                        </label>
                    </li>
                    </ul>
                    <p class="contact_submit">
                        <button type="submit" class="submit_btn">登録</button>
                    </p>
                </form>
            </div>
        </div>
@endsection
@section('page-script')
@endsection
