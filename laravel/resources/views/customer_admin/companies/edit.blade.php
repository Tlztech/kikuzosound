@extends('customer_admin.layouts.app')
@section('content')
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">企業情報登録</h2>
        <form class="form" method="post" action="{{route('customer_admin_companies_update', $company->id)}}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">企業名<span id="company"></span></p>
                        <input type="text" name="company" value="{{$company->company}}" required="required"><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">よみ(ひらがな)<span id="yomi"></span></p>
                        <input type="text" name="yomi" value="{{$company->yomi}}"><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">役割<span id="role"></span></p>
                        <select id="role" name="role">
                            <option value="0" @if($company->role == 0) selected="selected" @endif>販社</option>
                            <option value="1" @if($company->role == 1) selected="selected" @endif>顧客</option>
                            <option value="2" @if($company->role == 2) selected="selected" @endif>販社兼顧客</option>
                        </select>
                        <br>
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
