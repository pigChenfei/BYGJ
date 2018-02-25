<nav class="usercenter-row" style="margin-top:50px;position: relative;">
    <div class=" pull-left" style="margin-top: 15px;font-size: 16px;color: #666;margin-right: 80px;margin-left: 32px;"><b>存款方式</b></div>
    <img src="{!! asset('./app/img/left.png') !!}" alt="" style=" position: absolute;left: 140px;top: 10px;cursor: pointer;" class="left">
        <div class="user_nav">  
          	<ul class="nav nav-tabs pull-left col-md-10 usercenter-user" >
                {{--在线--}}
                @foreach($onlinePayList as $k=>$payType)

                    @foreach($payType as $key=>$pay)
                       <li class="col-md-2"><a data-toggle="tab" data="{!! $pay->id !!}" name='{!! $pay->bindedThirdPartGateway->defPayChannel->payChannelType->id !!}' href="#user-nav{!! $pay->id !!}">{!! $pay->display_name !!}</a></li>
                    @endforeach
                @endforeach
                {{--其他--}}
                @foreach($otherPayList as $k=>$payType)
                    @foreach($payType as $key=>$pay)
                        <li class="col-md-2"><a data-toggle="tab" data="{!! $pay->id !!}" name="{!! $pay->PayChannel->payChannelType->id !!}" href="#user-nav{!! $pay->id !!}">{!! $pay->display_name !!}</a></li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    <img src="{!! asset('./app/img/right.png') !!}" alt="" style="position: absolute;right: 125px;top: 10px;cursor: pointer;" class="right">
</nav>
<div class="tab-content usercenter-content" id="userCenterDeposit">
    {{--@if($onlinePayList)
        @foreach($onlinePayList as $k=>$payType)
            @foreach($payType as $key=>$pay)
                --}}{{--在线支付--}}{{--
                @if($k == \App\Models\Def\PayChannelType::ONLINE_PAY)@include('Web.default.deposits.online_deposit')@endif
            @endforeach
        @endforeach
    @endif--}}

    {{--@if($otherPayList)
            @foreach($otherPayList as $k=>$payType)
                @foreach($payType as $key=>$pay)
                    --}}{{--在线支付--}}{{--
                    @if($k == \App\Models\Def\PayChannelType::ONLINE_PAY)@include('Web.default.deposits.online_deposit')@endif

                    --}}{{--扫码支付--}}{{--
                    @if($k == \App\Models\Def\PayChannelType::SCAN_CODE_PAY)@include('Web.default.deposits.scan_code_deposit')@endif

                    --}}{{--线下银行转账--}}{{--
                    @if($k == \App\Models\Def\PayChannelType::BANK_TRANSFER_PAY)@include('Web.default.deposits.bank_transfer_deposit')@endif

                    --}}{{--扫码支付(公司)--}}{{--
                    @if($k == \App\Models\Def\PayChannelType::SCAN_CODE_COMPANY_PAY)@include('Web.default.deposits.scan_code_company_deposit')@endif

                    --}}{{--点卡支付--}}{{--
                    @if($k == \App\Models\Def\PayChannelType::POINT_CARD_PAY) @include('Web.default.deposits.card_deposit')@endif

                    --}}{{--在线支付/扫码支付--}}{{--
                    @if($k == \App\Models\Def\PayChannelType::ONLINE_OR_SCAN_PAY) @include('Web.default.deposits.online_scan_deposit')@endif
                @endforeach
            @endforeach
        @endif--}}
</div>
<link rel="stylesheet" href="{!! asset('./app/js/jedate/skin/jedate.css') !!}">
<script src="{!! asset('./app/js/jedate/jquery.jedate.js') !!}"></script>

<script>
$(function(){
    //会员存款,存款方式默认显示第一个
    $(document).find('.usercenter-content div.tab-pane').each(function(index){
        if(index == 0){
            $(this).show();
        }else{
            $(this).hide();
        }
    });
    $('#member-deposit li.col-md-2:first').addClass('active');
    if($('#member-deposit li.col-md-2:first').hasClass('active')){
        var payChannelTypeId = $('#member-deposit li.col-md-2:first a').attr('name');
        var carrierPayChannelId = $('#member-deposit li.col-md-2:first a').attr('data');
        depositType(payChannelTypeId, carrierPayChannelId);
    }

    var user_li = $(".usercenter-user>li").width();
    var user_length=$('.usercenter-user').children().length;
    var left =$(".usercenter-user").position().left;
    $(".usercenter-user>li>a").each(function(index){
        $(this).click(function(){
            //请求界面
            var payChannelTypeId = $(this).attr('name');
            var carrierPayChannelId = $(this).attr('data');
            depositType(payChannelTypeId, carrierPayChannelId);

            if(index >1&& index<user_length-3 ){
                $(".usercenter-user").css("left" ,-user_li*(index-2));
            }else if(index == 1) {
                $(".usercenter-user").css("left", "0");
            }
        });
    });
    $(".left").click(function(){
        if(left !=0 ){
            left=left+user_li;
            $(".usercenter-user").css("left",left);
        }
    });
    $(".right").click(function(){
        if(left > -(user_length-4)*user_li){
            left=left-user_li;
            $(".usercenter-user").css("left",left);
        }
    });
});
       
function depositType(payChannelTypeId, carrierPayChannelId){
    $.ajax({
        url : '{!! route('players.DepositTypePage') !!}',
        data : {
            'payChannelTypeId' : payChannelTypeId,
            'carrierPayChannelId' : carrierPayChannelId
        },
        dataType : 'text',
        success : function (resp) {
            $('#userCenterDeposit').html(resp);
            return false;
        },
        error : function () {
            layer.msg('请求失败,请稍后再试...');
            return false;
        }
    });
}
</script>