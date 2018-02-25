<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">通过取款申请</h4>
        </div>
        {!! Form::model($agentWithdrawLog, ['route' => ['carrierAgentWithdrawLogs.update', $agentWithdrawLog->id], 'method' => 'patch' , 'id' => 'playerWithDrawReviewForm']) !!}
        <div class="modal-body" id="modalContent">
            <div class="form-group col-sm-12">
                {!! Form::label('', '取款信息') !!}
                <dl class="dl-horizontal">
                    <dt>持卡人</dt>
                    <dd>{!! $agentWithdrawLog->bankCard->card_owner_name !!}</dd>
                    <dt>开户行</dt>
                    <dd>{!! $agentWithdrawLog->bankCard->bankType->bank_name !!}</dd>
                    <dt>卡号</dt>
                    <dd>{!! $agentWithdrawLog->bankCard->card_account !!}</dd>
                    <dt>分行</dt>
                    <dd>{!! $agentWithdrawLog->bankCard->card_birth_place !!}</dd>
                </dl>
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('', '出款申请金额') !!}
                <input type="text" readonly class="form-control" value="{!! $agentWithdrawLog->apply_amount !!}">
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('', '选择出款银行') !!}
                <select name="carrier_pay_channel" style="width: 100%" class="form-control disable_search_select2">
                    @foreach(\App\Models\CarrierPayChannel::available()->withdrawPurpose()->with('payChannel.payChannelType')->get()->filter(function($element){
                        return $element->payChannel->payChannelType->isCompanyBankTransfer();
                    }) as $payChannel)
                        <option value="{!! $payChannel->id !!}">{!! \App\Models\CarrierPayChannel::usedForPurposeMeta()[$payChannel->use_purpose].' '.$payChannel->payChannel->channel_name.' '.$payChannel->balance !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('', '手续费承担方') !!}
                <select name="fee_bear_side"  v-model="selectedFeeBear" style="width: 100%" class="form-control">
                    <option v-for="(item,index) in feeBearType" v-bind:value="item.value">@{{ item.text }}</option>
                </select>
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('fee_amount', '出款手续费') !!}
                <input id="fee_amount_input" type="number" name="fee_amount" min="0" max="{!! $agentWithdrawLog->apply_amount !!}" v-model="fee" class="form-control">
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('fee_amount', '最终出款确认') !!}
                <input type="number" v-bind:value="finallyAmount" id="finally_amount" readonly class="form-control">
            </div>
            <div class="form-group col-sm-12">
                {!! Form::label('fee_amount', '备注') !!}
                <input type="text" name="remark" class="form-control">
            </div>
            <input type="hidden" name="status" value="{!! \App\Models\Log\CarrierAgentWithdrawLog::STATUS_PAYED_OUT !!}">
            <div class="clearfix">
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierAgentWithdrawLogs.update',$agentWithdrawLog->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    $(function(){

        new Vue({
            created:function () {
                this.selectedFeeBear = this.feeBearType[0].value;
            },
            el:'#playerWithDrawReviewForm',
            data:{
                applyWithDrawAmount : '{!! $agentWithdrawLog->apply_amount !!}',
                fee: 0,
                feeBearType:[
                    {text:'公司',value:'company'},{text:'代理',value:'agent'},{text:'会员',value:'player'}
                ],
                selectedFeeBear : null
            },
            mounted:function () {
                $('.disable_search_select2').select2({
                    minimumResultsForSearch: Infinity
                });
            },
            computed:{
                finallyAmount:function () {
                    if(parseFloat(this.fee) > parseFloat(this.applyWithDrawAmount)){
                        this.fee = this.applyWithDrawAmount;
                    }
                    if(this.selectedFeeBear == 'player'){
                        return parseFloat(this.applyWithDrawAmount - this.fee).toFixed(2);
                    }
                    return parseFloat(this.applyWithDrawAmount).toFixed(2)
                }
            }
        })

    })
</script>