@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-offline">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">我的下线</h1>
        </header>
        <!--内容区-->
        <!--注意！！
        Class名 ready 标注了该项目是否已经通过验证
        Class名 readonly 标注了该项目是否只读，有该标记的不可触发点击事件，使用前务必加上 ready Class名
        -->
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link begin-time">
                  <div class="item-inner">
                    <div class="item-title">开始时间</div>
                    <div class="item-after">请选择</div>
                    <input id="begin-time" type="hidden">
                  </div></a></li>
              <li><a class="item-content item-link end-time">
                  <div class="item-inner">
                    <div class="item-title">结束时间</div>
                    <div class="item-after">请选择</div>
                    <input id="end-time" type="hidden">
                  </div></a></li>
            </ul>
          </div><a class="button button-ww button-red button-gray btn-done" href="javascript:">确定</a>
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
	<form method="get" action="{!! url('players.myReferrals')!!}" id="myform">
	<input name='start_time' id="start_time"/>
	<input name='end_time' id="end_time"/>
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
    	var dd = new Date(),
    		prevDateArr = tools.date.getDatetimeArr(dd.setDate(dd.getDate()-1)),
    		nowDateArr = tools.date.getDatetimeArr(new Date());
    	$('#begin-time').datetimePicker({value: prevDateArr})
    	$('#end-time').datetimePicker({value: nowDateArr})
    	$('.begin-time').click(function(){
    		$('#begin-time').picker('open')
    	})
    	$('.end-time').click(function(){
    		$('#end-time').picker('open')
    	})
    	$('#begin-time').on('change',function(){
    		$('.begin-time .item-after').text($(this).val())
    		toggleReady($('.begin-time'),true)
    		checkdone()
    	})
    	$('#end-time').on('change',function(){
    		$('.end-time .item-after').text($(this).val())
    		toggleReady($('.end-time'),true)
    		checkdone()
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
    	function checkdone(){
    		if(tools.checkEmpty.style($('.item-link'), 'ready')){
    			$('.btn-done').removeClass('button-gray')
    		}else{
    			$('.btn-done').addClass('button-gray')
    		}
    	}
		function formsubmit()
		{
			$('#start_time').val($('#begin-time').val());
			$('#end_time').val($('#end-time').val());
		}
    	$('.btn-done').click(function(){
    		if(!$(this).hasClass('button-gray')){
    			if(new Date($('#begin-time').val()) >= new Date($('#end-time').val()) ){
    				tools.tip('结束时间不能早于开始时间!')
    			}else if(new Date($('#begin-time')) > new Date()) {
    				tools.tip('开始时间不能晚于当前时间!')
    			}else if(new Date($('#end-time')) > new Date()) {
    				tools.tip('结束时间不能晚于当前时间!')
    			}else {
    				//do soming
    				//tools.date.getDate(string) -字符转时间
					//console.log($('#begin-time').val());
					$('#start_time').val($('#begin-time').val());
					$('#end_time').val($('#end-time').val());
					$('#myform').submit();
    			}
    		}else {
    			return false
    		}
    	})
    	$.init()
    })
  </script>
@endsection