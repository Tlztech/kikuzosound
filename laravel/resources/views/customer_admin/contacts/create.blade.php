@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">連絡先情報登録</h2>
        <form class="form" method="post" action="{{route('customer_admin_contacts_store')}}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">企業<span id="error"></span></p>
                        <select name="company_id">
                            @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->company}}</option>
                            @endforeach
                        </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">契約者名<span id="error"></span></p>
                        <input type="text" name="name" required="required"><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">契約者名よみ(ひらがな)<span id="error"></span></p>
                        <input type="text" id="memo" name="memo"><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">メールアドレス<span id="error"></span></p>
                        <input type="email" name="email" required="required" id="email"><br>
                        <p class="invalid_email msg_err">有効なメールアドレスを入力してください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">電話番号<span id="error"></span></p>
                        <input type="number" name="tel" required="required" id="phone_number" onkeydown="return event.keyCode !== 69">
                        <p class="invalid_phone msg_err">電話番号は10～11桁で入力してください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">郵便番号<span id="error"></span></p>
                        <input type="number" name="zip"  required="required" id="zip_code" onkeydown="return event.keyCode !== 69">
                        <p class="invalid_zip msg_err">郵便番号は7桁で入力してください</p>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">住所<span id="error"></span></p>
                        <input type="text" name="address" required="required"><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">所属・支店/営業所<span id="error"></span></p>
                        <input type="text" name="department" required="required"><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">権限<span id="error"></span></p>
                        <select id="auth" name="auth">
                            <option value="0" >一般</option>
                            <option value="1" >編集者</option>
                            <option value="2" >管理者</option>
                            <option value="2" >開発者</option>
                        </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">役割<span id="error"></span></p>
                        <select id="role" name="role">
                            <option value="0">営業</option>
                            <option value="1">顧客</option>
                            <option value="2">営業兼顧客</option>
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
<script src="/js/customer-admin-validations/contact-form.js"></script>
@endsection
