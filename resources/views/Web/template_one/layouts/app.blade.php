<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{!! WTemplate::title() !!}</title>
    <meta name="keywords" content="{!! WTemplate::keywords() !!}" />
	<meta name="description" content="{!! WTemplate::description() !!}" />
    <!--[if lt IE 9]>
    <script src="{!! asset('./app/template_one/js/html5shiv.min.js') !!}"></script>
    <script src="{!! asset('./app/template_one/js/respond.min.js') !!}"></script>
    <![endif]-->

    <link href="{!! asset('./app/template_one/favicon.ico') !!}" rel="shortcut icon" type="image/x-icon"/>

    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/bootstrap.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/bootstrap-datetimepicker.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/winwin_style.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/js/layer/skin/default/layer.css') !!}"/>

    <script src="{!! asset('./app/template_one/js/jquery.min.js') !!}"></script>
    <script src="{!! asset('./app/template_one/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('./app/js/layer/layer.js') !!}"></script>
    <script src="{!! asset('./app/js/jquery.validate.js') !!}"></script>
    <script src="{!! asset('./app/template_one/js/common.js') !!}"></script>
    <script src="{!! asset('./app/template_one/js/Txlogin.js') !!}"></script>
    @yield('css')
    @yield('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    </script>
</head>
<body>
<div class="index-container">
    <header class="header header-index">
        <div class="header-mid">
            <div class="head-main clearfix">
                <div class="logo-wrap float-left"><a href="/" style="width: 100%;height: 100%;display: block;"  ></a></div>
                {{--登录状态--}}
                <div class="btn-wrap float-right">
                    @if(!\WinwinAuth::memberUser())
                        <div class="form-inline admin-poke">
                        <div class="form-group">
                            <label for="username" class="username-label"></label>
                            <input type="text" class="form-control" name="username" autocomplete="off" placeholder="账户" maxlength="11">
                        </div>
                        <div class="form-group">
                            <label for="password" class="password-label"></label>
                            <input type="password" class="form-control" name="password" autocomplete="off" placeholder="密码" maxlength="20">
                        </div>
                        <button type="button" class="btn btn-default btn-login dbl">登录</button>
                        {{--<button type="button" class="btn btn-default btn-sign" onclick="location.href='{!! route('homes.registerPage') !!}'">注册</button>--}}
                        <button type="button" class="btn btn-default btn-sign tx-kaihu">注册</button>
                        <a href="javascript:" class="tx_forget" style="color:rgba(255,255,255,.8)!important;margin-left:5px;">忘记密码？</a>
                        </div>
                    @else
                        <div class="form-inline admin-poke clearfix logined">
                        <div class="memb-container float-left">
                            <div class="memb-desc float-left memb-common" style="background-image: url('{{\WinwinAuth::memberUser()->playerLevel->img?\WinwinAuth::memberUser()->playerLevel->imageAsset():'/app/template_one/img/common/memb-common.png'}}')">
                                <span>{{\WinwinAuth::memberUser()->playerLevel->level_name}}</span>
                            </div>
                            <div class="memb-info float-left">
                                <div>账号：<a href="javascript:void(0)" class="username-wrap">
                                        <i class="username">{!!  str_limit(\WinwinAuth::memberUser()->user_name, 40) !!}</i>&nbsp;
                                        <span class="glyphicon glyphicon-triangle-bottom f12"></span>
                                    </a>
                                </div>
                                <div>余额：<i class="balance mainAccountAmount">{!! \WinwinAuth::memberUser()->main_account_amount !!}</i>&nbsp;元&nbsp;&nbsp;<a href="javascript:void(0)" class="glyphicon glyphicon-refresh f12" id="mainAccountRefresh"></a></div>
                                <ul class="list-unstyled usermenu">
                                    <li><a href="{{ route('players.deposit') }}">账户存款</a></li>
                                    <li><a href="{!! route('players.withdraw-money') !!}">快速取款</a></li>
                                    <li><a href="{{ route('players.account-transfer') }}">转账中心</a></li>
                                    <li><a href="{!! route('players.rebateFinancialFlow') !!}">实时洗码</a></li>
                                    <li class="exit"><a href="javascript:void(0)" class="Logging-Out">退出登录</a></li>
                                    <li class="glyphicon glyphicon-triangle-top"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-inline float-left">
                            <button type="button" class="btn btn-default btn-memb" onclick="location.href='{!! route('players.account-security') !!}'">会员中心</button>
                            <button type="button" class="btn btn-default btn-dep" onclick="location.href='{!! route('players.deposit') !!}#member-deposit'">存款</button>
                            <button type="button" class="btn btn-default btn-draw" onclick="location.href='{!! route('players.withdraw-money') !!}#withdraw-money'">取款</button>
                        </div>
                        </div>
                        <script>
                            // 个人账户下拉显示
                            var wrap = document.getElementsByClassName('username-wrap')[0];
                            var menu = document.getElementsByClassName('usermenu')[0];
                            wrap.onmouseover = menu.onmouseover = function(){
                                menu.style.display = 'block';
                            };
                            wrap.onmouseout = menu.onmouseout = function(){
                                menu.style.display = 'none';
                            };
                        </script>
                    @endif
                </div>
            </div>
        </div>
        <div class="header-foot">
            <nav class="clearfix">
                @yield('header-nav')
            </nav>
        </div>
    </header>
    <div class="header-pad"></div>

    @yield('content')

    {{--首页底部--}}
    <footer class="text-center">
        <div class="logo-wrap">
            <img src="{!! asset('./app/template_one/img/common/footer-logo.png') !!}"/>
            <div class="hzline"></div>
        </div>
        <div class="fc">
            <ul class="list-unstyled">
                <li onclick="window.location.href='{!! url('homes.contactCustomer?type=about')!!}'">关于我们</li>
                <li onclick="window.location.href='{!! url('homes.contactCustomer?type=common')!!}'">常见问题</li>
                <li onclick="window.location.href='{!! url('homes.contactCustomer?type=duty')!!}'">责任博彩</li>
                <li onclick="window.location.href='{{route('agents.connectUs')}}'">联系我们</li>
                <li onclick="window.location.href='{{route('agents.registerPage')}}'">合作伙伴</li>
            </ul>
        </div>

        <h6>{!! \WinwinAuth::currentWebCarrier()->webSiteConf->site_footer_comment !!}</h6>
    </footer>
    {!! \WinwinAuth::currentWebCarrier()->webSiteConf->online_service_file_path !!}
    @yield('footer')

    @yield('scripts')
</div>
</body>
</html>








