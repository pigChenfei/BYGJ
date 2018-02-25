@extends('Web.mobile.layouts.app')
@section('content')
  <div class="page-group">
    <!--每个page有自己特有的id-->
    <div class="page page-current" id="page-verify-email">
      <!--标题栏-->
      <header class="bar bar-nav"><a class="icon icon-left back"></a>
        <h1 class="title">邮箱验证</h1>
      </header>
      <!--底部信息展示-->
      {!! \WTemplate::footer() !!}
      <!--内容区-->
      <div class="content native-scroll">
        <div class="msgview text-center">
          <p> <span>我们已经发送了</span><span class="fontred">认证</span><span>到您的邮箱，请查收！</span></p>
          <p class="fontemail">{!! $email !!}</p>
        </div><a class="button button-ww button-red iknow" href="/">知道了</a>
        <div class="tip-box"><span class="f-l">遇到问题？<a class="fontred concatus" href="{!! url('homes.contactCustomer') !!}">联系客服</a></span></div>
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