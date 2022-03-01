<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="app_version" content="{{Session::has('version')? session('version') : '0'}}"/>

    <title>3Sポータルサイト管理</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('/css/easySelectStyle.css')}}">
    <link rel="stylesheet" href="/css/bootstrap-table.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap-table.min.js"></script>
    <script src="/js/admin.js?v=1.2.3.20170731"></script>
    <script src="/js/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script src="{{asset('/js/easySelect.js')}}"></script>
    <!-- bxSlider Javascript file -->
    <script src="http://3sportal.localhost:52501/js/bxslider/jquery.bxslider.js"></script>
    <!-- bxSlider CSS file -->
    <link href="http://3sportal.localhost:52501/js/bxslider/jquery.bxslider.css" rel="stylesheet" />
    <script>
    tinymce.init({
        selector: '#description-field',
        branding: false,
        toolbar: 'bold italic underline | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
    });
    tinymce.init({
        selector: '#description-en-field',
        branding: false,
        toolbar: 'bold italic underline | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
    });
    tinymce.init({
        selector: '#img_description-field',
        branding: false,
        toolbar: 'bold italic underline | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
    });
    tinymce.init({
        selector: '#img_description-en-field',
        branding: false,
        toolbar: 'bold italic underline | fontselect fontsizeselect formatselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
    });
    // tinymce.init({
    //     selector: '#configuration-field',
    //     branding: false,
    //     toolbar: 'code',
    // });
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <script type="text/javascript">
    $(document).ready(function() {
        var currentlyScrolling = false;
        var SCROLL_AREA_HEIGHT = 40; // Distance from window's top and 
        $("#stetho_sound_list__tbody, #exam_list__tbody, #sortable_tbody").sortable({
            scroll: false,
            sort: function(event, ui) {
                if (currentlyScrolling) {
                    return;
                }
                var windowHeight   = $(window).height();
                var mouseYPosition = event.clientY;

                if (mouseYPosition < SCROLL_AREA_HEIGHT) {
                    currentlyScrolling = true;

                    $('html, body').animate({
                        scrollTop: "-=" + windowHeight / 2 + "px" // Scroll up half of window height.
                    }, 
                    400, // 400ms animation.
                    function() {
                        currentlyScrolling = false;
                    });

                } else if (mouseYPosition > (windowHeight - SCROLL_AREA_HEIGHT)) {

                    currentlyScrolling = true;

                    $('html, body').animate({
                        scrollTop: "+=" + windowHeight / 2 + "px" // Scroll down half of window height.
                    }, 
                    400, // 400ms animation.
                    function() {
                        currentlyScrolling = false;
                    });

                }
            },
        });
    });
    </script>
</head>


<?php

// //GET THE BROWSER TOKEN
// $browser_token = session('bwtk');

// //IF BROWSER TOKEN EXISTS IN LOCAL BROWSER
// if($browser_token){
//   //CHECK IF THERE IS ONETIME TOKEN FROM THE DATABASE OF THE USER
//   $current_user_id = session('current_user_id');
//   $is_exists_onetime_key = DB::select('SELECT onetime_key FROM onetime_keys WHERE user_id = ?', [$current_user_id]);
//   $onetime_token = $is_exists_onetime_key[0]->onetime_key;

//   //IF THERE IS ONETIME TOKEN
//   if($onetime_token){
//     //THEN ENCRYPT AND COMPARE TO THE EXISTING LOCAL BROWSER
//     $encrypted_token = openssl_encrypt($onetime_token, 'AES-128-ECB', csrf_token());
//     // echo $browser_token."--->".$encrypted_token;

//     //IF NOT EQUAL
//     // var_dump($browser_token);
//     if($browser_token !== $encrypted_token){
//       echo "<script>window.location='".env("APP_URL")."admin/logout'</script>";
//       // redirect('admin/logout');
//     }
//   }else{
//     //make a onetime_key that will be used to insert in onetime_keys table
//     $onetime_key = substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4);

//     //INSERT NEW TOKEN IN THE TABLE WITH USERID
//     $insert_user_id_and_onetime_key = DB::table("onetime_keys")->insert(['user_id' => $current_user_id, 'onetime_key' => $onetime_key]);

//     //THEN ENCRYPT THE TOKEN AND STORE IT IN LOCAL BROWSER
//     $encrypted_token = openssl_encrypt($onetime_key, 'AES-128-ECB', csrf_token());
//     session(['bwtk' => $encrypted_token]);
//   }

// }
?>

<body>
    <div>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{route('admin.quiz_packs.index')}}">3Sポータルサイト管理</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                        <li><a href="{{ url('/admin/login') }}">ログイン</a></li>
                        @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/admin/logout') }}" onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                                        ログアウト
                                    </a>
                                    <form id="logout-form" action="{{ url('/admin/logout') }}" method="GET"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li><a href="/" target="_blank">サイトを表示</a></li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                    <ul class="nav nav-sidebar">
                        {{-- システム管理者の場合は表示 --}}
                        @if (Auth::user()->role == App\User::$ROLE_ADMIN)
                        <li><a href="{{route('admin.exams.index')}}">EXAM一覧</a></li>
                        <li><a href="{{route('admin.quiz_packs.index')}}">クイズパック一覧</a></li>
                        <li style="padding-left:1em;"><a href="{{route('admin.quizzes.index')}}">クイズ一覧</a></li>
                        @endif
                        <li style="padding-left:2em;"><a href="{{route('admin.iPax.index')}}">iPaxライブラリ</a>
                        </li>
                        <li style="padding-left:2em;"><a href="{{route('admin.stetho_sounds.index')}}">聴診音ライブラリ</a></li>
                        <li style="padding-left:2em;"><a href="{{route('admin.palpation_library.index')}}">触診ライブラリ</a>
                        </li>
                        <li style="padding-left:2em;"><a href="{{route('admin.ecg_library.index')}}">心電図ライブラリ</a></li>
                        <li style="padding-left:2em;"><a href="{{route('admin.inspection_library.index')}}">視診ライブラリ</a></li>
                        <li style="padding-left:2em;"><a href="{{route('admin.xray_library.index')}}">レントゲンライブラリ</a></li>
                        <li style="padding-left:2em;"><a href="{{route('admin.ucg_library.index')}}">心エコーライブラリ</a></li>
                        {{-- システム管理者の場合は表示 --}}
                        @if (Auth::user()->role == App\User::$ROLE_ADMIN)
                        <li><a href="{{route('admin.users.index')}}">監修者一覧</a></li>
                        <li style="padding-left:2em;"><a href="{{route('admin.users.university.index')}}">Super admin</a></li>
                        <li style="padding-left:2em;"><a href="{{route('admin.users.teachers.index')}}">Group Admin (teachers)</a></li>
                        <li><a href="{{route('admin.informations.index')}}">お知らせ管理一覧</a></li>
                        <li><a href="{{route('admin.group_management.index')}}">コンテンツ権限一覧</a></li>
                        <!-- <li><a href="{{route('access_log')}}">Access log</a></li> -->
                        @endif
                        <li>
                            <a href="{{ url('/admin/logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                                ログアウト
                            </a>
                            <form id="logout-form" action="{{ url('/admin/logout') }}" method="GET"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-9 admin-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
</body>
@yield('page-script')
<script>
$("img").on('error', function(){
    $(this).addClass("img-error");
});
$("#exam_group-field").select2({
    placeholder: "グループ名を選択します"
});
</script>
</html>