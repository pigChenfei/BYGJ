<div class="modal-dialog" role="document" style="width: 880px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">洗码设置</h4>
        </div>
        {!! Form::model($carrierAgentLevel, ['route' => ['carrierAgentLevels.saveRebateFinancialFlowAgent', $carrierAgentLevel->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="form-group col-sm-4">
                    {!! Form::label('available_member_monthly_bet_amount', '有效会员当月投注额设置:') !!}
                    <input type="hidden" name="rebateFinancialFlowAgentBaseInfo_id" value="{!! $carrierRebateFinancialFlowAgentBaseInfo->id !!}">
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员当月有效投注额大于或等于此条件，在代理结算时才记为有效会员，建议为500" ></i>
                    {!! Form::text('available_member_monthly_bet_amount', $carrierRebateFinancialFlowAgentBaseInfo->available_member_monthly_bet_amount, ['class' => 'form-control','required']) !!}
                </div>
                
                <div class="form-group col-sm-4">
                    {!! Form::label('available_member_count', '有效会员数:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员当月有效会员数大于或等于此条件，在代理结算时才可结算佣金，建议为5" ></i>
                    {!! Form::text('available_member_count', $carrierRebateFinancialFlowAgentBaseInfo->available_member_count, ['class' => 'form-control','required']) !!}
                </div>
                <div class="form-group col-sm-4">
                    {!! Form::label('is_player_rebate_financial_adapt_carrier_conf', '会员是否跟随网站洗码优惠:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果是就按会员等级设置中洗码设置走，如果否洗码代理可在代理后台设置低于本身的洗码比例给会员" ></i>
                    <?php $playerRebateFinancialAdaptCarrierConfDic = \App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo::playerRebateFinancialAdaptCarrierConfMeta() ?>
                    <select name="is_player_rebate_financial_adapt_carrier_conf" class="form-control disable_search_select2" style="width: 100%;">
                        @foreach($playerRebateFinancialAdaptCarrierConfDic as $key => $value)
                            @if(isset($carrierRebateFinancialFlowAgentBaseInfo) && $carrierRebateFinancialFlowAgentBaseInfo instanceof \App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo && $carrierRebateFinancialFlowAgentBaseInfo->is_player_rebate_financial_adapt_carrier_conf == $key)
                                <option value="{!! $key !!}" selected>{!! $value !!}</option>
                            @else
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
       
                <div class="box-body">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover dataTable text-center">
                            <thead>
                                <tr role="row">
                                    <th>游戏平台</th>
                                    <th>
                                        代理洗码比例%
                                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="洗码比例通常设置为0.1-1.2之间，根据会员是否跟随网站洗码优惠来定义比例多少" ></i>
                                    </th>
                                    <th>
                                        代理洗码上限
                                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="洗码佣金上限，填0为不设上限" ></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agentpa as $key=>$rebateFinancialFlow)
                                    <tr role="row">
                                        <td>
                                            {!! $rebateFinancialFlow->carrierGamePlat->gamePlat->game_plat_name !!}
                                            <input type="hidden" name="setid[{!! $key !!}]" value="{!! $rebateFinancialFlow->id !!}">
                                        </td>
                                        <td>
                                            <input type="number" name="agent_rebate_financial_flow_rate[{!! $key !!}]" value="{!! $rebateFinancialFlow->agent_rebate_financial_flow_rate !!}" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="agent_rebate_financial_flow_max_amount[{!! $key !!}]" value="{!! $rebateFinancialFlow->agent_rebate_financial_flow_max_amount !!}" class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
       <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentLevels.saveRebateFinancialFlowAgent',$carrierAgentLevel->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>
    
<script>
    $(function(){
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
    })
</script>    