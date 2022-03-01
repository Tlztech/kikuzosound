<!doctype html>
<html style="background-color: #fff;">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">

        <title>kikuzosound.com </title>
        
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('/js/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    </head>
    <body>
        <div id="app">
            <!-- <div class="container"> -->
                <div id="router"></div>
            <!-- </div> -->
        </div>
        
        <script src="{{ asset('js/app.js')}}"></script>
    </body>
</html>
