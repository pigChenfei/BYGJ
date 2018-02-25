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
        <!--手机号-->
            <article class="mobile">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">邮箱修改</h4>
                    <!--已有绑定手机-->
                    @if(\WinwinAuth::memberUser()->email)
                    <div class="memb-box text-center" style="text-align:center;">
                        <div class="phonenum-box">邮箱账号：<i class="phonenum">{{substr_replace(\WinwinAuth::memberUser()->email,'****',3,4)}}</i></div>
                    </div>
                    @endif
                    <!--未绑定手机号-->
                    <div class="memb-box text-center cpwd-phone">
                        <div class="form-inline">
                            <label for="enter-phone" class="enter-phone"></label>
                            <input type="text" id="enter-phone" autocomplete="off" class="form-control enter-phone-test" placeholder="请输入绑定邮箱账号"/>
                            <!--错误提示-->
                            <div class="warning">
                            </div>
                        </div>
                        <div class="form-inline">
                            <label for="enter-phone" class="msg-code" ></label>
                            <input type="text" id="msgcode" autocomplete="off" class="form-control" placeholder="邮箱验证码"/>
                            <button class="btn btn-warning getmsg code" data-yanzheng="bangding">获取验证码</button>
                            <div class="warning">
                            </div>
                        </div>
                    </div>
                    <div class="text-center"><button class="btn btn-warning mb-20 phone-change-sure">提交</button></div>
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{!! asset('./app/template_one/js/account-security.js') !!}"></script>
@endsection