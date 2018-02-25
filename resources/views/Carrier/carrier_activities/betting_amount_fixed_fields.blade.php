    <!--投注额固定红利-->
    <div class="form-group col-sm-6">
        {!! Form::label('rebate_financial_bonuses_step_rate_json','昨日投注额固定红利').'<span style="color:red">(可设置多个条件)</span>' !!}
    </div>
    <div id="bettingFlowSetting">
        <input type="hidden" name="betting_json" v-model="resultJson">
        <div class="form-group col-sm-12">
                <a v-on:click="addSettingRow" v-bind:class="numberOfSettingRows == 0 ? 'col-sm-12 btn btn-success form-control' : 'col-sm-3 btn btn-success form-control'">@{{ numberOfSettingRows == 0 ? '点击设置' : '新增条件' }}</a>
        </div>
        <div class="form-group col-sm-12">
            <table class="table table-bordered" style="background-color: #3c8dbc;background-color: #3c8dbc;border-color: #367fa9;padding: 1px 10px;color: #fff;" v-if="numberOfSettingRows > 0" >
                <tbody>
                <tr v-for="(item, index) in displayedSettingRows">
                    <th style="vertical-align: middle">投注额 >=</th>
                    <th><input type="text" v-model="item.fixedAmount" class="form-control"></th>
                    <th style="vertical-align: middle">固定红利金额</th>
                    <th><input type="text" v-model="item.bonusesAatio" class="form-control"></th>
                    <th style="vertical-align: middle">流水倍数</th>
                    <th><input type="text" v-model="item.flowMultiple" class="form-control"></th>
                    <th style="vertical-align: middle">@{{ index != displayedSettingRows.length - 1 ? '否则按照下一步执行' : '' }}</th>
                    <th><a class="btn btn-warning btn-sm" v-on:click="removeSettingRow(index)">删除</a></th>
                </tr>
                </tbody>
            </table>
        </div> 
    </div>

<script>
    $(function(){
        var bettingFlowSetting = new Vue({
            el:'#bettingFlowSetting',
            data:{
                displayedSettingRows:[]
            },
            created:function(){
                @if(isset($carrierActivity) && $carrierActivity->bonuses_type ==4 && $rebate_financial_flow_step_rate_json = $carrierActivity->rebate_financial_bonuses_step_rate_json )
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
                        if(lastForm.fixedAmount == null || lastForm.bonusesAatio == null || lastForm.flowMultiple == null){
                            return;
                        }
                    }
                    this.displayedSettingRows.push({
                        fixedAmount:null,
                        bonusesAatio:null,
                        flowMultiple:null
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