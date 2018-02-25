<!-- Level Name Field -->
<div class="form-group col-sm-4">
    {!! Form::label('level_name', Lang::get('carrierPlayerLevelField.level_name')) !!}
    {!! Form::text('level_name', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Sort Rule Field -->
<div class="form-group col-sm-4">
    {!! Form::label('sort', '升级顺序(数字越小越靠前)') !!}
    {!! Form::text('sort', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Sort Rule Field -->
<div class="form-group col-sm-4">
    {!! Form::label('is_default', '是否是默认等级') !!}
    <?php $defaultLevelMeta = \App\Models\CarrierPlayerLevel::defaultLevelMeta(); ?>
    <select name="is_default" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($defaultLevelMeta as $key => $value)
            @if(isset($carrierPlayerLevel) && $carrierPlayerLevel instanceof \App\Models\CarrierPlayerLevel && $carrierPlayerLevel->is_default == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>


<div class="form-group col-sm-12" id="upgrade_rule_action">
    {!! Form::label('activity_apply_rule_string','升级规则(点击以下按钮或输入数字可以组合生成规则)') !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="" ></i>
    <input type="hidden" name="upgrade_rule" v-model="resultPremiumRuleString">
    <div class="col-sm-12">
        <div class="col-sm-2">
            <div class="btn-group">
                <button class="btn btn-primary" v-for="element,index in viewMeta.ruleType" v-bind:disabled="!ruleTypeButtonsAvailable" v-on:click="ruleTypeAction(index)" v-bind:style="{cursor: 'pointer'}" >@{{ element  }}</button>
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

<!-- Remark Field -->
<div class="form-group col-sm-12">
    {!! Form::label('img', '等级图片') !!}
    <input id="input-file-field" type="file" name="file" class="form-control">
    <input type="hidden" name="img" value="{{isset($carrierPlayerLevel)?$carrierPlayerLevel->img:''}}">
</div>
<div class="form-group col-sm-12">
    {!! Form::label('remark', Lang::get('common.remark')) !!}
    {!! Form::textarea('remark', null, ['class' => 'form-control','rows' => 5, 'style' => 'resize:none;']) !!}
</div>
<link href="{!! asset('bootstrap-fileinput/css/fileinput.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
<script src="{!! asset('bootstrap-fileinput/js/fileinput.min.js') !!}"></script>
<script src="{!! asset('bootstrap-fileinput/js/locales/zh.js') !!}"></script>
<script>
    $(function(){
        $("#input-file-field").fileinput({
            uploadUrl: '{{route('CarrierPlayerLevels.storeImg')}}', // you must set a valid URL here else you will get an error
            showUploadedThumbs:false,
            allowedFileExtensions : ['jpg', 'png','gif'],
            validateInitialCount:false,
            overwriteInitial: false,
            dropZoneTitle:'拖拽文件到这里 …',
            maxFileSize: 1024,
            maxFileCount: Infinity,
            slugCallback: function(filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            previewSettings:{
                image: {width: "auto", height: "160px"}
            },
            showUpload:true,
            showRemove:true,
            fileActionSettings:{
                showUpload:false,
                showRemove:false
            },
            language:'zh',
            initialPreview:[@if(isset($carrierPlayerLevel)&&!empty($carrierPlayerLevel->img))'<img src="{{$carrierPlayerLevel->imageAsset()}}" class="file-preview-frame">'@endif],
        });
        $('#input-file-field').on('fileuploaderror', function(event, data, msg) {  //一个文件上传失败
            toastr.error('操作失败，请重试' || '编辑失败', '出错啦!');
        });
        $('#input-file-field').on('fileuploaded', function(event, data, previewId, index) {
            data.extra.image_id = 1;
            toastr.success('图片上传成功');
            if (data.jqXHR.responseJSON.success == true){
                $('input[name=img]').val(data.jqXHR.responseJSON.data)
            }
        });
        $('#input-file-field').on('filecleared', function(event, key) {
            $('input[name=img]').val('');
            $('.file-preview-initial').remove();
        });
        $('#input-file-field').on('filebatchselected', function(event, key) {
            $('.file-preview-initial').remove();
        });

        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
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
                @if(isset($carrierPlayerLevel->upgrade_rule))
                <?php $upgradeRuleJson = json_decode($carrierPlayerLevel->upgrade_rule,true); ?>
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
                        betAmount:'有效投注额',
                        depositAmount:'存款额',
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
                    if(isNaN(parseFloat(this.currentInputValue)) || parseFloat(this.currentInputValue) <= 0){
                        return false;
                    }
                    return true;
                }
            }
        });


    })
</script>