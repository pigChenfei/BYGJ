@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-payresult">
        <!--头部-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">{{$title}}</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="pay-result"><i class="iconfont {{$icon}}"></i><span>{{$title}}！</span></div>
          <div class="pay-tips">
          	@if(!empty($data))
          	<p>存款金额为¥{{$data['amount']}}，手续费为¥{{$data['fee_amount']}}</p>
            <p>优惠¥{{$data['benefit_amount']}}，红利¥{{$data['bonus_amount']}}，</p>
            <p>实际到账¥{{$data['finally_amount']}}，当前账户余额为¥{{$data['main_account_amount']}}</p>
			@endif
          </div>
          <div class="btnwrap row"><a class="button button-ww button-red col-50" href="{{route('/')}}">返回首页</a><a class="button button-ww button-red col-50" href="{{route('players.depositRecords')}}">存款记录</a></div>
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
  </body>
@stop
@section('script')
    <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
    <script>$.config = {router: false};</script>
    <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
    <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
    <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
@stop