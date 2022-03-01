@extends('layouts.app')

@section('title', trans('app.login'))

@section('breadcrumb')
@endsection

@section('content')

<div id="container">
    <div class="container_inner clearfix">
        <div class="contents_box sp_mTB20 mTB20 t_center">
            <form action="" method="POST">
                <div class="contents_box_inner">
                    <h2 class="title_m mTB20" style="text-align: center;">@lang('customer_login.title')</h2>
                    <div style="max-width:650px;margin:0px auto 20px;text-align: left;">
                        <p style="margin:0px 0px 0px;">
                            @lang("customer_login.info.l1") <br>
                            @lang("customer_login.info.l2")
                        </p>
                    </div>
                </div>
                <ul class="contact_form">
                    <li>
                        <label for="" class="">
                            <p class="input_name must">
                                @lang('customer_login.id')
                                <span>@lang('customer_login.required')</span>
                            </p>
                            <input name="userId" type="text" value=""/>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must">
                                @lang('customer_login.mail')
                                <span>@lang('customer_login.required')</span>
                            </p>
                            <input name="email" type="email" value=""/>
                        </label>
                    </li>
                    <li>
                        <label for="" class="">
                            <p class="input_name must">
                                @lang('customer_login.pw')
                                <span>@lang('customer_login.required')</span>
                            </p>
                            <input name="password" type="password" value=""/>
                            </p>
                        </label>
                    </li>
                </ul>
                <br>
                <div class="contents_box_inner pTB20">
                    <p class="contact_submit mB20">
                        <input class="submit_btn" style="cursor:pointer;" type="submit" value="ログイン"/>
                    </p>
                    <p class="contact_submit"><b>@lang('customer_login.p1')</b></p>
                    <p class="contact_submit"><b>@lang('customer_login.p2')</b></p>
                </div>
            </form>

            <div class="vest">
                <div class="vest_title">@lang('customer_login.vest_title')</div>
                <div class="vest_text">
                    <p class="contact_submit"><b>@lang('customer_login.contact_submit_p')</b></p>
                </div>
                <div class="contents_box_inner pTB20">
                    <p class="contact_submit mB20 sp_none">
                        <a class="submit_btn" href="" style="cursor:pointer;margin-bottom:0px;">@lang('customer_login.go_to_reg')</a>
                    </p>

                    <p class="contact_submit mB20 pc_none" style="text-align:center;">
                        <a class="submit_btn" href="" style="cursor:pointer;width: 240px;">@lang('customer_login.go_to_reg')</a>
                    </p>
                </div>
            </div>
            <p class="contact_submit mB20"><b>@lang('customer_login.contact_submit_p2')</b></p>
        </div>
    </div>
</div>
@endsection
