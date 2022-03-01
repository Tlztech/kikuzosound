@extends('customer_admin.layouts.app')
@section('content')
<!-- coupons -->
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">クーポン登録</h2>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <?php
            $r_date = date_create($coupon->start_at);
            $d_date = date_create($coupon->end_at);
        ?>
        <form id="product" class="form" method="post" action="{{route('customer_admin_coupons.update', $coupon->id)}}">
            {{csrf_field()}}
            <ul class="contact_form">
                <li>
                    <label for="" class="">
                        <p class="input_name">発行販社<span id="dealer_id"></span></p>
                        <select name="dealer_id">
                            @foreach($companies as $company)
                            @if($company->id == $coupon->company_id)
                                <option value="{{$company->id}}" selected="$selected">{{$company->company}}</option>
                            @else
                                <option value="{{$company->id}}">{{$company->company}}</option>
                            @endif
                            @endforeach
                        </select>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">クーポンコード<br>
                            @if($errors->has("coupon_code"))
                                <span class="help-block">{{ $errors->first("coupon_code") }}</span>
                            @endif
                        </p>
                        <input type="text" name="coupon_code" value="{{$coupon->code}}" required="required">
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">還元金額<br>
                            @if($errors->has("cashback"))
                                <span class="help-block">{{ $errors->first("cashback") }}</span>
                            @endif
                        </p>
                        <input type="number" name="cashback" value="{{$coupon->cashback}}" min="0" required="required">
                    </label>
                </li>

                <li>
                    <label for="" class="">
                    <p class="input_name">有効期間開始</p>
                    <span id="cal1">
                    <select class="year" id="r_year" name="r_year" style="width:60px;display: inline-block;margin: 14px 2px 10px 10px;">
                        @for ($i = 2018; $i <= 2030; $i++)
                            @if(date_format($r_date,'Y') == $i)
                            <option value="{{$i}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{$i}}" >{{ $i }}</option>
                            @endif
                        @endfor
                    </select>年
                    <select class="month" id="r_month" name="r_month" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 1; $i <= 12; $i++)
                            @if(date_format($r_date,'m') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}" >{{ $i }}</option>
                            @endif
                        @endfor
                    </select>月
                    <select class="date" id="r_day" name="r_day" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 1; $i <= 31; $i++)
                            @if(date_format($r_date,'d') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>日
                    <select id="r_hour" name="r_hour" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 0; $i <= 23; $i++)
                            @if(date_format($r_date,'H') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>時
                    <select id="r_min" name="r_min" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 0; $i <= 59; $i++)
                            @if(date_format($r_date,'i') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>分
                    </span>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                    <p class="input_name">有効期間終了</p>
                    <span id="cal2">
                    <select class="year" id="d_year" name="d_year" style="width:60px;display: inline-block;margin: 14px 2px 10px 10px;">
                        @for ($i = 2018; $i <= 2030; $i++)
                            @if(date_format($d_date,'Y') == $i)
                            <option value="{{$i}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{$i}}" >{{ $i }}</option>
                            @endif
                        @endfor
                    </select>年
                    <select class="month" id="d_month" name="d_month" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 1; $i <= 12; $i++)
                            @if(date_format($d_date,'m') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}" >{{ $i }}</option>
                            @endif
                        @endfor
                    </select>月
                    <select class="date" id="d_day" name="d_day" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 1; $i <= 31; $i++)
                            @if(date_format($d_date,'d') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>日
                    <select id="d_hour" name="d_hour" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 0; $i <= 23; $i++)
                            @if(date_format($d_date,'H') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>時
                    <select id="d_min" name="d_min" style="width:50px;display: inline-block;margin: 14px 2px 10px 0px;">
                        @for ($i = 0; $i <= 59; $i++)
                            @if(date_format($d_date,'i') == $i)
                            <option value="{{ sprintf('%02d',$i)}}" selected="$selected">{{ $i }}</option>
                            @else
                            <option value="{{ sprintf('%02d',$i)}}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>分
                    </span>
                    </label>
                </li>
                <li>
                    <label for="" class="">
                        <p class="input_name">メモ<br>
                            @if($errors->has("memo"))
                                <span class="help-block">{{ $errors->first("memo") }}</span>
                            @endif
                        </p>
                        <input type="text" name="memo" value="{{ $coupon->memo }}" required="required"><br>
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
<script type="text/javascript">
</script>
@endsection
