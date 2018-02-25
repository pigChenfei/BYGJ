@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-changeloginpwd">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">修改密码</h1>
        </header>
        <!--底部信息展示-->
       {!! \WTemplate::footer() !!}
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-lock"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="oldpwd" type="password" placeholder="请输入原密码" maxlength="20">
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
                  <div class="div item-meida"><i class="icon icon-ww icon-lock"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="pwd" type="password" placeholder="请输入6-20位数字或字母组成的新密码" maxlength="20">
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
                  <div class="div item-meida"><i class="icon icon-ww icon-lock"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="regpwd" type="password" placeholder="请再次输入密码" maxlength="20">
                      <label class="label-switch">
                        <input type="checkbox">
                        <div class="checkbox"></div>
                      </label>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-red button-gray btn-done" href="javascript:">完成</a>
          <div class="tip-box"><span class="f-l">遇到问题？<a class="fontred concatus" href="{!! url('/homes.contactCustomer?type=contact')!!}">联系客服</a></span></div>
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
    $('.label-switch').click(function(){
    	var _input = $(this).siblings('input')
    	if(_input.attr('type') == 'text')
    		_input.attr('type','password')
    	else
    		_input.attr('type','text')	
    })
    $('.oldpwd,.pwd,.regpwd').on('input',function(){
    	checkdone()
    })
    function checkdone() {
    	if (tools.checkEmpty.vals($('.oldpwd,.pwd,.regpwd')).isOk)
    		$('.btn-done').removeClass('button-gray')
    	else
    		$('.btn-done').addClass('button-gray')
    }
    $('.btn-done').click(function(){
    	var _that = $(this)
    	if (_that.hasClass('button-gray')){
    		return false
    	}else {
    		if(!tools.check('pwd',$('.pwd').val()) || !tools.check('pwd',$('.oldpwd').val()) || !tools.check('pwd',$('.regpwd').val()) ) {
    			tools.tip(tools.errmsg.pwd)
    		}else {
    			if($('.pwd').val()!=$('.regpwd').val()){
    				tools.tip(tools.errmsg.rpwd)
    			}else {
    				$.ajax({
                type: 'post',
                async: true,
                url: "/userperfectinformation.resetPassword",
                data: {
                    'player_id' : {!! $player_id !!},
                    'old_password': $('.oldpwd').val(),
                    'password': $('.pwd').val(),
                    'password_confirmation': $('.regpwd').val(),
                    'type': '',
                },
                dataType: 'json',
                success: function(data){
                  console.log(data);
                    if(data.success == true){
                      tools.tip('密码修改成功');
                    }
                },
                error: function(xhr){
                  var temp =eval('(' + xhr.response + ')')
                   tools.tip(temp.message);
                }
            });
    			}
    		}
    	}
    })
  </script>
@endsection