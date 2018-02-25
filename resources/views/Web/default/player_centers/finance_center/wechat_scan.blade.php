@extends('Web.default.layouts.app')

@section('script')
    <script type="text/javascript" src="http://cdn.staticfile.org/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
    <div class="WeChat-scan">   
        <div><img src="{!! asset('./app/img/Wechat-scan.png') !!}" alt=""/></div>
        <div class="Wechat-back">
            <div id="qrcode"></div>
            <div><i class="with-wechat"></i><p class="pull-right">请使用微信扫描<br>二维码已完成支付</p></div>
            <div><b>¥<span>{!! $playerDepositPay->amount !!}</span></b></div>
            <div>
                <p>订单号{!! $playerDepositPay->pay_order_number !!}</p>
                {{--<p>金 额：{!! $playerDepositPay->amount !!}</p>--}}
            </div>
            <div><img src="{!! asset('./app/img/wechat-like.png') !!}" alt=""/></div>
            <div>
                <p>注意事项：</p>
                <p>1.注意核对订单金额以免造成损失</p>
                <p>2.充值未到账请联系站内或订单客服</p>
                <p>3.请在<b>五分钟</b>内完成支付 否则订单将自动失败</p>
                <p><b>手机用户：下载二维码 > 打开微信扫一扫 > 从相册导入二维码即可支付</b></p>
            </div>
            <div><b>客服电话：400-000-0000</b></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery('#qrcode').qrcode({width: 300,height: 300,correctLevel:0,render: "table",text: $pay_url});
    </script>
<style>
    .Wechat-back{
        background: url("../app/img/Wechat-background.png") no-repeat;
        width: 930px;
        height: 1280px;
        margin: 12px  auto;
        padding-top: 80px;

    }
    .WeChat-scan>div:nth-child(1){
        width: 100%;
        height: 58px;
        background-color: #363942;
    }
    .WeChat-scan>div:nth-child(1)>img{
        margin: 0 auto;
        display: block;
    }

    .Wechat-back>div:nth-child(1)>table{
		margin: 0 auto;
    }
    .Wechat-back>div:nth-child(2){
        width: 579px;
        height:86px;
        background-color: #445f85;
        margin: 0 auto;
        color: #fff;
        padding:  19px 184px 0 194px;;
        margin-top: 10px;
    }
    .WeChat-scan{
        background-color: #cfd0d2;
        padding-bottom: 55px;
    }
    .with-wechat{
        width: 56px;
        height: 56px;
        float: left;
        background:url("../app/img/with-wechat.png") no-repeat;
    }
    .Wechat-back>div:nth-child(2)>p{
        font-size: 16px;
         width: 130px;
        padding-top: 5px;
    }
    .Wechat-back>div:nth-child(3){
        text-align: center;
        font-size: 60px;
        color: #585858;
        margin-top: 60px;
        padding-bottom: 30px;
    }
    .Wechat-back>div:nth-child(4){
        width: 585px;
        height: 87px;
        margin: 0 auto;
        text-align: center;
        border-top: 1px solid #ddd;
        border-bottom:  1px solid #ddd;
        padding-top: 15px;
    }
    .Wechat-back>div:nth-child(4)>p:nth-child(1){
        font-size: 20px;
        color: #585858;
        padding-bottom: 5px;
    }
    .Wechat-back>div:nth-child(4)>p:nth-child(2){
        font-size: 14px;
        color: #585858;
    }
    .Wechat-back>div:nth-child(5)>img{
        display: block;
        margin: 10px auto;
    }
    .Wechat-back>div:nth-child(6){
        width: 585px;
        margin: 0 auto;
        font-size: 14px;
        height: 180px;
        border-bottom: 4px solid #ddd;
    }
    .Wechat-back>div:nth-child(7){
        width: 585px;
	    font-size: 14px;
	    margin: 28px auto;
    }
</style>
@endsection


