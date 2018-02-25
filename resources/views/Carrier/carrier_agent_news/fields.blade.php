<!-- Carrier Id Field -->
<div class="form-group col-sm-12" id="agentComponent">
    <input type="hidden" name="agent_user_id_json" v-bind:value="agentData">
    <div v-for="(value,index) in selectedData" class="row" style="margin-bottom: 10px">
        <div class="col-sm-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-sticky-note"></i> 请选择代理用户<i class="fa fa-question-circle" style="color: #f44336" data-toggle="tooltip" data-original-title="若不选择,则发送给全部代理"></i></span>
                <select onchange="$.fn.agentComponent.bannerChanged(this.id,this)" v-bind:id="index" multiple="multiple" data-placeholder="请输入代理名称搜索..."
                        class="form-control banner_page_select2"
                        style="width: 100%">
                    @foreach($agentUsers as $key => $value)
                        <option v-bind:selected="value.selectedAgentPages.indexOf('{!! $value->id !!}') != -1" value="{!! $value->id !!}">{!! $value->username !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('title', '信息标题:').Form::required_pin() !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('remark', '信息内容:').Form::required_pin() !!}
    <textarea name="remark" class="form-control" style="resize:none" id="" cols="30" rows="5"></textarea>
</div>


<script>
    $(function(){
    //会员用户
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
    })
})    
</script>
