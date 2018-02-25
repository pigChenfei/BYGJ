{{--在线支付--}}
<style>
    .member-container .memb-box .table-cell{padding-left:0;}
    .btn-warning {
        background: #a671ff;
        border-color: #a671ff;
    }
    .btn-warning:hover{
        background: #8545f1;
        border-color: #8545f1;
    }
</style>
<div class="pay-qq tab-pane active" id="user-nav">
    <div class="memb-box">
        <div class="form-inline mb-10">
            <label for="pay-money-qq">金&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额：</label>
            <input type="text" class="form-control" id="pay-money-qq">
            <span class="tip"> 最小充值金额{{$min_fee}}元 @if($max_fee > 0) , 最高充值金额{{$max_fee}}元@endif </span>
            <div class="bank-sure">
                <div><i class="bank-abc"></i><span>{!! $otherPay->payChannel->channel_name !!}</span><b></b></div>
            </div>
            <!--错误提示-->
            <div class="warning" style="display: none;">
                <i class="glyphicon glyphicon-warning-sign font-red"></i>
                <span class="tip">错误提示！</span>
            </div>
        </div>
        <div class="form-inline">
            <label for="pay-info">附加信息：</label>
            <input type="text" class="form-control" id="pay-info">
            <span class="tip">填写附加信息方便查询</span>
        </div>
    </div>
    <div class="memb-box">
        <div class="form-inline">
            <label for="pay-info">优惠活动：</label>
            <select class="form-control">
                <option>不参加任何活动</option>
                @foreach($carrierActivityList as $activity)
                    <option value="{!! $activity->id !!}" @if($act_id == $activity->id)selected @endif>{!! $activity->name !!}</option>
                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" value="{!! $onlinePay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
    <input type="hidden" value="{!! $onlinePay->id !!}" name="carrierPayChannelId">
    <div class="text-center"><button class="btn btn-warning mb-20 Confirm-the-address">提交</button></div>
    <div class="memb-box clearfix memb-bottomtip" style="position: unset">
        <div class="table">
            <div class="table-cell" style="width: 80px;vertical-align: top;">
                温馨提示：
            </div>
            <div class="table-cell">
                请您仔细核对预留信息是否正确，如与您实际预留信息不符，请您立即暂停操作并联系客服。
            </div>
        </div>
    </div>
</div>

