@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/bg_body.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection


@section('content')
    <main id='finance_center'>
    <div class="Member-Center">
        <ul class="nav nav-tabs" style="background-color: #eee;">
            <li class="active"><a href="#member-deposit" data-toggle="tab" id="memberDeposit">账户存款</a></li>
            <li><a href="#withdraw-money" data-toggle="tab" id="withdraw">快速取款</a></li>
            <li><a href="#account-transfer" data-toggle="tab" id="accountTransfer">转账中心</a></li>
            <li><a href="#real-time" data-toggle="tab" id="realTime">实时洗码</a></li>
            <li><a href="#apply-discount" data-toggle="tab" id="applyDiscount">申请优惠</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="member-deposit">
				{{--会员存款--}}
            </div>
            
            <div class="tab-pane" id="withdraw-money">
                {{--快速取款--}}
            </div>

            <div class="tab-pane" id="account-transfer" style="margin-left: 34px;margin-top: 50px;">
                {{--转账中心--}}
            </div>

            <div class="tab-pane" id="apply-discount">
                {{--申请优惠--}}
            </div>
            <div class="tab-pane"  id="real-time">
                {{--实时洗码--}}
            </div>
        </div>
    </div>
    </main>




    <div id="deposit5" style="display: none;">
        <div class="pull-left">
            <div>订单号：</div>
            <div>存款金额：</div>
            <div>存款方式：</div>
            <div>手续费：</div>
            <div>实际到账：</div>
        </div>
        <div class="pull-right">
            <p><b>1212121212122</b></p>
            <p><b class="pull">100</b></p>
            <p><b>点卡支付</b></p>
            <p><b>0</b></p>
            <p><b>100</b></p>
        </div>
        <div class="clearfix"></div>
        <div class="btn">立即支付</div>
    </div>
    <div id="deposit3" style="display: none;">
        <div><b>请充值到下列存款账户中：</b></div>
        <div class="pull-left">
            <p>支付宝：<b>11111</b></p>
            <p>姓名：<b>张三</b></p>
            <p>金额： <b>100.00</b></p>
            <p style="color: red;">附言：<b>9555（仅能使用一次）</b></p>
        </div>
        <div class="pull-right">
            <p class="btn">复制</p>
            <p class="btn">复制</p>
            <p class="btn">复制</p>
            <p class="btn">复制</p>
        </div>
        <div class="clearfix"></div>
        <div class="btn jump" style="color:#fff;">确认提交</div>
    </div>
    <div id="deposit2" style="display: none;">
        <div><b>请充值到下列存款账户中：</b></div>
        <div class="pull-left">
            <p>姓名：<b>张三</b></p>
            <p>账号：<b></b></p>
            <p>金额： <b>100.00</b></p>
            <p>地址： <b>中国建设银行江宁支行</b></p>
            <p style="color: red;">附言：<b>9555（仅能使用一次）</b></p>
        </div>
        <div class="pull-right">
            <p class="btn">复制</p>
            <p class="btn">复制</p>
            <p class="btn">复制</p>
            <p class="btn">复制</p>
            <p class="btn">复制</p>

        </div>
        <div class="clearfix"></div>
        <div class="btn jump" style="color:#fff;">跳转到网银去存款</div>
    </div>
    <div id="deposit1" style="display: none;">
        <div class="pull-left">
            <div>订单号：</div>
            <div>存款金额：</div>
            <div>存款方式：</div>
            <div>手续费：</div>
            <div>实际到账：</div>
        </div>
        <div class="pull-right">
            <p><b>1212121212122</b></p>
            <p><b class="pull"></b></p>
            <p><b>扫码支付</b></p>
            <p><b>0</b></p>
            <p><b>100</b></p>
        </div>
        <div class="clearfix"></div>
        <div class="btn">立即支付</div>
    </div>
    <div id="deposit" style="display: none;">
        <div class="pull-left">
            <div>订单号：</div>
            <div>存款金额：</div>
            <div>存款方式：</div>
            <div>手续费：</div>
            <div>实际到账：</div>
        </div>
        <div class="pull-right">
            <p><b>1212121212122</b></p>
            <p><b class="pull"></b></p>
            <p><b>在线支付</b></p>
            <p><b>0</b></p>
            <p><b>100</b></p>
        </div>
        <div class="clearfix"></div>
        <div class="btn">立即支付</div>
    </div>
@endsection

@section('scripts')
    <script src="{!! asset('app/js/finance_centers.js.php') !!}"></script>
    <script src="{!! asset('app/js/Finance-Center.js') !!}"></script>
@endsection
