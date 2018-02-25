@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
	<link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
@endsection

@section('header-nav')
	@include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
	<section class="member-container">
		<div class="member-wrap clearfix">
		@include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
		<!--账户存款-->
			<article class="savemoney">
				<div class="art-title">
					<ul class="list-unstyled clearfix pay-type usercenter-user">
						{{--在线--}}
						@foreach($onlinePayList as $k=>$payType)
							@foreach($payType as $key=>$pay)
								<li><a href="javascript:void(0)" data="{!! $pay->id !!}" name='{!! $pay->bindedThirdPartGateway->defPayChannel->payChannelType->id !!}'>{!! $pay->display_name !!}</a></li>
							@endforeach
						@endforeach
						{{--其他--}}
						@foreach($otherPayList as $k=>$payType)
							@foreach($payType as $key=>$pay)
								<li><a href="javascript:void(0)" data="{!! $pay->id !!}" name="{!! $pay->PayChannel->payChannelType->id !!}">{!! $pay->display_name !!}</a></li>
							@endforeach
						@endforeach
					</ul>
                    <input type="hidden" id="payChannelTypeId"/>
                    <input type="hidden" id="carrierPayChannelId"/>
				</div>
				<div class="art-body">
				</div>
			</article>
		</div>
        <input type="hidden" name="act_id" value="{{$act_id}}">
		@if(!\WinwinAuth::memberUser()->mobile && !\WinwinAuth::memberUser()->email)
            <div class="masklayer">
                <div class="dialog-wrap">
                    <div>
                        <div class="dialog-head">温馨提示</div>
                        <div class="dialog-body text-center">
                            <h4>您还没绑定手机号或者邮箱账号，是否去绑定？</h4>
                        </div>
                        <div class="dialog-foot clearfix">
                            <button class="btn btn-warning float-left" style="width: 150px;" onclick="location.href='{{ route('players.account-security') }}'">是</button>
                            <button class="btn btn-warning2 float-right fou" onclick="$(this).parents('.masklayer').hide();" style="width: 150px">否</button>
                        </div>
                    </div>
                    <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
                </div>
            </div>
        @endif
	</section>
@endsection

@section('scripts')
    <link rel="stylesheet" href="{!! asset('./app/js/jedate/skin/jedate.css') !!}">
    <script src="{!! asset('./app/js/jedate/jquery.jedate.js') !!}"></script>
	<script>
        $(function () {
            var act_id = $('input[name=act_id]').val();
            //会员存款,存款方式默认显示第一个
            /*$(document).find('.usercenter-content div.tab-pane').each(function(index){
                if(index == 0){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });*/
            $('.usercenter-user li:first').addClass('active');
            if($('.usercenter-user li').hasClass('active')){
                var payChannelTypeId = $('.usercenter-user>li>a').attr('name');
                var carrierPayChannelId = $('.usercenter-user>li>a').attr('data');
                depositType(payChannelTypeId, carrierPayChannelId);
            }


            $(".usercenter-user>li>a").click(function(e){
                e.preventDefault() ;
                e.stopPropagation() ;
                //请求界面
                var p = $(this).parent();
                p.addClass('active').siblings().removeClass('active');
                var payChannelTypeId = $(this).attr('name');
                var carrierPayChannelId = $(this).attr('data');
                $('#payChannelTypeId').val(payChannelTypeId);
                $('#carrierPayChannelId').val(carrierPayChannelId);
                depositType(payChannelTypeId, carrierPayChannelId);

            });

            function depositType(payChannelTypeId, carrierPayChannelId) {
                $.ajax({
                    url: '{!! route('players.DepositTypePage') !!}',
                    data: {
                        'payChannelTypeId': payChannelTypeId,
                        'carrierPayChannelId': carrierPayChannelId,
                        'act_id':act_id
                    },
                    dataType: 'text',
                    success: function (resp) {
                        $('.art-body').html(resp);
                        return false;
                    },
                    error: function () {
                        layer.msg('请求失败,请稍后再试...');
                        return false;
                    }
                });
            }


















            //点击切换支付方式
            $('.pay-qq').show().siblings().hide();
            addActive('.art-title li');

            //银行卡下拉菜单选中填充
            bindEvent('.choosebank','a','click',function(){
                var _that = $(this),
                    at = _that.attr('bank'),
                    html = $("<div class='bankname "+ at +"'></div>");
                _that.parents('.choosebank').find('.bankico-wrap').html(html);
            }) ;
        }) ;
	</script>
@endsection



