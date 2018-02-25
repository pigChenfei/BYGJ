@extends('Web.default.layouts.app')

@section('header-nav')
@include('Web.default.layouts.index_nav')
@endsection

@section('content') 
    <div class="register-background">
        <div class="register">
            <h2>欢迎注册博赢国际<br /><span>（请详细填写以下表格，带 <b style="color: red;">*</b>项目为必填项目）</span></h2>
            <form id="registerForm">
                <fieldset> 
                	<legend>账户资料</legend>
                	<div class="register_item">
	                    <label><b>*</b>账号</label>
	                    <input type="text" name="user_name" id="user_name" placeholder="请输入账号" autocomplete="off"/>	  
	                    <span class="tips">请输入4-11个字符，仅可输入字母数字</span>  
	                    <span class="valid"></span>                    
	                </div> 
	                
	                <div class="register_item">
	                    <label><b>*</b>登录密码</label>
                        <input type="password" name="password" id="password" placeholder="请输入登录密码" autocomplete="off"/>  
                        <span class="tips">请输入6-16个字符，必须含有字母和数字的组合</span>
                        <span class="valid"></span> 
	                </div>
	                	                
	                <div class="register_item">
	                    <label><b>*</b>确认密码</label>
	                    <input type="password" name="confirm_password" id="confirm_password" placeholder="请确认密码" autocomplete="off"/>
	                    <span class="tips">请确认您的登录密码</span>   
	                    <span class="valid"></span> 
	                </div>
                </fieldset>
	                               
                <fieldset>
                	<legend>个人资料</legend>	                 	
                	@foreach($playerAttr as $k=>$value)
	                    @include('Web.default.login_registers.register.'.$value)
	                @endforeach
	                
	                {{--邀请码--}}
	                <div class="register_item">
	                    <label>邀请码</label>
                        <input type="text" name="referral_code" id="referral_code" placeholder="非必填项" autocomplete="off" @if($recommend_code) value="{!! $recommend_code !!}" readonly @endif/>   
	                	<span class="tips">非必填项</span>
	                	<span class="valid"></span> 
	                </div>
					
	                {{--验证码--}}
	                <div class="register_item">
	                    <label><b>*</b>验证码</label>
	                    <input type="text" name="verification_code" id="verification_code" placeholder="请填写验证码" autocomplete="off" /><span class="captchaChange">{!! \Captcha::img() !!}</span>
	                	<span class="valid"></span>  
	                </div>
                </fieldset>
                	
                <div class="register_item" style="margin-left: 20px;margin-top: 20px;">
                    <input type="checkbox" style="width: 13px;height: 13px;margin: 0" id="protocol" name="protocol" checked/>
                    <span>我已同意并阅读博赢国际的 <a href="#" style="color: red">"服务和条款"</a></span>
                </div>
                <div class="register_item">  
                	<input type="submit" class="btn btn-warning register-btn submit" style="background-color: #2ac0ff;border: 0;width: 246px;height: 40px;" value="提交申请">
                </div>              
            </form>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="{!! asset('app/js/register.js').(App::environment() != 'production' ? '?v='.time() : '') !!}"></script>
    
    <script>
	    $(function(){ 
	        if ($("input[name=referral_code]").val() != "") {
	            $("input[name=referral_code]").attr("disabled", true);
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

