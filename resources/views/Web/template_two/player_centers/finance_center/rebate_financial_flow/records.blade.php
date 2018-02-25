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
        <!--实时洗码-->
            <article class="livecode">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">实时洗码</h4>
                    <div class="yue-count">
                        <span>可结算洗码金额&nbsp;&nbsp;<i class="font-plum"><span id="settleAllAmount">{!! $settleAmountTotal !!}</span>元</i></span>
                        <button class="btn btn-warning settleAll" @if(empty($settleAmountTotal)) disabled @endif>全部结算</button>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th width="22%" class="text-center">游戏厅</th>
                                <th width="22%" class="text-center">有效投注额</th>
                                <th width="19%" class="text-center">洗码比例</th>
                                <th width="18.5%" class="text-center">可结算金额（元）</th>
                                <th width="18.5%" class="text-center">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playerRebateFinancialFlow as $item)
                                <tr>
                                    <td>{!! $item->gamePlat->game_plat_name !!}</td>
                                    <td>{!! $item->bet_flow_amount !!}</td>
                                    <td>{!! number_format($item->rebate_financial_flow_amount/$item->bet_flow_amount, 2, '.', '') !!}</td>
                                    <td>{!! $item->rebate_financial_flow_amount !!}</td>
                                    <td class="settleTd" style="vertical-align: middle"><a href="javascript:" class="at-real settleOne text-purple" style="text-decoration:underline;" data="{!! $item->id !!}">立即结算</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$playerRebateFinancialFlow->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $playerRebateFinancialFlow->total() }}</i>项，共<i class="pagenum">{{ $playerRebateFinancialFlow->lastPage() }}</i>页，每页<i class="onpagenum">{{ $playerRebateFinancialFlow->perPage() }}</i>个</p>
                            </div>
                            {{ $playerRebateFinancialFlow->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
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
                    shade: [0.1, '#fff'],
                    offset: '401.5px'
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
                            layer.msg(resp.message,{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                            $('#settleAllAmount').html(resp.data);
                            if ($param){
                                me.parent('.settleTd').html('已结算');
                            } else {
                                $('.settleTd').html('已结算');
                            }
                        }else{
                            layer.msg(resp.message,{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                        }
                        return false;

                    },
                    error : function(xhr){
                        layer.close(index);
                        if(xhr.responseJSON.success == false){
                            layer.msg(xhr.responseJSON.message,{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                        }
                        return false;
                    }
                })
            });
        }
    </script>
@endsection