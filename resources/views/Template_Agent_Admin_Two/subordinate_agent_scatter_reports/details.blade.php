<div class="modal-dialog modal-lg" style=" width: 1200px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">子代理洗码报表</h4>
        </div>
        <div class="box-body">
            <table class="table table-responsive  table-bordered table-hover dataTable">
                <tbody>
                    <tr>
                        <th>游戏平台</th>
                        <th>有效会员</th>
                        <th>投注额</th>
                        <th>有效投注额</th>
                        <th>洗码比例</th>
                        <th>洗码佣金</th>
                    </tr>
                    @foreach($gamePlatBetFlow as $item)
                    <tr>
                        <td>
                            @foreach($gamePlatName as $key => $value)
                                @if($item->game_plat_id == $key)
                                {!! $value !!}
                                @endif
                            @endforeach
                        </td>
                        <td>{!! $item->num !!}</td>
                        <td>{!! $item->bet_amount !!}</td>
                        <td>{!! $item->available_bet_amount !!}</td>
                        <td>{!! $item->agent_rebate_financial_flow_rate !!}</td>
                        <td>{!! $item->agent_commission !!}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>小计</td>
                        <td>{!! $agentStatistics['num'] !!}</td>
                        <td>{!! $agentStatistics['bet_amount'] !!}</td>
                        <td>{!! $agentStatistics['available_bet_amount'] !!}</td>
                        <td></td>
                        <td>{!! $agentStatistics['agent_commission'] !!}</td>
                    </tr>
                    <tr>
                        <td>洗码佣金</td>
                        <td colspan="2">{!! $agentStatistics['agent_commission'] !!}</td>
                        <td>子代理提成</td>
                        <td colspan="2">{!! $agentStatistics['sub_agent_commission'] !!}</td>
                    </tr>
                    <tr>
                        <td>总佣金</td>
                        <td colspan="2">{!! $agentStatistics['commission'] !!}</td>
                        <td>我的提成</td>
                        <td colspan="2">{!! $agentStatistics['sub_agent_commission'] !!}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
    </div>
</div>