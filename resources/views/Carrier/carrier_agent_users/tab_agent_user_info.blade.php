<div class="box" style="height: 600px;border-top: none;box-shadow: none;">
    <div class="col-sm-12 text-left">
        <div class="form-group">
            <h3>基本信息</h3>
            <div style=" text-align: right;">
                <a onclick="
                        $('#user_financial_info_over_lay').toggle();
                        var _me = this;
                        $.fn.winwinAjax.buttonActionSendAjax(
                            _me,
                            '{!! route('carrierAgentUsers.editAgentType') !!}',
                            { id: '{!! $carrierAgentUser->id !!}'},
                            function (html) {
                                $('#user_financial_info_over_lay').toggle();
                                $('#editAddModal').html(html).modal('show');
                            },
                            function () {
                                $('#user_financial_info_over_lay').toggle();
                            },
                            'GET',
                            {
                                dataType:'html'
                            })
                        " class="btn btn-primary btn-xs" style="margin-left: 5px"><i class="glyphicon glyphicon-edit"><h5>调整代理类型</h5></i></a>
                </div>
        </div>
    </div>
    <table class="table table-responsive  table-bordered table-hover dataTable" id="agent_info_edit">
        <tr role="row">
            <th>账户余额</th>
            <td colspan="3" style="text-align: left;">
                {!! $carrierAgentUser->amount !!}
                <a onclick="
                        $('#user_financial_info_over_lay').toggle();
                        var _me = this;
                        $.fn.winwinAjax.buttonActionSendAjax(
                            _me,
                            '{!! route('carrierAgentUsers.agentAmount') !!}',
                            { id: '{!! $carrierAgentUser->id !!}'},
                            function (html) {
                                $('#user_financial_info_over_lay').toggle();
                                $('#editAddModal').html(html).modal('show');
                            },
                            function () {
                                $('#user_financial_info_over_lay').toggle();
                            },
                            'GET',
                            {
                                dataType:'html'
                            })
                        " class="btn btn-primary btn-xs" style="margin-left: 5px">调整账户余额</a></td>
            <th>会员礼金</th>
            <td colspan="3" style="text-align: left;">
                {!! $carrierAgentUser->experience_amount !!}
                <a onclick="
                        $('#user_financial_info_over_lay').toggle();
                        var _me = this;
                        $.fn.winwinAjax.buttonActionSendAjax(
                            _me,
                            '{!! route('carrierAgentUsers.experienceAmount') !!}',
                            { id: '{!! $carrierAgentUser->id !!}'},
                            function (html) {
                                $('#user_financial_info_over_lay').toggle();
                                $('#editAddModal').html(html).modal('show');
                            },
                            function () {
                                $('#user_financial_info_over_lay').toggle();
                            },
                            'GET',
                            {
                                dataType:'html'
                            })
                        " class="btn btn-primary btn-xs" style="margin-left: 5px">调整会员礼金</a></td>
        </tr>   
        <tr role="row">
            <th>账号</th>
            <td style="width: 14%">{!! $carrierAgentUser->username !!}</td>
            <th>真实姓名</th>
            <td style="width: 14%"><edit_input_component ref="realNameEditComponent" v-if="realNameEdit" :value="realname" v-on:on-saved-click="onSavedUserNameFunction"></edit_input_component> <div v-else><span>@{{ realname }}</span> <a class="fa fa-edit" style="float: right;color: #ff811e;" v-on:click="editUserName"></a></div></td>
            <th>邮箱</th>
            <td style="width: 14%"><edit_input_component ref="emailEditComponent" v-if="emailEdit" :value="email" v-on:on-saved-click="onSavedEmailFunction"></edit_input_component> <div v-else><span>@{{ email }}</span> <a class="fa fa-edit" style="float: right;color: #ff811e;" v-on:click="editEmail"></a></div></td>
            <th>邀请码</th>
            <td style="width: 14%">{!! $carrierAgentUser->promotion_code !!}</td>
        </tr>
        <tr role="row">
            <th>代理类型</th>
            <td style="width: 14%">{!! $carrierAgentLevelType !!}</td>
            <th>代理名称</th>
            <td style="width: 14%">{!! $carrierAgentLevel->level_name !!}</td>
            <th>注册时间</th>
            <td style="width: 14%">{!! $carrierAgentUser->created_at !!}</td>
            <th>注册IP</th>
            <td style="width: 14%">{!! $carrierAgentUser->register_ip !!}</td>
        </tr>
        <tr role="row">
            <th>上次登录时间</th>
            <td style="width: 14%">{!! $carrierAgentUser->login_time !!}</td>
            <th>skype</th>
            <td style="width: 14%">{!! $carrierAgentUser->skype !!}</td>
            <th>手机号</th>
            <td style="width: 14%"><edit_input_component ref="mobileEditComponent" v-if="mobileEdit" :value="mobile" v-on:on-saved-click="onSavedMobileFunction"></edit_input_component> <div v-else><span>@{{ mobile }}</span> <a class="fa fa-edit" style="float: right;color: #ff811e;" v-on:click="editMobile"></a></div></td>
            <th>QQ</th>
            <td style="width: 14%">{!! $carrierAgentUser->qq !!}</td>
        </tr>
    </table>
    
    <div class="col-sm-12 text-left">
        <div class="form-group">
            <h3>合营链接</h3>
        </div>
    </div>
    
    <table class="table table-responsive  table-bordered table-hover dataTable">
        <tbody>
            @foreach($carrierAgentDomain as $agentDomain)
                <tr role="row">
                    <td colspan="8" style=" text-align: left;">{!! $agentDomain->website !!}?promotion_code={!! $carrierAgentUser->promotion_code !!}</td>
                </tr>
            @endforeach    
    </table>
    
    
    <div class="overlay" id="user_base_info_over_lay" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
        <h5 style="text-align: center;position: absolute;top: 57%;width: 100%;">正在获取数据...</h5>
    </div>
</div>

<script>
    $(function(){
        Vue.component('edit_input_component',{
            template:   '<div class="input-group input-group-sm" style="width: 200px;margin:0 auto">' +
                        '<input type="text" class="form-control" v-model="value">' +
                                '<span class="input-group-btn">' +
                                    '<button v-bind:disabled="!savedButtonEnabled" v-on:click="onSavedClick" type="button" class="btn btn-primary btn-flat">@{{ savedButtonEnabled ? "保存" : "保存中..." }}</button>' +
                                '</span>' +
                        '</div>',
            data: function(){
                return{
                    savedButtonEnabled:true,
                    value: this.value
                }
            },
            methods:{
               onSavedClick:function () {
                   this.savedButtonEnabled = false;
                   this.$emit('on-saved-click')
               }
            },
            props:['value']
        });
        
        
        var agentInfoVueModel = new Vue({
            el:'#agent_info_edit',
            data:{
                realNameEdit:false,
                realname:'{!! $carrierAgentUser->realname !!}',
                
                mobileEdit:false,
                mobile:'{!! $carrierAgentUser->mobile !!}',
                
                emailEdit:false,
                email:'{!! $carrierAgentUser->email !!}',
            },
            methods:{
                editUserName:function () {
                    this.realNameEdit = true;
                },
                onSavedUserNameFunction:function () {
                    var _this = this;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('carrierAgentUsers.updateRealName',$carrierAgentUser->id) !!}',{
                        realname: _this.$refs.realNameEditComponent.value
                    },function () {
                        _this.realNameEdit = false;
                        _this.realname = _this.$refs.realNameEditComponent.value;
                        _this.$refs.realNameEditComponent.savedButtonEnabled = true;
                    },function () {
                        _this.$refs.realNameEditComponent.savedButtonEnabled = true;
                    });
                },
                
                editMobile:function () {
                    this.mobileEdit = true;
                },
                onSavedMobileFunction:function () {
                    var _this = this;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('carrierAgentUsers.updateTelephone',$carrierAgentUser->id) !!}',{
                        mobile: _this.$refs.mobileEditComponent.value
                    },function () {
                        _this.mobileEdit = false;
                        _this.mobile = _this.$refs.mobileEditComponent.value;
                        _this.$refs.mobileEditComponent.savedButtonEnabled = true;
                    },function () {
                        _this.$refs.mobileEditComponent.savedButtonEnabled = true;
                    });
                },
                
                editEmail:function () {
                    this.emailEdit = true;
                },
                onSavedEmailFunction:function () {
                    var _this = this;
                    $.fn.winwinAjax.sendUpdateAjax('{!! route('carrierAgentUsers.updateEmail',$carrierAgentUser->id) !!}',{
                        email: _this.$refs.emailEditComponent.value
                    },function () {
                        _this.emailEdit = false;
                        _this.email = _this.$refs.emailEditComponent.value;
                        _this.$refs.emailEditComponent.savedButtonEnabled = true;
                    },function () {
                        _this.$refs.emailEditComponent.savedButtonEnabled = true;
                    });
                },
               
            }
        })

    })
</script>
