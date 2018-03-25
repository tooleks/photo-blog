<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="fragment" content="!">
    <link rel="alternate"
          type="application/rss+xml"
          title="{{ sprintf('%s RSS', config('app.name')) }}"
          href="{{ route('rss') }}">
    <title>{{ config('app.name') }}</title>
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ mix('/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ mix('/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ mix('/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ mix('/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ mix('/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ mix('/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ mix('/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ mix('/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ mix('/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ mix('/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ mix('/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ mix('/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ mix('/favicon/favicon-16x16.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ mix('/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
<noscript>
    <div style="padding: 5em; text-align: center;">
        To use {{ config('app.name') }}, please enable JavaScript.
    </div>
</noscript>
@yield('content')
<script type="application/javascript" src="{{ mix('/js/app.js') }}"></script>
<?php if (env('GOOGLE_RECAPTCHA_SECRET_KEY')): ?>
<script src="https://www.google.com/recaptcha/api.js?onload=vueReCaptchaOnLoad&render=explicit" async defer></script>
<?php endif; ?>
</body>
</html>
