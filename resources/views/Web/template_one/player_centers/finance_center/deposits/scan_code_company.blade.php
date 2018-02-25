<style>
    .member-container .memb-box .table-cell{padding-left:0;}
</style>
<div class="pay-qq tab-pane active" >
    <form class="pull-left usercenter-content1"  id= "usercenter-content3">
        <div class="step-one-company">
            <div class="memb-box">
                <div class="form-inline mb-10">
                    <label for="pay-money-qq">存款金额：</label>
                    <input type="text" class="form-control user-de" name="amount" class="user-de23 ">
                    <span class="tip">在线支付下限100元 , 上限50000</span>
                    <!--错误提示-->
                    <div class="warning" style="display: none;">
                        <i class="glyphicon glyphicon-warning-sign font-red"></i>
                        <span class="tip">错误提示！</span>
                    </div>
                </div>
                <div class="form-inline">
                    <label for="pay-info">支付方式：</label>
                    <input type="text" class="form-control" id="pay-info">
                </div>
            </div>
            <div class="memb-box">
                <div class="form-inline">
                    <label for="pay-info">优惠活动：</label>
                    <select class="form-control" name="activityId">
                        <option>不参加任何活动</option>
                        @foreach($carrierActivityList as $activity)
                            <option value="{!! $activity->id !!}" @if($act_id == $activity->id)selected @endif>{!! $activity->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-center"><button class="btn btn-warning mb-20 next-operate-company">下一步</button></div>
        </div>
        <div class="step-two-company" style="display:none;" >
            <div class="memb-box">
                <div class="form-inline mb-10">
                    <label for="pay-money-qq">存款金额：</label>
                    <input type="text" class="form-control user-de" name="amount" class="user-de23 review-pay-company">
                    <span class="tip">注意：在线支付下限100元 , 上限50000</span>
                    <!--错误提示-->
                    <div class="warning" style="display: none;">
                        <i class="glyphicon glyphicon-warning-sign font-red"></i>
                        <span class="tip">错误提示！</span>
                    </div>
                </div>
                <div class="form-inline">
                    <label for="pay-info">支付方式：</label>
                    <input type="text" class="form-control" id="pay-info">
                </div>
            </div>
            <div class="memb-box">
                <div class="form-inline">
                    <label for="pay-info">备注：</label>
                    <input type="text" class="form-control user-de" >
                </div>
            </div>
            <input type="hidden" value="{!! $otherPay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
            <input type="hidden" value="{!! $otherPay->id !!}" name="carrierPayChannelId">
            <div class="btn-box text-center mt-20">
                <button class="btn btn-warning pre-operate-company">上一步</button>
                <button class="btn btn-warning ml-20 " id="usercenter-content2">提交</button>
            </div>
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

    </form>
</div>







{{--扫码支付(公司)--}}{{--
<div class="tab-pane active" id="user-nav">
    <form class="pull-left usercenter-content1"  id= "usercenter-content3">
        <div><b style="margin-top: 10px;">存款金额</b> <input type="text" name='amount' class="user-de23" maxlength="5"/><p><span>*</span><span>注意：在线支付下限100元 , 上限50000</span></p></div>
        <div>
            <img style="width: 80px;height: 80px;" src="{!! asset($otherPay->qrcode) !!}">
            --}}{{--<span style="margin-left: 5px">{!! $pay->payChannel->channel_name !!}</span><b></b>--}}{{--
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
            <input type="hidden" value="{!! $otherPay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
            <input type="hidden" value="{!! $otherPay->id !!}" name="carrierPayChannelId">
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
</div>--}}
<script>
    $(function () {
        $("#usercenter-content3").on('submit',function(e){
            e.preventDefault();
            return;
        });

        $("#usercenter-content3").on("click",".Confirm-the-address3",function(e){
            e.preventDefault();
            var userde =$(".user-de23").val();
            if (userde.trim() =="" || isNaN(Number(userde)) || userde < 100 || userde > 50000) {
                layer.tips('输入金额不对', '.user-de23', {
                    tips: [1, '#ff0000'],
                    time: 2000
                });
            }else { console.log(2);
                var _me =this;
                var confimText = $(_me).val();
                var form = $(_me).parents("form");
                $(_me).val('提交中');
                $.ajax({
                    url:"players.depositPayLogCreate",
                    data:form.serialize(),
                    type:"POST",
                    dataType:'json',
                    success:function(e){
                        if(e.success == true){
                            layer.open({
                                type: 1,
                                skin: 'layui-layer-rim', //加上边框
                                area: ['285px', '320px'], //宽高
                                content:$('#deposit3')
                            });
                        }else{
                            layer.msg('提交失败');
                        }
                        $(_me).val(confimText);
                    },
                    error:function(){
                        layer.msg('提交失败');
                        $(_me).val(confimText);
                    }
                });
            }
        });


        //存款过程点击下一步操作
        $(document).on('click','.next-operate-company',function () {
            //隐藏当前div
            $(".step-one-company").hide() ;
            //显示下一步div
            //支付第二步，预览支付金额
            var pay_money = $("#pay-money-fast").val() ;
            $(".review-pay-company").text("") ;
            $(".review-pay-company").text(pay_money) ;
            $(".step-two-company").show() ;
        }) ;
        //点击上一步
        $(document).on('click','.pre-operate-company',function () {
            //隐藏当前div
            $(".step-two-company").css('display','none') ;
            //显示上一步div
            $(".step-one-company").css('display','block') ;
        }) ;

    })
</script>
