<div class="modal-dialog modal-lg" style=" width: 1130px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">佣金设置</h4>
        </div>
        {!! Form::model($carrierCommissionAgent, ['route' => ['carrierAgentLevels.saveCommissionAgent', $carrierCommissionAgent->id], 'method' => 'patch']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">

                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_fee_undertake_ratio', '存款手续费承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员存款时承担的手续费，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('deposit_fee_undertake_ratio', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_fee_undertake_max', '存款手续费承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员存款手续费代理承担的上限，填0为不设上限" ></i>
                    {!! Form::text('deposit_fee_undertake_max', null, ['class' => 'form-control']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_preferential_undertake_ratio', '存款优惠承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员存款时给予的存款优惠，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('deposit_preferential_undertake_ratio', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('deposit_preferential_undertake_max', '存款优惠承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员存款优惠代理承担的上限，填0为不设上限" ></i>
                    {!! Form::text('deposit_preferential_undertake_max', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('rebate_financial_flow_undertake_ratio', '洗码承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员洗码，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('rebate_financial_flow_undertake_ratio', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-3">
                    {!! Form::label('rebate_financial_flow_undertake_max', '洗码承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员洗码代理承担的上限，填0为不设上限" ></i>
                    {!! Form::text('rebate_financial_flow_undertake_max', null, ['class' => 'form-control','required']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('bonus_undertake_ratio', '红利承担比例(%):') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="代理下级会员获得的优惠活动红利，由代理商承担的比例，填0则代理不承担，建议100%" ></i>
                    {!! Form::text('bonus_undertake_ratio', null, ['class' => 'form-control','required']) !!}
                </div>
                
                <div class="form-group col-sm-3">
                    {!! Form::label('bonus_undertake_max', '红利承担上限:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="优惠活动红利代理商的承担上限填0为不设上限" ></i>
                    {!! Form::text('bonus_undertake_max', null, ['class' => 'form-control','required']) !!}
                </div>
                
                @if($carrierAgentLevel->is_multi_agent == 1)
                    <div class="form-group col-sm-3">
                    {!! Form::label('available_member_monthly_bet_amount', '有效会员当月投注额设置:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员当月有效投注额大于或等于此条件，在代理结算时才记为有效会员，建议为500。设置佣金阶梯比例此处也需同时设置。" ></i>
                    {!! Form::text('available_member_monthly_bet_amount', null, ['class' => 'form-control','required']) !!}
                    </div>

                    <div class="form-group col-sm-3">
                        {!! Form::label('available_member_count', '总佣金有效会员数:') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员当月有效会员数大于或等于此条件，在代理结算时才可结算佣金，建议为5，如未设置阶梯比例，此处需要设置。" ></i>
                        {!! Form::text('available_member_count', null, ['class' => 'form-control','required']) !!}
                    </div>

                    <div class="form-group col-sm-3">
                        {!! Form::label('commission_ratio', '总佣金比例(%):') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="按照总输赢值来计算代理佣金的比例，建议为25-35，如未设置阶梯比例，此处需要设置" ></i>
                        {!! Form::text('commission_ratio', null, ['class' => 'form-control','required']) !!}
                    </div>
                
                    {{--<div class="form-group col-sm-3">
                        {!! Form::label('sub_commission_ratio', '下级代理佣金提成比例%:') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="按照下级代理业绩产生的佣金额外计算的提成比例，此部分提成运营商承担，建议为10%。" ></i>
                        {!! Form::number('sub_commission_ratio', null, ['class' => 'form-control','required']) !!}
                    </div>--}}
                @elseif($carrierAgentLevel->is_multi_agent == 0)
                    <div class="form-group col-sm-4">
                    {!! Form::label('available_member_monthly_bet_amount', '有效会员当月投注额设置:') !!}
                    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员当月有效投注额大于或等于此条件，在代理结算时才记为有效会员，建议为500。设置佣金阶梯比例此处也需同时设置。" ></i>
                    {!! Form::text('available_member_monthly_bet_amount', null, ['class' => 'form-control','required']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('available_member_count', '总佣金有效会员数:') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="会员当月有效会员数大于或等于此条件，在代理结算时才可结算佣金，建议为5，如未设置阶梯比例，此处需要设置。" ></i>
                        {!! Form::text('available_member_count', null, ['class' => 'form-control','required']) !!}
                    </div>
                    
                    <div class="form-group col-sm-4">
                        {!! Form::label('commission_ratio', '总佣金比例(%):') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="按照总输赢值来计算代理佣金的比例，建议为25-35，如未设置阶梯比例，此处需要设置" ></i>
                        {!! Form::text('commission_ratio', null, ['class' => 'form-control','required']) !!}
                    </div>
                @endif
                <div class="form-group col-sm-6">
                    {!! Form::label('commission_step_ratio','佣金阶梯比例设置').'<span style="color:red">(如果已设置佣金阶梯比例,则总佣金比例不生效)</span>' !!}
                </div>
                
                <div id="stepFlowSetting">
                    <input type="hidden" name="commission_step_ratio" v-model="resultJson">
                    <div class="form-group col-sm-12">
                            <a v-on:click="addSettingRow" v-bind:class="numberOfSettingRows == 0 ? 'col-sm-12 btn btn-success form-control' : 'col-sm-3 btn btn-primary form-control'">@{{ numberOfSettingRows == 0 ? '点击设置阶梯总佣金比例' : '新增比例' }}</a>
                    </div>
                    <div class="form-group col-sm-12">
                        <table class="table table-bordered" v-if="numberOfSettingRows > 0">
                            <tbody>
                            <tr v-for="(item, index) in displayedSettingRows">
                                <th style="vertical-align: middle">总输赢值 >=</th>
                                <th><input type="text" v-model="item.flowAmount" class="form-control" placeholder="填写总输赢值"></th>
                                <th style="vertical-align: middle">佣金比例%</th>
                                <th><input type="number" v-model="item.flowRate" class="form-control" min="0" placeholder="填写佣金比例"></th>
                                <th style="vertical-align: middle">有效会员数 >=</th>
                                <th><input type="text" v-model="item.availableMember" class="form-control" placeholder="填写有效会员数"></th>
                                <th style="vertical-align: middle">@{{ index != displayedSettingRows.length - 1 ? '否则按照下一步执行' : '' }}</th>
                                <th><a class="btn btn-warning btn-sm" v-on:click="removeSettingRow(index)">删除</a></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                        <!-- Submit Field -->
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentLevels.saveCommissionAgent',$carrierCommissionAgent->id)) !!}
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

        var rebateFinanceFlowSetting = new Vue({
            el:'#stepFlowSetting',
            data:{
                displayedSettingRows:[]
            },
            created:function(){
                @if(isset($carrierCommissionAgent) && $commission_step_ratio = $carrierCommissionAgent->commission_step_ratio )
                this.displayedSettingRows = $.parseJSON('{!! $commission_step_ratio !!}');
                @endif
            },
            methods:{
                removeSettingRow:function(index,element){
                    this.displayedSettingRows.splice(index,1);
                },
                addSettingRow:function(){
                    if(this.displayedSettingRows.length > 0){
                        var lastForm = this.displayedSettingRows[this.displayedSettingRows.length - 1];
                        if(lastForm.flowAmount == null || lastForm.flowRate == null || lastForm.availableMember == null){
                            return;
                        }
                    }
                    this.displayedSettingRows.push({
                        flowAmount:null,
                        flowRate:null,
                        availableMember:null
                    });
                }
            },
            computed:{
                numberOfSettingRows:function(){
                    return this.displayedSettingRows.length;
                },
                resultJson: function () {
                    return this.displayedSettingRows.length > 0 ? JSON.stringify(this.displayedSettingRows) : null;
                }
            }
        })


    })
</script>