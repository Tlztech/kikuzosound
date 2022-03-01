<!-- resources/views/emails/password.blade.php -->

{!! $content1 !!}
Customer name: {!! $name !!} <br>
Email: {!! $email !!}<br>
New license key: {!! $newLicense !!}<br>
URL: <a href="{!! $url !!}">{!! $url !!}</a><br><br>
{!! $content2 !!}
==================================<br><br>
@lang('about.company_address')
ask@telemedica.co.jp <br><br>
==================================<br><br>