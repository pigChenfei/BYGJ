<!DOCTYPE html>
<html lang="zh">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{!! WTemplate::title() !!}</title>
    <meta name="keywords" content="{!! WTemplate::keywords() !!}" />
	<meta name="description" content="{!! WTemplate::description() !!}" />
    <link rel="stylesheet" href="{!! asset('./app/mobile/css/layer.min.css') !!}">
    <script>var islogin =  @if(!\WinwinAuth::memberUser()) false @else true @endif;</script>
    <script src="{!! asset('./app/mobile/js/layer.min.js') !!}"></script>
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">
    <link rel="stylesheet" href="{!! asset('./app/mobile/css/main.min.css') !!}">
    <link rel="stylesheet">
  </head>
  <body>
    @yield('content')
  </body>
  @yield('script')
</html>