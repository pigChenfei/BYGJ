<html lang="zh-CN">
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

    <link rel="stylesheet" href="{!! asset('./app/template_one/css/bootstrap.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/bootstrap-datetimepicker.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::agentUser()->template_agent_admin.'/css/winwin_style.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::agentUser()->template_agent_admin.'/css/member_center.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/js/layer/skin/default/layer.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::agentUser()->template_agent_admin.'/css/agency.css') !!}"/>

    @yield('css')
</head>
<body>
<div class="agency-box-index">
    <header class="header header-agency">
        <div class="header-mid">
            <div class="head-main clearfix">
                <div class="logo-wrap float-left"><a href="/" style="width: 100%;height: 100%;display: block;" ></a></div>
                @if(!\WinwinAuth::agentUser())
                <div class="btn-wrap float-right unlogin">
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="username" class="username-label"></label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="账户">
                        </div>
                        <div class="form-group">
                            <label for="password" class="password-label"></label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="密码">
                        </div>
                        <button type="button" class="btn btn-default btn-login agent-login">登录</button>
                        <button type="button" class="btn btn-default btn-sign" onclick="window.location.href='{!! url('agents.registerPage') !!}'">代理注册</button>
                    </div>
                </div>
                @else
                    <div class="btn-wrap float-right clearfix logined">
                        <div class="memb-container float-left">
                            <div class="memb-desc float-left memb-common">
                                <span>{{ !is_null(\WinwinAuth::agentUser()->agentLevel) && \WinwinAuth::agentUser()->agentLevel->type == 2?'洗码代理':'佣金代理'}}</span>
                            </div>
                            <div class="memb-info float-left">
                                <div>账号：<a href="javascript:void(0)" class="username-wrap">
                                        <i class="username">{{\WinwinAuth::agentUser()->username}}</i>&nbsp;
                                        <span class="glyphicon glyphicon-triangle-bottom f12"></span>
                                    </a>
                                </div>
                                <div>余额：<i class="balance">{{\WinwinAuth::agentUser()->amount}}</i>&nbsp;元&nbsp;&nbsp;<a href="javascript:void(0)" class="glyphicon glyphicon-refresh f12"></a></div>
                                <ul class="list-unstyled usermenu">
                                    <li><a href="{!! route('agentPlayers.index') !!}">会员报表</a></li>
                                    <li><a href="{!! route('agentPerformances.index') !!}">业绩报表</a></li>
                                    <li><a href="{{route('sms.index')}}">信息服务</a></li>
                                    <li><a href="{!! route('agentWithdraws.index') !!}">快速取款</a></li>
                                    <li><a href="{!! route('agentSettleReports.index') !!}">结算报表</a></li>
                                    <li><a href="{!! route('agentWithdrawLogs.index') !!}">取款记录</a></li>
                                    <li class="exit"><a href="javascript:;" class="agent-loginOut">退出登录</a></li>
                                    <li class="glyphicon glyphicon-triangle-top"></li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-inline float-left">
                            <button type="button" class="btn btn-default btn-joincenter" onclick="window.location.href='{{url('agent/admin/agentAccountCenters')}}'">加盟中心</button>
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
        <div class="header-foot">
            <nav class="clearfix">
                <ul class="list-unstyled">
                    <li><a class="@if(strpos(Route::currentRouteName(), 'index'))active @endif" href="{{url('agents.index')}}">首页</a></li>
                    @if(!\WinwinAuth::agentUser())
                    <li><a class="@if(strpos(Route::currentRouteName(), 'registerPage'))active @endif" href="{{url('agents.registerPage')}}">我要加入</a></li>
                    @endif
                    <li><a class="@if(strpos(Route::currentRouteName(), 'pattern'))active @endif" href="{{url('agents.pattern')}}">合营模式</a></li>
                    <li><a class="@if(strpos(Route::currentRouteName(), 'protocol'))active @endif" href="{{url('agents.protocol')}}">合作协议</a></li>
                    <li><a class="@if(strpos(Route::currentRouteName(), 'policy'))active @endif" href="{{url('agents.policy')}}">佣金政策</a></li>
                    <li><a class="@if(strpos(Route::currentRouteName(), 'connectUs'))active @endif" href="{{url('agents.connectUs')}}">联系我们</a></li>
                    <li class="go-winwin"><a href="{{url('/')}}">返回官网</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="header-pad"></div>
    <section class="member-container">
        <div class="member-wrap clearfix">
            @yield('content')
        </div>
    </section>

    @include('Web.template_agent_one.layouts.footer')

</div>
<script src="{!! asset('./app/template_one/js/jquery.min.js') !!}"></script>
<script src="{!! asset('./app/template_one/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('./app/js/layer/layer.js') !!}"></script>
<script src="{!! asset('./agent-data/template_agent_one/js/common.js') !!}"></script>
@yield('scripts')
@yield('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
</script>

</body>
</html>
