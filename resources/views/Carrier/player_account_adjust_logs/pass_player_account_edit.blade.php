<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">调整会员余额</h4>
        </div>
        {!! Form::open(['route' => 'playerAccountAdjustLogs.savePassPlayerAccount']) !!}
        <div class="modal-body" id="modalContent">

            <div class="row">
                <div class="form-group col-sm-12 col-lg-12" id="agentComponent">
                    <input type="hidden" name="player_user_id_json" v-bind:value="playerData">
                    <div v-for="(value,index) in selectedData" class="row" style="margin-bottom: 10px">
                        <div class="col-sm-12 col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择会员</span>
                                <select onchange="$.fn.agentComponent.bannerChanged(this.id,this)" v-bind:id="index" multiple="multiple" data-placeholder="请输入会员名称搜索..."
                                        class="form-control banner_page_select2"
                                        style="width: 100%">
                                    @foreach($players as $key => $value)
                                        <option v-bind:selected="value.selectedPlayerPages.indexOf('{!! $value->id !!}') != -1" value="{!! $value->id !!}">{!! $value->user_name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-12" id="playerAccountModalContent">
                    <div class="form-group col-sm-12">
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
                    <div class="form-group col-sm-12">
                        <label for="pay_channel" class="col-sm-3 control-label">调整取款流水倍数</label>
                        <div class="col-sm-9">
                            <input v-model="adjustFlowRate" placeholder="请输入流水倍数" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
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
                    <div class="form-group col-sm-12">
                        <label for="pay_channel" class="col-sm-3 control-label">选择记账支付渠道</label>
                        <div class="col-sm-9">
                            <select style="width: 100%" name="amount_record_pay_channel" class="pay_channel_select2 form-control" id="">
                                <option value="">不记账</option>
                                @foreach($carrierPayChannel as $carrierPayChannel)
                                    @if($carrierPayChannel->payChannel->payChannelType->isCompanyPay())
                                        <option value="{!! $carrierPayChannel->id !!}">{!! '['.App\Models\CarrierPayChannel::usedForPurposeMeta()[$carrierPayChannel->use_purpose].'] '.$carrierPayChannel->display_name.'--'.$carrierPayChannel->balance.'元' !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="pay_channel" class="col-sm-3 control-label">清空之前流水限制 <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果选中,则该用户之前的取款流水限制将被清空." ></i> </label>
                        <div class="col-sm-3" style="padding-top: 7px">
                            <input type="checkbox" name="is_reset_bet_flow_limit" class="square-blue" />
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="bet_flow_game_plats[]" class="col-sm-3 control-label">限制流水平台  </label>
                        <div class="col-sm-9">
                            <select multiple style="width: 100%" name="bet_flow_game_plats[]" class="bet_flow_plat_select2 form-control" id="">
                                @foreach($carrierGamePlat as $gamePlat)
                                    <option value="{!! $gamePlat->gamePlat->game_plat_id !!}">{!! $gamePlat->gamePlat->game_plat_name !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="pay_channel" class="col-sm-3 control-label">备注</label>
                        <div class="col-sm-9">
                            <textarea name="remark" class="form-control" style="resize:none" id="" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>


            </div>



        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                {!! TableScript::addFormSubmitAndCancelButtonsScript(Route('playerAccountAdjustLogs.savePassPlayerAccount')) !!}
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


    //代理用户
    $.fn.agentComponent = new Vue({
        el: '#agentComponent',
        created:function(){
            this.insertNewSelectData();
        },
        data: {
            needInitialSelect2:false,
            selectedData: []
        },
        methods: {
            insertNewSelectData: function (event) {
                var obj = {
                    selectedPlayerPages:[],
                };
                this.selectedData.push(obj);
                if (!event) {
                    return
                }
                this.needInitialSelect2 = true;
            },
            removeData:function (index) {
                this.needInitialSelect2 = true;
                this.selectedData.splice(index,1);
            },
            initialLastSelect2:function () {
                if(this.needInitialSelect2 == false){ return }
                $('.banner_page_select2').select2({
                    closeOnSelect: false
                });
                this.needInitialSelect2 = false;
            },

            bannerChanged:function (index,event) {
                var selectedPageIds = [];
                $(event).find('option:selected').each(function(index,element){
                    selectedPageIds.push(element.value);
                });
                this.selectedData[index].selectedPlayerPages = selectedPageIds;
            },
        },
        updated:function(){
            this.initialLastSelect2();
        },
        mounted:function () {
            this.needInitialSelect2 = true;
            this.initialLastSelect2();
        },
        computed: {
            canNewRow:function () {
                if(this.selectedData.length > 0){
                    var latestObj = this.selectedData[this.selectedData.length - 1];
                    if (latestObj.selectedPlayerPages.length == 0){
                        return false;
                    }
                }
                return true;
            },
            playerData:function () {
                return JSON.stringify(this.selectedData.filter(function(element){
                    return element.selectedPlayerPages.length > 0;
                }));
            }
        }
    });
</script>
