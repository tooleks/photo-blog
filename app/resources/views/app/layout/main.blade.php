<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="fragment" content="!">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ mix('/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ mix('/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ mix('/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ mix('/safari-pinned-tab.svg') }}" color="#ff0000">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#f3f3f4">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" type="text/css">
    <link rel="manifest" href="{{ route('manifest') }}">
    <link rel="alternate"
          type="application/rss+xml"
          title="{{ sprintf('%s RSS', config('app.name')) }}"
          href="{{ route('rss') }}">
</head>
<body>
<noscript>
    <div style="padding: 5em; text-align: center;">
        To use {{ config('app.name') }}, please enable JavaScript.
    </div>
</noscript>
@yield('content')
<script type="application/javascript" src="{{ mix('/js/app.js') }}" async defer></script>
</body>
</html>
