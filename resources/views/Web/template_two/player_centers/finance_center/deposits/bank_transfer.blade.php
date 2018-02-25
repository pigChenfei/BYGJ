{{--<label for="pay-info"><i class="font-red">&nbsp;✱&nbsp;</i>首选存款账户：</label>--}}
<style>
    .steptwo em{
        display:inline-block;
        line-height:30px;
        vertical-align: middle;
    }
    .ml-20{
        margin-left: 13px;
    }
    .font-red{
        color:#a671ff!important;
    }
    div.form-control{
        height: 30px;
        line-height: 30px;
        margin-left:12px;
        color: rgba(0,0,0,0.9);
        width: 280px!important;
        box-shadow: none;
        padding-top: 0;
        padding-botom: 0;
    }
    .btn-warning {
        background: #a671ff;
        border-color: #a671ff;
    }
    .btn-warning:hover{
        background: #8545f1;
        border-color: #8545f1;
    }
</style>
<div class="steptwo">
	<p style="color:rgba(0,0,0,0.9)">收款账号信息</p>
	<div class="memb-box" style="padding-left:30px;">
		<div class="form-inline mt-20">
            <label for="pay-info">银&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;行：</label>
            <em class="ml-20" style="color:rgba(0,0,0,0.9)">{!! $otherPay->payChannel->channel_name !!}</em>
		</div>
		<div class="form-inline">
			<label for="pay-info">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</label>
			<em class="ml-20" style="color:rgba(0,0,0,0.9)">{!! $otherPay->owner_name !!}</em>
		</div>
		<div class="form-inline">
			<label for="pay-info">卡&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
			<em class="ml-20" style="color:rgba(0,0,0,0.9)">{!! $otherPay->account !!}</em>
		</div>
		<div class="form-inline">
			<label for="pay-info">开户分行：</label>
			<em class="ml-20" style="color:rgba(0,0,0,0.9)">{!! $otherPay->card_origin_place !!}</em>
		</div>
	</div>
	<p style="color:rgba(0,0,0,0.9)">存款账号信息(带<i class="font-red">✱&nbsp;</i>为必填项目)</p>
	<div class="memb-box" style="padding-left:30px;">
        <div class="form-inline mb-10">
            <label for="pay-money-fast"><i class="font-red">✱&nbsp;</i>存款金额：</label>
            <input type="text" class="form-control" id="pay-money-fast">
            <span class="tip"> 最小充值金额{{$min_fee}}元 @if($max_fee > 0) , 最高充值金额{{$max_fee}}元@endif </span>
        </div>
		<div class="form-inline mb-10">
			<label for="pay-name"><i class="font-red">✱&nbsp;</i>存款姓名：</label>
			<input type="text" class="form-control name-de99" id="pay-name" placeholder="请填写汇款人姓名">
		</div>
		<div class="form-inline mb-10">
			<label for="pay-name"><i class="font-red">✱&nbsp;</i>存款方式：</label>
			<select name="depositType" class="form-control" style="width: 212px;">
				@foreach($transferType as $k => $type)
					<option value="{!! $k !!}">{!! $type !!}</option>
				@endforeach
			</select>
		</div>
		<div class="form-inline mb-10">
			<label for="pay-info"><i class="font-red">✱&nbsp;</i>存款银行名称：</label>
			<select name="bankTypeId"  class="form-control" style="width: 212px;">
				@foreach($bankList as $bank)
					<option value="{!! $bank->type_id !!}">{!! $bank->bank_name !!}</option>
				@endforeach
			</select>
			<span class="tip">汇款时的银行名称或第三方渠道名称</span>
		</div>
		<div class="form-inline mb-10">
			<label for="pay-info"><i class="font-red">✱&nbsp;</i>存款账号：</label>
			<input type="text" class="reality-text form-control" maxlength="20"/>
		</div>
		<div class="form-inline mb-10">
			<label for="pay-name"><i class="font-red">✱&nbsp;</i>存款时间：</label>
			{{--<input type="text" class="form-control pay-time">--}}
			<input class="datainp form-control pay-time" id="dateinfo" type="text" placeholder="请选择"  readonly>
		</div>
		<div class="form-inline mb-10">
            <label for="pay-name">附&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;言：</label>
            <div class="form-control">{{$credential}}</div>
            <input type="hidden" class="form-control" name="credential" value="{{$credential}}" readonly>
            <div class="realname-tip" style="margin-left:8px;">
                <i class="iconfont icon-question"></i><span class="realname-tip-question font-white">&nbsp;附言是什么?</span>
                <div class="realname-tip-text">请在转账汇款时填入，如存入方式无法填写可不填，方便后台审核时核对附言码是否正确，快速找到某一笔转账</div>
            </div>
		</div>
    </div>
    <div class="memb-box" style="padding-left:30px;">
		<div class="form-inline">
			<label for="pay-info">优惠活动：</label>
			<select class="form-control" name="activityId">
                <option value="">不参加任何活动</option>
				@foreach($carrierActivityList as $activity)
					<option value="{!! $activity->id !!}" @if($act_id == $activity->id)selected @endif>{!! $activity->name !!}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="btn-box text-center mt-20">
		<button class="btn btn-warning ml-20 " id="usercenter-content2">提交</button>
	</div>
</div>
</div>
<script>
	$(function () {
        //时间选择
        $(".datainp").datetimepicker({
            startDate: '1970-01-01 00:00',
            language: 'zh-CN',
            format: 'yyyy-mm-dd hh:ii',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 1,
            forceParse: 0
        }) ;

        var min_fee = parseInt("{{$min_fee}}");
        var max_fee = parseInt("{{$max_fee}}");
        //账号存款
        $(document).on('click', '#usercenter-content2', function(e){
            //e.preventDefault();
            var bankAccount = $(".reality-text").val();//银行账号
            var useName = $(".name-de99").val();//持卡人
            var pattern = /^[\u4e00-\u9fa5]{2,40}$/;
            var cardId = $('select[name=cardId]').val();//账户选择
            var amount = $("#pay-money-fast").val();//金额
            var bankTypeId = $('select[name=bankTypeId]').val();//银行名称
            var type = $('select[name=depositType]').val();//存款类型
            var time = $("#dateinfo").val();
            var activityId = $("select[name=activityId]").val();
            var payChannelTypeId = $('#payChannelTypeId').val();
            var carrierPayChannelId = $('#carrierPayChannelId').val();
            var credential = $('input[name=credential]').val();
            var _me =this;
            var confimText = $(_me).val();
            var num_min = 16;
            var num_max = 19;
            if (type == 8){
                num_min = 11;
                num_max = 30;
            }
            if(!cardId){
            	if(amount.trim() =="" || isNaN(Number(amount)) || amount<=0 || (amount < min_fee) || (amount > max_fee && max_fee > 0)){
                    layer.tips('输入的金额不正确', '#pay-money-fast', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if(useName.trim() == ""  || pattern.test(useName) != true){
                    layer.tips('请输入正确的姓名', '.name-de99', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if(bankAccount.trim() =="" || isNaN(Number(bankAccount)) || bankAccount.length<num_min || bankAccount.length > num_max){
                    layer.tips('输入卡号不对', '.reality-text', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if(time==""){
                    layer.tips('请选择存款时间', '#dateinfo', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else {
                    //增加银行账户存款
                    var data = {
                        'amount': amount,
                        'useName': useName,
                        'bankTypeId': bankTypeId,
                        'bankAccount': bankAccount,
                        'depositType' : type,
                        'depositTime' : time,
                        'carrierPayChannelId' : carrierPayChannelId,
                        'payChannelTypeId' : payChannelTypeId,
                        'activityId' : activityId,
                        'credential':credential
                    };
                    $(_me).val('提交中');
                    $.ajax({
                        url:"players.depositPayLogCreate",
                        data:data,
                        type:"POST",
                        dataType:'json',
                        success:function(resp){
                            if(resp.success){
                                layer.msg('存款申请成功');
                                window.location.href = resp.data;
                            }else{
                                layer.msg('存款申请失败');
                            }
                            return false;
                            $(_me).val(confimText);
                        },
                        error:function(xhr){
                            layer.msg(xhr.responseJSON.message);
                            return false;
                            $(_me).val(confimText);
                        }
                    });
                    return false;
                }
            }else {
                if(amount.trim() =="" || isNaN(Number(amount)) || amount<=0){
                    layer.tips('输入的金额不正确', '#pay-money-fast', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else if(time.trim()=="") {
                    layer.tips('请选择存款时间', '#dateinfo', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }else {
                    var data ={
                        'cardId' : cardId,
                        'amount': amount,
                        'depositType' : type,
                        'depositTime' : time,
                        'carrierPayChannelId' : carrierPayChannelId,
                        'payChannelTypeId' : payChannelTypeId,
                        'activityId' : activityId
                    };
                    $(_me).val('提交中');
                    $.ajax({
                        url:"players.depositPayLogCreate",
                        data:data,
                        type:"POST",
                        dataType:'json',
                        success:function(resp){
                            if(resp.success){
                                layer.msg('存款申请成功');
                                window.location.href = resp.data;
                            }else{
                                layer.msg('存款申请失败');
                            }
                            return false;
                            $(_me).val(confimText);
                        },
                        error:function(xhr){
                            layer.msg(xhr.responseJSON.message);
                            $(_me).val(confimText);
                            return false;
                        }
                    });
                }
            }
        }) ;
    }) ;
</script>


            
