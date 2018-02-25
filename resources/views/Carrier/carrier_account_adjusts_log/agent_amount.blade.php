<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">调整会员余额</h4>
        </div>
        {!! Form::model($carrierAgentUser, ['route' => ['agentAccountAdjustLogs.store'],'class' => 'form-horizontal','id' => 'agentAccountAdjustCreateForm']) !!}
        <input type="hidden" name="agent_id" value="{!! $carrierAgentUser->id !!}">
        <div class="modal-body" id="amountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">调整金额</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa" v-bind:class="adjustIsPlus ? 'fa-plus' : 'fa-minus'" v-on:click="adjustIsPlus = !adjustIsPlus"></i>
                            </div>
                            <input type="hidden" name="adjust_is_plus" v-bind:value="adjustIsPlus ? 1 : 0 ">
                            <input type="hidden" name="adjust_type" value="{!! \App\Models\Log\PlayerAccountAdjustLog::ADJUST_TYPE_DEPOSIT !!}">
                            <input v-model="adjustAmount" name="amount" type="number" class="form-control pull-right">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="adjust_type" class="col-sm-3 control-label">调整类型</label>
                    <div class="col-sm-9">
                        <?php $adjustTypeDic = \App\Models\Log\AgentAccountAdjustLog::adjustTypeMeta() ?>
                        <select name="adjust_type" class="form-control disable_search_select2" style="width: 100%;">
                            @foreach($adjustTypeDic as $key => $value)
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">选择记账支付渠道</label>
                    <div class="col-sm-9">
                        <select style="width: 100%" name="amount_record_pay_channel" class="disable_search_select2 form-control" id="">
                            <option value="">不记账</option>
                            @foreach($carrierAgentUser->carrier->carrierPayChannels as $carrierPayChannel)
                                @if($carrierPayChannel->payChannel->payChannelType->isCompanyPay())
                                <option value="{!! $carrierPayChannel->id !!}">{!! '['.App\Models\CarrierPayChannel::usedForPurposeMeta()[$carrierPayChannel->use_purpose].'] '.$carrierPayChannel->display_name.'--'.$carrierPayChannel->balance.'元' !!}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">备注</label>
                    <div class="col-sm-9">
                        <textarea name="remark" class="form-control" style="resize:none" id="" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('agentAccountAdjustLogs.store')) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function () {

        new Vue({
            el:'#amountModalContent',
            data:{
                adjustIsPlus:true,
                adjustAmount: 0,
                adjustFlowRate: 1,
                adjustFlowFixPlus: true,
                adjustFlowFixAmount: 0,
            },
            methods:{
                adjustFlowFixTypeClick:function () {
                    this.adjustFlowFixPlus = !this.adjustFlowFixPlus;
                }
            },
            computed:{
                finallyFlowResult:function () {
                    this.adjustAmount = Number(this.adjustAmount);
                    if(this.adjustAmount < 0 || isNaN(this.adjustAmount)){
                        this.adjustAmount = 0;
                    }
                    this.adjustFlowRate = Number(this.adjustFlowRate);
                    if(this.adjustFlowRate < 0 || isNaN(this.adjustFlowRate)){
                        this.adjustFlowRate = 0;
                    }
                    this.adjustFlowFixAmount = Number(this.adjustFlowFixAmount);
                    if(isNaN(this.adjustFlowFixAmount)){
                        this.adjustFlowFixAmount = 0;
                    }
                    var amount = parseInt(this.adjustAmount * this.adjustFlowRate);
                    if(this.adjustFlowFixPlus){
                        return amount + parseInt(this.adjustFlowFixAmount);
                    }
                    return amount - parseInt(this.adjustFlowFixAmount);
                }
            }
        });

        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });

        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    })
</script>