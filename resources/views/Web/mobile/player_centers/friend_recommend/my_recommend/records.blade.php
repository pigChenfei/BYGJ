@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-myrecommend">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">推荐好友开户</h1>
        </header>
        <!--内容区-->
        <div class="content">
          <div class="list-block mt10">
            <ul>
              <li>
                <div class="item-content">
                  <div class="div item-inner">
                    <div class="item-title">好友开户</div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-people"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="user" type="text" placeholder="4-11位数字或字母组合的账号" maxlength="20">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="div item-meida"><i class="icon icon-ww icon-lock"></i></div>
                  <div class="div item-inner">
                    <div class="item-input">
                      <input class="pwd" type="password" placeholder="6-20位数字或字母组合的密码" maxlength="20">
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
          </div>
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content">
                  <div class="div item-inner">
                    <div class="item-title fbold">推荐链接：<span class="fontc9 f12">{!! $player->recommend_url !!}</span>
                      <input id="uri" type="hidden" value="{{$player->recommend_url}}">
                    </div>
                    <div class="item-after fbold" onclick="tools.copy('uri')">复制</div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="text-center" style="color:#999;">已成功邀请<span class="num fontred">{!! $player->invite_player_count !!}</span>个好友，累计获得奖金<span class="money fontred">{!! $player->totalBonus !!}</span>元</div><a class="button button-ww button-red button-gray btn-submit" href="javascript:">提交</a>
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
    $(function(){
    	$('.label-switch').click(function(){
    		var _input = $(this).siblings('input')
    		if(_input.attr('type') == 'text')
    			_input.attr('type','password')
    		else
    			_input.attr('type','text')	
    	})
    	$('.user,.pwd,.regpwd').on('input',function(){
    		checkdone()
    	})
    	function checkdone() {
    		if (tools.checkEmpty.vals($('.user,.pwd,.regpwd')).isOk)
    			$('.btn-submit').removeClass('button-gray')
    		else
    			$('.btn-submit').addClass('button-gray')
    	}
    	$('.btn-submit').click(function(){
    		var _that = $(this)
    		if (_that.hasClass('button-gray')){
    			return false
    		}else {
    			if(!tools.check('user',$('.user').val())){
    				tools.tip(tools.errmsg.user)
    			}else if(!tools.check('pwd',$('.pwd').val()) || !tools.check('pwd',$('.regpwd').val()) ) {
    				tools.tip(tools.errmsg.pwd)
    			}else {
    				if($('.pwd').val()!=$('.regpwd').val()){
    					tools.tip(tools.errmsg.rpwd)
    				}else {
    					$.ajax({
                        type: 'post',
                        url: "{!! route('homes.register') !!}",
                        data: {
                            'recommend_player_id' : {{$player->player_id}},
                            'user_name' : $('.user').val(),
                            'password' : $('.pwd').val(),
                            'confirm_password' : $('.regpwd').val()
                        },
                        dataType: 'json',
                        success: function (xhr) {
                            if (xhr.success == true) {
                                tools.tip('注册成功');    
                            }
                        },
                        error:function(xhr){
                          var jsonresult=JSON.parse(xhr.response);
                          
                            tools.tip(jsonresult.message);
                        }
                    });
    				}
    			}
    		}
    	})
    	$.init()
    })
  </script>
@endsection