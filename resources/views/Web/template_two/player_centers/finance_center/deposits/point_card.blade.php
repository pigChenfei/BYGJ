{{--点卡支付--}}
<style>
    .btn-warning {
        background: #a671ff;
        border-color: #a671ff;
    }
    .btn-warning:hover{
        background: #8545f1;
        border-color: #8545f1;
    }
</style>
<div class="tab-pane active" id="user-nav">
    <form class="pull-left deposit-tab"  id= "usercenter-content4">
        <div>
            <b>点卡选择</b>
            <select name="">
                <option value="1">{!! $otherPay->payChannel->channel_name !!}（15%手续费）</option>
            </select>
        </div>
        <div><b>点卡面额</b>
            <select name="" id="dide-kade">
                <option value="1">50</option>
                <option value="2">200</option>
                <option value="2">1000</option>
                <option value="2">99999</option> 
            </select>
        </div>
        <div>
            <b>实际到账</b>
            <input type="text" class="reality"/>
        </div>
        <div>
            <b>卡号</b>
            <input type="text" class="reality-text1" maxlength="20"/>
        </div>
        <div>
            <b>密码</b>
            <input type="password" class="reality-pass" maxlength="10"/>
        </div>
        <div>
            <b class="pull-left">优惠活动</b>
            <div class="dropdown pull-left select selsect-clir" style="border: 1px solid transparent" >
                <select name="activityId" id=""  class="select9" style="width: 508px;height: 41px;">
                    <option value="">不参加任何活动</option>
                    @foreach($carrierActivityList as $activity)
                        <option value="{!! $activity->id !!}" @if($act_id == $activity->id)selected @endif>{!! $activity->name !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <div>
            <b></b><input type="submit" class="btn  Confirm-the-address4 true-button" value="确认提交" style="width: 210px;height: 40px;border-radius: 2px;padding: 7px; background-color: #2ac0ff;color: #fff;">
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
