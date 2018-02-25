<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">调整会员红利</h4>
        </div>
        {!! Form::model($player, ['route' => ['playerAccountAdjustLogs.store'],'class' => 'form-horizontal','id' => 'playerAccountAdjustCreateForm']) !!}
        <input type="hidden" name="player_id" value="{!! $player->player_id !!}">
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">调整金额</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa" v-bind:class="adjustIsPlus ? 'fa-plus' : 'fa-minus'" v-on:click="adjustIsPlus = !adjustIsPlus"></i>
                            </div>
                            <input type="hidden" name="adjust_is_plus" v-bind:value="adjustIsPlus ? 1 : 0 ">
                            <input type="hidden" name="adjust_type" value="{!! \App\Models\Log\PlayerAccountAdjustLog::ADJUST_TYPE_BONUS !!}">
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
                    <label for="pay_channel" class="col-sm-3 control-label">清空之前流水限制 <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果选中,则该用户之前的取款流水限制将被清空." ></i> </label>
                    <div class="col-sm-3" style="padding-top: 7px">
                        <input type="checkbox" name="is_reset_bet_flow_limit" class="square-blue" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="bet_flow_game_plats[]" class="col-sm-3 control-label">限制流水平台  </label>
                    <div class="col-sm-9">
                        <select multiple style="width: 100%" name="bet_flow_game_plats[]" class="bet_flow_plat_select2 form-control" id="">
                            @foreach($player->carrier->mapGamePlats as $carrierGamePlat)
                                <option value="{!! $carrierGamePlat->gamePlat->game_plat_id !!}">{!! $carrierGamePlat->gamePlat->game_plat_name !!}</option>
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
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('playerAccountAdjustLogs.store')) !!}
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