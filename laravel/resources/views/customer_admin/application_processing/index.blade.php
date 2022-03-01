@extends('customer_admin.layouts.app')
@section('content')
<style type="text/css">
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    a{
        color: #fff;
    }
</style>

<div class="container">
    <div class="mat">
        <h2 class="title_m" style="text-align: center;">【認証申請】のメールが来た時の処理手順</h2>
        <div class="step">
            <p class="title_step">①「販社」の確認と登録</p>
            <input type="text" name="search" id="search1" placeholder="1.会社名の一部を入力">
            <input type="button" id="search1_btn" name="search1_btn" class="search_btn" value="検索">
            <ul class="link-ul">
                <div id="d_result"></div>
            </ul>
            <a href="{{route('customer_admin_companies_create')}}" class="link_btn">1. 会社がなければ新規登録</a>
            <input type="text" name="search" id="search4" placeholder="2.担当者名の一部を入力">
            <input type="button" id="search4_btn" name="search4_btn" class="search_btn" value="検索">
            <ul class="link-ul">
                <div id="e_result"></div>
            </ul>
            <a href="{{route('customer_admin_contacts_create')}}" class="link_btn">2. 担当者がいなければ新規登録</a>
        </div>
            <div class="down_arrow"></div>
            <div class="step">
                <p class="title_step">②「顧客」の確認と登録</p>
                <!--
                <a onclick="open_menu('c_contact')" class="non_link_btn">1. 契約者が登録されているかを確認</a>
                <br>
                -->
                <input type="text" name="search2" id="search2" placeholder="3.会社名の一部を入力">
                <input type="button" id="search2_btn" name="search2_btn" class="search_btn" value="検索">
                <ul class="link-ul">
                    <div id="c_result"></div>
                </ul>
                <a href="{{route('customer_admin_companies_create')}}" class="link_btn">3. 会社がなければ新規登録</a>
                <input type="text" name="search" id="search5" placeholder="4.契約者名の一部を入力">
                <input type="button" id="search5_btn" name="search5_btn" class="search_btn" value="検索">
                <ul class="link-ul">
                    <div id="f_result"></div>
                </ul>
                    <a href="{{route('customer_admin_contacts_create')}}" class="link_btn">4. 契約者がいなければ新規登録</a>
                </div>
                <div class="down_arrow"></div>
                <div class="step">
                    <p class="title_step">③「契約情報」の確認と登録</p>
                    <input type="text" name="search" id="search3" placeholder="5.契約情報名の一部を入力">
                    <input type="button" id="search6_btn" name="search5_btn" class="search_btn" value="検索">
                    <ul class="link-ul">
                        <div id="y_result"></div>
                    </ul>
                    <a href="{{route('customer_admin_accounts_create')}}" class="link_btn">5. 契約情報がなければ新規登録</a>
                    <p style="text-align:left;margin:0px 8px 8px 8px;">もし以前に「契約情報」があり「ユーザID」と「パスワード」を変更しないで「聴くゾウ」を追加する場合は「新規登録」ではなく、上記の検索結果から「契約情報の登録変更」を行う事。</p>
                </div>
                <div class="down_arrow"></div>
                <div class="step">
                    <p class="title_step">④「ログイン」の確認</p>
                    <a href="http://3sportal.localhost" class="link_btn" target="_blank">1. 聴診ポータルサイト</a>
                    <p style="text-align:left;margin:0px 8px 8px 8px;">作成した「ユーザID」「パスワード」で実際にログインできるか確認。確認後は必ず<span style="color:#ff0000;">ログアウト</span>する事。</p>
                </div>
                <div class="down_arrow"></div>
                <div class="step">
                    <p class="title_step">⑤「メール」の送信</p>
                    <div class="explain" >
                        <hr>
                        <p style="text-align:center;">購入の場合</p>
                        <hr style="margin-bottom:10px;">
                        <div style="text-align:center;">
                            <a onclick="open_menu('send_mail')" style="color: #EFF6F6;" class="link_btn">1. 「顧客」に発行アカウントを送信</a>
                        </div>
                        <div id="send_mail" style="display:none">
                            <br>
                            <hr>
                            <p><a href="https://wbtxa.scorpio.oneoffice.jp/" target="_blank">メールはここから送信</a><br>
                            ユーザID：3sp@telemedica.co.jp<br>
                            パスワード：telemedica<br>
                        </p>
                        <hr>
                        <p>件名：「聴くゾウ」のご登録(アカウント通知)につきまして</p>
                        <hr>
                        <p>
                            XXX病院 XXX様<br>
                            <br>
                            株式会社テレメディカ・カスタマーセンターでございます。<br>
                            この度は「聴くゾウ」のご購入、誠にありがとうございます。<br>
                            <br>
                            「聴診ポータルサイト」をご利用頂く為のアカウントを発行させて頂きました。<br>
                            <br>
                            ・ユーザID　　XXX<br>
                            ・パスワード　XXX<br>
                            ・対象シリアル番号　00000001～00000010<br>
                            <br>
                            下記「ログイン画面」のURLをクリックして頂き、上記「ユーザID」「パスワード」と対象の「シリアル番号」をご入力頂ければ、ご利用が可能となります。<br>
                            <br>
                            ・ログイン画面URL<br>
                            https://3sportal.telemedica.co.jp/member_login<br>
                            <br>
                            ※「ユーザID」と「パスワード」は大切に保管して頂きますよう、お願い致します。<br>
                            ※「聴くゾウ」を複数台ご購入頂いた場合でも「ユーザID」と「パスワード」は共通でご利用頂けます。<br>
                            但し、一つのシリアル番号を複数の端末で同時利用することはできません。<br>
                            <br>
                            なお、本メールにお心当たりのない場合はお手数ですが、下記までご連絡の程、よろしくお願い致します。<br>
                            <br>
                            --------------------------------------------------<br>
                            株式会社テレメディカ・カスタマーセンター<br>
                            mail : 3sp@telemedica.co.jp<br>
                            TEL : 045－875-1924<br>
                            FAX : 045－875-2059<br>
                            --------------------------------------------------<br>
                        </p>
                    </div>
                    <div style="text-align:center;">
                        <a onclick="open_menu('send_mail_dealer')" style="color: #EFF6F6;" class="link_btn">2. 「販社」に登録完了の旨を送信</a>
                    </div>
                    <div id="send_mail_dealer" style="display:none">
                        <br>
                        <hr>
                        <p><a href="https://wbtxa.scorpio.oneoffice.jp/" target="_blank">メールはここから送信</a><br>
                        ユーザID：3sp@telemedica.co.jp<br>
                        パスワード：telemedica<br>
                    </p>
                    <hr>
                    <p>
                        下記をCCにして入れる<br>
                        ケンツメディコ　eigyo@kenzmedico.co.jp<br>
                        ケンツ事業部　　n.matsuhira@kenzmedico.co.jp<br>
                    </p>
                    <hr>
                    <p>件名：XXX病院様へのご登録完了につきまして</p>
                    <hr>
                    <p>
                        XXX株式会社 XXX様<br>
                        <br>
                        株式会社テレメディカ・カスタマーセンターです。<br>
                        <br>
                        「聴くゾウ」をご購入頂きましたXXX病院様へのアカウントを発行し、ご担当のXXX様へメールでお伝え致しました。<br>
                        <br>
                        ・対象シリアル番号　00000001～00000010<br>
                        <br>
                        今後ともよろしくお願い申し上げます。<br>
                        <br>
                        なお、本メールにお心当たりのない場合はお手数ですが、下記までご連絡の程、よろしくお願い致します。<br>
                        <br>
                        --------------------------------------------------<br>
                        株式会社テレメディカ・カスタマーセンター<br>
                        mail : 3sp@telemedica.co.jp<br>
                        TEL : 045－875-1924<br>
                        FAX : 045－875-2059<br>
                        --------------------------------------------------<br>
                    </p>
                </div>
                <hr>
                <p style="text-align:center;">サイト利用プランの場合</p>
                <hr style="margin-bottom:10px;">
                <div style="text-align:center;">
                    <a onclick="open_menu('use_site')" style="color: #EFF6F6;" class="link_btn">1. 「顧客」に発行アカウントを送信</a>
                </div>
                <div id="use_site" style="display:none">
                    <br>
                    <hr>
                    <p><a href="https://wbtxa.scorpio.oneoffice.jp/" target="_blank">メールはここから送信</a><br>
                    ユーザID：3sp@telemedica.co.jp<br>
                    パスワード：telemedica<br>
                </p>
                <hr>
                <p>パスワード発行なのでスズケン担当者には送信しない</p>
                <hr>
                <p>件名：「聴くゾウ」のご登録(アカウント通知)につきまして</p>
                <hr>
                <p>
                    XXX病院 XXX様<br>
                    <br>
                    株式会社テレメディカ・カスタマーセンターでございます。<br>
                    この度は「サイト利用プラン」のご利用、誠にありがとうございます。<br>
                    <br>
                    「サイト利用プラン」をご利用頂く為のアカウントを発行させて頂きました。<br>
                    <br>
                    ・ユーザID　　XXX<br>
                    ・パスワード　XXX<br>
                    ・対象シリアル番号　00000001～00000010<br>
                    <br>
                    サイト利用開始日の20XX年XX月01日以降、下記「ログイン画面」のURLをクリックして頂き、上記「ユーザID」「パスワード」と対象の「シリアル番号」をご入力頂ければ、ご利用が可能となります。<br>
                    <br>
                    【ご注意】サイト利用開始日になりませんと、上記アカウントをご入力頂いてもご利用頂けません。<br>
                    <br>
                    ・ログイン画面URL<br>
                    https://3sportal.telemedica.co.jp/member_login<br>
                    <br>
                    ※「ユーザID」と「パスワード」は大切に保管して頂きますよう、お願い致します。<br>
                    ※「聴くゾウ」を複数台ご利用頂く場合でも「ユーザID」と「パスワード」は共通でご利用頂けます。<br>
                    但し、一つのシリアル番号を複数の端末で同時利用することはできません。<br>
                    <br>
                    なお、本メールにお心当たりのない場合、お問合せにつきましてはお手数ですが、下記までご連絡の程、よろしくお願い致します。<br>
                    <br>
                    --------------------------------------------------<br>
                    株式会社テレメディカ・カスタマーセンター<br>
                    mail : 3sp@telemedica.co.jp<br>
                    TEL : 045－875-1924<br>
                    FAX : 045－875-2059<br>
                    --------------------------------------------------<br>
                </p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(function() {
        $('#search1_btn').click(function() {
            $.ajax('{{route('customer_admin_companies_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search1').val(),
                    role: 0
                },
                success: function (data, status, xhr) {
                    $('#d_result').empty();
                    for (var i = 0; i < data.length; i++) {
                        var item = '<li><a style="color:#08D1FF;" href="{{url('/customer_admin/companies/edit')}}/'+data[i].id+'" >'+data[i].id+': '+data[i].company+' ['+data[i].contacts.length+'名]</a><br>';
                        for (var j = 0; j < data[i].contacts.length; j++) {
                            item += '<a style="margin-left:5%;color:#08D1FF;" href="{{url('/customer_admin/contacts/edit')}}/'+data[i].contacts[j].id+'" >    '+data[i].contacts[j].id+': '+data[i].contacts[j].name+'</a><br>';
                        }
                        item += '</li>';
                        $('#d_result').append(item);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#d_result').empty();
                    $('#d_result').append('Error' + errorMessage);
                }
            });
        });
        $('#search4_btn').click(function() {
            $.ajax('{{route('customer_admin_companies_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search4').val(),
                    role: 0
                },
                success: function (data, status, xhr) {
                    $('#e_result').empty();
                    var item = '';
                    for (var i = 0; i < data.length; i++) {
                        for (var j = 0; j < data[i].contacts.length; j++) {
                            item += '<li><a style="margin-left:5%;color:#08D1FF;" href="{{url('/customer_admin/contacts/edit')}}/'+data[i].contacts[j].id+'" >    '+data[i].contacts[j].id+': '+data[i].contacts[j].name+'</a></li>';
                        }
                    }
                    $('#e_result').append(item);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#e_result').empty();
                    $('#e_result').append('Error' + errorMessage);
                }
            });
        });
        $('#search2_btn').click(function() {
            $.ajax('{{route('customer_admin_companies_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search2').val(),
                    role: 1
                },
                success: function (data, status, xhr) {
                    $('#c_result').empty();
                    for (var i = 0; i < data.length; i++) {
                        var item = '<li><a style="color:#08D1FF;" href="{{url('/customer_admin/companies/edit')}}/'+data[i].id+'" >'+data[i].id+': '+data[i].company+' ['+data[i].contacts.length+'名]</a><br>';
                        for (var j = 0; j < data[i].contacts.length; j++) {
                            item += '<a style="margin-left:5%;color:#08D1FF;" href="{{url('/customer_admin/contacts/edit')}}/'+data[i].contacts[j].id+'" >    '+data[i].contacts[j].id+': '+data[i].contacts[j].name+'</a><br>';
                        }
                        item += '</li>';
                        $('#c_result').append(item);
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#c_result').empty();
                    $('#c_result').append('Error' + errorMessage);
                }
            });
        });
        $('#search5_btn').click(function() {
            $.ajax('{{route('customer_admin_companies_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search5').val(),
                    role: 1
                },
                success: function (data, status, xhr) {
                    $('#f_result').empty();
                    var item = '';
                    for (var i = 0; i < data.length; i++) {
                        for (var j = 0; j < data[i].contacts.length; j++) {
                            item += '<li><a style="margin-left:5%;color:#08D1FF;" href="{{url('/customer_admin/contacts/edit')}}/'+data[i].contacts[j].id+'" >    '+data[i].contacts[j].id+': '+data[i].contacts[j].name+'</a></li>';
                        }
                    }
                    $('#f_result').append(item);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#f_result').empty();
                    $('#f_result').append('Error' + errorMessage);
                }
            });
        });

        $('#search6_btn').click(function() {
            $.ajax('{{route('customer_admin_accounts_data')}}', {
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    search: $('#search3').val(),
                    role: 1
                },
                success: function (data, status, xhr) {
                    $('#y_result').empty();
                    var item = '';
                    for (var i = 0; i < data.length; i++) {
                        item += '<li><a style="margin-left:5%;color:#08D1FF;" href="{{url('/customer_admin/accounts/edit')}}/'+data[i].id+'" > '+data[i].c_com+': '+data[i].c_name+'</a><br><a style="margin-left:5%;color:#08D1FF;" href="{{url('/customer_admin/accounts/edit')}}/'+data[i].id+'" > '+data[i].d_com+': '+data[i].d_name+'</a><br><br></li>';
                    }
                    $('#y_result').append(item);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#y_result').empty();
                    $('#y_result').append('Error' + errorMessage);
                }
            });
        });
    });
</script>
@endsection
