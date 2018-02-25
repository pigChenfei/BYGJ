@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-cwbb">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">财务报表</h1>
        </header>
        <!--底部信息展示-->
        {!! \WTemplate::footer() !!}
        <!--内容区-->
        <style>
          .list-block .item-input{
            width:50%;
          }
        </style>
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content item-link type">
                  <div class="item-inner">
                    <div class="item-title">记录类型</div>
                    <div class="item-after item-input">
                      <input id="type" type="text" placeholder="请选择" readonly>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content item-link time">
                  <div class="item-inner">
                    <div class="item-title">时间</div>
                    <div class="item-after item-input">
                      <input id="time" type="text" placeholder="请选择" readonly>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content item-link state">
                  <div class="item-inner">
                    <div class="item-title">状态</div>
                    <div class="item-after item-input">
                      <input id="state" type="text" placeholder="请选择" readonly>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-red btn-query" href="javascript:">查询</a>
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
    <form method="get"  id="form">
      <input type="hidden" name="t" id="t"/>
      <input type="hidden" name="state" id="tstatus">
      <input type="hidden" name="game_plat_id" id="game_plat_id">
    </form>
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    $(function(){
    	
    	tools.authcode.createCode('authcodeview')
    	//点击查询
    	$('.btn-query').click(function(){
    		var ob = tools.checkEmpty.vals($('.list-block input'))
    		if(!ob.isOk){
    			tools.tip(ob.index == 0 ? tools.errmsg.jllx : ob.index== 1 ? tools.errmsg.time : tools.errmsg.state)
    		}
        else 
        {
          if($('#time').val()=='今天')
          {
              $('#t').val('1');
          }
          else if($('#time').val()=='本周')
          {
              $('#t').val('2');
          }
          else if($('#time').val()=='本月')
          {
              $('#t').val('3');
          }
          if($("#"+_id).val()=='订单创建'||$("#"+_id).val()=='未结算')
          {
            $('#tstatus').val('0');
          }
          else if($("#"+_id).val()=='支付成功'||$("#"+_id).val()=='已出款'||$("#"+_id).val()=='存款'||$("#"+_id).val()=='已结算'||($("#"+_id).val()=='待审核'&&$('#type').val()=='优惠记录')||$("#"+_id).val()=='PT真人')
          {
            $('#tstatus').val('1');
            $('#game_plat_id').val('1');
          }
          else if($("#"+_id).val()=='支付失败'||$("#"+_id).val()=='已拒绝'||$("#"+_id).val()=='拒绝')
          {
            $('#tstatus').val('-1');
          }
          else if(($("#"+_id).val()=='待审核'&&$('#type').val()=='存款记录')||$("#"+_id).val()=='取款'||$("#"+_id).val()=='通过'||$("#"+_id).val()=='BBIN真人')
          {
            $('#tstatus').val('2');
            $('#game_plat_id').val('2');
          }
          else if($("#"+_id).val()=='审核未通过'||($("#"+_id).val()=='待审核')&&$('#type').val()=='取款记录')
          {
             $('#tstatus').val('-2');
          }
          else if($("#"+_id).val()=='红利'||$("#"+_id).val()=='SUNBET真人')
          {
            $('#tstatus').val('3');
            $('#game_plat_id').val('3');
          }
          else if($("#"+_id).val()=='转账')
          {
            $('#tstatus').val('5');
          }
          else if($("#"+_id).val()=='洗码'||$("#"+_id).val()=='MG真人')
          {
            $('#tstatus').val('4');
            $('#game_plat_id').val('4');
          }
          else if($("#"+_id).val()=='好友邀请奖励'||$("#"+_id).val()=='GD真人')
          {
            $('#tstatus').val('6');
            $('#game_plat_id').val('6');
          }
          else if($("#"+_id).val()=='存款优惠'||$("#"+_id).val()=='BBIN体育')
          {
            $('#tstatus').val('7');
            $('#game_plat_id').val('7');
          }
          else if($("#"+_id).val()=='沙巴体育')
          {
            $('#game_plat_id').val('8');
          }          
          else if($("#"+_id).val()=='BBIN电子游戏')
          {
            $('#game_plat_id').val('9');
          }
          else if($("#"+_id).val()=='TGP电子游戏')
          {
            $('#game_plat_id').val('10');
          }
          else if($("#"+_id).val()=='MG电子游戏')
          {
            $('#game_plat_id').val('11');
          }
          else if($("#"+_id).val()=='PT电子游戏')
          {
            $('#game_plat_id').val('12');
          }
          else if($("#"+_id).val()=='BBIN彩票')
          {
            $('#game_plat_id').val('13');
          }
          else if($("#"+_id).val()=='BBIN小费')
          {
            $('#game_plat_id').val('14');
          }



          if($('#type').val()=='存款记录')
          {
            $('#form').attr('action',"{!! url('players.depositRecords')!!}");
            $('#form').submit();
          }
          if($('#type').val()=='取款记录')
          {
            $('#form').attr('action',"{!! url('players.withdrawRecords')!!}");
            $('#form').submit();
          }
          if($('#type').val()=='转账记录')
          {
            $('#form').attr('action',"{!! url('players.transferRecords')!!}");
            $('#form').submit();
          }
          if($('#type').val()=='洗码记录')
          {
            $('#form').attr('action',"{!! url('players.washCodeRecords')!!}");
            $('#form').submit();
          }
          if($('#type').val()=='优惠记录')
          {
            $('#form').attr('action',"{!! url('players.discountRecords')!!}");
            $('#form').submit();
          }
          if($('#type').val()=='投注记录')
          {
            $('#form').attr('action',"{!! url('players.bettingRecords')!!}");
            $('#form').submit();
          }
    			// dosoming
    		}
    	})
    	//选项数组
    	var typeArr = ['存款记录', '取款记录', '转账记录', '洗码记录', '优惠记录', '投注记录'],
    		timeArr = ['全部','今天', '本周', '本月'],
    		stateArr= [
    					['全部','订单创建','支付成功','支付失败','待审核','审核未通过'],
    					['全部','待审核','已拒绝','已出款'],
    					['全部','存款','取款','红利','转账','洗码','好友邀请奖励','存款优惠'],
    					['全部','已结算','未结算'],
    					['全部','待审核','通过','拒绝'],
    					['全部','PT真人','BBIN真人','SUNBET真人','MG真人','GD真人','BBIN体育','沙巴体育','BBIN电子游戏','TGP电子游戏','MG电子游戏','PT电子游戏','BBIN彩票','BBIN小费']
    				  ];
    	var stateInput = $('#state').parent().html()
    	//选择类型
    	tools.picker($("#type"), '请选择记录类型', typeArr)
    	//选择时间
    	tools.picker($("#time"), '请选择时间', timeArr)
    	//选择状态
    	tools.picker($("#state"), '请选择状态', stateArr[typeArr.indexOf($("#type").val())])
    	//根据所选类型动态修改状态
    	var _id = 'state'
    	$('#type').on('change',function(){
    		$('#'+_id).remove()
    		var dat = tools.date.getTimeStamp(new Date())
    		var arr = stateArr[typeArr.indexOf($(this).val())]
    		var ele = $('.state .item-after')
    		_id = 'state' + dat
    		ele.append($(stateInput).attr('id',_id))
    		tools.picker($("#"+_id), '请选择状态',arr)
    	})
    })
  </script>
@endsection