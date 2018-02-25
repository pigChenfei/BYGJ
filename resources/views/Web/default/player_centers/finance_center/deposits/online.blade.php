{{--在线支付--}}
<div class="tab-pane active" id="user-nav">
    <form  class="pull-left usercenter-content1" action="" id="usercenter-content" method='post' name="" >

        <div><b style="margin-top: 10px;">存款金额</b> <input type="text" name="amount" class="user-de" maxlength="5" /><p><span>*</span><span>注意：在线支付下限100元 , 上限50000</span></p></div>
        <div class="bank-sure">
                @if($onlinePay->bank_list)
                    @foreach( $onlinePay->bank_list as $item)
                    <div onclick="$('#bankSelectRadio_{!! $item->code !!}').click();" style="background-image: url({!! $item->icon !!});">
                        <i class=""></i><b></b></div>
                    <input type="radio" id="bankSelectRadio_{!! $item->code !!}" style="display: none;" name="bankCode" value="{!! $item->code !!}">
                @endforeach
                    @else
                    <div><i class="bank-abc"></i><span>{!! $onlinePay->bindedThirdPartGateway->defPayChannel->channel_name !!}</span><b></b></div>
                @endif
        </div>

        <div>
            <b class="pull-left" style="margin-top: 10px;">优惠活动</b>
            <div class="dropdown pull-left select" style="margin-top: 0; border: 0;">
                <select name="activityId" id="" style="margin-left: 0;" class="select1">
                    <option value="">不参与任何活动</option>
                    @foreach($carrierActivityList as $activity)
                        <option value="{!! $activity->id !!}">{!! $activity->name !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <div>
            <input type="hidden" value="{!! $onlinePay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
            <input type="hidden" value="{!! $onlinePay->id !!}" name="carrierPayChannelId">
            <input type="submit" class="btn btn-default Confirm-the-address"  style="padding-top: 10px;margin-top: 20px;" value="确认提交">
        </div>
    </form>
    <div class="pull-right annotation">
        <div><b>注意事项</b></div>
        <p>1.使用微信支付时，需绑定银行卡才能进行存款。</p>
        <p>2.单笔存款最低50元；大于上限金额，请您分多次转入。</p>
        <p>3.自助申请优惠，请阅读活动详情，如提交即默认同意条款。</p>
        <p>4.存款超过5分钟未到账时，请联系 <a href="">在线客服。</a></p>
    </div>
    <div class="clearfix"></div>
</div>
<script src="{!! asset('./app/js/Finance-Center.js') !!}"></script>

