<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">调整会员礼金</h4>
        </div>
        {!! Form::model($carrierAgentUser, ['route' => ['carrierAgentUsers.saveExperienceAmount',$carrierAgentUser->id],'method' => 'patch']) !!}
        <div class="modal-body" id="experienceAmountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">调整会员礼金</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa" v-bind:class="adjustIsPlus ? 'fa-plus' : 'fa-minus'" v-on:click="adjustIsPlus = !adjustIsPlus"></i>
                            </div>
                            <input type="hidden" name="adjust_is_plus" v-bind:value="adjustIsPlus ? 1 : 0 ">
                            <input v-model="adjustAmount" name="amount" type="number" class="form-control pull-right">
                        </div>
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
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierAgentUsers.saveExperienceAmount',$carrierAgentUser->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function () {

        new Vue({
            el:'#experienceAmountModalContent',
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
    })
</script>