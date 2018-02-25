<div class="form-group col-sm-12" id="gameComponent">
    {!! Form::label('main_game_plat_json', '该活动流水限平台') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="申请该活动获得的红利金额投注流水限在某个游戏平台内完成" ></i>
    <input type="hidden" name="main_game_plat_json" v-bind:value="gameData">
    <div v-for="(value,index) in selectedData" class="row" style="margin-bottom: 10px">
        <div class="col-sm-12">
            <div class="input-group col-sm-12">
                <span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择平台</span>
                <select onchange="$.fn.gameComponent.gameChanged(this.id,this)" v-bind:id="index" multiple="multiple" data-placeholder="请选择..."
                        class="form-control game_page_select2"
                        style="width: 100%">
                    @foreach($carrierGamePlatList as $key => $value)
                        <option v-bind:selected="value.selectedGamePages.indexOf('{!! $value->game_plat_id !!}') != -1" value="{!! $value->game_plat_id !!}">{!! $value['gamePlat']->game_plat_name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<!-- Act Type Id Field -->
<div class="form-group col-sm-3" id="bonuses_select">
    {!! Form::label('bonuses_type', '红利类型:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="红利发放方式的类型选择，可详细设置阶梯百分比或固定模式" ></i>
    <?php $bonusestypeDic = \App\Models\CarrierActivity::bonusesTypeMeta() ?>
    <select name="bonuses_type" class="form-control disable_search_select2" style="width: 100%;" onchange="selectOnchang(this)">
        <option value="0">请选择</option>
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
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="流水要求按存款额、红利额或存款额加红利额的计算方式" ></i>
    <?php $flowwantpatternDic = \App\Models\CarrierActivity::flowWantPatternMeta() ?>
    <select name="flow_want_pattern" class="form-control disable_search_select2" style="width: 100%;">
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
        {!! Form::label('is_bet_amount_enjoy_flow', '该活动投注额是否享受洗码:') !!}
        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="参加该活动后进行的投注是否享受洗码，如不享受洗码，达到流水要求后开始计算有效投注" ></i>
        <?php $bettingamountenjoyflowDic = \App\Models\CarrierActivity::bettingAmountEnjoyFlowMeta() ?>
        <select name="is_bet_amount_enjoy_flow" class="form-control disable_search_select2" style="width: 100%;">
            @foreach($bettingamountenjoyflowDic as $key => $value)
                @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->is_bet_amount_enjoy_flow == $key)
                    <option value="{!! $key !!}" selected>{!! $value !!}</option>
                @else
                    <option value="{!! $key !!}">{!! $value !!}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('mutex_parent_id', '互斥活动:') !!}
        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="互斥活动功能是选择参加该活动后不可在参加的活动" ></i>
        <select name="mutex_parent_id" class="form-control disable_search_select2" style="width: 100%;">
            <option value="0">请选择...</option>
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
    <div class="fixedamount" >
        @include('Carrier.carrier_activities.fixed_fields')<!--存送固定红利-->
    </div>
@else
    <div class="fixedamount" style="display: none">
        @include('Carrier.carrier_activities.fixed_fields')<!--存送固定红利-->
    </div>
@endif
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 3)
    <div class="positivedamount">
        @include('Carrier.carrier_activities.positive_negative_earnings_percentage_fields')<!--昨日正负盈利-->
    </div>
@else
    <div class="positivedamount" style="display: none">
        @include('Carrier.carrier_activities.positive_negative_earnings_percentage_fields')<!--昨日正负盈利-->
    </div>
@endif
@if(isset($carrierActivity) && $carrierActivity->bonuses_type == 4)
    <div class="bettingamount">
        @include('Carrier.carrier_activities.betting_amount_fixed_fields')<!--昨日投注额固定红利-->
    </div>
@else
    <div class="bettingamount" style="display: none">
        @include('Carrier.carrier_activities.betting_amount_fixed_fields')<!--昨日投注额固定红利-->
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
<div class="form-group col-sm-3">
    {!! Form::label('is_website_display', '网站前台是否显示:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="该活动是否在前台优惠活动中显示，如果显示访客会员均可看到" ></i>
    <?php $websitestatusDic = \App\Models\CarrierActivity::websiteStatusMeta() ?>
    <select name="is_website_display" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($websitestatusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->is_website_display == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('is_deposit_display', '存款页面是否显示:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果否该活动将在活动申请页面显示，如果是将在存款页面显示，存款时选择参加活动，处理方式可以为手动审核及自动审核" ></i>
    <?php $depositstatusDic = \App\Models\CarrierActivity::depositStatusMeta() ?>
    <select name="is_deposit_display" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($depositstatusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->is_deposit_display == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('is_active_apply', '是否主动申请:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="一般为主动申请，如果否，该活动就显示已参加，可作为优惠活动介绍使用" ></i>
    <?php $applystatusDic = \App\Models\CarrierActivity::applystatusMeta() ?>
    <select name="is_active_apply" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($applystatusDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->is_active_apply == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('censor_way', '处理方式:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="手动审核为会员申请参与活动后人工审核处理，自动审核为系统判定条件达到自动审核处理。" ></i>
    <?php $censorwayDic = \App\Models\CarrierActivity::censorWayMeta() ?>
    <select name="censor_way" class="form-control disable_search_select2" style="width: 100%;">
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
    {!! Form::label('ip_times', '同一IP限制参与次数:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="同一IP可申请的次数限制" ></i>
    {!! Form::text('ip_times', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('apply_times', '会员申请次数:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="该活动限制会员申请的次数" ></i>
    <?php $applynumberDic = \App\Models\CarrierActivity::applyNumberMeta() ?>
    <select name="apply_times" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($applynumberDic as $key => $value)
            @if(isset($carrierActivity) && $carrierActivity instanceof \App\Models\CarrierActivity && $carrierActivity->apply_times == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<!--<div class="form-group col-sm-3">
    {!! Form::label('sort', '活动排序:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="该活动排序的设置是在前台、存款页面、我的优惠等地方展示的位置排序，越大越靠前" ></i>
    {!! Form::number('sort', null, ['class' => 'form-control']) !!}
</div>
-->
<div class="form-group col-sm-12" id="upgrade_rule_action">
    {!! Form::label('apply_rule_string','申请规则(点击以下按钮或输入数字可以组合生成规则)') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="申请规则可多元化设置，如设置首存活动，可设置用户首次存款额>=100即可申请此活动。如设置救援金活动，可设置正负盈利百分比红利类型，那申请规则是总账户余额<=5，总账户余额必须小于或等于5元才可申请此活动。再举例：（昨日存款额－昨日取款额）>=200 且（今日存款额-今日取款额）>=200，“且”是需要同时满足两个条件，“或”是两个条件有一个条件满足即可达到申请条件" ></i>
    <input type="hidden" name="apply_rule_string" v-model="resultPremiumRuleString">
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
    {!! Form::label('agent_user_id_json', '活动所属代理') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="选择代理后限该代理下级会员享受此活动，可与会员等级同时设置" ></i>
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
    {!! Form::label('player_json', '活动所属会员等级:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="选择会员等级此等级的会员享受此活动，可与所属代理同时设置" ></i>
    <input type="hidden" name="player_level_json" v-bind:value="playerJson">
    <div class="row">
        @foreach($carrierPlayerLevel as $key => $value)
            <div class="col-sm-1">
                <input type="checkbox" v-model="playerJson" value="{!! $value->id !!}" class="square-blue">
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
                @if(isset($playerLevelGroup))
                    this.playerJson = $.parseJSON('{!! $playerLevelGroup !!}');
                @endif
            },
            computed:{
            }
        })
    })
</script>