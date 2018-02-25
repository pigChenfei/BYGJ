@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current page-wachcode" id="page-transfer-center">
        <!--标题栏-->
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title" >转账中心</h1>
        </header>
        <!--内容区-->
        <div class="content native-scroll">
          <div class="jiesuan btn-recover row">
            <div class="col-60">
              <p class="tit">主账户金额（元）</p>
              <p class="money f18 fontred fbold" id="mainaccountamount">{!! $main_account_amount !!}</p>
            </div>
            <div class="col-40 text-center"><a class="button button-fill button-danger button-jiesuan btn-recover">一键回收</a></div>
          </div>
          <div class="list-block mt10">
            <ul>
              <li>
                <div class="item-link item-content transfer-out">
                  <div class="item-inner">
                    <div class="item-title" id="zc">转出账户</div>
                    <div class="item-after">请选择</div>
                    <input id="transfer-out" type="hidden">
                  </div>
                </div>
              </li>
              <li>
                <div class="item-link item-content transfer-to">
                  <div class="item-inner">
                    <div class="item-title" id="zl">转入账户</div>
                    <div class="item-after">请选择</div>
                    <input id="transfer-to" type="hidden">
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="list-block mt10">
            <ul>
              <li>
                <div class="item-content">
                  <div class="item-inner">
                    <div class="item-title label">金额</div>
                    <div class="item-input">
                      <input class="text-right" id="money" type="number" placeholder="请输入转账金额">
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div><a class="button button-ww button-red button-gray btn-done" href="javascript:">确认转账																			</a>
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
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
  <script>
    var userlist = [{!! $str !!}],balance = 500
    var plat={!!$arr!!}
    var platamount={!!$platamount!!}

    var myarray = {}
    for(key in plat){
      myarray[plat[key]] = platamount[key]
    }

    tools.picker($('#transfer-out'), '请选择转出账户', userlist)
    tools.picker($('#transfer-to'), '请选择转入账户', userlist)
    var _id1 = '#transfer-to',
    _id2 = '#transfer-out';
    $('.transfer-out').click(function(){
      $(_id2).picker('open')
    })
    $('.transfer-to').click(function(){
      $(_id1).picker('open')
    })
    $(_id2).on('change',function(){
      $('.transfer-out .item-after').text($(this).val())
      checkdone()
    })
    $(_id1).on('change',function(){
      $('.transfer-to .item-after').text($(this).val())
      checkdone()
    })
    $('#transfer-to,#transfer-out,#money').on('input',function(){
      checkdone()
    })
    $(_id1).on('change',function(){
      change1()
    })
    function change1(){
      var val = $(_id1).val()
      var list = userlist.concat()
      var dat = tools.date.getTimeStamp(new Date())
      list.splice(list.indexOf(val),1)
      $(_id2).remove()
      _id2 = '#transfer-out' + dat
      $('.transfer-out .item-inner').append($('<input id="transfer-out'+dat+'" type="hidden">'))
      tools.picker($(_id2), '请选择转出账户',list)
      $(_id2).on('change',function(){
        $('.transfer-out .item-after').text($(this).val())
        change2()
        checkdone()
      })
      var temp2=myarray[$(_id1).val()];
      var toAccount ='转入帐户<span class="info f12"><span class="fontc9">余额&nbsp;</span><span class="fontred">'+temp2+'元</span></span>';
      $("#zl").html(toAccount);
    }
    $(_id2).on('change',function(){
      change2()
    })
    function change2(){
      var val = $(_id2).val()
      var list = userlist.concat()
      var dat = tools.date.getTimeStamp(new Date())
      list.splice(list.indexOf(val),1)
      $(_id1).remove()
      _id1 = '#transfer-to' + dat
      $('.transfer-to .item-inner').append($('<input id="transfer-to'+dat+'" type="hidden">'))
      tools.picker($(_id1), '请选择转入账户',list)
      $(_id1).on('change',function(){
        $('.transfer-to .item-after').text($(this).val())
        change1()
        checkdone()
      })
      var temp1 = myarray[$(_id2).val()];
      var fromAccount ='转出帐户<span class="info f12"><span class="fontc9">余额&nbsp;</span><span class="fontred">'+temp1+'元</span></span>';
      $("#zc").html(fromAccount);
    }
    function checkdone() {
      if (tools.checkEmpty.vals($('#transfer-to,#transfer-out,#money')).isOk)
        $('.btn-done').removeClass('button-gray')
      else
        $('.btn-done').addClass('button-gray')
    }
    //一键回收
    $('.button-fill').on('click',function(){
        $.ajax({
            url: "{!!url('players.accountRecycle')!!}",
            data: {},
            type: "POST",
            success: function (data) {
              if (data.success == true){
                  location.reload();
              }
            },
            error: function (xhr) {
                  if (xhr.responseJSON.success == false) {
                        tools.tip('回收失败');        
                  }
                  location.reload();
            }
          });
    })
    //确认转账
    $('.btn-done').on('click', function(){
    	if($(this).hasClass('button-gray')){
    		return false
    	}else{
    		if(parseInt($('#money').val()) > parseInt(balance)){
    			tools.tip('转出金额超过主帐户余额！')
    		}else{
           var transferFrom='';
           var transferTo='';
           var amount=$('#money').val();
           for(var key in plat)
          {
            if(plat[key]==$(_id1).prev().html())
            {
              transferTo=key;
            }
            if(plat[key]==$(_id2).prev().html())
            {
              transferFrom=key;
            }
          }
          $.ajax({
            url: "{!!url('players.accountTransfer')!!}",
            data: {
                    'amount': amount,
                    'transferFrom': transferFrom,
                    'transferTo': transferTo
                  },
            type: "POST",
            success: function (data) {
                  if (data.success == true) 
                  {
                    var toAccount ='转入帐户<span class="info f12"><span class="fontc9">余额&nbsp;</span><span class="fontred">'+data.data.transferToAccount+'元</span></span>';
                    var fromAccount ='转出帐户<span class="info f12"><span class="fontc9">余额&nbsp;</span><span class="fontred">'+data.data.transferFromAccount+'元</span></span>';
                    $("#zl").html(toAccount);
                    $("#zc").html(fromAccount);
                    $("#mainaccountamount").text(data.data.mainAccount);
                    myarray[$('.transfer-out .item-after').text()] = data.data.transferFromAccount;
                    myarray[$('.transfer-to .item-after').text()] = data.data.transferToAccount;
                    tools.tip('转入成功');
                  }
            },
            error: function (xhr) {
                  if (xhr.responseJSON.success == false) {
                        tools.tip('转入失败');        
                  }
            }
          });

    			// do soming
    		}
    	}
    })
  </script>
@endsection