@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-balance">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">帐户余额</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="balance-body text-center">
            <div class="pigcontent"></div>
            <p class="tit f16 fbold">可取余额(元)</p>
            <p class="fontred f20 fbold">{!! \WinwinAuth::memberUser()->main_account_amount !!}</p>
          </div><a class="button button-ww button-red btn-draw" href="{!! url('players.withdraw-money') !!}" >取款</a>
          <div class="finish f-l fbold"><span>完成流水：</span><span class="money fontred">{!! $complete !!}</span></div>
          <div class="unfinish f-r fbold"><span>未完成流水：</span><span class="money fontred">{!! $unfinished !!}</span></div>
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
@endsection