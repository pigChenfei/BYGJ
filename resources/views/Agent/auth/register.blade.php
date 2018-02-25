@extends('Web.fronts.layouts.app')
@section('css')
    <link rel="stylesheet" href="{!! asset('./agent-data/lib/jedate/skin/jedate.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('./agent-data/css/register.css') !!}"/>
@endsection
@section('script')
    <script src="{!! asset('./agent-data/lib/jedate/jquery.jedate.js') !!}"></script>
    <script src="{!! asset('./agent-data/js/register.js') !!}"></script>
    <script>
        $(function(){
            $("#type").change(function(){
                var objectModel = {};
                var   value = $(this).val();
                var   type = $(this).attr('id');
                objectModel[type] =value;
                $.ajax({
                    url:"{!! route('dataAgentLevel') !!}", //你的路由地址
                    type:"post",
                    dataType:"json",
                    data:objectModel,
                    timeout:30000,
                    success:function(data){
                        $("#agent_level_id").empty();
                        var count = data.length;
                        var i = 0;
                        var b="";
//                    b+="<option value=''>"+"请选择..."+"</option>";
                        for(i=0;i<count;i++){
                            b+="<option value='"+data[i].id+"'>"+data[i].level_name+"</option>";
                        }
                        $("#agent_level_id").append(b);

                    }
                });
            });
        })
    </script>
@endsection


@section('content')
<!--页面主内容-->
<main id='main_content'>
    <div class="container">
        <div>
            <h2>代理注册</h2>
            <span>（请填写以下表格，带*项目为必填项目）</span>
        </div>
        <form class="form-horizontal" role="form" method="post" id="jsForm">
            {!! csrf_field() !!}
            <!--[if IE]>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <![endif]-->

            <div class="form-group">
                <label for="account" class="col-sm-2 control-label"><span>*</span>账号</label>
                <div class="col-md-10 col-sm-10 col-xs-10 col-xxs-10">
                    <input type="text" class="form-control" name="username" id="account" placeholder="请输入账号" required data-rule-account='true' data-msg-required='请输入账号' data-msg-account='账号格式不正确'>
                </div>
                <p class="input_tips col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请输入6-16个字符，仅可输入英文字母</p>
            </div>

            <div class="form-group">
                <label for="pwd" class="col-sm-2 control-label"><span>*</span>登录密码</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="password" class="form-control" name="password" id="password" placeholder="请输入登录密码" required data-rule-pwd='true' data-msg-required='请输入密码' data-msg-pwd='密码格式不正确'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请输入6-20个字符，必须包含字母和数字的组合</p>
            </div>

            <div class="form-group">
                <label for="repwd" class="col-sm-2 control-label"><span>*</span>确认密码</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="password" class="form-control"  id="repwd" placeholder="请再次输入登录密码" required equalTo='#password' data-msg-required='请确认密码' data-msg-equalTo='两次密码不一致'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请确认您的登录密码</p>
            </div>

            @if(($agentRegisterConf->agent_realname_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="username" class="col-sm-2 control-label"><span>*</span>真实姓名</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="real_name" id="username" placeholder="请输入真实姓名" required data-rule-username='true' data-msg-required='请输入真实姓名' data-msg-username='必须与取款银行卡姓名一致'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">必须与取款银行卡姓名一致</p>
            </div>
            @elseif(($agentRegisterConf->agent_realname_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="username" class="col-sm-2 control-label">真实姓名</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="real_name" id="username" placeholder="请输入真实姓名"  data-rule-username='true' data-msg-required='请输入真实姓名' data-msg-username='必须与取款银行卡姓名一致'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">必须与取款银行卡姓名一致</p>
            </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_birthday_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="birthday" class="col-sm-2 control-label"><span>*</span>出生日期</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="birthday" id="birthday" placeholder="请选择出生日期" required data-rule-date='true' data-msg-required='请选择出生日期' data-msg-date='请选择出生日期' autocomplete="off">
                </div>
            </div>
            @elseif(($agentRegisterConf->agent_birthday_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
                <div class="form-group">
                    <label for="birthday" class="col-sm-2 control-label">出生日期</label>
                    <div class="col-md-10 col-sm-10 col-xs-10">
                        <input type="text" class="form-control" name="birthday" id="birthday" placeholder="请选择出生日期"  data-rule-date='true' data-msg-required='请选择出生日期' data-msg-date='请选择出生日期' autocomplete="off">
                    </div>
                </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_email_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label"><span>*</span>电子邮箱</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="email" class="form-control" name="email" id="email" placeholder="请务必输入有效的邮箱地址" required data-rule-email='true' data-msg-required='请输入有效的邮箱地址' data-msg-date='邮箱格式不正确'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">以便我们联系开通合营代理事宜</p>
            </div>
            @elseif(($agentRegisterConf->agent_email_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">电子邮箱</label>
                    <div class="col-md-10 col-sm-10 col-xs-10">
                        <input type="email" class="form-control" name="email" id="email" placeholder="请务必输入有效的邮箱地址"  data-rule-email='true' data-msg-required='请输入有效的邮箱地址' data-msg-date='邮箱格式不正确'>
                    </div>
                    <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">以便我们联系开通合营代理事宜</p>
                </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_phone_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="tel" class="col-sm-2 control-label"><span>*</span>手机号</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="mobile" id="tel" placeholder="请输入手机号" required data-rule-phone='true' data-msg-required='手机号必填' data-msg-phone='手机号码格式不正确'>
                </div>
            </div>
            @elseif(($agentRegisterConf->agent_phone_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="tel" class="col-sm-2 control-label">手机号</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="mobile" id="tel" placeholder="请输入手机号"  data-rule-phone='true' data-msg-required='手机号必填' data-msg-phone='手机号码格式不正确'>
                </div>
            </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_qq_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="qq" class="col-sm-2 control-label"><span>*</span>QQ</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="qq" id="qq" placeholder="请输入QQ号" required data-rule-qq='true' data-msg-required='qq号必填' data-msg-qq='qq号格式不正确'>
                </div>
            </div>
            @elseif(($agentRegisterConf->agent_qq_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="qq" class="col-sm-2 control-label">QQ</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="qq" id="qq" placeholder="请输入QQ号"  data-rule-qq='true' data-msg-required='qq号必填' data-msg-qq='qq号格式不正确'>
                </div>
            </div>
            @else

            @endif
            <!--[if IE]>
            </div>
            <![endif]-->
            <!--[if IE]>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <![endif]-->
            @if(($agentRegisterConf->agent_wechat_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="wechat" class="col-sm-2 control-label"><span>*</span>微信</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="wechat" id="wechat" placeholder="请输入微信号" required data-rule-wechat='true' data-msg-required='微信号必填' data-msg-wechat='微信号格式不正确'>
                </div>
            </div>
            @elseif(($agentRegisterConf->agent_wechat_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="wechat" class="col-sm-2 control-label">微信</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="wechat" id="wechat" placeholder="请输入微信号"  data-rule-wechat='true' data-msg-required='微信号必填' data-msg-wechat='微信号格式不正确'>
                </div>
            </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_skype_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="skype" class="col-sm-2 control-label"><span>*</span>skype</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="skype" id="skype" placeholder="请输入skype" required data-rule-skype='true' data-msg-required='skype必填' data-msg-skype='skype账号格式不正确'>
                </div>
                <div class="error_tips col-lg-5 col-md-5 col-sm-5">
                    <span class="glyphicon glyphicon-remove-sign"></span>skype格式不正确
                </div>
            </div>
            @elseif(($agentRegisterConf->agent_skype_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="skype" class="col-sm-2 control-label">skype</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="skype" id="skype" placeholder="请输入skype"  data-rule-skype='true' data-msg-required='skype选填' data-msg-skype='skype账号格式不正确'>
                </div>
                <div class="error_tips col-lg-5 col-md-5 col-sm-5">
                    <span class="glyphicon glyphicon-remove-sign"></span>skype格式不正确
                </div>
            </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_type_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="agentways" class="col-sm-2 control-label"><span>*</span>代理类型</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <?php $typeDic = \App\Models\CarrierAgentLevel::typeMeta() ?>
                    <select name="" id="type" style="width: 200px;height: 34px;color: black;">
                        @foreach($typeDic as $key => $value)
                        <option value="{!! $key !!}">{!! $value !!}</option>
                        @endforeach
                    </select>

                    <select name="agent_level_id" id="agentways" style="width: 200px;height: 34px;color: black;">
                        @if(isset($carrierAgentLevelName))
                            @foreach($carrierAgentLevelName as $key => $value)
                        <option value="{!! $value->id !!}">{!! $value->level_name !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <p class="input_tips col-lg-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请选择您的代理模式（一旦选定，不可修改）</p>
            </div>

            @elseif(($agentRegisterConf->agent_type_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="agentways" class="col-sm-2 control-label">代理类型</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <?php $typeDic = \App\Models\CarrierAgentLevel::typeMeta() ?>
                    <select name="" id="type" style="width: 200px;height: 34px;color: black;">
                        @foreach($typeDic as $key => $value)
                        <option value="{!! $key !!}">{!! $value !!}</option>
                        @endforeach
                    </select>

                    <select name="agent_level_id" id="agent_level_id" style="width: 200px;height: 34px;color: black;">
                        @if(isset($carrierAgentLevelName))
                            @foreach($carrierAgentLevelName as $key => $value)
                                <option value="{!! $value->id !!}">{!! $value->level_name !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <p class="input_tips col-lg-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请选择您的代理模式（一旦选定，不可修改）</p>
            </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_promotion_url_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="website" class="col-sm-2 control-label"><span>*</span>推广网址</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="promotion_url" id="website" placeholder="请输入您的推广网址" required data-rule-site='true' data-msg-required='推广网址必填' data-msg-site='网址格式不正确'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请输入您的推广网址</p>
            </div>
            @elseif(($agentRegisterConf->agent_promotion_url_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="website" class="col-sm-2 control-label">推广网址</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="promotion_url" id="website" placeholder="请输入您的推广网址" data-rule-site='true' data-msg-required='推广网址必填' data-msg-site='网址格式不正确'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">请输入您的推广网址</p>
            </div>
            @else

            @endif

            @if(($agentRegisterConf->agent_promotion_idea_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED ) == \App\Models\Conf\CarrierDashLoginConf::IS_REQUIRED)
            <div class="form-group">
                <label for="promotetype" class="col-sm-2 control-label"><span>*</span>邀请介绍</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <textarea name="promotion_notion" rows="3" style="width: 200px;color: black;" placeholder="请填写您的邀请介绍" required data-rule-introduce='true' data-msg-required='邀请介绍必填' data-msg-introduce='邀请介绍20-100个字符'></textarea>
                </div>
            </div>
            @elseif(($agentRegisterConf->agent_promotion_idea_conf_status & \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY ) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
            <div class="form-group">
                <label for="promotetype" class="col-sm-2 control-label">邀请介绍</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <textarea name="promotion_notion" rows="3" style="width: 200px;color: black;" placeholder="请填写您的邀请介绍"  data-rule-introduce='true' data-msg-required='邀请介绍必填' data-msg-introduce='邀请介绍20-100个字符'></textarea>
                </div>
            </div>
            @else

            @endif

            <div class="form-group">
                <label for="sponsor" class="col-sm-2 control-label">邀请码</label>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <input type="text" class="form-control" name="promotion_code" id="sponsor" placeholder="请填写邀请码" minlength='6' maxlength="6" data-rule-number='true' data-msg-minlength='邀请码格式不正确' data-msg-number='邀请码只能是数字'>
                </div>
                <p class="input_tips col-lg-12 col-md-12 col-xs-12 col-sm-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">非必填项</p>
            </div>

            <div class="form-group">
                <label for="refercode" class="col-sm-2 control-label"><span>*</span>验证码</label>
                <div class="col-md-10 col-sm-10 col-xs-10" id="div_refercode">
                    <input type="text" class="form-control" name="refercode" id="refercode" placeholder="请输入验证码" required style="width: 120px;float: left;" data-rule-refercode='true' data-msg-required='验证码必填' data-msg-refercode='验证码输入错误'>
                    <span id="identify_code">123456</span>
                </div>
            </div>

            <div class="checkbox col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                <label>
                    <input type="checkbox" id="checked" checked>我已同意并阅读<a href="">“合营和条件”</a>
                </label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <input type="submit" value="提交申请" id="btn_submit" class="btn btn-block btn-info col-lg-offset-2 col-md-offset-2" style="margin-top: 20px;width: 200px;">
            </div>
            <!--[if IE]>
            </div>
            <![endif]-->
        </form>
    </div>
</main>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><img src="{!! asset('./agent-data/img/logo@2x.png') !!}"/></h4>
            </div>
            <div class="modal-body"><i class="glyphicon glyphicon-ok-sign"></i>代理账号注册成功!</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>
@endsection







