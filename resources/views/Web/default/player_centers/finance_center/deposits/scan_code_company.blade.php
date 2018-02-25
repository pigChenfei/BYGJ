{{--扫码支付(公司)--}}
<div class="tab-pane active" id="user-nav">
    <form class="pull-left usercenter-content1"  id= "usercenter-content3">
        <div><b style="margin-top: 10px;">存款金额</b> <input type="text" name='amount' class="user-de23" maxlength="5"/><p><span>*</span><span>注意：在线支付下限100元 , 上限50000</span></p></div>
        <div>
            <img style="width: 80px;height: 80px;" src="{!! asset($otherPay->qrcode) !!}">
            {{--<span style="margin-left: 5px">{!! $pay->payChannel->channel_name !!}</span><b></b>--}}
        </div>
        <div style="margin-top: 20px;">
            <b class="pull-left">优惠活动</b>
            <div class="dropdown pull-left select" >
                <select name="activityId" id="" style="margin-left: 0;" class="select22">
                    <option value="">不参加任何活动</option>
                    @foreach($carrierActivityList as $activity)
                        <option value="{!! $activity->id !!}">{!! $activity->name !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <div>
            <input type="submit" class="btn btn-default Confirm-the-address3" style="padding-top: 10px;margin-top: 20px;" value="确认提交">
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
