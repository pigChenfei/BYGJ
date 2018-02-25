    <!--正负盈利百分比-->
    <div class="form-group col-sm-6">
        {!! Form::label('rebate_financial_bonuses_step_rate_json','昨日正负盈利百分比').'<span style="color:red">(可设置多个条件)</span>' !!}
    </div>
    <div id="positiveFlowSetting">
        <input type="hidden" name="positive_json" v-model="resultJson">
        <div class="form-group col-sm-12">
                <a v-on:click="addSettingRow" v-bind:class="numberOfSettingRows == 0 ? 'col-sm-12 btn btn-success form-control' : 'col-sm-3 btn btn-success form-control'">@{{ numberOfSettingRows == 0 ? '点击设置' : '新增条件' }}</a>
        </div>
        <div class="form-group col-sm-12" v-if="numberOfSettingRows > 0">
            <div class="col-sm-12">
                <label for="main_game_plat_json">请选择正负盈利产生的游戏平台</label>
                 <div class="form-group col-sm-12 col-lg-12">
                    <input type="hidden" name="amphoteric_game_plat_json" v-bind:value="gameDataInfo">
                    <div v-for="(value,index) in selectedDataInfo" class="row" style="margin-bottom: 10px">
                        <div class="col-sm-12 col-lg-12">
                            <div class="input-group col-lg-12">
                                <span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择平台</span>
                                <select onchange="$.fn.rebateFinanceFlowSetting.gamesChanged(this.id,this)" v-bind:id="index" multiple="multiple" data-placeholder="请选择或输入平台名称搜索..."
                                        class="form-control games_page_select2"
                                        style="width: 100%">
                                    @foreach($carrierGamePlatList as $key => $value)
                                        <option v-bind:selected="value.selectedGames.indexOf('{!! $value->game_plat_id !!}') != -1" value="{!! $value->game_plat_id !!}">{!! $value['gamePlat']->game_plat_name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered" style="background-color: #3c8dbc;background-color: #3c8dbc;border-color: #367fa9;padding: 1px 10px;color: #fff;">
                <tbody>
                <tr v-for="(item, index) in displayedSettingRows">
                    <th style="vertical-align: middle">正负盈利</th>
                    <th>
                        <select v-model="item.amphoteric" class="form-control" style="width: 100%;">
                            <option value="-1">负盈利</option>
                            <option value="1">正盈利</option>
                        </select>
                    </th>
                    <th style="vertical-align: middle">正负盈利额>=</th>
                    <th><input type="text" v-model="item.fixedAmount" class="form-control"></th>
                    <th style="vertical-align: middle">红利比例%</th>
                    <th><input type="number" v-model="item.bonusesAatio" class="form-control"></th>
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
        $.fn.rebateFinanceFlowSetting = new Vue({
            el:'#positiveFlowSetting',
            data:{
                displayedSettingRows:[],
                 needInitialSelect2:false,
                selectedDataInfo: []
            },
             updated:function(){
                this.initialLastSelect2();
                console.log(2)
            },
            mounted:function () {
                this.needInitialSelect2 = true;
                console.log(1)
                this.initialLastSelect2();
            },
            created:function(){
                @if(isset($carrierActivity) && $carrierActivity->bonuses_type ==3 && $rebate_financial_flow_step_rate_json = $carrierActivity->rebate_financial_bonuses_step_rate_json )
                     this.displayedSettingRows = $.parseJSON('{!! $rebate_financial_flow_step_rate_json !!}');
                @endif
                
                this.insertNewSelectData();
                @if(isset($amphotericGamePlatGroup) && $carrierActivity->bonuses_type ==3)
                    this.selectedDataInfo = $.parseJSON('{!! $amphotericGamePlatGroup !!}');
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
                        amphoteric:-1,
                        fixedAmount:null,
                        bonusesAatio:null,
                        flowMultiple:null
                    });
                },
                insertNewSelectData: function (event) {
                    var obj = {
                        selectedGames:[],
                    };
                    this.selectedDataInfo.push(obj);
                    if (!event) {
                        return
                    }
                    this.needInitialSelect2 = true;
                },
                removeData:function (index) {
                    this.needInitialSelect2 = true;
                    this.selectedDataInfo.splice(index,1);
                },
                initialLastSelect2:function () {
                    var games_page_select2 = $('.games_page_select2');
                    //if(this.needInitialSelect2 == false && games_page_select2.length > 0){ return }
                    
                    if(games_page_select2.length > 0){
                         $('.games_page_select2').select2({
                            closeOnSelect: false
                        });
                     //this.needInitialSelect2 = false;
                    }
                   
                },
                
                gamesChanged:function (index,event) {
                    var selectedPageIds = [];
                    $(event).find('option:selected').each(function(index,element){
                        selectedPageIds.push(element.value);
                    });
                    this.selectedDataInfo[index].selectedGames = selectedPageIds;
                },
            },
            computed:{
                numberOfSettingRows:function(){
                    return this.displayedSettingRows.length;
                },
                resultJson: function () {
                    return this.displayedSettingRows.length > 0 ? JSON.stringify(this.displayedSettingRows) : null;
                },
                canNewRow:function () {
                    if(this.selectedDataInfo.length > 0){
                        var latestObj = this.selectedDataInfo[this.selectedDataInfo.length - 1];
                        if (latestObj.selectedGames.length == 0){
                            return false;
                        }
                    }
                    return true;
                },
                gameDataInfo:function () {
                    return JSON.stringify(this.selectedDataInfo.filter(function(element){
                        return element.selectedGames.length > 0;
                    }));
                }
            }
        })


    })
</script>