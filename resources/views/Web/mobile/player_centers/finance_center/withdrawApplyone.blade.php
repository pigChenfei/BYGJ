@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-input-drawpwd">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">取款密码</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="inputbox text-center">
            <div class="pwdbox">
              <p class="f18 fbold mb10">请输入取款密码</p>
              <ul class="clearfix">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
              </ul>
              <div class="tip1 fontc9">
                <p>默认取款密码:000000</p>
                <p> <a class="fontred">去修改取款密码</a></p>
              </div>
              <div class="tip2 fontc9">
                <p>忘记密码？<a class="fontred" href="{!! url('homes.contactCustomer?type=contact')!!}">联系客服		</a></p>
              </div>
            </div>
            <input id="drawpwd" type="number" maxlength="6" style="opacity:0;position:absolute;"><a class="button button-ww button-fill button-danger btn-draw">取款</a>
          </div>
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
    <input type="hidden" id="player_bank_card" value="{!! $player_bank_card !!}"/>
    <input type="hidden" id="apply_amount" value="{!! $apply_amount !!}"/>
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    $(function(){
    	$('#drawpwd').focus();
    })
    $('ul').click(function(){
    	$('#drawpwd').focus();
    })
    $('#drawpwd').on('keyup', function(event){
    	var val = $(this).val()
    	if(val.length > 6) {
    		$(this).val(val.substring(0,6))
    	}
    	$('li').removeClass('active')
    	for(var i = 0; i < val.length;i++){
    		$('li').eq(i).addClass('active')
    	}
    })
    $('.btn-draw').on('click',function(){
    	$.ajax({
                type: 'post',
                async: true,
                url: "/playerwithdraw.withdrawApply",
                data: {
                        'player_bank_card':$('#player_bank_card').val(),
                        'apply_amount':$('#apply_amount').val(),
                        'pay_password':$('#drawpwd').val()
                      },
                      dataType: 'json',
                      success: function(data){
                            if(data.success == true){
                               tools.tip('申请成功');
                               tools.go("{!!url('players.account-security')!!}");
                            }
                        },
                        error: function(xhr){
                          var temperror =eval("("+xhr.response+")");
                            if(temperror.success==false){
                                tools.tip(temperror.message);
                                tools.go("{!!url('players.account-security')!!}");
                            }
                        }
                    });

    })
  </script>
@endsection