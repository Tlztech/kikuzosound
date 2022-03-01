@extends('customer_admin.layouts.app')
@section('content')
<style type="text/css">
    select{
        -webkit-appearance: button !important;
    }
    .title_m{
        display: inline-block;
        width: 70%;
        margin-left: 60%;
    }
    .contact_form input{
        width: 56%;
    }
    .contact_form select{
        width: 56%;
    }
    .select2{
        margin-left:10px;
    }
    .select2-container .select2-search--inline .select2-search__field{
        font-size: 125% !important;
        margin-top:8px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        font-size :15px;
    }
    .select2.select2-container.select2-container--default{
        margin: 14px 0 10px 10px;
    }
    .select2-container--default .select2-selection--multiple{
        width: 362px;
        border-radius: 0px;
        border:1px solid #ccc !important;
        height: 0px;

    }
</style>
<div class="container">
    <div class="mat">
        <ul class="info">
                <!-- 中央揃えの為の空き -->
            <li><h2 class="title_m">契約情報登録</h2></li>
            <li><a href="{{route('customer_admin_accounts_create')}}" id="c_delete"><button class="submit_btn"> 削除 </button></a></li>
        </ul>
        <form id="f_contract" class="form" method="post" action="{{route('customer_admin_accounts_store')}}">
            {{csrf_field()}}
            <input type="hidden" id="user" name="user" required="required">
            <input type="hidden" id="password" name="password" value="{{$password}}">
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                    <p class="input_name">販社連絡先</p>
                    <select name="dealer_id">
                        @foreach($sales_contacts as $contact)
                            <option value="{{$contact->id}}">{{$contact->company->company}}　{{$contact->name}}</option>
                        @endforeach
                    </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">顧客連絡先</p>
                    <select name="customer_id">
                        @foreach($customer_contacts as $contact)
                            <option value="{{$contact->id}}">{{$contact->company->company}}　{{$contact->name}}</option>
                        @endforeach
                    </select><br>
                    </label>
                </li>
                {{-- <li style="display:none">
                    <label for="" class="">
                    <p class="input_name">発行アカウント<span id="error1"></span></p>
                    <input type="hidden" id="user" name="user" required="required"><br>
                    </label>
                </li>
                <li style="display:none">
                    <label for="" class="">
                    <p class="input_name">発行パスワード<span id="error2"></span></p>
                    <input type="hidden" id="password" name="password" value="{{$password}}">
                    <br>&nbsp&nbsp&nbsp※何も入れない場合は、前のままのパスワードになります<br>
                    </label>
                </li> --}}
                <li>
                    <label for="" class="">
                    <p class="input_name">重複</p>
                    <select id="auth" name="auth">
                        <option value="0" >非許可</option>
                        <option value="1" >許可</option>
                    </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">購入種類</p>
                    <select id="usage_type" name="usage_type">
                        <option value="0">購入</option>
                        <option value="1">年間利用１年(月払い・請求書発行)</option>
                        <option value="2">年間利用１年(年払い・請求書発行)</option>
                        <option value="3">年間利用３年(月払い・請求書発行)</option>
                        <option value="4">年間利用３年(年払い・請求書発行)</option>
                        <option value="5">年間利用１年(月払い・請求書なし)</option>
                        <option value="6">年間利用１年(年払い・請求書なし)</option>
                        <option value="7">年間利用３年(月払い・請求書なし)</option>
                        <option value="8">年間利用３年(年払い・請求書なし)</option>
                        <option value="9">購入(請求書発行)</option>
                        <option value="99" >デモ</option>
                    </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">予約</p>
                    <select id="reservation" name="reservation">
                        <option value="0" selected="selected">予約なし</option>
                        <option value="1">予約あり</option>
                    </select><br>
                    <select id="r_register" name="r_register">
                        <option value="0" selected="selected">予約登録なし</option>
                        <option value="1">予約登録あり</option>
                        <option value="2">予約登録削除</option>
                    </select><br>
                    <span id="cal1">
                        <select class="year" id="r_year" name="r_year" style="width:80px;display: inline-block;margin: 14px 2px 10px 10px;">
                            @foreach(config('select.years') as $i)
                                @if(date('Y') == $i)
                                <option value="{{$i}}" selected="$selected">{{ $i }}</option>
                                @else
                                <option value="{{$i}}" >{{ $i }}</option>
                                @endif
                            @endforeach
                        </select>年
                        <select class="month" id="r_month" name="r_month" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                            @foreach (config('select.months') as $i)
                                @if(date('m') == $i)
                                <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                                @else
                                <option value="{{ sprintf('%02d',$i)}}" >{{ $i }}</option>
                                @endif
                            @endforeach
                        </select>月
                        <select class="date" id="r_day" name="r_day" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                            @foreach (config('select.days') as $i)
                                @if(date('d') == $i)
                                <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                                @else
                                <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                                @endif
                            @endforeach
                        </select>日
                        <select id="r_hour" name="r_hour" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                            @foreach (config('select.hours') as $i)
                                @if(date('H') == $i)
                                <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                                @else
                                <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                                @endif
                            @endforeach
                        </select>時
                        <select id="r_min" name="r_min" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                            @foreach (config('select.mins') as $i)
                                @if(date('i') == $i)
                                <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                                @else
                                <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                                @endif
                            @endforeach
                        </select>分
                    </span>
                    <select id="d_register" name="d_register">
                        <option value="0">予約削除なし</option>
                        <option value="1">予約削除あり</option>
                        <option value="2">予約削除削除</option>
                    </select><br>
                    <span id="cal2">
                    <select class="year" id="d_year" name="d_year" style="width:80px;display: inline-block;margin: 14px 2px 10px 10px;">
                        @foreach(config('select.years') as $i)
                            @if(date('Y') == $i)
                            <option value="{{$i}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{$i}}" >{{ $i }}</option>
                            @endif
                        @endforeach
                    </select>年
                    <select class="month" id="d_month" name="d_month" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @foreach (config('select.months') as $i)
                            @if(date('m') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}" >{{ $i }}</option>
                            @endif
                        @endforeach
                    </select>月
                    <select class="date" id="d_day" name="d_day" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @foreach (config('select.days') as $i)
                            @if(date('d') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endforeach
                    </select>日
                    <select id="d_hour" name="d_hour" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @foreach (config('select.hours') as $i)
                            @if(date('H') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endforeach
                    </select>時
                    <select id="d_min" name="d_min" style="width:60px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @foreach (config('select.mins') as $i)
                            @if(date('i') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endforeach
                    </select>分
                    </span>
                    <div style="margin-top:10px;">
                        <button class="easyButton" type="button" data-year="1" data-month="1">翌月から1年契約</button>
                        <button class="easyButton" type="button" data-year="3" data-month="1">翌月から3年契約</button>
                        <button class="easyButton" type="button" data-year="1" data-month="2">翌々月から1年契約</button>
                        <button class="easyButton" type="button" data-year="3" data-month="2">翌々月から3年契約</button>
                    </div>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">更新ご案内方法</p>
                    <select id="add_way" name="add_way">
                        <option value="0">なし</option>
                        <option value="1">電話</option>
                        <option value="2">郵送</option>
                        <option value="3">電話と郵送</option>
                    </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">クーポン</p>
                    <select id="coupon_id" name="coupon_id">
                        <option value="0" selected="selected">なし</option>
                        <option value="1">suzuken001(株式会社スズケン 2018-09-03～2018-12-28 5000円)</option>
                        <option value="2">132654987(株式会社テレメディカ 2020-03-30～2020-03-30 10000円)</option>
                    </select><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">メモ</p>
                    <input type="text" id="memo" name="memo" value=""><br>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">製品番号<span id="error3"></span></p>
                    <select class="js-example-basic-multiple" name="product_no[]" multiple="multiple" style="margin-left:50px;width: 50%;">
                        @foreach($products as $product)
                            <option style="width:100px;" value="{{$product->id}}">{{$product->id}}</option>
                        @endforeach
                    </select>
                    </label>
                </li>
            </ul>
            <p class="contact_submit">
                        <button type="submit" class="submit_btn"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">登録</font></font></button>
                    </p>
        </form>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(function() {
        $('.js-example-basic-multiple').select2({
            placeholder: "製品を選択"
        });
        $(".easyButton").on("click", function() {
            var type = $(this).data('year');
            var offset = $(this).data('month');

            $("#reservation").val("1");
            $("#r_register").val("1");

            var dt = new Date();    // 日時
            dt.setDate(1);  // まず1日にする
            dt.setMonth(dt.getMonth() + offset); // 日を1日にしてその翌月、とか
            var year = dt.getFullYear();    // 年
            var month = dt.getMonth()+1;    // 翌月を取得
            var date = dt.getDate();    // 1日を取得(単に"01"で良いけど)
            month = zeroPadding(month,2);   // 0パディング
            date = zeroPadding(date,2); // 0パディング
            $("#cal1").find("#r_year").val(year);
            $("#cal1").find("#r_month").val(month);
            $("#cal1").find("#r_day").val(date);
            $("#cal1").find("#r_hour").val("00");
            $("#cal1").find("#r_min").val("00");

            /* 予約削除 */
            $("#d_register").val("1");

            /* 予約削除日時 */
            var dt = new Date();    // 日時
            dt.setDate(1);  // まず1日にする
            dt.setMonth(dt.getMonth() + offset); // 日を1日にしてその翌月、とか
            dt.setFullYear(dt.getFullYear() + type); // 日を1日にしてその1年後、とか

            var year = dt.getFullYear();    // 年
            var month = dt.getMonth()+1;    // 翌月を取得
            var date = dt.getDate();    // 1日を取得(単に"01"で良いけど)

            month = zeroPadding(month,2);   // 0パディング
            date = zeroPadding(date,2); // 0パディング

            $("#cal2").find("#d_year").val(year);
            $("#cal2").find("#d_month").val(month);
            $("#cal2").find("#d_day").val(date);
            $("#cal2").find("#d_hour").val("00");
            $("#cal2").find("#d_min").val("00");
        });

        /* 0パディング */
        function zeroPadding(num,length)
        {
            return ('0000000000' + num).slice(-length);
        }
    });
</script>
@endsection
