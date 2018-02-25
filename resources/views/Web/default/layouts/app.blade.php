<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>{!! WTemplate::title() !!}</title>
    <meta name="keywords" content="{!! WTemplate::keywords() !!}" />
	<meta name="description" content="{!! WTemplate::description() !!}" />
    <link rel="stylesheet" href="{!! asset('./app/css/style.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/css/bootstrap.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/js/jedate/skin/jedate.css') !!}">
    <link rel="stylesheet" href="{!! asset('./app/css/reset.css') !!}"/>
    @yield('css')
    <link rel="stylesheet" href="{!! asset('./app/js/layer/skin/default/layer.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/css/commmon.css') !!}"/>
   {{-- <script src="{!! asset('./app/js/jquery-1.10.2.min.js') !!}"></script>--}}
    <script src="{!! asset('./app/js/jquery-3.2.1.min.js') !!}"></script>
    <script src="{!! asset('./app/js/jedate/jquery.jedate.js') !!}"></script>
    <script src="{!! asset('./app/js/layer/layer.js') !!}"></script>
    <script src="{!! asset('./app/js/jquery.validate.js') !!}"></script>
    {{--<script src="{!! asset('./app/js/bootstrap.min.js') !!}"></script>--}}
    <!--[if lt IE 9]>
    <script src="{!! asset('./app/js/html5shiv.js') !!}"></script>
    <script src="{!! asset('./app/js/respond.min.js') !!}"></script>
    <![endif]-->
    @yield('script')
</head>
<body>

<header>
    <div class="header-header">
        <div>
            <div class="pull-left marquee">
                <i class="broadcast"></i>
                <marquee behavior="" direction="" width="840" ailngn loop="infinite" onmouseover="this.stop()"
                         onmouseout="this.start()">
                    尊敬的博赢国际会员你好，尊敬的博赢国际会员您好！
                </marquee>
            </div>
            {{--<ul class="list-inline header-top-left pull-left">
                <div class="clearfix"></div>
                <li class="set"><i class="set-homepage"></i><a href=# onClick="this.style.behavior='url(#default#homepage)';this.setHomePage ('');">设为首页</a> </li>
                <li class="web"><i class="web-homepage"></i><a href="">备用地址</a></li>
            </ul>--}}
            <ul class="list-inline header-top-right pull-right">
                <div class="clearfix"></div>
                <li class="set"><i class="license-homepage"></i><a href="">牌照展示</a></li>
                <li class="web"><i class="service-homepage"></i><a href="">在线客服</a></li>
            </ul>
        </div>
    </div>
    <div>
        <div class="header-box">
            <div class="logo pull-left"><a href="{!! route('/') !!}"><img src="{!! asset('./app/img/logo.png') !!}" alt="" style="width: 220px;"/></a></div>
            {{--登录状态--}}
            @if(!\WinwinAuth::memberUser())
                <div class="admin-poke pull-right" style=" position: relative;">
                    <input type="text" placeholder="账号" maxlength="11"/>
                    <input type="password" placeholder="密码" maxlength="20"/>
                    {{--<input type="text" placeholder="验证码" name="loginVericode" maxlength="5"  style="width: 72px;margin-right: 10px;" /><span class="captchaChange">{!! \Captcha::img() !!}</span>--}}
                    <span class="btn btn-block dbl" style="margin-left: 15px;    padding: 8px 0;">{{--<a href="--}}{{--{!! route('homes.login') !!}--}}{{--">--}}
                        	登录</span>
                    <span class="btn btn-block" style="padding: 8px 0;"><a href="{!! route('homes.registerPage') !!}">注册</a></span>
                    <p><a href="{!! route('homes.forget-password') !!}">忘记密码?</a></p>
                </div>
            @else
                <div class="pull-right">
                    <div class="pull-left header-box-right">
                        <span class="pull-left">欢迎</span>
                        <span class="pull-left"><b style="color: #ff9620">{!! \WinwinAuth::memberUser()->user_name !!}</b></span>
                        <span class="pull-left">普通会员</span>
                        <b><a href="{!! route('players.sms-subscriptions') !!}" style="color: #ff9620;" class="pull-right"><i class="message"></i>(1)</a></b>
                    </div>
                    <div class="pull-left header-box-left">
                          <div class="pull-left">
                            <p>主账户余额 
                                <b style="margin-left: 5px;color: #ff9620" class="mainAccountAmount">{!! \WinwinAuth::memberUser()->main_account_amount !!}
                                </b>
                                <i class="refresh" id="mainAccountRefresh" style="position: relative;top: 0px;"></i>
                            </p>
                        <a href="{!! route('players.financeCenter') !!}#member-deposit" class="btn btn-danger" id="deposit_header">存款</a>
                        <a href="{!! route('players.financeCenter') !!}#withdraw-money" class="btn btn-primary" id="withdraw_header">取款</a>
                        <a href="{!! route('players.financeCenter') !!}#account-transfer" class="btn btn-success" id="transfer_header">转账</a>
                        </div>
                        <div class="pull-right" style="margin-left: 30px;">
                            <div class="btn btn-primary" style="background-color: #2ac0ff;">
                            	<a href="{!! route('players.account-security') !!}">会员中心</a></div>
                            <div class="btn btn-default Logging-Out" style="background-color: #8fc714;color: #fff;">
                            	安全退出
                            </div>
                        </div>
                    </div>

                    <div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="header-background">
        <div class="header-nav">
            @yield('header-nav')
        </div>
    </div>
</header>

@yield('content')

{{--首页底部--}}
<footer class="pull-left">
    <span class="footer-header">
        <img src="{!! asset('./app/img/brand.png') !!}" alt=""/>
        <img src="{!! asset('./app/img/brand.png') !!}" alt=""/>
        <img src="{!! asset('./app/img/brand.png') !!}" alt=""/>
        <img src="{!! asset('./app/img/brand.png') !!}" alt=""/>
        <img src="{!! asset('./app/img/brand.png') !!}" alt=""/>
    </span>
    <div>
        <div class="pull-left">
            <ul class="list-inline footer-nav">
                <li><a href="{!! route('homes.about-us') !!}">关于我们</a></li>
                <li><a href="{!! route('homes.contact-us') !!}">联系我们</a></li>
                <li><a href="{!! route('homes.vip-system') !!}">VIP制度</a></li>
                <li><a href="{!! route('homes.FAQ') !!}">常见问题</a></li>
                <li><a href="{!! route('homes.privacy-protection') !!}">隐私保护</a></li>
                <li><a href="{!! route('homes.gambling-responsibility') !!}">博彩责任</a></li>
                <li><a href="{!! route('homes.terms-of-service') !!}">服务条款</a></li>
                <li><a href="{!! route('agents.index') !!}" style="border: 0;">合作伙伴</a></li>
            </ul>
            <p class="footer-footer">Copyright © 2016-2017 博赢国际官方网站</p>
        </div>
        <div class="pull-right footer-footer-click">
            <img src="{!! asset('./app/img/w-ck2.png') !!}" alt="" class="pull-left"/>
            <div class="pull-right">
                <div>在线客服：<a href="">点击联系</a></div>
                <div>联系电话：11111111111</div>
                <div>邮箱地址：111111111@qq.com</div>
            </div>
        </div>
    </div>
</footer>
<aside class="fixed">
    <div><a href=""><img src="{!! asset('./app/img/onlineservice.png') !!}" alt=""/></a></div>
    <div><a href=""><img src="{!! asset('./app/img/phoneservice.png') !!}" alt=""/></a></div>
    <div><a href=""><img src="{!! asset('./app/img/phonedownload.png') !!}" alt=""/></a></div>
    <div><a href="{!! route('/') !!}"><img src="{!! asset('./app/img/backhomepage.png') !!}" alt=""/></a></div>
</aside>

@yield('footer')
<script src="{!! asset('./app/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('./app/js/layer/layer.js') !!}"></script>
<script src="{!! asset('./app/js/jquery.rotate.min.js') !!}"></script>
<script src="{!! asset('./app/js/common.js') !!}"></script>
@yield('scripts')

<script>
    $(function(){
        $("#deposit_header").on("click", function(){
            window.location.reload();
        });
        $("#withdraw_header").on("click", function(){
            window.location.reload();
        });
        $("#transfer_header").on("click", function(){
            window.location.reload();
        });
    });
</script>
</body>
</html>








