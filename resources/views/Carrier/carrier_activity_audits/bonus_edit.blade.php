<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">通过活动申请</h4>
        </div>
        {!! Form::model($carrierActivityAudit, ['route' => ['carrierActivityAudits.update',$carrierActivityAudit->id],'class' => 'form-horizontal','id' => 'playerAccountAdjustCreateForm','method' => 'PATCH']) !!}
        <input type="hidden" name="player_id" value="{!! $player_id !!}">
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="">当前会员参与该活动如果通过,那么将获得{!! $bonus !!}元红利, 同时将产生 {!! $withdrawFlowLimit !!}元流水限制,如果不需要在此基础上调整红利,直接点击"保存"按钮即可</label>
                </div>
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">调整金额</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa" v-bind:class="adjustIsPlus ? 'fa-plus' : 'fa-minus'" v-on:click="adjustIsPlus = !adjustIsPlus"></i>
                            </div>
                            <input type="hidden" name="adjust_is_plus" v-bind:value="adjustIsPlus ? 1 : 0 ">
                            <input type="hidden" name="passed" value="1">
                            <input v-model="adjustAmount" name="amount" type="number" class="form-control pull-right">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">调整取款流水倍数</label>
                    <div class="col-sm-9">
                        <input v-model="adjustFlowRate" placeholder="请输入流水倍数" type="number" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">调整流水限制</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa" v-bind:class="adjustFlowFixPlus ? 'fa-plus' : 'fa-minus'" v-on:click="adjustFlowFixPlus = !adjustFlowFixPlus"></i>
                            </div>
                            <input v-model="adjustFlowFixAmount" type="number" placeholder="请输入调整值" class="form-control">
                            <div class="input-group-addon">
                                流水限制: <span style="color: #00a9ed">@{{ finallyFlowResult }}</span>
                            </div>
                            <input type="hidden" name="withdraw_limit_amount" v-bind:value="finallyFlowResult">
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
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierActivityAudits.update',$carrierActivityAudit->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function () {

        new Vue({
            el:'#playerAccountModalContent',
            data:{
                adjustIsPlus:true,
                adjustAmount: 0,
                adjustFlowRate: 0,
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

        $('.pay_channel_select2').select2({
            minimumResultsForSearch: Infinity
        });
        $('.bet_flow_plat_select2').select2({
            minimumResultsForSearch: Infinity,
            closeOnSelect: false
        });
        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    })
</script>