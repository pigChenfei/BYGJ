@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/style.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/css/account.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
<div class="bg_container">
    <main class="account-security1" style="min-height: 595px;border: 1px solid #ddd;">
        <div class="account-top"><b>账户资料</b></div>
        <div class="account-security pull-left"  style="margin-left: 40px;" id="account-security">
        <p><b>基本资料</b></p> 

        @if(($player->registerConf->player_realname_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div id="real_name">
            	<span><b>真实姓名</b></span>
            	<input type="text" vereist="yes"  value="{!! \WinwinAuth::memberUser()->real_name !!}" class="security-name" maxlength="15" placeholder="请输入真实姓名"/>
            	<span style="color: red;padding-top: 10px;"><b>*</b></span>
            </div>
            <div class="clearfix"></div>
        @elseif(($player->registerConf->player_realname_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div id="real_name">
            	<span><b>真实姓名</b></span>
            	<input type="text" vereist="no" value="{!! \WinwinAuth::memberUser()->real_name !!}" class="security-name" maxlength="15" placeholder="请输入真实姓名"/>
            </div>
            <div class="clearfix"></div>
        @else

        @endif

        @if(($player->registerConf->player_phone_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div id="mobile">
            	<span><b>手机号码</b></span>
            	<input type="text" value="{!! \WinwinAuth::memberUser()->mobile !!}"  vereist="yes" class="security-tel"  maxlength="15" placeholder="请输入手机号"/>
            	<span style="color: red;padding-top: 10px;"><b>*</b></span>
            </div>
            <div class="clearfix"></div>
        @elseif(($player->registerConf->player_phone_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div id="mobile">
            	<span><b>手机号码</b></span>
            	<input type="text" value="{!! \WinwinAuth::memberUser()->mobile !!}"  vereist="no" class="security-tel"  maxlength="15" placeholder="请输入手机号"/>
            </div>
            <div class="clearfix"></div>
        @else

        @endif

        @if(($player->registerConf->player_email_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div id="email">
            	<span><b>邮箱地址</b></span>
            	<input type="text" value="{!! \WinwinAuth::memberUser()->email !!}" class="security-e-mail" vereist="yes" maxlength="25" placeholder="请输入邮箱地址"/>
            	<span style="color: red;padding-top: 10px;"><b>*</b></span>
            </div>
            <div class="clearfix"></div>
        @elseif(($player->registerConf->player_email_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div id="email"><span><b>邮箱地址</b></span>
            	<input type="text" value="{!! \WinwinAuth::memberUser()->email !!}" class="security-e-mail" vereist="no"  maxlength="25" placeholder="请输入邮箱地址"/>
            	<a style="color: red;padding-top: 10px;" class="Modify-the-mail">修改</a>
            </div>
            <div class="clearfix"></div>
        @else

        @endif

        @if( ($player->registerConf->player_sex_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div id="sex"><span style="padding-top: 2px;"><b>性别</b></span>
                <?php $statusDic = \App\Models\Player::userSex() ?>
                @foreach($statusDic as $key => $value)
                    @if(isset($player) && $player instanceof \App\Models\Player && $player->sex ==$key)
                    <input type="radio" style="margin-left: 13px;" value="{!! $key !!}" checked name="popple" vereist="yes"/><span>{!! $value !!}</span>
                    @else
                    <input type="radio" style="margin-left: 13px;" value="{!! $key !!}" name="popple" vereist="yes"/><span>{!! $value !!}</span>
                    @endif
                @endforeach</div>
            <div class="clearfix"></div>
        @else

        @endif 

        @if(($player->registerConf->player_birthday_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div>
            	<span><b>出生日期</b></span>
                <input class="pull-left fete-day datainp" id="birthday"  type="text" style="width:210px;" vereist="yes" value="{!! \WinwinAuth::memberUser()->birthday !!}" placeholder="请选择出生年月" readonly/>
               	<span style="color: red;padding-top: 10px;"><b>*</b></span>
            </div>
        	<div class="clearfix"></div>
        @elseif(($player->registerConf->player_birthday_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div>
            	<span><b>出生日期</b></span>
                <input class="pull-left fete-day datainp" id="birthday"  type="text" style="width:210px;" vereist="yes" value="{!! \WinwinAuth::memberUser()->birthday !!}" placeholder="请选择出生年月" readonly/>
            </div>
            <div class="clearfix"></div>
        @else

        @endif

        @if(($player->registerConf->player_qq_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
                <div class="qq_account">
                	<span><b>QQ</b></span>
                    <input type="text" value="{!! \WinwinAuth::memberUser()->qq_account !!}" class="security-QQ" vereist="yes" maxlength="20" placeholder="请输入QQ号码"/> 
                    <span style="color: red;padding-top: 10px;"><b>*</b></span>
                </div>
                <div class="clearfix"></div>
            @elseif(($player->registerConf->player_qq_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
                <div id="qq_account">
                	<span><b>QQ</b></span>
                    <input type="text" value="{!! \WinwinAuth::memberUser()->qq_account !!}" class="security-QQ"  vereist="no" maxlength="20" placeholder="请输入QQ号码"/>
                </div>
                <div id="clearfix"></div>
            @else

        @endif

        @if(($player->registerConf->player_wechat_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div id="wechat">
            	<span><b>微信</b></span>
            	<input type="text" value="{!! \WinwinAuth::memberUser()->wechat !!}" vereist="yes" class="security-wechat" maxlength="20" placeholder="请输入微信号"/>
            	<span style="color: red;padding-top: 10px;"><b>*</b></span>
            </div>
            <div class="clearfix"></div>
        @elseif(($player->registerConf->player_wechat_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div id="wechat">
            	<span><b>微信</b></span> 
            	<input type="text" value="{!! \WinwinAuth::memberUser()->wechat !!}" vereist="no" class="security-wechat" maxlength="20" placeholder="请输入微信号"/>
            </div>
            <div class="clearfix"></div>
        @else

        @endif
            <div>
            	<span></span>
            	<button class="btn btn-default security-true" style="background-color:#2ac0ff;color: #fff;margin-bottom: 20px;" >保存信息</button>
            </div>
        </div>

        <div class="pull-right account-security-right" style="margin-right: ;">
            <p><b>安全设置</b></p>
            <div class="Modify-the-passw">
            	<i class="back-lock"></i>
            	<div>修改登录密码</div>
            </div>
            @if($player->pay_password)
            <div class="Modify-the-passr">
            	<i class="back-many"></i>
            	<div>修改取款密码</div>
            </div>
            @else

            @endif

            @if($gameAccount)
            <div class="Modify-the-passo"> 
            	<i class="back-game"></i>
            	<div>修改PT专用密码</div>
            </div>
            @else

            @endif
        </div>
        <div class="clearfix"></div>
    </main>


    <div id="pass-many" style="display: none;">
        <div class="show-withdraw">
        	<span>原密码</span>
        	<input type="password" placeholder="默认密码:000000" class="with-name pass-many1" maxlength="16"/>
        </div>
        <div class="clearfix"></div>
        <div>
        	<span>新密码</span>
        	<input type="password" placeholder="请输入新密码" class="with-name pass-many2" maxlength="16"/>        	
        </div>
        <div class="clearfix"></div>
        <div>
        	<span>修改密码</span>
        	<input type="password" placeholder="请再次输入新密码" class="with-name pass-many3" maxlength="16"/>
        	<div class="clearfix"></div>
        	<button class="btn btn-default tel-alter-btn4" style="background-color: #2ac0ff;color: #fff;padding: 8px 12px;width: 218px;height: 40px;margin-top: 20px;border: none;margin-left: -16px;border-radius: 2px;">确认修改</button>
        </div>
    </div>

	<!--修改PT专用密码弹框-->
    <div id="pass-gane" style="display: none;">
        <div class="show-pt-error">
        	<span>新密码</span>
        	<input type="password" placeholder="请输入新密码" class="with-name pass-game2" maxlength="16"/>
        </div>
        <div class="clearfix"></div>	
        <div>
        	<span>确认密码</span>
        	<input type="password" placeholder="请再次输入新密码" class="with-name pass-game3" maxlength="16"/>
        	<div class="clearfix"></div>
            <button class="btn btn-default  tel-alter-btn3" style="background-color: #2ac0ff;color: #fff;padding: 8px 12px;width: 218px;height: 40px;margin-top: 20px;border: none;margin-left: -16px;border-radius: 2px;"  >确认修改</button>
        </div>	
    </div>

	<!--修改登录密码弹框-->
    <div id="pass-xu" style="display: none;">
        <input type="hidden" name="player_id" value="{{\WinwinAuth::memberUser()->player_id}}">
        <div class="show-error">
        	<span>原密码</span>
        	<input type="password" placeholder="请输入原密码" class="with-name pass-age" maxlength="16"/>
        </div>
        <div class="clearfix"></div>

        <div>
        	<span>新密码</span>
        	<input type="password" placeholder="请输入新密码" class="with-name pass-age2" maxlength="16"/>
        </div>
        <div class="clearfix"></div>
        <div>
        	<span>确认密码</span>
        	<input type="password" placeholder="请再次输入密码" class="with-name pass-age3" maxlength="16"/>
        	<div class="clearfix"></div>
            <button class="btn btn-default tel-alter-btn2" style="background-color: #2ac0ff;color: #fff;padding: 8px 12px;width: 220px;height: 40px;margin-top: 20px;border: none;margin-left: -16px;border-radius: 2px;"  >确认修改</button>
        </div>
    </div>
	
	<!--修改邮箱地址弹框-->
    <div id="tel-mail" style="display: none;">
        <div>
        	<span>原邮箱地址</span>
        	<input type="text" placeholder="请输入原邮箱地址" class="with-name email-yan" maxlength="30"/>
        	<div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <div style="margin-bottom: 25px;" class="forget-password-main1-div">
        	<span class="pull-left" style="background-color: transparent;color: #333;" >验证码</span>
        	<input type="text" maxlength="10" placeholder="输入验证码" class="to-text email-yan2" style="margin-right: 0px;"/> 
        	<button class="btn btn-small get-code" onclick="resetCode(this)" id="J_getCo" style=" color:#fff;padding: 4px 12px;background-color: #00a9ed;margin-left: -11px;height: 34px;">获取验证码</button>
            <button class="btn btn-small reset-code" id="J_resetCo" style="display:none;padding: 4px 12px;margin-left: -11px;height: 34px;"><span id="J_seco">60</span>秒后重发</button>
        </div>         
        <div class="clearfix"></div>

        <div>
        	<span>新邮箱地址</span><input type="text" placeholder="请输入现邮箱地址" class="with-name email-yan3" maxlength="30"/>
        	<div class="clearfix"></div>
        	<div class="btn btn-default tel-alter-btn1" style="background-color: #2ac0ff;color: #fff;padding: 8px 12px;width: 275px;height: 40px;margin-top: 20px;border: none;margin-left: -11px;border-radius: 2px;">确认修改</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{!! asset('./app/js/account-security.js') !!}"></script>
@endsection



