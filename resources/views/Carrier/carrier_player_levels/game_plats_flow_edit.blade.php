<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">洗码比例设置</h4>
        </div>
        {!! Form::model($carrierPlayerGamePlatRebateFinancialFlow, ['route' => ['CarrierPlayerLevels.rebateFlowUpdate', $carrierPlayerGamePlatRebateFinancialFlow->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">

            <div class="row">
                <div class="form-group col-sm-6">
                    {!! Form::label('rebate_type','洗码方式') !!}
                    <select name="rebate_type" class="form-control disable_search_select2" onChange="tochange(this.value)" style="width: 100%;">
                        @foreach(\App\Models\CarrierPlayerGamePlatRebateFinancialFlow::rebateTypeMeta() as $key => $value)
                            @if($carrierPlayerGamePlatRebateFinancialFlow->rebate_type == $key)
                                <option value="{!! $key !!}" selected>{!! $value !!}</option>
                            @else
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-6 @if($carrierPlayerGamePlatRebateFinancialFlow->rebate_type == 2) hide @endif" id="rebate_manual_period_hours">
                    {!! Form::label('rebate_manual_period_hours','洗码周期') !!}
                    <select name="{{$carrierPlayerGamePlatRebateFinancialFlow->rebate_type == 1 ?'rebate_manual_period_hours':''}}" class="form-control disable_search_select2 rebate_manual_period_hours" style="width: 100%;">
                        @foreach(\App\Models\CarrierPlayerGamePlatRebateFinancialFlow::rebatePeriodMeta() as $key => $value)
                            @if($carrierPlayerGamePlatRebateFinancialFlow->rebate_manual_period_hours == $key)
                                <option value="{!! $key !!}" selected>{!! $value !!}</option>
                            @else
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group @if($carrierPlayerGamePlatRebateFinancialFlow->rebate_type != 2 ) hide @endif col-sm-6" id="rebate_manual_period_hours_2">
                    {!! Form::label('rebate_manual_period_hours','领取周期') !!}
                    <select name="{{$carrierPlayerGamePlatRebateFinancialFlow->rebate_type == 2 ?'rebate_manual_period_hours':''}}" class="form-control disable_search_select2 rebate_manual_period_hours" style="width: 100%;">
                        @foreach(\App\Models\CarrierPlayerGamePlatRebateFinancialFlow::rebatePeriodMetaAuto() as $key => $value)
                            @if($carrierPlayerGamePlatRebateFinancialFlow->rebate_manual_period_hours == $key)
                                <option value="{!! $key !!}" selected>{!! $value !!}</option>
                            @else
                                <option value="{!! $key !!}">{!! $value !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('limit_amount_per_flow','单次限额(0为不限额)') !!}
                    {!! Form::number('limit_amount_per_flow',null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('rebate_financial_flow_rate','总洗码比例(%)') !!}
                    {!! Form::number('rebate_financial_flow_rate',null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('rebate_financial_flow_step_rate_json','洗码阶梯比例').'<span style="color:red">(如果已设置该比例,则总洗码比例不生效)</span>' !!}
                </div>

                <div id="stepFlowSetting">
                    <input type="hidden" name="rebate_financial_flow_step_rate_json" v-model="resultJson">
                    <div class="form-group col-sm-12">
                            <a v-on:click="addSettingRow" v-bind:class="numberOfSettingRows == 0 ? 'col-sm-12 btn btn-success form-control' : 'col-sm-3 btn btn-primary form-control'">@{{ numberOfSettingRows == 0 ? '点击设置阶梯洗码比例' : '新增比例' }}</a>
                    </div>
                    <div class="form-group col-sm-12">
                        <table class="table table-bordered" v-if="numberOfSettingRows > 0">
                            <tbody>
                            <tr v-for="(item, index) in displayedSettingRows">
                                <th style="vertical-align: middle">如果有效流水 >=</th>
                                <th><input type="text" v-model="item.flowAmount" class="form-control" placeholder="填写流水额"></th>
                                <th style="vertical-align: middle">则洗码比例为</th>
                                <th><input type="number" v-model="item.flowRate" class="form-control" min="0" placeholder="填写洗码比例"></th>
                                <th style="vertical-align: middle;border-right: none">%</th>
                                <th style="vertical-align: middle">@{{ index != displayedSettingRows.length - 1 ? '否则按照下一步执行' : '' }}</th>
                                <th><a class="btn btn-warning btn-sm" v-on:click="removeSettingRow(index)">删除</a></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('CarrierPlayerLevels.rebateFlowUpdate',$carrierPlayerGamePlatRebateFinancialFlow->id),'window.location.reload()') !!}
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
                @if(isset($carrierPlayerGamePlatRebateFinancialFlow) && $rebate_financial_flow_step_rate_json = $carrierPlayerGamePlatRebateFinancialFlow->rebate_financial_flow_step_rate_json )
                this.displayedSettingRows = $.parseJSON('{!! $rebate_financial_flow_step_rate_json !!}');
                @endif
            },
            methods:{
                removeSettingRow:function(index,element){
                    this.displayedSettingRows.splice(index,1);
                },
                addSettingRow:function(){
                    if(this.displayedSettingRows.length > 0){
                        var lastForm = this.displayedSettingRows[this.displayedSettingRows.length - 1];
                        if(lastForm.flowAmount == null || lastForm.flowRate == null){
                            return;
                        }
                    }
                    this.displayedSettingRows.push({
                        flowAmount:null,
                        flowRate:null
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


    });
    function tochange($val) {
		if($val == 2) {
			$('#rebate_manual_period_hours').addClass('hide');
			$('#rebate_manual_period_hours_2').removeClass('hide');
		} else {
			$('#rebate_manual_period_hours').removeClass('hide').find('select');
			$('#rebate_manual_period_hours_2').addClass('hide').find('select');
		}
		$('.rebate_manual_period_hours').each(function(index, value){
		    var _this = $(value);
		    if (_this.attr('name') == 'rebate_manual_period_hours'){
                _this.attr('name', '')
            }else{
                _this.attr('name', 'rebate_manual_period_hours')
            }
        })
    }
</script>