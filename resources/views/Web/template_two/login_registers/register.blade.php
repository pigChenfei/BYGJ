@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('header-nav')
@include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection
@section('css')
<link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/agency.css') !!}"/>
<style>
.bgwrap{
	background: url({!! asset('./app/template_two/img/common/recommend-bg.jpg')!!}) no-repeat center center;
	background-size: cover;
	overflow: hidden;
	padding: 30px 0;
}
.joinus-main{
	width: 1140px;
	background: rgba(255, 255, 255, 0.6);
	box-shadow: 10px 10px 50px rgba(0,0,0,0.2);
	padding: 50px 150px;
}
.joinus-main .form-inline{
	padding-left: 100px;
}
</style>
@endsection
@section('content')
<div class="bgwrap">
	<section class="joinus-main">
		<form role="form" method="post" id="registerForm" novalidate="novalidate">
			<h2 class="text-center mb-30">欢迎来到博赢国际</h2>
			<h4>
				<span class="img-circle"></span>账户资料
				<span>（请填写以下表格，带
					<i> ✱ </i>项目为必填项目）</span>
			</h4>
			<div class="form-box" style="padding-top:0;">
				<div class="form-inline clearfix">
					<label for="username">
						<i class="font-red">✱ </i>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
					<input type="text" class="form-control" id="user_name" name="user_name" placeholder="请输入账号" autocomplete="off" required=""
					data-rule-account="true" data-msg-required="请输入账号" data-msg-account="账号格式不正确">
					<span class="tip">为4-11个字母或数字</span>
				</div>
				<div class="form-inline clearfix">
					<label for="pwd">
						<i class="font-red">✱ </i>登录密码：</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="请输入登录密码" autocomplete="off" required=""
					data-rule-pwd="true" data-msg-required="请输入密码" data-msg-pwd="密码格式不正确">
					<span class="tip">为6-20个字母或数字</span>
				</div>
				<div class="form-inline clearfix">
					<label for="confirm_password">
						<i class="font-red">✱ </i>确认密码：</label>
					<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="请再次输入登录密码" autocomplete="off" required="" data-msg-required="请再次输入登录密码"
					data-msg-equalto="两次密码不一致">
				</div>
			</div>
			<div class="form-box">
				<h4>
					<span class="img-circle"></span>个人资料
					<span>（请填写以下表格，带
					<i> ✱ </i>项目为必填项目）</span>
				</h4>
				@foreach($playerAttr as $k=>$value)
	                    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.login_registers.register.'.$value)
	            @endforeach
				<div class="form-inline clearfix">
					<label for="recommend-code">邀&nbsp;&nbsp;请&nbsp;&nbsp;码：</label>
					<input type="text" class="form-control" name="referral_code" id="referral_code" autocomplete="off" placeholder="请输入邀请码" @if($recommend_code) value="{!! $recommend_code !!}" readonly @endif>
					@if($recommend_code)<input type="hidden" name="referral_code" value="{{$recommend_code}}" >@endif
					<span class="tip">非必填项</span>
				</div>
				<div class="form-inline clearfix">
					<label for="authcode">
						<i class="font-red">✱ </i>验&nbsp;&nbsp;证&nbsp;&nbsp;码：</label>
					<input type="text" class="form-control" name="verification_code" id="authcode" autocomplete="off" placeholder="请输入验证码" required=""
					style="width: 130px;float: left;" data-rule-refercode="true" data-msg-required="验证码必填" data-msg-refercode="验证码输入错误" data-form-vc="1517293049102.8513">
					<div class="authcode-wrap" style="height: 34px">
						<img src="/captcha" onclick="this.src='/captcha?r='+Math.random();">
					</div>
				</div>
				<div class="form-inline clearfix check-rule">
					<label></label>
					<input type="checkbox" id="checkrule" value="1" name="checkbox" style="min-width: auto;width: auto; margin-right: 5px;" checked=""
					required="" data-msg-required="合营条件必选">
					<span>我已同意并阅读&nbsp;&nbsp;
						<a href="javascript:void(0)" style="color: #a671ff!important;">“服务和条款”</a>
					</span>
				</div>
				<div class="form-inline text-center" style="padding: 0;margin-top: 20px;">
					<button type="submit" class="btn btn-warning register-btn submit" style="width: 120px;" >提交</button>
				</div>
			</div>
		</form>
	</section>
</div>
@endsection
@section('scripts')
    <script src="{!! asset('app/js/register.js').(App::environment() != 'production' ? '?v='.time() : '') !!}"></script>
    
    <script>
	    $(function(){
	        if ($("#referral_code").val() != "") {
	            $("#referral_code").attr("disabled", true);
	        }
	        /*保存密码 返回后台值*/
	        $(".nav-nav li ul li a").mouseover(function () {
	            $(".back-nav>div").css("display", "none");
	        });
	        $(".nav-nav li ul li a").mouseout(function () {
	            $(".back-nav>div").css("display", "block");
	        });
	    });
    </script>
    @endsection

