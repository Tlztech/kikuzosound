<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="/css/common.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/css/telemedica.css" />
    <link rel="stylesheet" type="text/css" href="/css/customer-admin.css" />
    <link href="{{asset('css/bootstrap-switch-button.css')}}" rel="stylesheet"> 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap-table.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <title>3SP　管理者サイト</title>

</head>
<body>
<div class="wrapper">
<div class="header">
    <a class="customer-admin-tophead"><h1 >stetho sound speaker MANAGEMENT SITE</h1><button class="button_head" onclick="logout();">Logout</button></a>
    <ul class="table-ul">
    <!-- <li><a href='{{route('customer_admin_trials')}}'>聴診会員ライブラリ</a></li>
    <li><a href='{{route('customer_admin_exams')}}'>コンダクタ</a></li>
    <li><a href='{{route('customer_admin_application_processing')}}'>申請処理</a></li>
    <li><a href='{{route('customer_admin_registrations')}}'>登録変更</a></li>
    <li><a href='{{route('customer_admin_coupons')}}'>クーポン登録</a></li>
    <li><a href='{{route('customer_admin_products_register')}}'>製品登録</a></li>
    <li><a href='{{route('cookie')}}/3s_animal'>動物版</a></li>
    <li><a href='{{route('cookie')}}/3s_en_kkz'>英語版(仮)</a></li>
    <li><a href='{{route('cookie')}}/3s_local'>ローカル版</a></li>
    <li><a href='{{route('cookie')}}/3s_portal'>開発版</a></li>
    <li><a href='/'>3SP</a></li> -->
    <li><a href="{{url('customer_admin')}}">顧客管理</a></li>
    <li><a href="{{url('customer_admin/exam_groups')}}">Examグループ管理</a></li>
    </ul>

    <ul class="info">
    <li></li>
    <li></li>
    <li>2017年06月13日リリース</li>
    </ul>
</div>
    <div class="main-wrapper customer_admin">
        @yield('content')
    </div>
<div class="footer">
    <p class="contact_submit">
        Copyright &copy; 2017 TELEMEDICA Inc.. 株式会社テレメディカ All Rights Reserved.
    </p>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/jquery.calendar.js"></script>
<script type="text/javascript" src="/js/bootstrap-switch-button.js?v=1.1.6.20210421"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
<script>
    function logout(){
        self.location = '{{url('customer_admin/logout')}}';
    }
</script>
@yield('page-script')
</body>
</html>
