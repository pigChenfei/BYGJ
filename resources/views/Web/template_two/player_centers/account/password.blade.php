@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="member-container">
        <div class="member-wrap clearfix">
        @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
        <!--修改密码-->
            <article class="changepwd">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">登录密码修改</h4>
                    {{--<ul class="changepwd-type list-unstyled clearfix">--}}
                        {{--<li class="active account-tab" data-value="changgui"><a href="javascript:void(0)">常规修改</a></li>--}}
                        {{--<li class="account-tab" data-value="shouji"><a href="javascript:void(0)">邮箱验证修改</a></li>--}}
                    {{--</ul>--}}
                    <div class="account-password changgui">
                        <div class="memb-box text-center cpwd-normal">
                            <div class="form-inline">
                                <label for="old-pwd">原&nbsp;&nbsp;密&nbsp;&nbsp;码：</label>
                                <input type="password" id="old-pwd" class="form-control pass-age" placeholder="请输入您的原密码"/>
                                <span class="tip">密码为6-20位字母和数字的组合</span>
                                <div class="warning"></div>
                            </div>
                            <div class="form-inline">
                                <label for="new-pwd">新&nbsp;&nbsp;密&nbsp;&nbsp;码：</label>
                                <input type="password" id="new-pwd" class="form-control pass-age2" placeholder="请输入您的新密码"/>
                                <span class="tip">密码为6-20位字母和数字的组合</span>
                                <div class="warning"></div>
                            </div>
                            <div class="form-inline">
                                <label for="reg-pwd">确认密码：</label>
                                <input type="password" id="reg-pwd" class="form-control pass-age3" placeholder="请再次输入您的新密码"/>
                                <!--错误提示-->
                                <div class="warning">

                                </div>
                            </div>
                        </div>
                        <div class="text-center"><button class="btn btn-warning mb-20 changgui-sure" data-type="denglu">提交</button></div>
                    </div>
                    {{--<div class="account-password shouji" hidden>--}}
                        {{--<div class="memb-box text-center cpwd-phone">--}}
                            {{--<div class="form-inline">--}}
                                {{--<label for="enter-phone" class="enter-phone"></label>--}}
                                {{--<input type="text" id="enter-phone" class="form-control enter-phone-test" placeholder="请输入绑定邮箱账号"/>--}}
                                {{--<!--错误提示-->--}}
                                {{--<div class="warning">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-inline">--}}
                                {{--<label for="msgcode" class="msg-code" ></label>--}}
                                {{--<input type="number" id="msgcode" class="form-control" placeholder="邮箱验证码"/>--}}
                                {{--<button class="btn btn-warning getmsg code" data-yanzheng="yanzheng">获取验证码</button>--}}
                                {{--<!--错误提示-->--}}
                                {{--<div class="warning">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-inline">--}}
                                {{--<label for="new-pwd">新&nbsp;&nbsp;密&nbsp;&nbsp;码：</label>--}}
                                {{--<input type="password" id="new-pwd" class="form-control pass-phone1" placeholder="请输入您的新密码"/>--}}
                                {{--<span class="tip">密码为6-16位字母和数字的组合</span>--}}
                                {{--<div class="warning">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-inline">--}}
                                {{--<label for="reg-pwd">确认密码：</label>--}}
                                {{--<input type="password" id="reg-pwd" class="form-control pass-phone2" placeholder="请再次输入您的新密码"/>--}}
                                {{--<!--错误提示-->--}}
                                {{--<div class="warning">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="text-center"><button class="btn btn-warning mb-20 phone-sure">提交</button></div>--}}
                    {{--</div>--}}
                    <input type="hidden" name="player_id" value="{{ \WinwinAuth::memberUser()->player_id }}">

                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{!! asset('./app/template_one/js/account-security.js') !!}"></script>
@endsection