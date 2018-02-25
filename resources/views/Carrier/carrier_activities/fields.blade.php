<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', '活动名称:').Form::required_pin() !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('act_type_id', '活动类型') !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%;">
        <option value="0">请选择</option>
        @foreach($carrierActivityType as $key => $value)
            @if(isset($carrierActivity) &&  $carrierActivity->act_type_id == $value->id)
                <option value="{!! $value->id !!}" selected>{!! $value->type_name !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->type_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12 col-lg-12" id="gameComponent">
    {!! Form::label('main_game_plat_json', '该活动流水限平台') !!}
    <input type="hidden" name="main_game_plat_json" v-bind:value="gameData">
    <div v-for="(value,index) in selectedData" class="row" style="margin-bottom: 10px">
        <div class="col-sm-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择平台</span>
                <select onchange="$.fn.gameComponent.gameChanged(this.id,this)" v-bind:id="index" multiple="multiple" data-placeholder="请选择或输入平台名称搜索..."
                        class="form-control game_page_select2"
                        style="width: 100%">
                    @foreach($carrierGamePlatList as $key => $value)
                        <option v-bind:selected="value.selectedGamePages.indexOf('{!! $value->id !!}') != -1" value="{!! $value->id !!}">{!! $value['gamePlat']->game_plat_name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Act Type Id Field -->
<div class="form-group col-sm-3" id="bonuses_select">
    {!! Form::label('bonuses_type', '红利类型:') !!}
    <?php $bonusestypeDic = \App\Models\CarrierActivity::bonusesTypeMeta() ?>
    <select name="bonuses_type" class="form-control" style="width: 100%;" onchange="selectOnchang(this)">
        <option value="">请选择</option>
        @foreach($bonusestypeDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->bonuses_type == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('flow_want_pattern', '流水要求模式:') !!}
    <?php $flowwantpatternDic = \App\Models\CarrierActivity::flowWantPatternMeta() ?>
    <select name="flow_want_pattern" class="form-control" style="width: 100%;">
        @foreach($flowwantpatternDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->flow_want_pattern == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
        {!! Form::label('betting_amount_enjoy_flow', '该活动投注额是否享受洗码:') !!}
        <?php $bettingamountenjoyflowDic = \App\Models\CarrierActivity::bettingAmountEnjoyFlowMeta() ?>
        <select name="betting_amount_enjoy_flow" class="form-control" style="width: 100%;">
            @foreach($bettingamountenjoyflowDic as $key => $value)
                @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->betting_amount_enjoy_flow == $key)
                    <option value="{!! $key !!}" selected>{!! $value !!}</option>
                @else
                    <option value="{!! $key !!}">{!! $value !!}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('mutex_parent_id', '互斥活动:') !!}
        <select name="mutex_parent_id" class="form-control" style="width: 100%;">
            <option value="">请选择...</option>
            @foreach($carrierActivitylist as $key => $value)
                @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->mutex_parent_id == $value->id)
                    <option value="{!! $value->id !!}" selected>{!! $value->name !!}</option>
                @else
                    <option value="{!! $value->id !!}">{!! $value->name !!}</option>
                @endif
            @endforeach
        </select>
    </div>

<!--------- 阶梯比例设置 ------------------>
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 1)
    <div class="percentage">
        @include('Carrier.carrier_activities.percentage_fields')<!--存送百分比-->
    </div>
@else
    <div class="percentage" style="display: none">
        @include('Carrier.carrier_activities.percentage_fields')<!--存送百分比-->
    </div>
@endif
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 2)
    <div class="fixedamount">
        @include('Carrier.carrier_activities.fixed_fields')<!--存送固定红利-->
    </div>
@else
    <div class="fixedamount" style="display: none">
        @include('Carrier.carrier_activities.fixed_fields')<!--存送固定红利-->
    </div>
@endif
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 3)
    <div class="positivedamount">
        @include('Carrier.carrier_activities.positive_negative_earnings_percentage_fields')<!--当日正负盈利-->
    </div>
@else
    <div class="positivedamount" style="display: none">
        @include('Carrier.carrier_activities.positive_negative_earnings_percentage_fields')<!--当日正负盈利-->
    </div>
@endif
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 4)
    <div class="bettingamount">
        @include('Carrier.carrier_activities.betting_amount_fixed_fields')<!--当日投注额固定红利-->
    </div>
@else
    <div class="bettingamount" style="display: none">
        @include('Carrier.carrier_activities.betting_amount_fixed_fields')<!--当日投注额固定红利-->
    </div>
@endif
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 5)
    <div class="memberamount">
        @include('Carrier.carrier_activities.member_level_percentage_fields')<!--会员等级存送百分比-->
    </div>
@else
    <div class="memberamount" style="display: none">
        @include('Carrier.carrier_activities.member_level_percentage_fields')<!--会员等级存送百分比-->
    </div>
@endif 
<!--------- 阶梯比例设置 ------------------>

<!-- Ip Number Field -->

<!-- Flow Multiple Field -->

<div class="form-group col-sm-3">
    {!! Form::label('deposit_status', '存款页面是否显示:') !!}
    <?php $depositstatusDic = \App\Models\CarrierActivity::depositStatusMeta() ?>
    <select name="deposit_status" class="form-control" style="width: 100%;">
        @foreach($depositstatusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->deposit_status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('website_status', '网站前台是否显示:') !!}
    <?php $websitestatusDic = \App\Models\CarrierActivity::websiteStatusMeta() ?>
    <select name="website_status" class="form-control" style="width: 100%;">
        @foreach($websitestatusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->website_status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('act_status', '优惠活动是否显示:') !!}
    <?php $actstatusDic = \App\Models\CarrierActivity::actStatusMeta() ?>
    <select name="act_status" class="form-control" style="width: 100%;">
        @foreach($actstatusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->act_status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('censor_way', '处理方式:') !!}
    <?php $censorwayDic = \App\Models\CarrierActivity::censorWayMeta() ?>
    <select name="censor_way" class="form-control" style="width: 100%;">
        @foreach($censorwayDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->censor_way == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('ip_number', '同一IP限制参与次数:') !!}
    {!! Form::text('ip_number', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('apply_number', '会员申请次数:') !!}
    <?php $applynumberDic = \App\Models\CarrierActivity::applyNumberMeta() ?>
    <select name="apply_number" class="form-control" style="width: 100%;">
        @foreach($applynumberDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->apply_number == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('sort', '活动排序:') !!}
    {!! Form::number('sort', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('status', '活动状态:') !!}
    <?php $statusDic = \App\Models\CarrierActivity::statusMeta() ?>
    <select name="status" class="form-control" style="width: 100%;">
        @foreach($statusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12" id="upgrade_rule_action">
    {!! Form::label('activity_apply_rule_string','申请规则(点击以下按钮或输入数字可以组合生成规则)') !!}
    <input type="hidden" name="activity_apply_rule_string" v-model="resultPremiumRuleString">
    <div class="col-sm-12">
        <div class="col-sm-2">
            <div class="btn-group">
                <button type="button" v-bind:disabled="!ruleTypeButtonsAvailable" class="btn btn-info upgrade_rule_btn">点击选择规则类型</button>
                <button type="button" v-bind:disabled="!ruleTypeButtonsAvailable" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="height: 34px;">
                    <span class="caret"></span>
                    <span class="sr-only"><font><font>切换下拉菜单</font></font></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li v-for="element,index in viewMeta.ruleType">
                        <a v-on:click="ruleTypeAction(index)" v-bind:style="{cursor: 'pointer' , color: ruleTypeButtonsAvailable ? '#333' : '#ccc'}" >@{{ element  }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-2" style="width: 185px">
            <div class="btn-group">

                <button v-bind:disabled="!leftRequireButtonAvailable" v-on:click="ruleRelationAction(viewMeta.ruleRelation.leftRequire)" type="button"  v-bind:title="viewMeta.ruleRelation.leftRequire.content"
                        class="btn btn-warning upgrade_rule_btn">
                    @{{ viewMeta.ruleRelation.leftRequire.content }}
                </button>
                <button v-bind:disabled="!rightRequireButtonAvailable" v-on:click="ruleRelationAction(viewMeta.ruleRelation.rightRequire)" type="button"  v-bind:title="viewMeta.ruleRelation.rightRequire.content"
                        class="btn btn-warning upgrade_rule_btn">
                    @{{ viewMeta.ruleRelation.rightRequire.content }}
                </button>
                <button v-bind:disabled="!andOrRelationButtonAvailable" v-on:click="ruleRelationAction(viewMeta.ruleRelation.andRelation)" type="button"  v-bind:title="viewMeta.ruleRelation.andRelation.content"
                        class="btn btn-warning upgrade_rule_btn">
                    @{{ viewMeta.ruleRelation.andRelation.content }}
                </button>
                <button v-bind:disabled="!andOrRelationButtonAvailable" v-on:click="ruleRelationAction(viewMeta.ruleRelation.orRelation)" type="button"  v-bind:title="viewMeta.ruleRelation.orRelation.content"
                        class="btn btn-warning upgrade_rule_btn">
                    @{{ viewMeta.ruleRelation.orRelation.content }}
                </button>
            </div>
        </div>
        <div class="col-sm-3" style="width: 288px">
            <div class="btn-group">
                <button v-for="element,index in viewMeta.operatorRelation" v-on:click="operatorInputAction(element)" v-bind:disabled="!operatorButtonAvailable" class="btn btn-primary upgrade_rule_btn" type="button" v-bind:title="element">
                    @{{ element }}
                </button>
                <button v-for="element,index in viewMeta.largeOrLessRelation" v-on:click="largeOrLessRelationAction(element)" v-bind:disabled="!largeOrLessOrEqualRelationAvailable" type="button" v-bind:title="element"
                        class="btn btn-primary upgrade_rule_btn">
                    @{{ element }}
                </button>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="input-group">
                <input type="hidden" name="upgrade_rule">
                <input type="number" class="form-control" v-bind:disabled="!numberInputAvailable" v-model="currentInputValue" placeholder="在此输入数值">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-info btn-flat" v-bind:disabled="!numberInputAvailable || !canSubmitNumberInput" v-on:click="numberInputAction">确定</button>
                </span>
            </div>
        </div>
        <div class="col-sm-3" style="width: 200px">
            <div class="btn-group">
                <button type="button" v-bind:disabled="totalInputStack.length == 0" class="btn btn-warning btn-flat"  v-on:click="back">上一步</button>
                <button type="button" class="btn btn-primary btn-flat"  v-on:click="reChoose">清空规则</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="pad no-print">
            <div class="callout callout-default" style="margin-bottom: 0!important;">
                <h5 style="color: #f39c12"></h5>
                <h4><i class="fa fa-coffee"></i> 规则预览:</h4>
                <h3>
                    @{{ resultPremiumString }}
                </h3>
            </div>
        </div>
    </div>

</div>

<div class="form-group col-sm-12 col-lg-12" id="agentComponent">
    {!! Form::label('agent_user_id_json', '所属代理') !!}
    <input type="hidden" name="agent_user_id_json" v-bind:value="agentData">
    <div v-for="(value,index) in selectedData" class="row" style="margin-bottom: 10px">
        <div class="col-sm-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择所属代理</span>
                <select onchange="$.fn.agentComponent.bannerChanged(this.id,this)" v-bind:id="index" multiple="multiple" data-placeholder="请输入代理名称搜索..."
                        class="form-control banner_page_select2"
                        style="width: 100%">
                    @foreach($carrierAgentUser as $key => $value)
                        <option v-bind:selected="value.selectedAgentPages.indexOf('{!! $value->id !!}') != -1" value="{!! $value->id !!}">{!! $value->username !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>


<div class="form-group col-sm-12" id="player_ids_json">
    {!! Form::label('player_json', '会员等级:') !!}
    <input type="hidden" name="player_level_Json" v-bind:value="playerJson">
    <div class="row">
        @foreach($carrierPlayerLevel as $key => $value)
            <div class="col-sm-1">
                <input type="checkbox" v-model="playerJson" value="{!! $value->id !!}">
                {!! $value->level_name !!}
            </div>
        @endforeach
    </div>
</div>
<script>
    $(function(){
        new Vue({
            el:'#player_ids_json',
            data:{
                playerJson:[],
            },
            created:function(){
                @if(isset($carrierActivity) && $player_level_Json = $carrierActivity->player_level_Json )
                    this.playerJson = $.parseJSON('{!! $player_level_Json !!}');
//                    this.playerJson = this.JSON.parse('{!! $player_level_Json !!}');
                @endif
            },
            computed:{
                resultJson: function () {
//                     return this.playerJson.length > 0 ? JSON.stringify(this.playerJson) : null;
                }
            }
        })


    })
</script>

<div class="form-group col-sm-12">
    {!! Form::label('images_id', '活动图片:') !!}
    <div class="row">
        <div class="col-sm-6 col-lg-12">
            @include('Components.ImagePicker.index',['name' => 'images_id'])
        </div>
    </div>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('act_content', '活动内容:') !!}
    <input type="hidden" name="update_type" value="act_content">
    <div class="form-group col-sm-12">
        @if(isset($carrierActivity))
            @include('Components.Editor.index',['id' => 'act_content','name' => 'act_content', 'defaultContent' => $carrierActivity->act_content()])
        @else
            @include('Components.Editor.index',['id' => 'act_content','name' => 'act_content', 'defaultContent' => ''])
        @endif
    </div>
</div>

@include('Components.ImagePicker.scripts')

<!--红利类型切换END-->
<script type="text/JavaScript">
    function selectOnchang(obj) {
        var value = obj.options[obj.selectedIndex].value;
        if (value == 1) {
            $(".percentage").show();//存送百分比
            $(".fixedamount").hide();//存送固定红利
            $(".positivedamount").hide();//昨日正负盈利
            $(".bettingamount").hide();//昨日投注额固定红利
            $(".memberamount").hide();//会员等级存送百分比
        } else if(value == 2) {
            $(".percentage").hide();//存送百分比
            $(".fixedamount").show();//存送固定红利
            $(".positivedamount").hide();//昨日正负盈利
            $(".bettingamount").hide();//昨日投注额固定红利
            $(".memberamount").hide();//会员等级存送百分比
            $(".profitamount").hide();//最低盈利百分比
        }else if(value == 3)
        {
            $(".percentage").hide();//存送百分比
            $(".fixedamount").hide();//存送固定红利
            $(".positivedamount").show();//昨日正负盈利
            $(".bettingamount").hide();//昨日投注额固定红利
            $(".memberamount").hide();//会员等级存送百分比
        }else if(value == 4)
        {
            $(".percentage").hide();//存送百分比
            $(".fixedamount").hide();//存送固定红利
            $(".positivedamount").hide();//昨日正负盈利
            $(".bettingamount").show();//昨日投注额固定红利
            $(".memberamount").hide();//会员等级存送百分比
        }else if(value == 5){
            $(".percentage").hide();//存送百分比
            $(".fixedamount").hide();//存送固定红利
            $(".positivedamount").hide();//昨日正负盈利
            $(".bettingamount").hide();//昨日投注额固定红利
            $(".memberamount").show();//会员等级存送百分比
        }
        else{
            $(".percentage").hide();//存送百分比
            $(".fixedamount").hide();//存送固定红利
            $(".positivedamount").hide();//昨日正负盈利
            $(".bettingamount").hide();//昨日投注额固定红利
            $(".memberamount").hide();//会员等级存送百分比
        }
    }
</script>
<!--代理用户-->
<script>
    $(function () {
        $.fn.agentComponent = new Vue({
            el: '#agentComponent',
            created:function(){
                this.insertNewSelectData();
                @if(isset($carrierActivity) && $agent_user_id_json = $carrierActivity->agent_user_id_json )
                this.selectedData = $.parseJSON('{!! $agent_user_id_json !!}');
                @endif
            },
            data: {
                needInitialSelect2:false,
                selectedData: []
            },
            methods: {
                insertNewSelectData: function (event) {
                    var obj = {
                        selectedAgentPages:[],
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
                    this.selectedData[index].selectedAgentPages = selectedPageIds;
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
                        if (latestObj.selectedAgentPages.length == 0){
                            return false;
                        }
                    }
                    return true;
                },
                agentData:function () {
                    return JSON.stringify(this.selectedData.filter(function(element){
                        return element.selectedAgentPages.length > 0;
                    }));
                }
            }
        });
    })
</script>
<!--该活动流水限平台-->
<script>
    $(function () {
        $.fn.gameComponent = new Vue({
            el: '#gameComponent',
            created:function(){
                this.insertNewSelectData();
                @if(isset($carrierActivity) && $main_game_plat_json = $carrierActivity->main_game_plat_json )
                    this.selectedData = $.parseJSON('{!! $main_game_plat_json !!}');
                @endif
            },
            data: {
                needInitialSelect2:false,
                selectedData: []
            },
            methods: {
                insertNewSelectData: function (event) {
                    var obj = {
                        selectedGamePages:[],
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
                    $('.game_page_select2').select2({
                        closeOnSelect: false
                    });
                    this.needInitialSelect2 = false;
                },
                
                gameChanged:function (index,event) {
                    var selectedPageIds = [];
                    $(event).find('option:selected').each(function(index,element){
                        selectedPageIds.push(element.value);
                    });
                    this.selectedData[index].selectedGamePages = selectedPageIds;
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
                        if (latestObj.selectedGamePages.length == 0){
                            return false;
                        }
                    }
                    return true;
                },
                gameData:function () {
                    return JSON.stringify(this.selectedData.filter(function(element){
                        return element.selectedGamePages.length > 0;
                    }));
                }
            }
        });
    })
</script>
<script>
    $(function () {
        Array.prototype.lastElement = function (slice) {
            if(this.length == 0){
                return null;
            }
            return this[this.length - 1 - (slice ? slice : 0)];
        };

        Array.prototype.removeLastElement = function () {
            if(this.length > 0){
                this.splice(this.length - 1, 1);
            }
        };


        Vue.component(
                'andOrRelationComponent',
                {
                template: '<button v-bind:disabled="!data.ruleRelationButtonsAvailable" type="button"  v-bind:title="data.content" class="btn btn-warning upgrade_rule_btn">@{{ data.content }}</button>',
                props: ['data']
            }
        );

        $.fn.activityApplyRuleVue = new Vue({
            el: '#upgrade_rule_action',
            created:function(){
               
            },
            data: {
                ruleTypeInputStack:[],
                ruleRelationInputStack:[],
                largeOrLessRelationInputStack:[],
                numberInputStack:[],
                totalInputStack:[],
                operatorInputStack:[],
                viewMeta: {
                    ruleType: {
                        userFirstDeposit:'用户首次存款额',
                        todayFirstDeposit:'今日首次存款额',
                        thisWeekFirstDeposit:'本周首次存款额',
                        accountRemain: '总账户余额',
                        todayDepositAmount: '今日存款额',
                        todayWithdrawAmount: '今日取款额',
                        todayWinorlose:'今日总输赢',
                        yesterdayDepositAmount: '昨日存款额',
                        yesterdayWithdrawAmount: '昨日取款额',
                        thisWeekDepositAmount: '本周存款额',
                        thisWeekWithdrawAmount: '本周取款额',
                        lastWeekDepositAmount: '上周存款额',
                        lastWeekWithdrawAmount: '上周取款额',
                    },
                    ruleRelation: {
                        leftRequire: {
                            content:'(',
                            data:'('
                        },
                        rightRequire: {
                            content:')',
                            data:')'
                        },
                        andRelation: {
                            content:'且',
                            data:'and'
                        },
                        orRelation: {
                            content:'或',
                            data:'or'
                        }
                    },
                    operatorRelation:{
                        plus : '+',
                        reduce : '-'
                    },
                    largeOrLessRelation: {
                        large: '>',
                        less: '<',
                        largeAndEqual: '>=',
                        lessAndEqual: '<=',
                        //equal: '=',
                    }
                },
                currentInputValue:null,
            },
            methods:{
                reChoose:function () {
                    this.ruleTypeInputStack = [];
                    this.ruleRelationInputStack  =  [];
                    this.largeOrLessRelationInputStack = [];
                    this.numberInputStack = [];
                    this.totalInputStack = [];
                    this.operatorInputStack = [];
                },
                back:function () {
                    this.currentInputValue = null;
                    if(this.totalInputStack.length == 0){ return };
                    if(this.totalInputStack.lastElement() == this.numberInputStack.lastElement()){
                        this.numberInputStack.removeLastElement();
                    }
                    if(this.totalInputStack.lastElement() == this.ruleTypeInputStack.lastElement()){
                        this.ruleTypeInputStack.removeLastElement();
                    }
                    if(this.totalInputStack.lastElement() == this.ruleRelationInputStack.lastElement()){
                        this.ruleRelationInputStack.removeLastElement();
                    }
                    if(this.totalInputStack.lastElement() == this.largeOrLessRelationInputStack.lastElement()){
                        this.largeOrLessRelationInputStack.removeLastElement();
                    }
                    this.totalInputStack.removeLastElement();
                },
                operatorInputAction:function (data) {
                    this.operatorInputStack.push(data);
                    this.totalInputStack.push(data);
                    console.log(this.totalInputStack,this.operatorInputStack)
                },
                numberInputAction:function () {
                    this.currentInputValue = parseFloat(this.currentInputValue);
                    this.numberInputStack.push(this.currentInputValue);
                    this.totalInputStack.push(this.currentInputValue);
                    this.currentInputValue = null;
                    console.log(this.totalInputStack,this.numberInputStack);
                },
                ruleTypeAction:function (data) {
                    this.ruleTypeInputStack.push(data);
                    this.totalInputStack.push(data);
                    console.log(this.totalInputStack,this.ruleTypeInputStack);
                },
                ruleRelationAction:function (data) {
                    this.ruleRelationInputStack.push(data);
                    this.totalInputStack.push(data);
                    console.log(this.totalInputStack,this.ruleRelationInputStack);
                },
                largeOrLessRelationAction:function (data) {
                    this.largeOrLessRelationInputStack.push(data);
                    this.totalInputStack.push(data);
                    console.log(this.totalInputStack,this.largeOrLessRelationInputStack);
                },
                _isLastTypeIsLargeOrLessRelation:function () {
                    if(this.totalInputStack.lastElement() == this.viewMeta.largeOrLessRelation.large){ return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.largeOrLessRelation.less){ return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.largeOrLessRelation.equal){ return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.largeOrLessRelation.largeAndEqual){ return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.largeOrLessRelation.lessAndEqual){ return true}
                    return false;
                },
            },
            computed:{
                operatorButtonAvailable:function () {
                    if(this.totalInputStack.length == 0){return false}
                    if(this.totalInputStack.lastElement(1) == this.largeOrLessRelationInputStack.lastElement() && this.largeOrLessRelationInputStack.lastElement() != null){ return false}
                    if(this.totalInputStack.lastElement() == this.ruleTypeInputStack.lastElement() ){ return true }
                    return false;
                },
                // 100,233
                numberInputAvailable:function () {
                    if(this.totalInputStack.length == 0){return false}
                    if(this._isLastTypeIsLargeOrLessRelation()){ return true}
                    return false;
                },
                // 首次存款额, 当天存款额
                ruleTypeButtonsAvailable : function () {
                    if(this.totalInputStack.length == 0){return true}
                    if(this._isLastTypeIsLargeOrLessRelation()){ return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.ruleRelation.leftRequire){ return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.ruleRelation.andRelation){return true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.ruleRelation.orRelation){return true}
                    if(this.totalInputStack.lastElement() == this.operatorInputStack.lastElement() ){return true}
                    return false;
                },
                // (
                leftRequireButtonAvailable:function () {
                    if(this.totalInputStack.length == 0){return true}
                    var leftRequireNumber = 0;
                    var rightRequireNumber = 0;
                    var requireQuoteResult = false;
                    for(var i = 0 ; i < this.ruleRelationInputStack.length; i++){
                        if(this.ruleRelationInputStack[i].data == '('){ leftRequireNumber++ }
                        if(this.ruleRelationInputStack[i].data == ')'){ rightRequireNumber++ }
                    }
                    if(leftRequireNumber == 0 || leftRequireNumber > 0 && leftRequireNumber == rightRequireNumber){ requireQuoteResult = true; }
                    if(this.totalInputStack.lastElement() == this.viewMeta.ruleRelation.andRelation){return requireQuoteResult && true}
                    if(this.totalInputStack.lastElement() == this.viewMeta.ruleRelation.orRelation){return requireQuoteResult && true}

                    return false;
                },
                // )
                rightRequireButtonAvailable:function () {
                    if(this.totalInputStack.length == 0){return false}
                    var leftRequireNumber = 0;
                    var rightRequireNumber = 0;
                    var requireQuoteResult = false;
                    for(var i = 0 ; i < this.ruleRelationInputStack.length; i++){
                        if(this.ruleRelationInputStack[i].data == '('){ leftRequireNumber++ }
                        if(this.ruleRelationInputStack[i].data == ')'){ rightRequireNumber++ }
                    }
                    if(leftRequireNumber > 0 && leftRequireNumber > rightRequireNumber){requireQuoteResult = true}
                    if(this.totalInputStack.lastElement() == this.numberInputStack.lastElement()){return requireQuoteResult && true}
                    if(this.totalInputStack.lastElement() == this.ruleTypeInputStack.lastElement() && this.totalInputStack.lastElement(1) == this.operatorInputStack.lastElement() && this.operatorInputStack.lastElement() != null){ return requireQuoteResult && true}
                    return false;
                },
                // and or
                andOrRelationButtonAvailable: function () {
                    console.log('AND OR CONDITION:',this.totalInputStack.length,this.totalInputStack.lastElement(),this.viewMeta.ruleRelation.rightRequire.data,this.ruleTypeInputStack.lastElement());
                    if(this.totalInputStack.length == 0){return false}
                    if(this.totalInputStack.lastElement() == this.viewMeta.ruleRelation.rightRequire && this.totalInputStack.lastElement(2) == this.operatorInputStack.lastElement()){
                        return false;
                    }

                    if(this.totalInputStack.lastElement() == this.numberInputStack.lastElement()){return true}
                    if(this.totalInputStack.lastElement(1) == this.largeOrLessRelationInputStack.lastElement() && this.largeOrLessRelationInputStack.lastElement() != null){ return true}
                    return false;
                },
                // = > < <= >=
                largeOrLessOrEqualRelationAvailable:function () {
                    if(this.totalInputStack.length == 0){return false}
                    if(this.totalInputStack.lastElement(1) == this.operatorInputStack.lastElement() && this.operatorInputStack.lastElement() != null){ return false }
                    if(this.totalInputStack.lastElement(1) == this.largeOrLessRelationInputStack.lastElement() && this.largeOrLessRelationInputStack.lastElement() != null){ return false}
                    if(this.totalInputStack.lastElement() == this.ruleTypeInputStack.lastElement()){return true}
                    if(this.totalInputStack.lastElement() == this.ruleRelationInputStack.lastElement() && this.ruleRelationInputStack.lastElement().data == ')' && this.totalInputStack.lastElement(2) == this.operatorInputStack.lastElement()){ return true}
                    return false;
                },
                resultPremiumString:function () {
                    var str = "";
                    for(var i = 0; i < this.totalInputStack.length; i++){
                        if(this.totalInputStack[i].content != undefined){
                            str += this.totalInputStack[i].content+" ";
                        }
                        else if(this.viewMeta.ruleType[this.totalInputStack[i]]){
                            str += this.viewMeta.ruleType[this.totalInputStack[i]] +" ";
                        }else{
                            str += this.totalInputStack[i]+" ";
                        }
                    }
                    return str;
                },
                resultPremiumRuleString:function () {
                    var str = "";
                    for(var i = 0; i < this.totalInputStack.length; i++){
                        if(this.totalInputStack[i].data != undefined){
                            str += this.totalInputStack[i].data+" ";
                        }else if(this.viewMeta.ruleType[this.totalInputStack[i]]){
                            str += "$" + this.totalInputStack[i] + " ";
                        } else{
                            str += this.totalInputStack[i]+" ";
                        }
                    }
                    return str;
                },
                canSubmitNumberInput:function () {
                    if(isNaN(parseFloat(this.currentInputValue)) || parseFloat(this.currentInputValue) <= 0){
                        return false;
                    }
                    return true;
                }
            }
        });

        $('.agent_select').select2({
            closeOnSelect: false
        });
        
        $('.main_game_plat_select').select2({
            closeOnSelect: false
        });
    })
</script>