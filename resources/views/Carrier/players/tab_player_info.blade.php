<div class="box" style="height: 300px;border-top: none;box-shadow: none;">
    <table class="table table-responsive  table-bordered table-hover dataTable" id="player_info_edit">
        <tbody>
        <tr role="row">
            <th>账号</th>
            <td style="width: 14%">{!! $player->user_name !!}</td>
            <th>姓名</th>
            <td style="width: 14%">
                <edit_input_component ref="realNameEditComponent" v-if="realNameEdit" :value="real_name"
                                      v-on:on-saved-click="onSavedUserNameFunction"></edit_input_component>
                <div v-else><span>@{{ real_name }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateUserName'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;" v-on:click="editUserName"></a>
                    @endif
                </div>
            </td>
            <th>会员等级</th>
            <td>
                <edit_select_component :show-search="false" ref="userLevelEditComponent" v-if="userLevelEdit"
                                       :select-options="userLevelsOptionsData" :selected-data="userLevel"
                                       v-on:on-saved-click="onSavedUserLevelFunction"></edit_select_component>
                <div v-else><span>@{{ userLevel }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateLevel'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;"
                           v-on:click="editUserLevel"></a>
                    @endif
                </div>
            </td>
            <th>邀请人</th>
            <td>
                <edit_select_component ref="userInviteEditComponent" v-if="userInviteEdit"
                                       :select-options="userInvitesOptionsData" :selected-data="userInvite"
                                       v-on:on-saved-click="onSavedUserInviteFunction"></edit_select_component>
                <div v-else><span>@{{ userInvite }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateInviteUser'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;"
                           v-on:click="editUserInvite"></a>@endif</div>
            </td>

        </tr>
        <tr role="row">
            <th>所属代理</th>
            <td>
                <edit_select_component ref="userAgentEditComponent" v-if="userAgentEdit"
                                       :select-options="userAgentsOptionsData" :selected-data="userAgent"
                                       v-on:on-saved-click="onSavedUserAgentFunction"></edit_select_component>
                <div v-else><span>@{{ userAgent }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateAgent'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;"
                           v-on:click="editUserAgent"></a>
                    @endif</div>
            </td>
            <th id="primary_info">出生日期</th>
            <td id="reservationtime">
                <edit_date_component ref="birthdayEditComponent" v-if="birthdayEdit" v-bind:value="birthday"
                                      v-on:on-saved-click="onSavedBirthdayFunction"></edit_date_component>
                <div v-else><span>@{{ birthday }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateBirthday'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;"
                           v-on:click="editBirthday"></a>
                    @endif
                </div>
            </td>
            <th>手机号码</th>
            <td>
                <edit_input_component ref="mobileEditComponent" v-if="mobileEdit" :value="mobile"
                                      v-on:on-saved-click="onSavedMobileFunction"></edit_input_component>
                <div v-else><span>@{{ mobile }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateMobile'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;"
                           v-on:click="editMobile"></a>
                    @endif
                </div>
            </td>
            <th>邮箱地址</th>
            <td>
                <edit_input_component ref="emailEditComponent" v-if="emailEdit" :value="email"
                                      v-on:on-saved-click="onSavedEmailFunction"></edit_input_component>
                <div v-else><span>@{{ email }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateEmail'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;"
                           v-on:click="editEmail"></a>
                    @endif
                </div>
            </td>

        </tr>
        <tr role="row">
            <th>QQ</th>
            <td><edit_input_component ref="qqEditComponent" v-if="qqEdit" :value="qq"
                                      v-on:on-saved-click="onQQFunction"></edit_input_component>
                <div v-else><span>@{{ qq }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateQQ'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;" v-on:click="editQQ"></a>
                    @endif
            <th>微信</th>
            <td><edit_input_component ref="wechatEditComponent" v-if="wechatEdit" :value="wechat"
                                      v-on:on-saved-click="onWechatFunction"></edit_input_component>
                <div v-else><span>@{{ wechat }}</span>
                    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.updateWechat'))
                        <a class="fa fa-edit" style="float: right;color: #ff811e;" v-on:click="editWechat"></a>
                @endif</td>
            <th>注册时间</th>
            <td>{!! $player->created_at !!}</td>
            <th>注册IP</th>
            <td>{!! $player->register_ip !!}</td>

        </tr>
        <tr role="row">
            <th>上次登录时间</th>
            <td>{!! $player->loginLogs->count() >= 2 ? $player->loginLogs->all()[1]->login_time : '' !!}</td>
            <th>上次登录IP</th>
            <td>{!! $player->loginLogs->count() >= 2 ? $player->loginLogs->all()[1]->login_ip : '' !!}</td>
            <th>最后登录时间</th>
            <td>{!! $player->login_at !!}</td>
            <th>最后登录IP</th>
            <td>{!! $player->login_ip !!}</td>
        </tr>
        </tbody>
    </table>
    <div class="overlay" id="user_base_info_over_lay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
        <h5 style="text-align: center;position: absolute;top: 57%;width: 100%;">正在获取数据...</h5>
    </div>
</div>
<script>
    $(function () {
        $('#primary_info').datepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY'});

        Vue.component('edit_input_component', {
            template: '<div class="input-group input-group-sm" style="width: 200px;margin:0 auto">' +
            '<input type="text" class="form-control" v-model="value">' +
            '<span class="input-group-btn">' +
            '<button v-bind:disabled="!savedButtonEnabled" v-on:click="onSavedClick" type="button" class="btn btn-primary btn-flat">@{{ savedButtonEnabled ? "保存" : "保存中..." }}</button>' +
            '</span>' +
            '</div>',
            data: function () {
                return {
                    savedButtonEnabled: true,
                    value: this.value
                }
            },
            methods: {
                onSavedClick: function () {
                    this.savedButtonEnabled = false;
                    this.$emit('on-saved-click')
                }
            },
            props: ['value']
        });

        Vue.component('edit_date_component', {
            template: '<div class="input-group input-group-sm" style="width: 200px;margin:0 auto">' +
            '<input type="text" class="form-control" v-model="value">' +
            '<span class="input-group-btn">' +
            '<button v-bind:disabled="!savedButtonEnabled" v-on:click="onSavedClick" type="button" class="btn btn-primary btn-flat">@{{ savedButtonEnabled ? "保存" : "保存中..." }}</button>' +
            '</span>' +
            '</div>',
            data: function () {
                return {
                    savedButtonEnabled: true,
                    value: this.value,
                    hasInitialedDateComponent: false,
                }
            },
            methods: {
                onSavedClick: function () {
                    this.savedButtonEnabled = false;
                    this.value = $(this.$el).find('input').val();
                    this.$emit('on-saved-click');
                }
            },
            mounted:function(){
                if(!this.hasInitialedDateComponent){
                    var _input = $(this.$el).find('input');
                    _input.datepicker({
                        autoclose: true,
                        language:'cn',
                        format:'yyyy-mm-dd',
                    }).on('changeDate',function(){
                        _input.change();
                    })
                }
            },
            props: ['value']
        });

        Vue.component('edit_select_component', {
            template: '<form><div class="input-group">' +
            '<select name="updateSelectData" class="form-control disable_search_select2" style="width: 100%;">' +
            '<option v-for = "option in selectOptions" v-bind:selected="option.text == selectedData" v-bind:value="option.value">@{{ option.text }}</option>' +
            '</select>' +
            '<div class="input-group-btn">' +
            '<button v-bind:disabled="!savedButtonEnabled" v-on:click="onSavedClick" type="button" class="btn btn-primary btn-flat">@{{ savedButtonEnabled ? "保存" : "保存中..." }}</button>' +
            '</div>' +
            '</div></form>',
            data: function () {
                return {
                    savedButtonEnabled: true,
                    selectOptions: this.selectOptions,
                    selectedData: this.selectedData,
                    showSearch: this.showSearch,
                }
            },
            methods: {
                onSavedClick: function () {
                    this.savedButtonEnabled = false;
                    this.$emit('on-saved-click')
                },
                selectValue: function () {
                    var data = $(this.$el).serializeJson();
                    return data.updateSelectData;
                }
            },
            computed: {},
            mounted: function () {
                var options = {};
                if (this.showSearch == false) {
                    options.minimumResultsForSearch = Infinity;
                }
                $(this.$el).find('select').select2(options);
            },
            props: ['selectOptions', 'selectedData', 'showSearch']
        });

        var playerInfoVueModel = new Vue({
            el: '#player_info_edit',
            data: {
                realNameEdit: false,
                real_name: '{!! $player->real_name !!}',

                mobileEdit: false,
                mobile: '{!! $player->mobile !!}',

                emailEdit: false,
                email: '{!! $player->email !!}',

                userAgent: '{!! isset($player->agent) ? ($player->agent->isCarrierDefaultAgent() ? '系统用户' : $player->agent->realname )  : '' !!}',
                userAgentEdit: false,
                userAgentsOptionsData: null,
                userInvite: '{!! isset($player->invitedPlayer) ? $player->invitedPlayer->real_name : '' !!}',
                userInviteEdit: false,
                userInviteOptionsData: null,
                userLevel: '{!! isset($player->playerLevel) ? $player->playerLevel->level_name : '' !!}',
                userLevelEdit: false,
                userLevelsOptionsData: null,

                birthday: '{!! isset($player->birthday) ? $player->birthday : '' !!}',
                birthdayEdit:false,
                qq:'{!! isset($player->qq_account) ? $player->qq_account : '' !!}',
                qqEdit:false,
                wechat:'{!! isset($player->wechat) ? $player->wechat : ''  !!}',
                wechatEdit:false

            },
            methods: {
                editUserName: function () {
                    this.realNameEdit = true;
                },
                onSavedUserNameFunction: function () {
                    var _this = this;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateUserName',$player->player_id) !!}', {
                        real_name: _this.$refs.realNameEditComponent.value
                    }, function () {
                        _this.realNameEdit = false;
                        _this.real_name = _this.$refs.realNameEditComponent.value;
                        _this.$refs.realNameEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.realNameEditComponent.savedButtonEnabled = true;
                    });
                },
                editMobile: function () {
                    this.mobileEdit = true;
                },
                onSavedMobileFunction: function () {
                    var _this = this;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateMobile',$player->player_id) !!}', {
                        mobile: _this.$refs.mobileEditComponent.value
                    }, function () {
                        _this.mobileEdit = false;
                        _this.mobile = _this.$refs.mobileEditComponent.value;
                        _this.$refs.mobileEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.mobileEditComponent.savedButtonEnabled = true;
                    });
                },
                editEmail: function () {
                    this.emailEdit = true;
                },
                onSavedEmailFunction: function () {
                    var _this = this;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateEmail',$player->player_id) !!}', {
                        email: _this.$refs.emailEditComponent.value
                    }, function () {
                        _this.emailEdit = false;
                        _this.email = _this.$refs.emailEditComponent.value;
                        _this.$refs.emailEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.emailEditComponent.savedButtonEnabled = true;
                    });
                },
                editUserLevel: function () {
                    var _this = this;
                    if (!_this.userLevelsOptionsData) {
                        $('#user_base_info_over_lay').toggle();
                        $.fn.winwinAjax.sendFetchAjax('{!! route('Carrier.allPlayerLevels') !!}', {}, function (resp) {
                            $('#user_base_info_over_lay').toggle();
                            var data = [];
                            $.each(resp.data, function (index, element) {
                                data.push({value: element.id, text: element.level_name})
                            });
                            _this.userLevelsOptionsData = data;
                            _this.userLevelEdit = true;
                        }, function () {
                            $('#user_base_info_over_lay').toggle();
                        })
                    } else {
                        this.userLevelEdit = true;
                    }
                },
                onSavedUserLevelFunction: function () {
                    var _this = this;
                    var value = _this.$refs.userLevelEditComponent.selectValue();
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateLevel',$player->player_id) !!}', {
                        level_id: value
                    }, function () {
                        _this.userLevelEdit = false;
                        var options = _this.$refs.userLevelEditComponent.selectOptions;
                        $.each(options, function (index, element) {
                            if (element.value == value) {
                                _this.userLevel = element.text;
                            }
                        });
                        _this.$refs.userLevelEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.userLevelEditComponent.savedButtonEnabled = true;
                    });
                },
                editUserAgent: function () {
                    var _this = this;
                    if (!_this.userAgentsOptionsData) {
                        $('#user_base_info_over_lay').toggle();
                        $.fn.winwinAjax.sendFetchAjax('{!! route('Carrier.allAgents') !!}', {}, function (resp) {
                            $('#user_base_info_over_lay').toggle();
                            var data = [];
                            $.each(resp.data, function (index, element) {
                                data.push({value: element.id, text: element.username + (element.realname ? ( '(' +element.realname + ')'): '')})
                            });
                            _this.userAgentsOptionsData = data;
                            _this.userAgentEdit = true;
                        }, function () {
                            $('#user_base_info_over_lay').toggle();
                        })
                    } else {
                        this.userAgentEdit = true;
                    }
                },
                onSavedUserAgentFunction: function () {
                    var _this = this;
                    var value = _this.$refs.userAgentEditComponent.selectValue();
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateAgent',$player->player_id) !!}', {
                        agent_id: value
                    }, function () {
                        _this.userAgentEdit = false;
                        var options = _this.$refs.userAgentEditComponent.selectOptions;
                        $.each(options, function (index, element) {
                            if (element.value == value) {
                                _this.userAgent = element.text;
                            }
                        });
                        _this.$refs.userAgentEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.userAgentEditComponent.savedButtonEnabled = true;
                    });
                },
                editUserInvite: function () {
                    var _this = this;
                    if (!_this.userInvitesOptionsData) {
                        $('#user_base_info_over_lay').toggle();
                        $.fn.winwinAjax.sendFetchAjax('{!! route('Carrier.allPlayers') !!}', {}, function (resp) {
                            $('#user_base_info_over_lay').toggle();
                            var data = [];
                            $.each(resp.data, function (index, element) {
                                data.push({value: element.player_id, text: element.user_name + (element.real_name ? ( '(' +element.real_name + ')'): '')})
                            });
                            _this.userInvitesOptionsData = data;
                            _this.userInviteEdit = true;
                        }, function () {
                            $('#user_base_info_over_lay').toggle();
                        })
                    } else {
                        this.userInviteEdit = true;
                    }
                },
                onSavedUserInviteFunction: function () {
                    var _this = this;
                    var value = _this.$refs.userInviteEditComponent.selectValue();
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateInviteUser',$player->player_id) !!}', {
                        user_id: value
                    }, function () {
                        _this.userInviteEdit = false;
                        var options = _this.$refs.userInviteEditComponent.selectOptions;
                        $.each(options, function (index, element) {
                            if (element.value == value) {
                                _this.userInvite = element.text;
                            }
                        });
                        _this.$refs.userInviteEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.userInviteEditComponent.savedButtonEnabled = true;
                    });
                },
                editBirthday: function () {
                    this.birthdayEdit = true;
                },
                onSavedBirthdayFunction: function () {
                    var _this = this,value = _this.$refs.birthdayEditComponent.value;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateBirthday',$player->player_id) !!}', {
                        birthday: value
                    }, function () {
                        _this.birthdayEdit = false;
                        _this.birthday = value;
                        _this.$refs.birthdayEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.birthdayEditComponent.savedButtonEnabled = true;
                    });
                },
                editQQ: function () {
                    this.qqEdit = true;
                },
                onQQFunction: function () {
                    var _this = this,value = _this.$refs.qqEditComponent.value;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateQQ',$player->player_id) !!}', {
                        qq: value
                    }, function () {
                        _this.qqEdit = false;
                        _this.qq = value;
                        _this.$refs.qqEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.qqEditComponent.savedButtonEnabled = true;
                    });
                },
                editWechat: function () {
                    this.wechatEdit = true;
                },
                onWechatFunction: function () {
                    var _this = this,value = _this.$refs.wechatEditComponent.value;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('players.updateWechat',$player->player_id) !!}', {
                        wechat: value
                    }, function () {
                        _this.wechatEdit = false;
                        _this.wechat = value;
                        _this.$refs.wechatEditComponent.savedButtonEnabled = true;
                    }, function () {
                        _this.$refs.wechatEditComponent.savedButtonEnabled = true;
                    });
                },
            }
        })

    })
</script>