{{--在线支付--}}
<style>
    .member-container .memb-box .table-cell{padding-left:0;}
    .masklayer .dialog-wrap .dialog-body span {
         position: relative;
         top: 0;
         left: 0;
         color: #f7b531;
    }
</style>
<div class="pay-qq tab-pane active" id="user-nav">
    <form  class="pull-left usercenter-content1" action="" id="usercenter-content" method='post' name="" >
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
    <div class="memb-box">
        <div class="form-inline mb-10">
            <label for="pay-money-qq">金&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额：</label>
            <input type="text" class="form-control user-de" name="amount" id="pay-money-qq">
            <span class="tip">最小充值金额{{$min_fee}}元 @if($max_fee > 0) , 最高充值金额{{$max_fee}}元@endif </span>
            <!--错误提示-->
            <div class="warning" style="display: none;">
                <i class="glyphicon glyphicon-warning-sign font-red"></i>
                <span class="tip">错误提示！</span>
            </div>
        </div>
        <div class="form-inline">
            <label for="pay-info">支付方式：</label>
            @if(!empty($bankList))
            <select name="bank_no" class="form-control" style="width: 230px;">
            	@foreach($bankList as $key=>$val)
            	<option value="{{$key}}">{{$val}}</option>
            	@endforeach
            </select>
            @elseif(!empty($scan))
            <select name="scan" class="form-control" onchange="updatePayType(this.value)" style="width: 230px;">
            	@foreach($scan as $key=>$val)
            	<option value="{{$key}}">{{$val}}</option>
            	@endforeach
            </select>
            @else
            <span type="text" class="form-control pay-ico " style="background-image:url({{$background}});"></span>
            @endif
        </div>
        <input type="hidden" name="pay_type" value="{{$gateway}}">
    </div>
    <div class="memb-box">
        <div class="form-inline">
            <label for="pay-info">优惠活动：</label>
            <select class="form-control" name="activityId" >
                <option value="">不参加任何活动</option>
                @foreach($carrierActivityList as $activity)
                    <option value="{!! $activity->id !!}" @if($act_id == $activity->id)selected @endif>{!! $activity->name !!}</option>
                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" value="{!! $onlinePay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
    <input type="hidden" value="{!! $onlinePay->id !!}" name="carrierPayChannelId">
    <div class="text-center"><button class="btn btn-warning mb-20 Confirm-the-address">提交</button></div>
    </form>
</div>
<div class="masklayer add-card" style="display: none;">
    <div class="dialog-wrap">
        <!--绑定银行卡-->
            <div class="add-card">
                <div class="dialog-head">
                    扫码支付
                </div>
                <div class="dialog-body text-center">
                    <div style="width:320px;height:320px;margin:20px auto;">
                    	<img src="" width="320" height="320" id="qrcode_url">
                    </div>
                    <p>
                    	使用手机打开<span id="app_type"></span>客户端，完成支付操作
                    </p>
                </div>
            </div>
        <!--关闭-->
        <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
    </div>
</div>
<script>
    $(function () {
        //账号存款
        var min_fee = parseInt("{{$min_fee}}");
        var max_fee = parseInt("{{$max_fee}}");
        $("#usercenter-content").on('submit',function(e){ e.preventDefault(); return })
        $("#usercenter-content").on("click",".Confirm-the-address:not(.disabled)",function(e){
            e.preventDefault();
            var entered  = $(".user-de").val();
            if(entered.trim() == "" || isNaN(Number(entered)) || entered <= 0 || (entered < min_fee) || (entered > max_fee && max_fee > 0)){
                layer.tips('输入金额不对', '.user-de', {
                    tips: [1, '#ff0000'],
                    time: 2000
                });
            } else {
                var _me =this;
                var confimText = $(_me).val();
                var form = $(_me).parents("form");
                $(_me).addClass('disabled');
                $(_me).val('提交中');
                $.ajax({
                    url:"players.depositPayLogCreate",
                    data:form.serialize(),
                    type:"POST",
                    dataType:'json',
                    success:function(repos){
//                     	if (!repos.match("^\{(.+:.+,*){1,}\}$")) {
//                             window.location.href(repos);
//                             return ;
//                         }
//                     	var res = $.parseJSON(repos);
                        var data = repos.data;
                        if(data.success == 200){
                            layer.msg('订单生成成功');
                            $('#qrcode_url').attr('src', data.qrcode);
                            $('#app_type').html(data.platform);
                            $('.masklayer.add-card').show();
//                             window.location.href = data.data;
                        } else if(data.success == 2007){
                        	layer.msg(data.message);
							window.open(data.redictUrl);
                        }else{
                            layer.msg(data.message);
                        }
                        $(_me).val(confimText);
                        $(_me).removeClass('disabled');
                    },
                    error:function(xhr){
                        if(xhr.responseJSON.success == false ){
                            layer.msg(xhr.responseJSON.message);
                            console.log(xhr.responseJSON);
                        }
                        $(_me).removeClass('disabled');
                    }
                });
            }
        });
    });
    function updatePayType(value){
		$('input[name=pay_type]').val(value);
    }
</script>
