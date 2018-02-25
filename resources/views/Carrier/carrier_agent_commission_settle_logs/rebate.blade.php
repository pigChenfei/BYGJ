<div class="modal-dialog modal-lg" style=" width: 1000px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">代理洗码记录</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                <table class="table table-bordered table-hover dataTable text-center">
                    <thead>
                        <tr role="row">
                            <th>游戏平台</th>
                            <th>投注额</th>
                            <th>有效投注额</th>
                            <th>比例</th>
                            <th>洗码金额</th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($platFormFee as $pff)
                    	@if(!empty($rebates[$pff->carrier_game_plat_id]))
                        <tr role="row">
                            <td>{{$pff->carrierGamePlat->gamePlat->game_plat_name}}</td>
                            <td>{{$rebates[$pff->carrier_game_plat_id]['cathectic_amount']}}</td>
                            <td>{{$rebates[$pff->carrier_game_plat_id]['available_cathectic_amount']}}</td>
                            <td>{!! $pff->agent_rebate_financial_flow_rate !!}%</td>
                            <td>{!! $rebates[$pff->carrier_game_plat_id]['rebateFinancialFlow'] !!}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>