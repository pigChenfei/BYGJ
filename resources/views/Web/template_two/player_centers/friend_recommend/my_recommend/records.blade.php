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
        <!--推荐好友-->
            <article class="recommend-friends">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">我要邀请</h4>
                    <div class="memb-box">
                        <div class="form-inline">
                            <div class="form-inline">
                                <label for="pay-money-qq">邀&nbsp;&nbsp;请&nbsp;&nbsp;码：</label>
                                <input type="text" style="width: 300px;" class="form-control recommend-code" id="recommend-code" readonly value="{!! $player->referral_code !!}">
                                <button class="btn btn-warning" onclick="jsCopy('recommend-code')" style="margin-left:18px;">
                                    点击复制
                                </button>
                                <div class="tooltip right" role="tooltip" style="top:6px;">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">复制成功！</div>
                                </div>
                            </div>
                            <div class="form-inline">
                                <label for="pay-money-qq">邀请链接：</label>
                                <input type="text" style="width: 300px;" class="form-control" id="recommend-link" readonly value="{!! $player->recommend_url !!}">
                                <button class="btn btn-warning" onclick="jsCopy('recommend-link')" style="margin-left:18px;">
                                    点击复制
                                </button>
                                <div class="warning" style="margin-left:115px;margin-top:10px;">
                                    <span>已成功邀请<i class="reced-num">{!! $player->invite_player_count !!}</i>个好友，获得奖金<i class="bonus">{!! $player->totalBonus !!}</i>元</span>
                                </div>
                                <div class="tooltip right" role="tooltip">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">复制成功！</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="art-tit">直接开户</h4>
                    <div class="memb-box cpwd-normal">
                        <div class="form-inline">
                            <label for="frd-username">账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
                            <input type="text" id="frd-username" class="form-control" placeholder="请输入您的账号"/>
                            <span class="tip frd-username">账号为4-11位字母或数字的组合</span>
                            <div class="warning">
                            </div>
                        </div>
                        <div class="form-inline">
                            <label for="frd-pwd">输入密码：</label>
                            <input type="password" id="frd-pwd" class="form-control" placeholder="请输入您的密码"/>
                            <span class="tip frd-pwd">密码为6-20位字母和数字的组合</span>
                            <div class="warning">
                            </div>
                        </div>
                        <div class="form-inline">
                            <label for="frd-regpwd">确认密码：</label>
                            <input type="password" id="frd-regpwd" class="form-control" placeholder="请再次输入您的密码"/>
                            <div class="warning">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-warning I-go">提交</button>
                        <input type="hidden" name="recommend_player_id" value="{!! $player->player_id !!}">
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function () {

            $(".I-go").on('click',function(e){
                e.preventDefault();
                e.stopPropagation();
                var button = $(this);
                var id = $("input:hidden[name='recommend_player_id']").val();
                var reg3 = /^([a-z0-9]){4,11}$/;
                var user_name =$("#frd-username").val();
                var reg2 = /^([a-z0-9]){6,16}$/;
                var password = $("#frd-pwd").val();
                var confirm_password = $("#frd-regpwd").val();
                if (user_name.trim() == "" || reg3.test(user_name) != true) {
                    alertMessage('.frd-username', '账号为4-11位字母或数字的组合')
                }else if (reg2.test(password) != true) {
                    alertMessage('.frd-pwd', '密码为6-20位字母和数字的组合')
                }else if(password != confirm_password){
                    alertMessage('.frd-regpwd', '两次密码不一致')
                }else {
                    button.removeClass('I-go');
                    $.ajax({
                        type: 'post',
                        url: "{!! route('homes.register') !!}",
                        data: {
                            'recommend_player_id' : id,
                            'user_name' : user_name,
                            'password' : password,
                            'confirm_password' : confirm_password
                        },
                        dataType: 'json',
                        success: function (xhr) {
                            if (xhr.success == true) {
                                layer.msg('开户成功!',{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                                window.location.reload();
                            }
                        },
                        error:function(xhr){
                            button.disabled = false;
                            if(xhr.responseJSON.success == false) {
                                layer.msg(xhr.responseJSON.message,{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                                button.addClass('I-go');
                            }
                        }
                    });
                }
            });
        });
        function jsCopy(ele){
            var e=document.getElementById(ele);//对象是contents
            e.select(); //选择对象
            tag=document.execCommand("Copy"); //执行浏览器复制命令
            if(tag){
                $('#'+ele).parent().find(".tooltip").css('opacity','1');
                setTimeout(function(){
                    $('#'+ele).parent().find(".tooltip").fadeOut(800);
                },1000)
            }
        }
    </script>
@endsection

