@extends('layouts.app')

@section('title', 'Contact Us')

@section('breadcrumb')
{!! Breadcrumbs::render('contact') !!}
@endsection

@section('content')
{{-- お問合わせ送信後のメッセージ表示(注意：HTMLのタグを許可している) --}}
@if(Session::has('message'))
<div style="background:#CCFFCC;font-size:19px;color:#004d00;text-align:center;padding:1em;" class="alert-success">
    {!! session('message') !!}
</div>
@endif
<!-- メインコンテンツ（左） -->
<div id="container">
	<!-- お問い合わせ -->
	<div class="container_inner clearfix contact">
		<div class="contents">
			<div class="contents_box clearboth sp_mTB20 mTB20">
				<div class="contents_box_inner pTB20">
					<h2 class="title_m mTB20">@lang('contact.h1') </h2>
					<ul style="padding: 0px 15px 0px 15px;">
						<li>
							<p>@lang('contact.p1')</p>
						</li>
						<li>
							<p>@lang('contact.p2') <a href="{{ route('faq') }}">@lang('contact.a1')</a> @lang('contact.p3')</p>
						</li>
						<li>
							<p>@lang('contact.p4') <a href="{{ route('privacy') }}">@lang('contact.a2') </a> @lang('contact.p5') </p>
						</li>
					</ul>
					<p class="mTB20 sp_t_center"><a href="{{ route('faq') }}" class="blue_btn">@lang('contact.a3')</a>
					</p>
				</div>
			</div>
			<div class="contents_box sp_mTB20 mTB20">
				<div class="contents_box_inner pTB20">
					<h2 class="title_m mTB20">@lang('contact.h2')</h2>
					<ul style="padding: 0px 15px 0px 15px;">
						<li>
							<p>@lang('contact.p6')</p>
						</li>
						<li>
							<p>@lang('contact.p7') <a href="{{ route('faq') }}">@lang('contact.a4')</a> @lang('contact.p8')</p>
						</li>
						</ul>
							<p class="mTB20 sp_t_center"><a href="{{ route('contact_form') }}" class="blue_btn">@lang('contact.a6')</a>
                            </p>
                    </div>
			</div> <?php $sample_status = $params['sample_status']; // 0:未ログイン 1:ログイン(3sp) 2:ログイン(試聴音) ?> <?php $sample_user = $params['sample_user']; // 暗号化されたメアド ?> @if($sample_status == 1) <div class="contents_box sp_mTB20 mTB20">
				<div class="contents_box_inner pTB20">
					<h2 class="title_m mTB20">聴診会員ライブラリ利用ログアウト</h2>
					<ul style="padding: 0px 15px 0px 15px;">
						<li>
							<p>・聴診会員ライブラリを聴く等、一部無料の機能をログアウトします。</p>
						</li>
						<li>
							<p>・個人情報の取り扱いにつきましては「<a href="{{ route('privacy') }}">プライバシーポリシー</a>」のページをご覧ください。 </p>
						</li>
					</ul>
					<p class="mTB20 sp_t_center blue_btn" id="sample-logout" style="cursor: pointer;"> ログアウト</p>
				</div>
			</div> @endif <div class="contents_box sp_mTB20 mTB20">
				<div class="contents_box_inner pTB20">
					<h2 class="title_m mTB20">@lang('contact.h4')</h2>
					<ul style="padding: 0px 15px 0px 15px;">
						<li>
							<p>@lang('contact.p13')</p>
						</li>
						<li>
							<p>@lang('contact.p14') <a href="{{ route('privacy') }}">@lang('contact.a9')</a> @lang('contact.p15') </p>
						</li> <?php /* shimizu@telemedica.co.jpの場合は表示しない */ ?> @if($sample_user != "n89GfPNcyNCjODQeHVK0QK6eizAnRyIto7onfk8lD_4-") <p class="mT20 sp_t_center" style="display: inline;margin-top:20px"><a href="{{ route('r-mail-form') }}<?php echo("?edit=".$sample_user); ?>" class="blue_btn">@lang('contact.a10')</a></p> @endif @if($params['auth']==1) <a href="{{ route('member_logout') }}" class="mTB20 sp_t_center blue_btn"><span>@lang('contact.a12')</span></a> @else <a href="{{ route('member_login') }}" class="mTB20 sp_t_center blue_btn"><span>@lang('contact.a11')</span></a> @endif
				</div>
			</div> <?php $cancel = $params['cancel']; // 0:未表示 1:表示 ?> <?php $delete_at = $params['delete_at']; ?> @if($cancel == 1) <div class="contents_box sp_mTB20 mTB20">
				<div class="contents_box_inner pTB20">
					<h2 class="title_m mTB20">サイト使用プラン【ご解約】</h2>
					<p>・途中解約の場合の利用料は、契約期間満了するまでの利用料残総額を一括でお支払いいただきます。 </p>
					<p>・解約は3ヶ月前の申請となる為、実際の解約は3ヶ月後(月単位)となります。</p>
					<p>・この処理は取消ができません。十分ご確認の上、解約手続きをして下さい。</p>
					<p>・個人情報の取り扱いにつきましては「<a href="{{ route('privacy') }}">プライバシーポリシー</a>」のページをご覧ください。 </p>
					<p class="mTB20 sp_t_center"><a href="{{ route('cancel_form') }}" class="blue_btn">ご解約</a></p>
				</div>
			</div> @elseif($cancel == 2) <div class="contents_box sp_mTB20 mTB20">
				<div class="contents_box_inner pTB20">
					<h2 class="title_m mTB20">サイト使用プラン【ご解約予定】</h2>
					<p>・途中解約の場合の利用料は、契約期間満了するまでの利用料残総額を一括でお支払いいただきます。 </p>
					<p>・解約は3ヶ月前の申請となる為、実際の解約は3ヶ月後(月単位)となります。</p>
					<p>・この処理は取消ができません。十分ご確認の上、解約手続きをして下さい。</p>
					<p>・個人情報の取り扱いにつきましては「<a href="{{ route('privacy') }}">プライバシーポリシー</a>」のページをご覧ください。 </p>
					<p class="mTB20 sp_t_center"> <?= $delete_at ?>　以降、ログインできなくなります。</p>
				</div>
			</div> @endif
		</div>
		<!-- サイドコンテンツ（右） -->
		<div class="side_column">
			<!-- Aboutリンクー --> @include('layouts.about_menus')
			<!-- 聴診専用スピーカとは？ --> @include('layouts.whatspeaker') </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#sample-logout").on("click", function() {
        $.ajax({
                url: "./ajaxsamplelogout",
                type: "get",
                data: {
                    "data": "data"
                },
                cache: false,
                dataType: "html"
            })
            .done(function(res) {
                location.reload();
            })
            .fail(function(jqxhr, status, error) {});
    });
    /************************************************************/
    /* 
    試聴音登録者削除(暫定版)
    確認作業が終わったら、削除する事
    */
    /************************************************************/
    $("#sample-delete").on("click", function() {
        $.ajax({
                url: "./ajaxsampledelete",
                type: "get",
                data: {
                    "data": "data"
                },
                cache: false,
                dataType: "html"
            })
            .done(function(res) {
                location.reload();
            })
            .fail(function(jqxhr, status, error) {});
    });
});
</script>
@endsection