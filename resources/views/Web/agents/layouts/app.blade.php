<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="author" content="xiao">
    <title>博赢国际--代理</title>
    <link href="{!! asset('./agent-data/lib/bootstrap-3.3.7/css/bootstrap.css" rel="stylesheet') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('./agent-data/css/header.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/js/layer/skin/default/layer.css') !!}"/>
    @yield('css')

</head>
<body>
<!--页面顶部-->
<header id="page_header">
    <div class="container">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-xxs-3">
            <img src="{!! asset('./agent-data/img/logo.png') !!}"/>
        </div>

        {{--登录状态--}}
        @if(!\WinwinAuth::agentUser())
        <div class="form_header col-lg-9 col-md-9 col-sm-9 col-xs-9 col-xxs-9">
            <div class="form-inline header-header" >
                <div class="input-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="用户名">
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="pwd" class="form-control" placeholder="密码" style="width: 80px;">
                    <span class="input-group-btn">
                        <button id="btn_forget" class="btn btn-default" type="button" style="line-height: 34px;">忘记密码？</button>
                    </span>
                </div>

                {{--<div class="input-group">--}}
                    {{--<input type="text" class="form-control" name="refercode" id="refercode" placeholder="验证码" style="width: 100px;">--}}
                    {{--<span class="input-group-addon captchaChange" style="width: 40px;height: 34px;padding: 0;">{!! \Captcha::img() !!}</span>--}}
                {{--</div>--}}

                <div class="pull-right" style="margin-left: 10px;margin-top: 2px;">
                    <button type="submit" class="btn" id="btn_login">登录</button>
                    <button type="button" class="btn btn-info" onclick="window.open('{!! route('agents.registerPage') !!}','_parent')">代理注册</button>
                </div>
            </div>
        </div>
        @else
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 col-xxs-9" id="info_header">
            <p>欢迎回来，<span>wonder***500</span></p>
            <p>账户余额 <span>2000</span><i class="glyphicon glyphicon-refresh"></i></p>
            <button id="btn_account">账户中心</button>
            <button id="btn_exit">安全退出</button>
        </div>
        @endif
    </div>

    <!--导航-->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">切换导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav" id="myTab">
                    <li @if(Route::currentRouteName() == 'agents.index')class="active" @endif><a href="{!! route('agents.index') !!}" >首页</a></li>
                    <li @if(Route::currentRouteName() == 'agents.pattern')class="active" @endif><a href="{!! route('agents.pattern') !!}" >合营模式</a></li>
                    <li @if(Route::currentRouteName() == 'agents.policy')class="active" @endif><a href="{!! route('agents.policy') !!}" >佣金政策</a></li>
                    <li @if(Route::currentRouteName() == 'agents.protocol')class="active" @endif><a href="{!! route('agents.protocol') !!}" >合营协议</a></li>
                    <li @if(Route::currentRouteName() == 'agents.connectUs')class="active" @endif><a href="{!! route('agents.connectUs') !!}" >联系我们</a></li>
                    <li class="pull-right"><a data-toggle="tab">双赢官网</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
@yield('content')
<script src="{!! asset('./agent-data/lib/jquery-3.1.0.min.js') !!}"></script>
<script src="{!! asset('./agent-data/lib/jquery.validate.js') !!}"></script>
<script src="{!! asset('./agent-data/lib/bootstrap-3.3.7/js/bootstrap.js') !!}"></script>
<script src="{!! asset('./agent-data/js/common.js') !!}"></script>
<script src="{!! asset('./app/js/layer/layer.js') !!}"></script>
@yield('script')
</body>
</html>
