<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', '活动名称:').Form::required_pin() !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="在前台优惠活动、存款页面和我的优惠中显示，建议文字不要过多" ></i>
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('act_type_id', '活动类型') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="选择优惠活动展示的活动分类" ></i>
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%;">
        <option value="">请选择</option>
        @foreach($carrierActivityType as $key => $value)
            @if(isset($carrierActivity) &&  $carrierActivity->act_type_id == $value->id)
                <option value="{!! $value->id !!}" selected>{!! $value->type_name !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->type_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-3">
    {!! Form::label('sort', '活动排序:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="该活动排序的设置是在前台、存款页面、我的优惠等地方展示的位置排序，越大越靠前" ></i>
    {!! Form::number('sort', null, ['class' => 'form-control']) !!}
</div>




<div class="form-group col-sm-12">
    {!! Form::label('image_id', '活动图片:') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="首先在前台设置，广告图片管理中上传活动图片，然后选择" ></i>
    <div class="row">
        <div class="col-sm-6 col-lg-12">
            @include('Components.ImagePicker.index',[
            'onchange' => 'var photo = $(this).find(\'option:selected\').html();$(\'#image\').attr(\'src\',photo);',
            'name' => 'image_id',
            'default' => isset($carrierActivity) ? $carrierActivity->image_id : null,
            'id' => 'activityImageSelector'
            ])
            <div class="right" style="margin-bottom:10px;">
                <div>
                    <img id="image" src="">
                </div>
            </div>
        </div>
    </div>
</div>
<style>
#photo {
    margin-bottom:10px;
}
#image {
    margin-top:5px;
}
</style>
<script type="text/javascript">

$(function(){
    var photo = $('#activityImageSelector').find('option:selected').html();$('#image').attr('src',photo);
})
</script>

<div class="form-group col-sm-12">
    {!! Form::label('act_content', '活动内容:') !!}
    <input type="hidden" name="update_type" value="content_file_path">
    <div class="form-group col-sm-12">
        @if(isset($carrierActivity))
            @include('Components.Editor.index',['id' => 'content_file_path','name' => 'content_file_path', 'defaultContent' => $carrierActivity->act_content()])
        @else
            @include('Components.Editor.index',['id' => 'content_file_path','name' => 'content_file_path', 'defaultContent' => ''])
        @endif
    </div>
</div>
@include('Components.ImagePicker.scripts')

<!--红利类型切换END-->
<script type="text/JavaScript">
    function selectOnchang(obj) {
        var value = obj.options[obj.selectedIndex].value;
        var classes = {
            '.percentage' : 1, //存送百分比
            '.fixedamount':2, //存送固定红利
            '.positivedamount':3, //昨日正负盈利
            '.bettingamount':4, //昨日投注额固定红利
            '.memberamount':5 //会员等级存送百分比
        };
        $.each(classes,function (key,classesValue) {
            if (classesValue == value){
                $(key).show();
            }else{
                $(key).hide();
            }
        });
    }
</script>
<!--该活动流水限平台-->
<script>
    $(function () {
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
        
        //该活动流水限平台
        $.fn.gameComponent = new Vue({
            el: '#gameComponent',
            created:function(){
                this.insertNewSelectData();
                @if(isset($flowLimitedPlatformGroup))
                    this.selectedData = $.parseJSON('{!! $flowLimitedPlatformGroup !!}');
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
        
        
        //代理用户
        $.fn.agentComponent = new Vue({
            el: '#agentComponent',
            created:function(){
                this.insertNewSelectData();
                @if(isset($agentUserGroup))
                    this.selectedData = $.parseJSON('{!! $agentUserGroup !!}');
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
                @if(isset($carrierActivity->apply_rule_string))
                <?php $upgradeRuleJson = json_decode($carrierActivity->apply_rule_string,true); ?>
                    @if(count($upgradeRuleJson) == 2)
                        var data = $.parseJSON('{!! json_encode($upgradeRuleJson[1]) !!}');
                        this.ruleTypeInputStack = data.ruleTypeInputStack;
                        this.ruleRelationInputStack = data.ruleRelationInputStack;
                        this.largeOrLessRelationInputStack = data.largeOrLessRelationInputStack;
                        this.numberInputStack = data.numberInputStack;
                        this.totalInputStack = data.totalInputStack;
                        this.operatorInputStack = data.operatorInputStack;
                    @endif;
                @endif
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
                    return JSON.stringify([str,this.$data]);
                },
                canSubmitNumberInput:function () {
                    if(isNaN(parseFloat(this.currentInputValue)) || parseFloat(this.currentInputValue) < 0){
                        return false;
                    }
                    return true;
                }
            }
        });

    })
</script>