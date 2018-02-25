@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
        <div class="page page-current page-pay" id="page-addbankcard">
            <!--标题栏-->
            <header class="bar bar-nav"><a class="icon icon-left back"></a>
                <h1 class="title">添加银行卡</h1>
            </header>
            <!--内容区-->
            <div class="content native-scroll">
                <div class="list-block">
                    <ul>
                        <li>
                            <div class="item-content user">
                                <div class="item-inner">
                                    <div class="item-title label">开户人：</div>
                                    <div class="item-input">
                                        <input id="user" type="text" placeholder="默认本人姓名" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content cardnum">
                                <div class="item-inner">
                                    <div class="item-title label">卡号：</div>
                                    <div class="item-input">
                                        <input id="cardnum" type="text" placeholder="请填写您的银行卡号" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content item-link bankcard">
                                <div class="item-inner">
                                    <div class="item-title">银行名称：</div>
                                    <div class="item-after">请选择</div>
                                    <input id="bankcard" type="hidden" value="请选择">
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content bankname">
                                <div class="item-inner">
                                    <div class="item-title label">分行名称：</div>
                                    <div class="item-input">
                                        <input id="bankname" type="text" placeholder="如：岳阳市岳阳楼支行" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div><a class="button button-ww button-fill button-danger button-gray save">保存</a>
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
        var bankArray = new Array();
        var bankObject = new Object();
        var banks = JSON.parse('{!! $banks !!}');
        $.each(banks, function (index, value) {
            bankArray.push(value);
            bankObject[value] = index;
        });
        tools.picker($('#bankcard'), '请选择银行卡类型', bankArray);
        $('.bankcard').click(function(){
            $('#bankcard').picker('open')
        });
        //选择银行卡
        $('#bankcard').on('change',function(){
            $('.bankcard .item-after').text($(this).val())
            checkdone()
        });
        //开户人
        $('.user').on('click', function(){
            $.prompt('请输入开户人姓名',function(val){
                if(tools.check('zhName',val)){
                    $('#user').val(val);
                    checkdone()
                }else{
                    $.alert('开户人姓名格式不正确！')
                }
            })
        });
        //卡号
        $('.cardnum').on('click', function(){
            $.prompt('请输入银行卡卡号',function(val){
                if(/^[0-9]{16,19}$/.test(val)){
                    $('#cardnum').val(val);
                    checkdone()
                }else{
                    $.alert('银行卡卡号格式不正确！')
                }
            })
        });
        //分行名称
        $('.bankname').on('click', function(){
            $.prompt('请输入分行名称',function(val){
                if(/^([\u4e00-\u9fa5]){6,50}$/.test(val)){
                    $('#bankname').val(val);
                    checkdone()
                }else{
                    $.alert('开户行名称格式不正确！')
                }
            })
        });
        $('#cardnum,#user,#bankname,#bankcard').on('input',function(){
            checkdone()
        });
        function checkdone() {
            if (tools.checkEmpty.vals($('#user,#cardnum,#bankname,#bankcard')).isOk)
                $('.save').removeClass('button-gray');
            else
                $('.save').addClass('button-gray')
        }
        $('.save').on('click', function(){
            var button = $(this);
            if(button.hasClass('button-gray')){
                $.alert('请完善银行卡信息')
            }else{
                var userde = $("#user").val() ;//持卡人姓名
                var card_account = $("#cardnum").val() ; //卡号
                var bank_type_id = bankObject[$("#bankcard").val()]; //银行类型id
                var branch_name = $("#bankname").val() ;//分行名称
                button.removeClass('save');
                $.ajax({
                    type: 'post',
                    url: "{!! route('playerwithdraw.addBankCard') !!}",
                    data: {
                        'card_owner_name' : userde,
                        'card_account': card_account,
                        'card_type': bank_type_id,
                        'card_birth_place': branch_name
                    },
                    dataType: 'json',
                    success: function(data){
                        tools.tip('添加银行卡成功')
                        location.href = '{!! route('playerwithdraw.bankcard') !!}'
                    },
                    error: function(xhr){
                        var mes = $.parseJSON(xhr.response);
                        $.alert(mes.message);
                        button.addClass('save');
                    }
                });
            }
        })
    </script>
@endsection