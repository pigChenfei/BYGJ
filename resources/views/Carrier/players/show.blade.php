<div class="col-sm-12" id="playerInfoModalContent">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right" id="tabManagerVue">
            <li><a v-on:click="tabClicked('tab_7')" href="#tab_7" data-toggle="tab">好友邀请记录</a></li>
            <li><a v-on:click="tabClicked('tab_6')" href="#tab_6" data-toggle="tab">防止套利查询</a></li>
            <li><a v-on:click="tabClicked('tab_8')" href="#tab_8" data-toggle="tab">银行卡管理</a></li>
            <li><a v-on:click="tabClicked('tab_5')" href="#tab_5" data-toggle="tab">登录日志</a></li>
            <li><a v-on:click="tabClicked('tab_4')" href="#tab_4" data-toggle="tab">游戏管理</a></li>
            <li><a v-on:click="tabClicked('tab_3')" href="#tab_3" data-toggle="tab">交易信息</a></li>
            <li><a v-on:click="tabClicked('tab_2')" href="#tab_2" data-toggle="tab">财务信息</a></li>
            <li class="active"><a href="#tab_1" data-toggle="tab">会员资料</a></li>
            <li class="pull-left header">会员信息编辑</li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                @include('Carrier.players.tab_player_info')
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_4">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_5">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_6">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_7">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_8">
                <div class="box box-primary" style="height: 300px;border-top: none">
                    <div class="overlay" id="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
        <!-- /.tab-content -->

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
                    tab_4:false,
                    tab_5:false,
                    tab_6:false,
                    tab_7:false,
                    tab_8:false,
                },
                tabIdRouteMap:{
                    tab_2:'{!! route('players.showFinancial',$player->player_id) !!}',
                    tab_3:'{!! route('players.showTradeLog',$player->player_id) !!}',
                    tab_4:'{!! route('players.gameManage',$player->player_id) !!}',
                    tab_5:'{!! route('players.showLoginLog',$player->player_id) !!}',
                    tab_6:'{!! route('players.showCheatLog', $player->player_id) !!}',
                    tab_7:'{!! route('players.showRecommendLog',$player->player_id) !!}',
                    tab_8:'{!! route('players.showBankManage',$player->player_id) !!}'
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

//        $('.disable_search_select2').select2({
//            minimumResultsForSearch: Infinity
//        });
//


    })
</script>