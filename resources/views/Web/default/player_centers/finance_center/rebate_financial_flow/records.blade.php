<main>
    <nav class="usercenter-row  wash-code recodes">
        <div style="width: 500px;margin: 50px 0;">
            <b style="font-size: 22px;line-height: 36px;margin-left: 32px;">可结算洗码金额：<span class="money-span" id="settleAllAmount">{!! $settleAmountTotal !!}</span></b>
            <div style="float: right;cursor: pointer;"><img src="{!! asset('./app/img/btn_total.png') !!}" class="settleAll"/></div>
        </div>
        <main style='width: 1075px;padding: 10px;'>
            <table class="table table-bordered tab-checkbox" style='width: 1050px;margin: 0;'>
                <thead>
	                <tr>
	                    <th>游戏厅</th>
	                   {{-- <th>有效投注额</th>--}}
	                    <th>未结算有效投注额</th>
	                    <th>洗码比例</th>
	                    <th>可结算金额</th>
	                    <th>状态</th>
	                </tr>
                </thead>
                <tbody id="rebateFinancialFlow">
					@include('Web.default.player_centers.finance_center.rebate_financial_flow.lists')
                </tbody>
            </table>
        </main>
    </nav>
</main>

<script src="{!! asset('./app/js/Finance-Center.js') !!}"></script>
<script>
    $(function(){
        //结算
        playerRebateFinanceFlow('.settleOne', 'settle');

        //结算所有
        playerRebateFinanceFlow('.settleAll');

    });

    function playerRebateFinanceFlow(operator, $param){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var data = {};
            var me = $(this);
            if($param){
                var playerRebateFinanceFlowId = $(this).attr('data');
                data = { 'playerRebateFinanceFlowId' : playerRebateFinanceFlowId };
            }
            $.ajax({
                url : '{!! route('players.settleMoney') !!}',
                data : data,
                type : 'POST',
                dataType : 'json',
                success : function(resp){
                    layer.close(index);
                    if(resp.success){
                        $('#settleAllAmount').html(resp.data);
                        me.parent('.settleTd').html('已结算');
                        layer.msg(resp.message);
                    }else{
                        layer.msg(resp.message);
                    }
                    return false;

                },
                error : function(xhr){
                    layer.close(index);
                    if(xhr.responseJSON.success == false){
                        layer.msg(xhr.responseJSON.message);
                    }
                    return false;
                }
            })
        });
    }

    function addEventListener(operator, url){
        $(operator).on('click', function(e){
            e.preventDefault();
            var index = layer.load(1, {
                shade: [0.1, '#fff']
            });
            var perPage = $('#washCodePerPage option:selected').val();
            var type = 'list';
            if(url == undefined){
                url = $(this).attr('href');
            }
            var data = {
                'type' : type,
                'perPage' : perPage
            };
            $.ajax({
                url : url,
                data : data,
                dataType : 'text',
                success : function(resp){
                    layer.close(index);
                    $('#rebateFinancialFlow').html(resp);
                },
                error : function(xhr){
                    layer.close(index);
                    ayer.msg('查无此记录');
                    return false;
                }
            })
        });
    }
</script>

