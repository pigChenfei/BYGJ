{{--银行转账--}}
<div class="tab-pane active" id="user-nav">
    <form class="pull-left deposit-tab change-tab transferDeposit"  id="usercenter-content2" action="" method='post' >
		<input type="hidden" value="{!! $otherPay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
		<input type="hidden" value="{!! $otherPay->id !!}" name="carrierPayChannelId">
        <div>
			<b>首选存款账户</b>
			<select name="cardId" id="deposit-select" class="deposit-select" style="width: 320px;">
				<option value="">新增存款账户</option>
				@foreach($player as $item)
					@foreach($item->bankCards as $bank)
						<option value="{!! $bank->card_id !!}" >{!! $bank->bankType->bank_name !!}-{!! $bank->card_owner_name !!}-{!! $bank->card_account !!}</option>
					@endforeach
				@endforeach
			</select>
        </div>
        <div>
        	<b>存款金额</b> 
        	<input type="text" class="user-de12" maxlength="20"/>
        	<p><span>*</span><span>请填写存款金额</span></p>
        </div>
        <div class="deposit-display">
            <b>存款人姓名</b>
            <input type="text" class="name-de99"/><p><span>*</span><span>请填写汇款人姓名，若为本人无需填写</span></p>
        </div>
        <div class="deposit-display">
			<b>存款银行名称</b>
			<select name="bankTypeId" style="width: 212px;">
				@foreach($bankList as $bank)
					<option value="{!! $bank->type_id !!}">{!! $bank->bank_name !!}</option>
				@endforeach
			</select>
            <p><span>*</span><span>请选择汇款时使用的银行名称</span></p>
        </div>
        <div class="deposit-display">
            <b>存款银行账号</b>
            <input type="text" class="reality-text" maxlength="20"/>
        </div>
        <div style="width: 650px;height: 40px;">
            <b style="height: 40px;">存入银行信息</b>
            <div class="bank-sure" style="width: 540px;height: 40px;margin-left: 135px;margin-top: 20px;">
                <div class="bankcard">
                    <img style="width: 18px;height: 18px;margin: auto" src="{!! asset($otherPay->payChannel->icon_path_url) !!}">
                    <span style="margin-left: 5px;">{!! $otherPay->payChannel->channel_name !!}</span>
                </div>
            </div>           
        </div>
		 
		<div><b>存款类型选择</b>
			<select name="depositType" style="width: 212px;">
				@foreach($transferType as $k => $type)
					<option value="{!! $k !!}">{!! $type !!}</option>
				@endforeach
			</select>
		    <p><span>*</span><span>请选择汇款时使用的银行名称</span></p>
		</div>
		<div>
		    <b>存款时间</b>
		    <div class="datep">
		        <input class="datainp" id="dateinfo" type="text" placeholder="请选择"  readonly>
		    </div>
		</div>
		<div>
			<b class="pull-left">优惠活动</b>
			<div class="dropdown pull-left select" style="">
				<select name="activityId" id=""  class="select44" style="width: 420px;margin: 0px;">
					<option value="">不参加任何活动</option>
					@foreach($carrierActivityList as $activity)
						<option value="{!! $activity->id !!}">{!! $activity->name !!}</option>
					@endforeach
				</select>
		    </div>
		    <div class="clearfix"></div> 
		</div>
		<div>
		    <b></b><input type="submit" class="Confirm-the-address2 btn"  value="确认提交" style="background-color: #2ac0ff;color: white;border: none;">
		</div>
	</form>

	<!--弹框-->
	<div id="bank-information" style="z-index: 9999999;display: none;">
	    <div><label for="">持卡人</label><span>{!! $otherPay->owner_name !!}</span></div>	    
	    <div><label for="">开户地址</label><span>{!! $otherPay->card_origin_place !!}</span></div>
	    <div><label for="">账号</label><span>{!! $otherPay->account !!}</span></div>
	    <div><label for="">凭证</label><span>sfdgree46</span></div>
	</div>
		
    <div class="pull-right annotation">
        <div><b>注意事项</b></div>
        <p>1.使用微信支付时，需绑定银行卡才能进行存款。</p>
        <p>2.单笔存款最低50元；大于上限金额，请您分多次转入。</p>
        <p>3.自助申请优惠，请阅读活动详情，如提交即默认同意条款。</p>
        <p>4.存款超过5分钟未到账时，请联系 <a href="">在线客服。</a></p>
    </div>
    <div class="clearfix"></div>
</div>

<style type="text/css">
	#bank-information>div{
		text-align: left;
		width: 80%;
	    margin-left: 10%;
	    height: 36px;
	    line-height: 36px;
	   	margin-top: 17px;
	    font-size: 14px;
	    border: 1px solid lightgray;
	}
	#bank-information label{
		width: 82px;
		height: 100%;
		text-align: center;
		font-weight: normal;
		color: black;
		background-color: #eee;
		margin: 0;
		margin-right: 8px;
		padding-right: 10px;
	}
</style>

<script src="{!! asset('./app/js/Finance-Center.js') !!}"></script>
<script>
	$(".bank-sure .bankcard").click(function(){
	    layer.open({
	        type: 1,
	        skin: 'layui-layer-rim', //加上边框
	        area: ['300px', '300px'], //宽高
	        title:['银行卡信息',true], 
	        content:$('#bank-information') 
	    });
	    $('.layui-layer-shade').css('display','none');
	    $('.layui-layer-shade').eq(0).css('display','block');
	    
	    $('.layui-layer-setwin').click(function(){
		 	$('.layui-layer-shade').css('display','none');
		}); 
	});


</script>

            
