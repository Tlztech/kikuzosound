<?php
$PageName = "jump";
$UrlJumpTo3SP = env('APP_URL') . 'home';
$UrlJumpToTMN = env('TMN_URL');
?>
<html>
	<head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta http-equiv="refresh" content="0; URL={{ $UrlJumpToTMN }}">
	</head>
	<body style="background-color: #FDFCFF">
            <p>3SPにログインしました。</p>
            <a href="{{ $UrlJumpTo3SP }}">[3SPトップページを表示する]</a><br>
            <br>
            <a href="{{ $UrlJumpToTMN }}">[テレメディカネットに戻る]</a><br>
<!--
            <a href="{{ $UrlJumpToTMN }}" target="_blank">[別窓でテレメディカネットを開く]</a><br>
-->
	</body>
</html>