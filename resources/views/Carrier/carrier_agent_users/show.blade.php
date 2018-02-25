<div class="col-sm-12" id="agentUserInfoModalContent">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right" id="tabManagerVue">
            <li><a v-on:click="tabClicked('tab_3')" href="#tab_3" data-toggle="tab">代理线下代理列表</a></li>
            <li><a v-on:click="tabClicked('tab_2')" href="#tab_2" data-toggle="tab">代理线下会员列表</a></li>
            <li class="active"><a href="#tab_1" data-toggle="tab">代理用户资料</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                @include('Carrier.carrier_agent_users.tab_agent_user_info')
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_3">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
    </div>

</div>

<div class="overlay" id="overlay" style="display: none">
    <i class="fa fa-refresh fa-spin"></i>
</div>
<div class="clearfix"></div>


<script>
    $(function(){
        $.fn.vueModalContentComponent = new Vue({
            el:'#tabManagerVue',
            data:{
                loadedRecord:{
                    tab_2:false,
                    tab_3:false,
                },
                tabIdRouteMap:{
                    tab_2:'{!! route('carrierAgentUsers.agentSubPlayer',$carrierAgentUser->id) !!}',
                    tab_3:'{!! route('carrierAgentUsers.subAgent',$carrierAgentUser->id) !!}',
                }
            },
            methods:{
                tabClicked:function(tabId){
                    @if(App::environment() == 'production')
                    if(this.loadedRecord[tabId] == true){
                        return;
                    }
                    @endif
                    this.loadedRecord[tabId] = true;
                    var tabIdJQ = $('#'+tabId);
                    tabIdJQ.load(this.tabIdRouteMap[tabId],null, function (resp) {

                    });
                }
            }
        });
    })
</script>