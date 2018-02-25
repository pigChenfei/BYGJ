@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
        <div class="page page-current page-wachcode" id="page-tablist">
            <!--标题栏-->
            <header class="bar bar-nav"><a class="icon icon-left back"></a><a class="icon iconfont icon-refresh pull-right"></a>
                <h1 class="title">实时洗码</h1>
            </header>
            <!--内容区-->
            <div class="content native-scroll">
                <div class="jiesuan row">
                    <div class="col-60">
                        <p class="tit">可结算洗码金额（元）</p>
                        <p class="money f18 fontred fbold" id="settleAllAmount">{!! $settleAmountTotal !!}</p>
                    </div>
                    <div class="col-40 text-center"><a class="button button-fill button-danger button-jiesuan settleAll">全部结算</a></div>
                </div>
                <div class="tab-wrap infinite-scroll native-scroll"></div>
                <div class="infinite-scroll-preloader">
                    <div class="preloader"></div>
                </div>
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
        $(function(){
            //公共参数
            var pageNumber = 1;
            dataInit(pageNumber, {});
            $(document).on("pageInit", "#page-tablist", function(e, id, page) {
                var loading = false;
                $(page).on('infinite', function() {
                    // 如果正在加载，则退出
                    if(loading) return;
                    // 设置flag
                    loading = true;
                    // 模拟1s的加载过程
                    setTimeout(function() {
                        // 重置加载flag
                        loading = false;
                        dataInit(pageNumber,{});
                        // 更新最后加载的序号
                        $.refreshScroller();
                    }, 1000);
                });
            });
            $('.icon-refresh').on('click', function(){
                location.reload();
            });
            //结算单个
            playerRebateFinanceFlow('.settleOne', 'settle');

            //结算所有
            playerRebateFinanceFlow('.settleAll');
            $.init();
            function dataInit(page, date){
                if(!page){page=1;}
                date.page=page;
                date.mobile='mobile';
                $.ajax({
                    type: 'get',
                    url: "{!! app('request')->url() !!}",
                    data: date,
                    dataType: 'json',
                    success: function(data){
                        $.each(data.data.data,function (index,value) {
                            $('.tab-wrap').append('<div class="list-block clearfix"><div class="item"><span>平台：'+value.game_plat.game_plat_name+
                                '</span></div><div class="item"><span>未结算投注额：'+value.bet_flow_amount+
                                '</span></div><div class="item"><span>洗码比例：'+(value.rebate_financial_flow_amount/value.bet_flow_amount).toFixed(2)+
                                '</span></div><div class="item"><span>可结算金额：'+value.rebate_financial_flow_amount+
                                '</span></div><div class="item"><span>状态：'+statusFun(value.is_already_settled, value.id)+'</span></div></div>');
                        });
                        if ($('.list-block.clearfix').length >= data.data.total) {
                            // 加载完毕，则注销无限加载事件，以防不必要的加载
                            $.detachInfiniteScroll($('.infinite-scroll'));
                            // 删除加载提示符
                            $('.infinite-scroll-preloader').remove();
                        }
                        pageNumber++;
                    },
                    error: function(xhr){

                    }
                });
            }
        });
        function statusFun(value, id) {
            if (value == 0){
                return '<span class="settleTd"><span class="fontred settleOne" data-id="'+id+'">立即结算</span></span>';
            }
            return '<span class="settleTd">已结算</span>';
        }
        function playerRebateFinanceFlow(operator, $param){
            $(document).on('click', operator, function(e){
                e.preventDefault();
                layer.open({type: 2});
                var data = {};
                var me = $(this);
                if($param){
                    var playerRebateFinanceFlowId = $(this).attr('data-id');
                    data = { 'playerRebateFinanceFlowId' : playerRebateFinanceFlowId };
                }
                $.ajax({
                    url : '{!! route('players.settleMoney') !!}',
                    data : data,
                    type : 'POST',
                    dataType : 'json',
                    success : function(resp){
                        layer.closeAll();
                        if(resp.success){
                            tools.tip(resp.message);
                            $('#settleAllAmount').html(resp.data);
                            if ($param){
                                me.parent('.settleTd').html('已结算');
                            } else {
                                $('.settleTd').html('已结算');
                            }
                        }else{
                            tools.tip(resp.message);
                        }
                        return false;

                    },
                    error : function(xhr){
                        layer.closeAll();
                        var mes = $.parseJSON(xhr.response);
                        $.alert(mes.message);
                        return false;
                    }
                })
            });
        }
    </script>
@endsection