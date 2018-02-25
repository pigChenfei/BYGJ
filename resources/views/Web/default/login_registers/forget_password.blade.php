@extends('Web.default.layouts.app');

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
<div class="bg_forget">
    <main class="forget-password">
        <p class="text-center">重置密码</p>
        <div class="forget-password-nav">
            <ul class="list-inline">
            	<li class="forget-password-li1"><i class="forgt-password-img1"></i>确认会员账号</li>
            	<li class="forget-password-li2"><i class="forget-password-img2"></i>安全验证</li>
            	<li class="forget-password-li3"><i class="forget-password-img3"></i>重置密码</li>
            </ul>
        </div>
        <div class="forget-password-main">
            <div><span>会员账号：</span><input type="text" placeholder="4-11位小写字母或者数字" class="password-name" /></div>
            <div><span>选择找回方式：</span>
                <!--<button class="btn btn-success password1-to-go" style="background-color: #2ac0ff;border: 0">手机验证</button>
                <p class="pull-right">点击按钮后，将会发送验证码到您绑定的手机</p>-->
                <button class="btn btn-success password1-to-go">邮箱验证</button>
                <p class="pull-right">点击按钮后，将会发送验证码到您绑定的邮箱</p>
                <button class="btn btn-danger" >在线客服</button>
                <dl class="pull-left">如需帮助，请点击在线客服按钮</dl>
            </div>
        </div>
        <div class="forget-password-main1" style="display: none;" >
               <div class="forget-password-main1-div">
                   验证码：<input type="text" placeholder="请输入您收到的短信验证码" class="to-text"/>
                 </div>
            <div><div class="btn btn-default password1-to-go1">下一步</div></div>
        </div>
        <div class="forget-password-main2" style="display: none;" >
            <div></div>
            <div class="" >新密码：<input type="text" placeholder="请设置新密码" class="to-pass" style="margin-left: 2px;"/> </div>
            <div class="">重复密码：<input type="text" placeholder="请再次输入密码" class="to-pass1"/> </div>
            <div><div class="btn btn-default password1-to-go2">确认提交</div></div>
        </div>
    </main>
</div>
@endsection;

@section('scripts')
<script>
    $(".nav-nav li ul li a").mouseover(function(){
        $(".back-nav>div").css("display","none");
    });
    $(".nav-nav li ul li a").mouseout(function(){
        $(".back-nav>div").css("display","block");
    });

</script>

<script>

    //倒计时
    function resetCode(){
        $('#J_getCode').hide();
        $('#J_second').html('60');
        $('#J_resetCode').show();
        var second = 60;
        var timer = null;
        timer = setInterval(function(){
            second -= 1;
            if(second >0 ){
                $('#J_second').html(second);
            }else{
                clearInterval(timer);
                $('#J_getCode').show();
                $('#J_resetCode').hide();
            }
        },1000);
    }
</script>
    @endsection

