@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-changepwd">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">修改密码</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li>
                <a class="item-content item-link" href="{!! url('players.accountPassword') !!}">
                  <div class="item-inner">
                    <div class="item-title">修改登录密码</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link" href="{!!url('players.accountQukuan')!!}">
                  <div class="item-inner">
                    <div class="item-title">修改支付密码</div>
                  </div>
                </a>
              </li>    
            </ul>
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
@endsection