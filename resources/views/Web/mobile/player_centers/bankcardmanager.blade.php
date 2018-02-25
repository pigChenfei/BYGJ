@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-bankcard-manager">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a><a class="button button-link button-nav pull-right edit">编辑</a>
          <h1 class="title">银行卡管理</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="checkboxlist"></div>
          <div class="cardlist"></div>
          <div class="btns"><a class="button button-ww button-red btn-addcard" href="{!!url('players.addBankcard')!!}">添加银行卡</a><a class="button button-ww button-red btn-delcard" href="javascript:">删除银行卡</a></div>
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
    // 数据填入
    var data = [{!! $str !!}]
    var logoArr = {
    	'renmin' : '&#xe61e;',
    	'pingan' : '&#xe61c;',
    	'beijing' : '&#xe61b;',
    	'guangda' : '&#xe61a;',
    	'pufa' : '&#xe619;',
    	'guangdong' : '&#xe618;',
    	'jianshe' : '&#xe617;',
    	'nongye' : '&#xe616;',
    	'xinyongshe' : '&#xe615;',
    	'jiaotong' : '&#xe614;',
    	'huaxia' : '&#xe613;',
    	'zhongguo' : '&#xe612;',
    	'gongshang' : '&#xe611;',
    	'youzheng' : '&#xe610;',
    	'zhaoshang' : '&#xe60f;'
    }
    var bgnum = 1;
    function addItem(data){
    	if(data && data.length!=0){
    		var html=''
    		var ck = ''
    		for(var key in data){
    			if(bgnum>8) bgnum=1
    			html += '<div class="card-item bankcardbg'+bgnum+'" data-id="'+data[key]._id+'">'+
    					'<div class="bankinfo"><i class="banklogo">'+logoArr[data[key].bankcode]+'</i>'+
    						'<div class="cardinfo"><span class="bankname f16">'+data[key].bankname+'</span><br><span class="cardtype f12">'+data[key].cardtype+'</span></div>'+
    					'</div>'+'<div class="cardnum f12">'+data[key].cardmum+'</div></div>'
    			ck += '<div class="ckitem" data-id="'+data[key]._id+'"></div>'
    			bgnum++
    		}
    		$('.cardlist').html($(html))
    		$('.checkboxlist').html($(ck))
    	}else{
    		$('.content').addClass('nomsg')
    		$('.edit').hide()
    	}
    }
    addItem(data)
    $(document).on('click', '.edit', function(){
    	var _that = $(this)
    	var btn1 = $('.btn-addcard'),btn2 = $('.btn-delcard')
    	if(_that.hasClass('editing')){
    		_that.html('编辑').removeClass('editing')
    		$('.content').removeClass('editing')
    		btn1.attr('style','display:block');btn2.attr('style','display:none')
    	}else{
    		_that.html('取消').addClass('editing')
    		$('.content').addClass('editing')
    		btn1.attr('style','display:none');btn2.attr('style','display:block')
    	}
    })
    $(document).on('click', '.ckitem', function(){
    	var _that = $(this)
    	_that.toggleClass('checked')
    })
    $(document).on('click', '.btn-delcard', function(){
    	if($('.checked').size()>0){
    		layer.open({
    			content: '确定要删除选中银行卡吗？'
    			,btn: ['确定', '不要']
    			,yes: function(index){
    				$('.checked').each(function(index, item){
    					$('.card-item').eq($(item).index()).remove()
    					$(item).remove()
    				})
    				//请求接口删除 tools.ajax...
    				layer.close(index);
    			}
    		});
    	}else{
    		return false
    	}
    })
    $(document).on('click', '.btn-readed', function(){
    	if($('.checked').size()>0 && $('.checked').parent().hasClass('newmsg')){
    		$('.checked').parent().removeClass('newmsg')
    		//请求接口删除 tools.ajax...
    	}else{
    		return false
    	}
    })
    $(document).on('click', '.btn-qx', function(){
    	if($('.checkbox').size() != $('.checked').size() || $('.checked').size() == 0){
    		$('.checkbox').addClass('checked')
    	}else{
    		$('.checkbox').removeClass('checked')
    	}
    })
  </script>
@endsection