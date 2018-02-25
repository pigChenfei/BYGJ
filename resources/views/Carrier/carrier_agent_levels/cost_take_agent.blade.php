<div class="modal-dialog" role="document" style="width: 880px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">占成设置</h4>
        </div>
        {!! Form::model($carrierAgentLevel, ['route' => ['carrierAgentLevels.saveCostTakeAgent', $carrierAgentLevel->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_fee_undertake_ratio', '存款手续费承担比例(%):') !!}
                    <input type="hidden" name="carrierCostTakeAgent_id" value="{!! $carrierCostTakeAgent->id !!}">
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员存款时承担的手续费，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('deposit_fee_undertake_ratio', $carrierCostTakeAgent->deposit_fee_undertake_ratio, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_fee_undertake_max', '存款手续费承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员存款手续费代理承担的上限，填0为不设上限" ></i>
                    {!! Form::text('deposit_fee_undertake_max', $carrierCostTakeAgent->deposit_fee_undertake_max, ['class' => 'form-control']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_preferential_undertake_ratio', '存款优惠承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员存款时给予的存款优惠，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('deposit_preferential_undertake_ratio', $carrierCostTakeAgent->deposit_preferential_undertake_ratio, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_preferential_undertake_max', '存款优惠承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员存款优惠代理承担的上限，填0为不设上限" ></i>
                    {!! Form::text('deposit_preferential_undertake_max', $carrierCostTakeAgent->deposit_preferential_undertake_max, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('rebate_financial_flow_undertake_ratio', '洗码承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员洗码，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('rebate_financial_flow_undertake_ratio', $carrierCostTakeAgent->rebate_financial_flow_undertake_ratio, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('rebate_financial_flow_undertake_max', '洗码承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员洗码代理承担的上限，填0为不设上限" ></i>
                    {!! Form::text('rebate_financial_flow_undertake_max', $carrierCostTakeAgent->rebate_financial_flow_undertake_max, ['class' => 'form-control','required']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('bonus_undertake_ratio', '红利承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员获得的优惠活动红利，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('bonus_undertake_ratio', $carrierCostTakeAgent->bonus_undertake_ratio, ['class' => 'form-control','required']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('bonus_undertake_max', '红利承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="优惠活动红利代理商的承担上限填0为不设上限" ></i>
                    {!! Form::text('bonus_undertake_max', $carrierCostTakeAgent->bonus_undertake_max, ['class' => 'form-control','required']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('can_player_join_activity', '会员是否跟随网站优惠活动:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果是会员可申请网站优惠活动，红利承担比例设置将生效。如果否会员不可参与任何活动，红利承担比例设置不生效" ></i>
                    <?php $canPlayerJoinActivityDic = \App\Models\Conf\CarrierCostTakeAgent::canPlayerJoinActivityMeta() ?>
                    <select name="can_player_join_activity" class="form-control disable_search_select2" style="width: 100%;">
                        @foreach($canPlayerJoinActivityDic as $key => $value)
                            @if(isset($carrierCostTakeAgent) && $carrierCostTakeAgent instanceof \App\Models\Conf\CarrierCostTakeAgent && $carrierCostTakeAgent->can_player_join_activity == $key)
                                <option value="{!! $key !!}" selected>{!! $value !!}</option>
                            @else
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('is_player_rebate_financial_adapt_carrier_conf', '会员是否跟随网站洗码优惠:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果是会员享受网站的洗码优惠，洗码承担比例设置将生效。如果否会员不享受洗码优惠，洗码承担比例设置不生效" ></i>
                    <?php $playerRebateFinancialAdaptCarrierConfDic = \App\Models\Conf\CarrierCostTakeAgent::playerRebateFinancialAdaptCarrierConfMeta() ?>
                    <select name="is_player_rebate_financial_adapt_carrier_conf" class="form-control disable_search_select2" style="width: 100%;">
                        @foreach($playerRebateFinancialAdaptCarrierConfDic as $key => $value)
                            @if(isset($carrierCostTakeAgent) && $carrierCostTakeAgent instanceof \App\Models\Conf\CarrierCostTakeAgent && $carrierCostTakeAgent->is_player_rebate_financial_adapt_carrier_conf == $key)
                                <option value="{!! $key !!}" selected>{!! $value !!}</option>
                            @else
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('cost_take_ration', '占成比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="按照代理要求的占成比例设置" ></i>
                    {!! Form::text('cost_take_ration', $carrierCostTakeAgent->cost_take_ration, ['class' => 'form-control','required']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('protection_fund', '占成代理保障金:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="占成代理应存入的保障金，存入后占成代理的推荐链接将生效，否则注册的会员属系统会员" ></i>
                    {!! Form::text('protection_fund', $carrierCostTakeAgent->protection_fund, ['class' => 'form-control','required']) !!}
                </div>
    
       
                <div class="box-body">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover dataTable text-center">
                            <thead>
                                <tr role="row">
                                    <th>游戏平台</th>
                                    <th>
                                        平台费比例%
                                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="比例通常设置为10%-17%之间" ></i>
                                    </th>
                                    <th>
                                        平台费上限
                                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="平台费上限，填0为不设上限" ></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carrierCostTakeAgentPlatformFee as $key=>$rebateFinancialFlow)
                                    <tr role="row">
                                        <td>
                                            {!! $rebateFinancialFlow->carrierGamePlat->gamePlat->game_plat_name !!}
                                            <input type="hidden" name="setid[{!! $key !!}]" value="{!! $rebateFinancialFlow->id !!}">
                                        </td>
                                        <td>
                                            <input type="number" name="platform_fee_rate[{!! $key !!}]" value="{!! $rebateFinancialFlow->platform_fee_rate !!}" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="platform_fee_max[{!! $key !!}]" value="{!! $rebateFinancialFlow->platform_fee_max !!}" class="form-control">
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
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentLevels.saveCostTakeAgent',$carrierAgentLevel->id)) !!}
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