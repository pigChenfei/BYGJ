@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-tablist">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">取款记录</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="time-wrap text-center mt10"><span class="time-begin">{!! $parameter['start_time'] !!}</span>-<span class="time-end">{!! $parameter['end_time'] !!}</span></div>
          <div class="tab-wrap infinite-scroll native-scroll"></div>
          <div class="infinite-scroll-preloader">
            <div class="preloader"></div>
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
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    var data = [{!! $str!!}]
    // 隐藏加载提示符
    function closeInfinite() {
    	$('.infinite-scroll-preloader').hide()
    }
    // 显示加载提示符
    function openInfinite() {
    	$('.infinite-scroll-preloader').show()
    }
    // 加载完毕，则注销无限加载事件，以防不必要的加载
    function removeInfinite(){
    	$.detachInfiniteScroll($('.infinite-scroll'));
    	// 删除加载提示符
    	$('.infinite-scroll-preloader').remove();
    }
    $('.infinite-scroll-preloader').remove();
    //- closeInfinite()
    function drawTab(data){
    	if(data && data.length!=0){
    		data.forEach(function(item, index){
    			if(typeof item != 'object'){
    				return false
    			}else{
    				//如果列表里面的东西是一行一行显示的 就给个lines Class名
    				//如果列表里面的东西是一行两列显示的 就不要lines Class名
    				var $wrap = $("<div class='list-block lines clearfix'/>")
    				for( var key in item ){
    					var val = item[key]
    					var $item = $("<div class='item'/>")
    					var $span = $("<span/>")
    					if (key == 'link'){
    						var $a = $('<a class="fontred" href="'+val.href+'">'+val.title+'</a>')
    						$item.append($a)
    					}else {
    						$span.html(key+'：'+val)
    						$item.append($span)
    					}
    					$wrap.append($item)
    				}
    				$('.tab-wrap').append($wrap)
    			}
    		})
    	}else{
    		$('.content').addClass('nomsg')
    		closeInfinite()
    		$('.time-wrap').hide()
    		return false
    	}
    }
    $(function(){
    	$(document).on("pageInit", "#page-tablist", function(e, id, page) {
    		var loading = false;
    		drawTab(data)
    		$(page).on('infinite', function() {
    			// 如果正在加载，则退出
    			if(loading) return;
    			// 设置flag
    			loading = true;
    			// 模拟1s的加载过程
    			setTimeout(function() {
    				// 重置加载flag
    				loading = false;
    				drawTab(data)
    				// 更新最后加载的序号
    				$.refreshScroller();
    			}, 1000);
    		});
    	});
    	$.init()
    })
  </script>
@endsection