@extends('Web.mobile.layouts.app')
@section('content')
	<div class="page-group">
      <div class="page page-current page-pay" id="page-wxpay">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">{{$onlinePay->display_name}}</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
        <form id="onlinePayForm">
        
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content money">
                  <div class="item-inner">
                    <div class="item-title label">金额：</div>
                    <div class="item-input">
                      <input id="money" type="number" name="amount" placeholder="最小充值金额为100元，最高为50000元">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content item-link pay_type">
                  <div class="item-inner">
                    <div class="item-title">支付方式：</div>
                    <div class="item-after">请选择支付方式</div>
                    <input type="hidden" id="pay_type" value="{{ !empty($gateway)?$gateway:'' }}">
                    <input type="hidden" name="pay_type" value="{{ !empty($gateway)?$gateway:'' }}">
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content item-link discount">
                  <div class="item-inner">
                    <div class="item-title">优惠活动：</div>
                    <div class="item-after">不参与任何活动</div>
                    <input id="discount" type="hidden">
                    <input name="activityId" type="hidden">
                  </div>
                </div>
              </li>
            </ul>
            <input type="hidden" value="{!! $onlinePay->PayChannel->payChannelType->id !!}" name="payChannelTypeId">
    		<input type="hidden" value="{!! $onlinePay->id !!}" name="carrierPayChannelId">
          </div><a class="button button-ww button-fill button-danger button-gray submit">提交</a>
          <div class="paytip f13">请您仔细核对预留信息是否正确，如果与您预留信息不符，请您立即暂停操作并<a class="fontred">联系客服	</a></div>
        </form>
        </div>
      </div>
    </div>
    <div class="layui-m-layer layui-m-layer2" id="layui-m-layer0" index="0">
      <div class="layui-m-layershade"></div>
      <div class="layui-m-layermain">
        <div class="layui-m-layersection">
          <div class="layui-m-layerchild layui-m-anim-scale">
            <div class="layui-m-layercont"><i></i><i class="layui-m-layerload"></i><i></i>
              <p>加载中...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
@stop
@section('script')
    <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
    <script>$.config = {router: false};</script>
    <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
    <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
    <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
    <script>
    </script>
    <script>
    var eventArr=new Array();var scanArr= new Array();
    var eventObj ={};var scanObj = {};
    var events = JSON.parse('{!! $carrierActivityList !!}');
    var scan = JSON.parse('{!! $scan !!}');
    $.each(events, function (index, value) {
    	eventArr.push(value);
    	eventObj[value] = index;
    });
    $.each(scan, function(index,val){
    	scanArr.push(val);
    	scanObj[val] = index;
    });
    tools.picker($('#discount'), '请选择优惠活动', eventArr)
    $('.discount').click(function(){
    	$('#discount').picker('open');
    })
    $('#discount').on('change',function(){
    	$('.discount .item-after').text($(this).val())
    	$('input[name=activityId]').val(eventObj[$(this).val()]);
    })
    tools.picker($('#pay_type'), '请选择支付方式', scanArr)
    $('.pay_type .item-after').text(scan[$('#pay_type').val()])
    $('.pay_type').click(function(){
    	$('#pay_type').picker('open');
    })
    $('#pay_type').on('change',function(){
    	$('.pay_type .item-after').text($(this).val())
    	$('input[name=pay_type]').val(scanObj[$(this).val()]);
    })
    $('#money').on('input',function(){
    	var _that = $(this)
    	if(_that.val().length>0){
    		$('.submit').removeClass('button-gray')
    	}else {
    		$('.submit').addClass('button-gray')
    	}
    })
    $('.submit').on('click', function(){
    	if($(this).hasClass('button-gray') || $(this).hasClass('disabled')){
    		return false
    	}else{
        	var _me = this;
        	var form = $('#onlinePayForm');
    		$.ajax({
                url:"{{route('players.depositPayLogCreate')}}",
                data:form.serialize(),
                type:"POST",
                dataType:'json',
                success:function(repos){
//                 	if (!repos.match("^\{(.+:.+,*){1,}\}$")) {
//                         window.location.href(repos);
//                         return ;
//                     }
//                 	var res = $.parseJSON(repos);
                    var data = repos.data;
                    if (data.success == 200) {
                    	tools.tip('订单生成成功');
                        window.location.href = data.qrcode;
                    } else if (data.success == 2007) {
						window.location.href = data.redictUrl;
                    } else if (data.success == 2006) {
                    	window.location.href = data.prepayUrl;
                    } else {
                    	tools.tip(data.message);
                    }
                    $(_me).removeClass('disabled');
                },
                error:function(xhr){
                    if(xhr.responseJSON.success == false ){
                    	tools.tip('订单生成失败');
                        console.log(xhr.responseJSON);
                    }
                    $(_me).removeClass('disabled');
                }
            });
    	}
    })
  </script>
@endsection