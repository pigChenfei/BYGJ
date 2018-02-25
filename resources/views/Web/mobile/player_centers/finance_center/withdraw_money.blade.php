@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-quick-withdrawal">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">快速取款</h1>
        </header>
        <!--底部信息展示-->
       {!! \WTemplate::footer() !!}
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block mt10 media-list inset">
            <ul>
              <li>
                <div class="item-content">
                  <div class="item-inner noline">
                    <!--当有银行卡时有active 样式-->
                    <div class="item-title">到账银行卡
                      @if($str)
                      <span class="add-card active">{{str_replace("'",'',explode(',',$str)[0])}}</span>
                      @else
                      <span class="add-card">+ 添加银行卡</span>
                      @endif
                    </div>
                    <input id="bankcard" type="hidden" @if($str) value="{{str_replace("'",'',explode(',',$str)[0])}}" @endif>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner noline">
                    <div class="item-title">取款金额</div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-input money">
                      <input class="fbold" type="number" id="apply_amount">
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content">
                  <div class="item-inner tip">
                    <div class="item-title"></div>
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-red button-gray btn-done" href="javascript:">确定</a>
          <div class="tip-box"><span class="f-l">单笔最低提款额为<span class="min-money">50</span>元，最高<span class="mmax-money">2000</span>元</span></div>
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
    <form method="post" action="{!! url('playerwithdraw.withdrawApplyone')!!}" id="form">
      <input id="player_bank_card" name="player_bank_card"/>
      <input id="amount" name="apply_amount"/>
    </form>
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    $(function(){
      $('#apply_amount').val('');
    	var $ipt = $('.money input')
    	var balance = {!! \WinwinAuth::memberUser()->main_account_amount !!} //余额 需要后台给到
    	var $err = $('<span class="fontred">输入金额超过余额</span>')
    	var $balance = '余额￥' + balance
    	var $tip = $('.tip .item-title')
    	var rate = 0.001 //手续费费率
      var bcardid='';
    	$tip.html($balance)
    	$ipt.focus()
    	$ipt.on('input', function(){
    		var val = $(this).val()
    		if(val == ''){
    			$tip.html($balance)
    			$('.btn-done').addClass('button-gray')
    		}else if(val > balance){
    			$tip.html($err)
    			$('.btn-done').addClass('button-gray')
    		}else{
    			$tip.html('额外扣除￥'+( ((val*rate).toFixed(2) == 0 ? 0.01 : (val*rate).toFixed(2)) ) +'手续费(费率' + (rate*100+'%') + ')' )
    			$('.btn-done').removeClass('button-gray')
    		}
    	})
      var bankcardlist = [{!! $str !!}]
    	tools.picker($('#bankcard'), '请选择银行卡', bankcardlist)
    	$('.add-card').click(function(){
        if(bankcardlist.length>0)
        {
          $('#bankcard').picker('open')
        }
        else
        {
          tools.go("{!! route('playerwithdraw.bankcard') !!}");
        }
    		
    	})
    	$('#bankcard').on('change',function(){
    		$('.add-card').text($(this).val()).addClass('active')
    	})
    })
    //点击确定
    $('.btn-done').click(function(){
    	var _that = $(this)
        var cards={!! $arr !!};
    	if (_that.hasClass('button-gray')){
    		return false
    	}else{
    		if($('#bankcard').val() == null){
    			tools.tip('请选择一张银行卡')
    		}else{
             var bcardid = cards[$('#bankcard').val()];

          $('#player_bank_card').val(bcardid);
          $('#amount').val($('#apply_amount').val());
          $('#form').submit();
    		}
    	}
    })
  </script>
@endsection