<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Url disabled</div>
                <a href="/"><button>go to site</button></a>
                <div id="cache_name" data-cache_version="aus-cache{{session('cache_version')}}"></div>
            </div>
        </div>
    </body>
<script>
    $(document).ready(function() {
    var CACHE_NAME = $("#cache_name").data("cache_version");
    caches.open(CACHE_NAME)
	.then(function(cache) {
		cache.delete(window.location.href);
        cache.add(window.location.href)
		return true;
	});
    })
</script>
</html>
