@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-account-statistic">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">账目统计</h1>
        </header>
        <!--内容区-->
        <div class="content">
          <div class="list-block mt10">
            <ul>
              <li><a class="item-content item-link accounttype">
                  <div class="item-inner">
                    <div class="item-title">统计类型</div>
                    <div class="item-after">统计概况</div>
                    <input id="accounttype" type="hidden">
                  </div></a></li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link time">
                  <div class="item-inner">
                    <div class="item-title">时间</div>
                    <div class="item-after">今天</div>
                    <input id="time" type="hidden">
                  </div></a></li>
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
    <form method="get" action="{{url('players.accountStatistics')}}" id="form">
      <input type="hidden" name="t" id="t" value="1"/>
      <input type="hidden" name="tabletype" id="type" value="1"/>
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
    	tools.picker($('#accounttype'), '请选择统计类型', ['统计概况','结算详情'])
    	$('.accounttype').click(function(){
    		$('#accounttype').picker('open')
    	})
      $('.btn-query').click(function(){
        $('#form').submit();
      })
    	$('#accounttype').on('change',function(){
    		$('.accounttype .item-after').text($(this).val())
        if($(this).val()=='统计概况')
        {
            $('#type').val('1');
        }
        else
        {
           $('#type').val('2');
        }
    	})
    	tools.picker($('#time'), '请选择查询时间', ['今天','本周','本月'])
    	$('.time').click(function(){
    		$('#time').picker('open')
    	})
    	$('#time').on('change',function(){
    		$('.time .item-after').text($(this).val())
        if($(this).val()=='今天')
        {
            $('#t').val('1');
        }
        else if($(this).val()=='本周')
        {
            $('#t').val('2');
        }
        else if($(this).val()=='本月')
        {
            $('#t').val('3');
        }
    	})
    	$.init()
    })
  </script>
@endsection