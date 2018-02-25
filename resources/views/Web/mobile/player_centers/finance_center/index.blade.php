@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
        <div class="page page-current" id="page-purse">
            <!--标题栏-->
            <header class="bar bar-nav">
                <h1 class="title"></h1>
            </header>
            <!--工具栏-->
            @include('Web.mobile.layouts.index_nav')
            <!--内容区-->
            <div class="content native-scroll">
                <div class="list-block">
                    <ul>
                        <li><a class="item-content">
                                <div class="item-media"><i class="icon icon-ww icon-savemoney"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">存款<span class="f12 fontc9 pl10">每次存款前务必和对最新收款账号</span></div>
                                </div></a></li>
                    </ul>
                    <div class="paylist f12 text-center">
                        <div class="row no-gutter">
                        @foreach($onlinePayList as $k=>$payType)
							@foreach($payType as $key=>$pay)
							<a class="col-20" href="{{route('players.pay-type',['carrierPayChannelId'=>$pay->id,'payChannelTypeId'=>$pay->bindedThirdPartGateway->defPayChannel->payChannelType->id])}}">
                                <div class="pay-ico fastpay"></div>
                                <div class="pay-txt">{!! $pay->display_name !!}</div></a>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="list-block">
                    <ul>
                        <li><a class="item-content item-link" href="{!! url('players.balance')!!}">
                                <div class="item-media"><i class="icon icon-ww icon-balance"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">余额</div>
                                    <div class="item-after fontred fbold f16" >￥{!! $balance !!}</div>
                                </div></a></li>
                        <li><a class="item-content item-link" href="{!! url('players.withdraw-money')!!}">
                                <div class="item-media"><i class="icon icon-ww icon-drawmoney"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">快速取款</div>
                                </div></a></li>
                        <li><a class="item-content item-link" href="{!!url('players.account-transfer')!!}">
                                <div class="item-media"><i class="icon icon-ww icon-transfer"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">转账中心</div>
                                </div></a></li>
                        <li><a class="item-content item-link" href="{!! route('players.rebateFinancialFlow') !!}">
                                <div class="item-media"><i class="icon icon-ww icon-wachcode"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">实时洗码</div>
                                </div></a></li>
                        <li><a class="item-content item-link" href="{!! route('playerwithdraw.bankcard') !!}">
                                <div class="item-media"><i class="icon icon-ww icon-bankcard"></i></div>
                                <div class="item-inner">
                                    <div class="item-title">银行卡管理</div>
                                </div></a></li>
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
    <script>
    </script>
@endsection