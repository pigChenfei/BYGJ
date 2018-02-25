@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-personalinfo">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">个人信息</h1>
        </header>
        <!--内容区-->
        <!--注意！！
        Class名 ready 标注了该项目是否已经通过验证
        Class名 readonly 标注了该项目是否只读，有该标记的不可触发点击事件，使用前务必加上 ready Class名
        -->
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link username ready readonly">
                  <div class="item-inner">
                    <div class="item-title">用户名</div>
                    <div class="item-after">{{$player->user_name}}</div>
                  </div></a></li>
                @if( ($player->registerConf->player_sex_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
                  <li><a class="item-content item-link create-actions {{isset($player->sex)?'ready':'sex' }}">
                      <div class="item-inner">
                        <div class="item-title">性别</div>
                        <div class="item-after sex-select">{!! isset($player->sex)?($player->sex?'女':'男') :'请选择' !!}</div>
                        <input id="sex" type="hidden" value="{!! $player->sex !!}">
                      </div></a></li>
                @endif
              <li><a class="item-content item-link {{ $player->real_name ? 'ready': 'realname' }}">
                  <div class="item-inner">
                    <div class="item-title">真实姓名</div>
                    <div class="item-after">{{ $player->real_name or '请填写' }}</div>
                    <input id="realname" type="hidden" value="{{ $player->real_name }}">
                  </div></a></li>
              <li><a class="item-content item-link {{ $player->birthday ? 'ready': 'birthdate' }}">
                  <div class="item-inner">
                    <div class="item-title">出生日期</div>
                    <div class="item-after birth-select">{{ $player->birthday or '请选择' }}</div>
                    <input id="birthdate" type="hidden" value="{{ $player->birthday }}">
                  </div></a></li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link {{ $player->mobile ? 'ready': 'phone' }}">
                  <div class="item-inner">
                    <div class="item-title">手机号</div>
                    <div class="item-after">{{ $player->mobile or '未绑定' }}</div>
                    <input id="phone" type="hidden" value="{{ $player->mobile }}">
                  </div></a></li>
              <li><a class="item-content item-link {{ $player->email ? 'ready': 'email' }}">
                  <div class="item-inner">
                    <div class="item-title">邮箱</div>
                    <div class="item-after">{{ $player->email or '未绑定' }}</div>
                    <input id="email" type="hidden" value="{{ $player->email }}">
                  </div></a></li>
            </ul>
          </div>
            @if(empty($player->email))
            <a class="button button-ww button-red btn-done" href="javascript:">确定</a>
            @endif
            <input type="hidden" name="player_id" value="{{$player->player_id}}">
          <div class="tip-box clearfix"><span class="f-l">注：性别,真实姓名,出生日期 后期无法修改,真实姓名必须与银行卡开户名一致</span></div>
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

        //日期选择
    	$('#birthdate').datetimePicker({});

    	$('.birthdate').click(function(){
    		$('#birthdate').picker('open');
    		$('.picker-items-col').hide();
    		for(var i = 0;i<$('.picker-items-col').size();i++){
    			$('.picker-items-col').eq(i).show();
    			if(i==3) break
    		}
    		toggleReady($('.birthdate'),true)
    	});
    	$('#birthdate').on('change',function(){
    		$('.birth-select').text($(this).val().split(' ')[0])
    	});
    	//性别选择
    	$('.sex').on('click',function(){
    		var _that = $(this);
    		var buttons1 = [
            {
              text: '请选择',
              label: true
            },
            {
              text: '男',
              bold: true,
              onClick: function() {
                _that.find('.item-after').text('男');
                $('#sex').val(0);
                toggleReady($('.sex'),true)
              }
            },
            {
              text: '女',
              color: 'danger',
              onClick: function() {
                _that.find('.item-after').text('女');
                $('#sex').val(1);
                toggleReady($('.sex'),true)
              }
            }
    		];
    		var buttons2 = [
    	        {
    	          text: '取消',
    	          bg: 'danger'
    	        }
    		];
    		var groups = [buttons1, buttons2];
    		$.actions(groups);
    	});
    	//真实姓名
    	$('.realname').on('click',function(){
    		$.prompt('请输入您的真实姓名',function(val){
    			if(tools.check('zhName',val)){
    				$('.realname').find('.item-after').html(val);
            $('#realname').val(val);
    				toggleReady($('.realname'),true)
    			}else{
    				$.alert('真实姓名格式不正确！')
    			}
    		})
    	})
    	$('.phone').on('click',function(){
    		$.prompt('请输入您的手机号',function(val){
    			if(tools.check('phone',val)){
    				$('.phone').find('.item-after').html(val);
            $('#phone').val(val);
    				toggleReady($('.phone'),true)
    			}else{
    				$.alert(tools.errmsg.phone)
    			}
    		})
    	})
    	$('.email').on('click',function(){
    		$.prompt('请输入您的邮箱地址',function(val){
    			if(tools.check('email',val)){
    				$('.email').find('.item-after').html(val);
             $('#email').val(val);
    				toggleReady($('.email'),true)
    			}else{
    				$.alert(tools.errmsg.email)
    			}	
    		})
    	})
    	function toggleReady(ele,isadd){
    		if(isadd){
    			ele.addClass('ready')
    		}else{
    			if(ele.hasClass('ready')){
    				ele.removeClass('ready')
    			}
    		}
    	}
        //保存修改信息
        $(".btn-done").on('click',function(e){
            e.preventDefault();
            if(tools.checkEmpty.style($('.item-link'), 'ready')){
                var button = $(this);
                var id = $("input[name=player_id]").val();
                var birthday = $(".birth-select").text();
                var userName =$("#realname").val();
                var mobile=$('#phone').val();
                var email=$('#email').val();
                var sex=$('.sex-select').text();
                button.removeClass('btn-done');
                $.ajax({
                    type: 'post',
                    url:"/players.perfectUserInformation",
                    async: true,
                    data:{
                        'player_id':id,
                        'real_name':userName,
                        'birthday':birthday,
                        'mobile':mobile,
                        'email':email,
                        'sex':sex,
                    } ,
                    dataType: 'json',
                    success:function(data){
                        tools.tip('修改成功');
                        location.reload();
                    },
                    error: function(xhr){
                        var o = JSON.parse(xhr.response);
                        if(o.success==false)
                        {
                            tools.tip(o.message);
                        }
                        button.addClass('btn-done');
                    }

                });
            }else {
                $.alert('请完善您的个人信息！')
            }
        });

    	$('.readonly').off('click')
    	$.init()
    })
  </script>
@endsection