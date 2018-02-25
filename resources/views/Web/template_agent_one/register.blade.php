@extends('Web.'.\WinwinAuth::currentWebCarrier()->template_agent.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./agent-data/lib/jedate/skin/jedate.css') !!}" />
    <style>
        .check-rule > div.msg.font-red.f14 {
            margin-left: 152px;
        }
    </style>
@endsection

@section('content')
    <section class="banner-index" style="background-image:url({!! $webConf?$webConf->imageAsset():asset('./app/template_one/img/agency/banner-joinus.jpg') !!});"></section>

    <section class="joinus-main">
        <form role="form" method="post" id="jsForm">
            {{--{{csrf_field()}}--}}
        <h4><span class="img-circle"></span>代理注册<span>（请填写以下表格，带<i> ✱ </i>项目为必填项目）</span></h4>
        <div class="form-box">
            <div class="form-inline clearfix">
                <label ><i class="font-red">✱ </i>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
                <input type="text" class="form-control"  name="username" placeholder="请输入账号" autocomplete="off" required data-rule-account='true' data-msg-required='请输入账号' data-msg-account='账号格式不正确'/>
                <span class="tip">请输入4-11个字符，数字或字母组合</span>
                {{--<div class="msg font-red f14">账号格式有误</div>--}}
            </div>
            <div class="form-inline clearfix">
                <label><i class="font-red">✱ </i>登录密码：</label>
                <input type="password" class="form-control" name="password" id="passwords" placeholder="请输入登录密码" autocomplete="off" required data-rule-pwd='true' data-msg-required='请输入密码' data-msg-pwd='密码格式不正确'/>
                <span class="tip">请输入6-20个字符，数字或字母组合</span>
            </div>
            <div class="form-inline clearfix">
                <label for="regpwd"><i class="font-red">✱ </i>确认密码：</label>
                <input type="password" class="form-control" id="regpwd" placeholder="确认密码" autocomplete="off" equalTo="#passwords" required data-msg-required='请输入确认密码' data-msg-equalTo='两次密码不一致'/>
                <span class="tip">请确认您的登录密码</span>
            </div>
        </div>


        <div class="form-box">

            <div class="form-inline clearfix">
                <label for="realname"><i class="font-red">✱ </i>真实姓名：</label>
                <input type="text" class="form-control" id="realname" name="realname" placeholder="请输入真实姓名" autocomplete="off" required data-rule-username='true' data-msg-required='请输入真实姓名' data-msg-username='姓名输入有误'/>
                <span class="tip">必须与取款银行卡姓名一致</span>
            </div>

            <div class="form-inline clearfix">
                <label for="email"><i class="font-red">✱ </i>电子邮箱：</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="请务必输入有效的邮箱地址" autocomplete="off" required data-rule-email='true' data-msg-required='请输入有效的邮箱地址' data-msg-date='邮箱格式不正确'/>
                <span class="tip">以便我们联系开通合营代理事宜</span>
            </div>
            <div class="form-inline clearfix">
                <label for="realname"><i class="font-red">✱ </i>手&nbsp;&nbsp;机&nbsp;&nbsp;号：</label>
                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="请输入手机号" autocomplete="off" required data-rule-phone='true' data-msg-required='手机号必填' data-msg-phone='手机号码格式不正确'/>
                <span class="tip">请务必填写，方便我们联系</span>
            </div>
            <div class="form-inline clearfix">
                <label for="realname"><i class="font-red">✱ </i>联系方式：</label>
                <div class="dropdown types" style="display: inline-block;">
                    <button class="btn dropdown-toggle" id="dn-draw" data-toggle="dropdown"/>
                    QQ
                    </button>
                    <ul class="dropdown-menu clearfix" role="menu" aria-labelledby="dn-draw">
                        <li role="presentation" class="content-type" data-value="qq">
                            <a role="menuitem" tabindex="-1" href="javascript:void(0)">QQ</a>
                        </li>
                        <li role="presentation" class="content-type" data-value="wechat">
                            <a role="menuitem" tabindex="-1" href="javascript:void(0)">微信</a>
                        </li>
                        <li role="presentation" class="content-type" data-value="skype">
                            <a role="menuitem" tabindex="-1" href="javascript:void(0)">Skype</a>
                        </li>
                    </ul>
                </div>
                <input type="text" class="form-control sm" name="qq" id="contact-num" autocomplete="off" required data-msg-required='联系方式必填' />
                <span class="tip">需要填写一个联系方式</span>
            </div>
        </div>
        <div class="form-box">
            <div class="form-inline clearfix">
                <label for="realname">代理类型：</label>
                <div class="dropdown types">
                    <button class="btn dropdown-toggle" id="dn-draw1" data-toggle="dropdown"/>
                    请选择
                    </button>
                    <ul class="dropdown-menu clearfix" role="menu" aria-labelledby="dn-draw1">
                        <?php
                        $typeDic = \App\Models\CarrierAgentLevel::typeMeta()?>
                            @foreach($typeDic as $key => $value)
                                <li role="presentation" class="agent_level_one" data-value="{!! $key !!}">
                                    <a role="menuitem" tabindex="-1" href="javascript:void(0)">{!! $value !!}</a>
                                </li>
                            @endforeach
                    </ul>
                </div>
                <div class="dropdown" id="erjidaili" style="display: none">
                    <button class="btn dropdown-toggle" id="dn-draw2" data-toggle="dropdown"/>
                    --请选择--
                    </button>
                    <ul class="dropdown-menu clearfix" role="menu" id="agent_level_two" aria-labelledby="dn-draw2">
                    </ul>
                    <input type="hidden" name="agent_level_id" value="0">
                </div>
                <span class="tip">请选择您的代理模式<i>（一旦选定，不可修改）</i></span>
            </div>
            <div class="form-inline clearfix">
                <label for="promotion_url">邀请域名：</label>
                <input type="text" class="form-control" name="promotion_url" id="promotion_url" autocomplete="off" placeholder="推广网址,不包含http://及https://" />
            </div>
            <div class="form-inline clearfix">
                <label for="promotion_notion">邀请介绍：</label>
                <textarea type="text" class="form-control" name="promotion_notion" rows="6" autocomplete="off" id="promotion_notion" placeholder="请输入您的邀请介绍" ></textarea>
            </div>
            @if($is_pro)
            <div class="form-inline clearfix">
                <label for="recommend-code">邀&nbsp;&nbsp;请&nbsp;&nbsp;码：</label>
                <input type="text" class="form-control" name="promotion_code" id="recommend-code" autocomplete="off" placeholder="请输入邀请码" />
            </div>
            @endif
            <div class="form-inline clearfix">
                <label for="authcode"><i class="font-red">✱ </i>验&nbsp;&nbsp;证&nbsp;&nbsp;码：</label>
                <input type="text" class="form-control" name="refercode" id="authcode" autocomplete="off" placeholder="请输入验证码" required style="width: 173px;float: left;" data-rule-refercode='true' data-msg-required='验证码必填' data-msg-refercode='验证码输入错误'/>
                <div class="authcode-wrap" style="height: 34px"><img src="/captcha" onclick="this.src='/captcha?r='+Math.random();"/></div>
            </div>
            <div class="form-inline clearfix check-rule">
                <label></label>
                <input type="checkbox" id="checkrule" value="1" name="checkbox" style="min-width: auto;width: auto; margin-right: 5px;" checked required data-msg-required='合营条件必选'/>
                <span>我已同意并阅读&nbsp;&nbsp;<a href="javascript:void(0)" style="color: #d8a659!important;">“ 合营条件 ”</a></span>
            </div>
            <div class="form-inline text-center" style="padding: 0;margin-top: 20px;">
                <button class="btn btn-warning" style="width: 120px;">注册</button>
            </div>
        </div>
        </form>
    </section>
@endsection

@section('script')
    <script src="{!! asset('./agent-data/lib/jedate/jquery.jedate.js') !!}"></script>
    <script src="{!! asset('./agent-data/lib/jquery.validate.js') !!}"></script>
    <script src="{!! asset('./agent-data/template_agent_one/js/register.js') !!}"></script>
@endsection