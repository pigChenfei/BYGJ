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
            <style>
                .member-container .memb-box.text-center label{
                    width:98px;
                }
            </style>
            <article class="changepwd">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">取款密码修改</h4>
                    <ul class="changepwd-type list-unstyled clearfix">
                        <li class="active account-tab" data-value="changgui"><a href="javascript:void(0)">常规修改</a></li>
                        <li class="account-tab" data-value="shouji"><a href="javascript:void(0)">人工客服</a></li>
                    </ul>
                    <div class="account-password changgui">
                        <div class="memb-box text-center cpwd-normal">
                            <div class="form-inline">
                                <label for="old-pwd">初始取款密码：</label>
                                <input type="password" id="old-pwd" class="form-control pass-age" placeholder="请输入初始取款密码"/>
                                <span class="tip"></span>
                                <div class="warning">
                                </div>
                            </div>
                            <div class="form-inline">
                                <label for="new-pwd">新取款密码：</label>
                                <input type="password" id="new-pwd" class="form-control pass-age2" placeholder="请输入新取款密码"/>
                                <span class="tip">密码为6位数字</span>
                                <div class="warning">
                                </div>
                            </div>
                            <div class="form-inline">
                                <label for="reg-pwd">确认取款密码：</label>
                                <input type="password" id="reg-pwd" class="form-control pass-age3" placeholder="确认取款密码"/>
                                <span class="tip"></span>
                                <div class="warning">
                                </div>
                            </div>
                        </div>
                        <div class="text-center"><button class="btn btn-warning mb-20 changgui-sure" data-type="qukuan">提交</button></div>
                    </div>
                    <div class="account-password shouji" hidden>
                        <div class="memb-box text-center cpwd-phone" style="padding:0;position:relative;">
                            <img src="{!! asset('./app/template_one/img/member/zixun.jpg') !!}" alt="">
                        </div>
                    </div>
                    <input type="hidden" name="player_id" value="{{ \WinwinAuth::memberUser()->player_id }}">
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{!! asset('./app/template_one/js/account-security.js') !!}"></script>
@endsection