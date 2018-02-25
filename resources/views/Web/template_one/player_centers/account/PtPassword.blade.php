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
        <!--PT客户端密码修改-->
            <article class="change-ptpwd">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">PT客户端密码修改</h4>
                    <div class="memb-box text-center cpwd-normal">
                        {{--<div class="form-inline">--}}
                            {{--<label for="logpwd">登录密码：</label>--}}
                            {{--<input type="password" id="logpwd" class="form-control" placeholder="请输入您的登录密码"/>--}}
                        {{--</div>--}}
                        <div class="form-inline">
                            <label for="setptpwd" class="setptpwd ptusername"></label>
                            <input type="text" id="account" class="form-control pass-game2" value="{{$gameaccount}}" readonly />
                            <div class="warning">
                            </div>
                        </div>
                        <div class="form-inline">
                            <label for="setptpwd" class="setptpwd"></label>
                            <input type="password" id="setptpwd" class="form-control pass-game2" placeholder="请输入PT新密码"/>
                            <div class="warning">
                            </div>
                        </div>
                        <div class="form-inline">
                            <label for="regptpwd" class="regptpwd"></label>
                            <input type="password" id="regptpwd" class="form-control pass-game3" placeholder="确认PT新密码"/>
                            <!--错误提示-->
                            <div class="warning">
                            </div>
                        </div>
                    </div>
                    <div class="text-center"><button class="btn btn-warning mb-20 account-pt-sure">提交</button></div>
                    <div class="memb-box clearfix memb-bottomtip" style="position: unset">
                        <div class="table">
                            <div class="table-cell" style="width: 80px;vertical-align: top;">
                                温馨提示：
                            </div>
                            <div class="table-cell">
                                1、目前PT客户端仅支持安卓系统。<br />
                                2、登录PT客户端，需要在游戏账号前加“k8s”。 <br />
                                3、您可以将PT客户端密码，与您登录博赢国际网站时所用的密码保持一致。
                            </div>
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