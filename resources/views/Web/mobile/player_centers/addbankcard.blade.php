@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current page-pay" id="page-realpayinfo">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">实际支付信息</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content money">
                  <div class="item-inner">
                    <div class="item-title label">支付金额(元)：</div>
                    <div class="item-after fontred">100</div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content username">
                  <div class="item-inner">
                    <div class="item-title label">支付人姓名：</div>
                    <div class="item-input">
                      <input id="username" type="text" placeholder="请填写" readonly>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content item-link paytype">
                  <div class="item-inner">
                    <div class="item-title">线下支付形式：</div>
                    <div class="item-after">请选择</div>
                    <input id="paytype" type="hidden" value="撒旦发射啊">
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content item-link bankcard">
                  <div class="item-inner">
                    <div class="item-title">存款银行名称：</div>
                    <div class="item-after">中国建设银行(0128)</div>
                  </div>
                </div>
              </li>
              <li>
                <div class="item-content paytime">
                  <div class="item-inner">
                    <div class="item-title label">支付时间：</div>
                    <div class="item-after">2017-12-06 10:08</div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li>
                <div class="item-content fuyan">
                  <div class="item-inner">
                    <div class="item-title label">附言：</div>
                    <input id="fuyan" type="text">
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-fill button-danger button-gray submit">提交</a>
          <div class="paytip f13">1.填写金额与银行&nbsp;&nbsp;2.和对汇款信息&nbsp;&nbsp;3.转账汇款&nbsp;&nbsp;4.充值成功</div>
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
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <<script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    var bankcardlist = ['支付形式1','支付形式2']
    tools.picker($('#paytype'), '请选择支付形式', bankcardlist)
    $('.paytype').click(function(){
    	$('#paytype').picker('open')
    })
    $('#paytype').on('change',function(){
    	$('.paytype .item-after').text($(this).val())
    	checkdone()
    })
    $('.username').on('click', function(){
    	$.prompt('请输入您的真实姓名',function(val){
    		if(tools.check('zhName',val)){
    			$('#username').val(val);
    			checkdone()
    		}else{
    			$.alert('真实姓名格式不正确！')
    		}
    	})
    })
    $()
    $('#paytype,#username').on('input',function(){
    	checkdone()
    })
    function checkdone() {
    	if (tools.checkEmpty.vals($('#username,#paytype')).isOk)
    		$('.submit').removeClass('button-gray')
    	else
    		$('.submit').addClass('button-gray')
    }
    $('.submit').on('click', function(){
    	if($(this).hasClass('button-gray')){
    		return false
    	}else{
    		// do soming
    	}
    })
  </script>
@endsection