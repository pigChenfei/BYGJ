@extends('Carrier.layouts.app')

@section('css')
    <style>
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="left">
        </div>
    </section>
    <form action="" id="inviteFriendConf">
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> 邀请好友设置</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">投注额奖励</a></li>
                        <li><a href="#tab_2" data-toggle="tab">存款额奖励</a></li>
                        <li><a href="#tab_3" data-toggle="tab">有效会员设置</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="form-group col-sm-12" style="margin-top: 20px">
                                <label for="">自动结算周期:</label>
                                <select name="bet_reward_settle_period" id="" class="form-control disable_search_select2" style="width: 100%;;">
                                    <option {!! $invitePlayerConf->bet_reward_settle_period == \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_DAY ? 'selected' : '' !!} value="{!! \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_DAY !!}">按天结算</option>
                                    <option {!! $invitePlayerConf->bet_reward_settle_period == \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_WEEK ? 'selected' : '' !!} value="{!! \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_WEEK !!}">按周结算</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="">规则:</label>
                                <table class="table table-responsive table-hover">
                                    <tr v-for="(betRule,index) in betRules">
                                        <td style="width: 120px">当有效投注额 >=</td>
                                        <td style="width: 120px"><input type="text" v-model="betRule.availableBetAmount" min="0" class="form-control"></td>
                                        <td style="width: 90px">时,则按照</td>
                                        <td style="width: 80px"><input type="text" v-model="betRule.playerRewardPercent" min="0" max="100" class="form-control"></td>
                                        <td style="width: 260px">%洗码比例奖励被推荐人，最高不超过</td>
                                        <td style="width: 120px"><input type="text" v-model="betRule.playerRewardMax" min="0" class="form-control"></td>
                                        <td style="width: 80px;"> 元，按照</td>
                                        <td style="width: 80px"><input type="text" v-model="betRule.invitePlayerRewardPercent" min="0" max="100" class="form-control"></td>
                                        <td style="width: 250px;">% 洗码比例奖励推荐人，最高不超过</td>
                                        <td style="width: 120px"><input type="text" v-model="betRule.invitePlayerRewardMax" min="0" class="form-control"></td>
                                        <td style="width: 60px;"> 元</td>
                                        <td><a style="width: 100%;" v-on:click="deleteBetRule(index)" class="btn btn-danger">删除</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="12">
                                            <a class="btn btn-success" style="width: 100%" v-on:click="newBetRule">新增规则</a>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                            <input type="hidden" name="bet_reward_rule" v-bind:value="betRuleResult">
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <div class="form-group col-sm-12" style="margin-top: 20px">
                                <label for="">自动结算周期:</label>
                                <select name="deposit_reward_settle_period" id="" class="form-control disable_search_select2" style="width: 100%">
                                    <option {!! $invitePlayerConf->deposit_reward_settle_period == \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_DAY ? 'selected' : '' !!}  value="{!! \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_DAY !!}">按天结算</option>
                                    <option {!! $invitePlayerConf->deposit_reward_settle_period == \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_WEEK ? 'selected' : '' !!} value="{!! \App\Models\Conf\CarrierInvitePlayerConf::SETTLE_PERIOD_WEEK !!}">按周结算</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="">规则:</label>
                                <table class="table table-responsive table-hover">
                                    <tr v-for="(depositRule,index) in depositRules">
                                        <td style="width: 200px">当被推荐人总存款额达到</td>
                                        <td style="width: 200px"><input type="text" v-model="depositRule.depositAmount" min="0" class="form-control"></td>
                                        <td style="width: 150px">时，奖励被推荐人</td>
                                        <td style="width: 200px"><input type="text" v-model="depositRule.playerRewardAmount" min="0" class="form-control"></td>
                                        <td style="width: 150px">元，奖励推荐人</td>
                                        <td style="width: 200px"> <input type="text" v-model="depositRule.invitePlayerRewardAmount" min="0" class="form-control"></td>
                                        <td style="width: 40px">元</td>
                                        <td><a style="width: 100%;" class="btn btn-danger" v-on:click="deleteDepositRule(index)">删除</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">
                                            <a class="btn btn-success" v-on:click="newDepositRule" style="width: 100%">新增规则</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <input type="hidden" name="deposit_reward_rule" v-bind:value="depositRuleResult">
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <div class="form-group col-sm-12" style="margin-top: 20px">
                                <table class="table table-responsive table-hover">
                                    <tr>
                                        <td>被推荐人有效投注额达到</td>
                                        <td><input type="text" name="invalid_player_bet_amount" value="{!! $invitePlayerConf->invalid_player_bet_amount !!}" min="0" class="form-control"></td>
                                        <td style="width: 110px">且存款额达到</td>
                                        <td><input type="text" name="invalid_player_deposit_amount" value="{!! $invitePlayerConf->invalid_player_deposit_amount !!}" min="0" class="form-control"></td>
                                        <td>时为有效会员</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary pull-right" onclick="var _me = this; var data = $('#inviteFriendConf').serializeJson();
                        $.fn.winwinAjax.buttonActionSendAjax(
                                _me,
                        '{!! route('saveInvitePlayerConf') !!}',
                        data,
                        function() {
                            $.fn.alertSuccess('保存成功')
                        },function() {

                        },'PATCH')">保存</button>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
    </form>

    {!! TableScript::createEditOrAddModal() !!}

@endsection

@section('scripts')
    <script src="{!! asset('js/vue.min.js') !!}"></script>
    <script>
        $(function(){
            var betRule = function(){
                return{
                    availableBetAmount:null,
                    playerRewardPercent:null,
                    playerRewardMax:999999,
                    invitePlayerRewardPercent:null,
                    invitePlayerRewardMax:999999
                }
            };

            var depositRule = function(){
                return {
                    depositAmount:null,
                    playerRewardAmount:null,
                    invitePlayerRewardAmount:null
                }
            };
            new Vue({
                created:function(){
                    this.betRules = $.parseJSON('{!! $invitePlayerConf->bet_reward_rule !!}')
                    this.depositRules = $.parseJSON('{!! $invitePlayerConf->deposit_reward_rule !!}')
                },
                el:'#inviteFriendConf',
                data:{
                    betRules:[],
                    depositRules:[],
                },
                methods:{
                    newBetRule:function () {
                        for(index in this.betRules){
                            var rule = this.betRules[index];
                            if(rule.availableBetAmount == null || rule.playerRewardPercent == null || rule.invitePlayerRewardPercent == null){
                                return;
                            }
                        }
                        this.betRules.push(new betRule());
                    },
                    deleteBetRule:function(index){
                        this.betRules.splice(index,1);
                    },
                    newDepositRule:function(){
                        for(index in this.depositRules){
                            var rule = this.depositRules[index];
                            if(rule.depositAmount == null || rule.playerRewardAmount == null || rule.invitePlayerRewardAmount == null){
                                return;
                            }
                        }
                        this.depositRules.push(new depositRule());
                    },
                    deleteDepositRule:function (index) {
                        this.depositRules.splice(index,1);
                    }
                },
                computed:{
                    betRuleResult:function(){
                        return JSON.stringify(this.betRules);
                    },
                    depositRuleResult:function () {
                        return JSON.stringify(this.depositRules)
                    }
                },
                mounted:function(){
                    $('.disable_search_select2').select2({
                        minimumResultsForSearch: Infinity
                    });
                }
            })
        });
    </script>
@endsection
