@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-regist">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">会员注册</h1>
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
                      <input class="user"  type="text" placeholder="4-11位数字或字母" maxlength="11">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-lock"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="pwd"  type="password" placeholder="6-20位数字或字母" maxlength="20">
                      <label class="label-switch">
                        <input type="checkbox">
                        <div class="checkbox"></div>
                      </label>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-authcode"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="codenum" type="text" placeholder="请输入验证码" maxlength="4">
                      <div class="codebox" id="authcodeview" onclick="tools.authcode.createCode('authcodeview')"></div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-red button-gray btn-regist" href="javascript:">注册</a>
          <div class="tip-box"><span class="f-l">已有账号？<a class="loginnow fontred" href="{{ url('/homes.mobileLogin') }}">在此登录</a></span></div>
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
    var canregist = {btn1:false,btn2:false,btn3:false}
    var code;
    $('.label-switch').click(function(){
      checked = !checked
      if(checked)
        $('.pwd').attr('type','text')
      else
        $('.pwd').attr('type','password') 
    })
    $('.user').on('input',function(){
      if($(this).val().length>0)
        canregist.btn1 = true
      else
        canregist.btn1 = false
      checkregist() 
    })
    $('.pwd').on('input',function(){
      if($(this).val().length>0)
        canregist.btn2 = true
      else
        canregist.btn2 = false
      checkregist() 
    })
    $('.codenum').on('input',function(){
      if($(this).val().length>0)
        canregist.btn3 = true
      else
        canregist.btn3 = false
      checkregist() 
    })
    function checkregist() {
      if (canregist.btn1 && canregist.btn2 && canregist.btn3)
        $('.btn-regist').removeClass('button-gray')
      else
        $('.btn-regist').addClass('button-gray')
    }
    tools.authcode.createCode('authcodeview')
    //点击注册
    $('.btn-regist').click(function(){
      var _that = $(this)
      if (_that.hasClass('button-gray')){
        return false
      }else{
        if(!tools.authcode.validateCode('codenum','authcodeview')){
          tools.tip(tools.errmsg.auth)
        }else if(!tools.check('user',$('.user').val())){
          tools.tip(tools.errmsg.user)
        }else if(!tools.check('pwd',$('.pwd').val())){
          tools.tip(tools.errmsg.pwd)
        }else {
          var data ={'user_name':$('.user').val(),'password':$('.pwd').val(),'confirm_password':$('.pwd').val()};
          tools.ajax('{{url("/homes.register") }}', data, function(e){
            console.log(e);
            if(e.success==true){
              window.location.href="{{url('/players.account-info')}}";
            }
          },function(a){
            var o = JSON.parse(a.response);
            if(o.success==false&&o.field=='user_name')
            {
              tools.tip(tools.errmsg.user);
            }
            else if(o.success==false&&o.field=='password')
            {
              tools.tip(tools.errmsg.pwd);
            };
          });
        }
      }
    })
    
  </script>
@endsection