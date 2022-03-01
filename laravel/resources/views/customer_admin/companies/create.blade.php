@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">企業情報登録</h2>
        <form class="form" method="post" action="{{route('customer_admin_companies_store')}}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">企業名<span id="company"></span></p>
                        <input type="text" name="company" required="required" class="company">
                        <p class="required1 msg_err" style="display: block">会社フィールドを入力してください</p>
                        <p class="max_char1 msg_err">30文字以内で入力してください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">よみ(ひらがな)<span id="yomi"></span></p>
                        <input type="text" name="yomi" class="yomi">
                        <p class="required2 msg_err" style="display: block">よみフィールドを入力してください</p>
                        <p class="max_char2 msg_err">30文字以内で入力してください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">役割<span id="role"></span></p>
                        <select id="role" name="role">
                            <option value="0">販社</option>
                            <option value="1">顧客</option>
                            <option value="2">販社兼顧客</option>
                        </select>
                        <br>
                    </label>
                </li>
            </ul>
            <p class="contact_submit">
                <button type="submit" class="submit_btn" disabled>登録</button>
            </p>
        </form>
    </div>
</div>
@endsection
@section('page-script')
<script src="/js/customer-admin-validations/companies-form.js"></script>
@endsection
