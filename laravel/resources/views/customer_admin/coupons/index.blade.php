@extends('customer_admin.layouts.app')
@section('content')
<!-- coupons -->
<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Coupon information</font></font></h2>
        <ul class="link-ul">
            @foreach($coupons as $coupon)
            <li>
                <a href="{{route('customer_admin_coupons.edit', $coupon->id)}}">
                    {{$coupon->id}}: {{$coupon->code}} ({{$coupon->companies->company}} {{Carbon\Carbon::parse($coupon->start_at)->toDateString()}}~{{Carbon\Carbon::parse($coupon->end_at)->toDateString()}} {{$coupon->cashback}} 円)
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div style="text-align: center;">
        <a href="{{route('customer_admin_coupons.create')}}">
            <button class="customer-admin">新規登録</button>
        </a>
    </div>
    </div>
@endsection
@section('page-script')
<script type="text/javascript">
</script>
@endsection
