@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-login">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">会员安全登录</h1>
        </header>
        <!--底部信息展示-->
        {!! \WTemplate::footer() !!}
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-people"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="user" type="text" placeholder="账号" maxlength="11">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-lock"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="pwd" type="password" placeholder="密码" maxlength="20">
                      <label class="label-switch">
                        <input type="checkbox">
                        <div class="checkbox"></div>
                      </label>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-red button-gray btn-login" href="javascript:">登录</a>
          <div class="tip-box"><a class="f-l forgetpwd" href="javascript:;">忘记密码？</a><span class="f-r">还没有账号？<a class="registnow fontred" href="{{ url('/homes.moblieRegister') }}">立即注册</a></span></div>
        </div>
      </div>
    </div>
    <div class="layui-m-layer layui-m-layer2" id="layui-m-layer0" index="0">
      <div class="layui-m-layershade"></div>
      <div class="layui-m-layermain">
        <div class="layui-m-layersection">
          <div class="layui-m-layerchild layui-m-anim-scale">
            <div class="layui-m-layercont"><i></i><i class="layui-m-layerload"></i><i></i>
              <p>加载中...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    var checked = false
    var canlogin = {btn1:false,btn2:false}
    $('.label-switch').click(function(){
    	checked = !checked
    	if(checked)
    		$('.pwd').attr('type','text')
    	else
    		$('.pwd').attr('type','password')	
    })
    $('.user').on('input',function(){
    	if($(this).val().length>0)
    		canlogin.btn1 = true
    	else
    		canlogin.btn1 = false
    	checklogin()	
    })
    $('.pwd').on('input',function(){
    	if($(this).val().length>0)
    		canlogin.btn2 = true
    	else
    		canlogin.btn2 = false
    	checklogin()	
    })
    function checklogin() {
    	if (canlogin.btn1 && canlogin.btn2)
    		$('.btn-login').removeClass('button-gray')
    	else
    		$('.btn-login').addClass('button-gray')
    }
    //点击登录
    $('.btn-login').click(function(){
    	var _that = $(this)
    	if (_that.hasClass('button-gray')){
    		return false
    	}else{
    		if(!tools.check('user',$('.user').val())){
    			tools.tip(tools.errmsg.user)
    		}else if(!tools.check('pwd',$('.pwd').val())){
    			tools.tip(tools.errmsg.pwd)
    		}else {
    			var data ={'user_name':$('.user').val(),'password':$('.pwd').val()};
          tools.ajax('{{url("/homes.login") }}', data, function(e){
            console.log(e);
            if(e.success==true){
              window.location.href="{{url('/players.account-security')}}";
            }
          },function(a){
            var o = JSON.parse(a.response);
            if(o.success==false)
            {
              tools.tip('用户名或密码错误');
            };
          });
    		}
    	}
    })
    //忘记密码
    $('.forgetpwd').on('click', function(){
        $.prompt('请输入要找回的邮箱账号',function(val){
            if(tools.check('email',val)){
                $.ajax({
                    type:"get",
                    url:'{!! url('/homes.mobileForget') !!}',
                    data:{email:val,exist:1,info:'player',type:'forget_url'},
                    dataType:'json',
                    success:function(data){
                        tools.go('/homes.mobileForgetPage?email='+val)
                    },
                    error:function(xhr){
                        var mes = $.parseJSON(xhr.response);
                        $.alert(mes.message)
                    }
                })
            }else{
                $.alert(tools.errmsg.email)
            }
        })
    })
  </script>
@endsection