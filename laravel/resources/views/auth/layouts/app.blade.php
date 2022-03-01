<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', '3Sポータル') }}</title>

  <!-- Styles -->
  <link href="/css/auth.css" rel="stylesheet">

  <!-- Scripts -->
  <script>
    window.Laravel = <?php echo json_encode([
      'csrfToken' => csrf_token(),
      ]); ?>
  </script>
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-default navbar-static-top">
    </nav>

    @yield('content')
  </div>

  <!-- Scripts -->
  <script src="/js/auth.js"></script>
</body>

</html>
