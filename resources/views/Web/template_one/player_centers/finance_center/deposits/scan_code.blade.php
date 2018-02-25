{{--扫码支付--}}
<div class="tab-pane active" id="user-nav">
    <form action="" class="pull-left usercenter-content1" id= "usercenter-content1" action="" method='post'>

        <div><b style="margin-top: 10px; font-size: 16px;color: #666;margin-right: 52px;margin-left: 22px;">存款金额</b> <input type="text" class="user-de1" maxlength="5"/><p><span>*</span><span>注意：在线支付下限100元 , 上限50000</span></p></div>
        <div class="bank-sure" style="height: 28px;">
                <div class="bank-true">
                    <img style="width: 18px;height: 18px;margin: auto;" src="{!! asset($otherPay->payChannel->icon_path_url) !!}">
                <span style="margin-left: 5px">{!! $otherPay->payChannel->channel_name !!}</span><b></b></div>
        </div>
        <div>
            <b class="pull-left">优惠活动</b>
            <div class="dropdown pull-left select">
                <select name="activityId" id="" style="margin-left: 0;" class="select2">
                    <option value="">不参加任何活动</option>
                    @foreach($carrierActivityList as $activity)
                        <option value="{!! $activity->id !!}" @if($act_id == $activity->id)selected @endif>{!! $activity->name !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <div>
            <input type="hidden" value="{!! $onlinePay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
            <input type="hidden" value="{!! $onlinePay->id !!}" name="carrierPayChannelId">
            <input type="submit" class="btn btn-default Confirm-the-address1" style="padding-top: 10px;margin-top: 20px;" value="确认提交">
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
<script>
    $(function () {
        $("#usercenter-content1").on("click",".Confirm-the-address1",function(e){
            e.preventDefault();
            var entered  = $(".user-de1").val();
            if( entered.trim() =="" || isNaN(Number(entered)) || entered < 100 || entered > 50000 ){
                layer.tips('输入金额不对', '.user-de1', {
                    tips: [1, '#ff0000'],
                    time: 2000
                });
            } else {
                var _me =this;
                var confimText = $(_me).val();
                var form = $(_me).parents().parents("form");
                $(_me).val('提交中');
                var amount = form.find(".user-de1").val();
                var payChannelTypeId = $("input[name=payChannelTypeId]").val();
                var carrierPayChannelId = $("input[name=carrierPayChannelId]").val();
                $.ajax({
                    url:"players.depositPayLogCreate",
                    data:{
                        'amount':amount,
                        'carrierPayChannelId' : carrierPayChannelId,
                        'payChannelTypeId' : payChannelTypeId,
                    },
                    type:"POST",
                    dataType:'json',
                    success:function(data){
                        if(data.success == true){
                            layer.msg('订单生成成功');
                            //console.log(data.data);return;
                           // window.location.href = data.data;
                            return false;
                        }else{
                            layer.msg('订单生成失败');
                            console.log(1);
                        }
                        $(_me).val(confimText);
                    },
                    error:function(xhr){
                        if(xhr.responseJSON.success == false ){
                            layer.msg('订单生成失败');
                            console.log(xhr.responseJSON);
                        }
                    }
                });
                return false;
            }
        });
    }) ;
</script>
