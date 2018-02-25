@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
    <style>
    .btn-warning {
        background: #a671ff;
        border-color: #a671ff;
    }
    .btn-warning:hover{
        background: #8545f1;
        border-color: #8545f1;
    }
    </style>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="member-container">
        <div class="member-wrap clearfix">
            @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
            <!--基本资料-->
                <article class="basicdata">
                    <div class="art-title"></div>
                    <div class="art-body">
                        <h4 class="art-tit">个人信息</h4>
                        <div class="memb-box text-center cpwd-normal">
                            <div class="form-inline">
                                <input type="hidden" name="player_id" value="{{\WinwinAuth::memberUser()->player_id}}">
                                <label for="username">账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
                                <div style="display: inline-block;margin-left: 18px;vertical-align: middle;color:rgba(0,0,0,.9);">{!! \WinwinAuth::memberUser()->user_name !!}</div>
                            </div>
                            <div class="form-inline level-wrap">
                                <input type="hidden" name="player_id" value="{{\WinwinAuth::memberUser()->player_id}}">
                                <label for="username">等&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;级：</label>
                                <div style="display: inline-block;margin-left: 18px;vertical-align: middle;width:280px;line-height:30px;">
                                    <div style="display: inline-block;vertical-align: middle;color:rgba(0,0,0,.9);">{{\WinwinAuth::memberUser()->playerLevel->level_name}}</div>
                                    <div style="display: inline-block;margin-left:8px;"><img src='{{\WinwinAuth::memberUser()->playerLevel->img?\WinwinAuth::memberUser()->playerLevel->imageAsset():'/app/template_one/img/common/memb-common.png'}}' alt=""> </div>
                                    <a style="color: #a671ff;" class="float-right level-detail" href="javascript:">关于提升等级说明 >
                                        <div class="level-tip">
                                            <div>如何提高会员等级</div>
                                            <div class="table">
                                                <div class="table-cell" style="width:30px;"><img src='{{\WinwinAuth::memberUser()->playerLevel->img?\WinwinAuth::memberUser()->playerLevel->imageAsset():'/app/template_one/img/common/memb-common.png'}}' alt=""></div>
                                                <div class="table-cell">这个一段关于等级说明的测试文本这个一段关于等级说明的测试文本这个一段关于等级说明的测试文本</div>
                                            </div>
                                            <div class="table">
                                                <div class="table-cell" style="width:30px;"><img src='{{\WinwinAuth::memberUser()->playerLevel->img?\WinwinAuth::memberUser()->playerLevel->imageAsset():'/app/template_one/img/common/memb-common.png'}}' alt=""></div>
                                                <div class="table-cell">xxx</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="form-inline">
                                <label for="realname">真实姓名：</label>
                                <input type="text" id="realname" class="form-control" value="{!! \WinwinAuth::memberUser()->real_name !!}" placeholder="请填写真实姓名，预收款银行开户人相同" @if(\WinwinAuth::memberUser()->real_name) disabled @endif />
                                <div class="realname-tip">
                                    <i class="iconfont icon-question"></i><span class="realname-tip-question font-white">&nbsp;为什么要填写真实姓名?</span>
                                    <div class="realname-tip-text">取款银行卡姓名必须与游戏账户真实 姓名一致才可以申请取款，请务必用 真实姓名</div>
                                </div>
                            </div>
                            <div class="form-inline birth-wrap @if(\WinwinAuth::memberUser()->birthday) ready @endif">
                                <label for="birth">出生日期：</label>
                                <!--input 标签添加属性disabled可以禁用日期插件-->
                                <input type="text" class="form-control" id="birth" value="{!! \WinwinAuth::memberUser()->birthday !!}" @if(\WinwinAuth::memberUser()->birthday) disabled @endif />
                                <!--错误提示-->
                                {{-- <div class="warning">
                                    <i class="glyphicon glyphicon-warning-sign font-red"></i>
                                    <span class="tip">错误提示！</span>
                                </div>--}}
                            </div>

                            @if( ($player->registerConf->player_sex_conf_status && \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY) == \App\Models\Conf\CarrierDashLoginConf::IS_DISPLAY)
                                <div class="form-inline choosemale">
                                    <label>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：</label>
                                    <?php
                                    $statusDic = \App\Models\Player::userSex()?>
                                    @foreach($statusDic as $key => $value)
                                        @if(isset($player) && $player instanceof \App\Models\Player && $player->sex ==$key)
                                            <div class="input-box">
                                                <label>{!! $value !!}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            <div class="form-inline">
                                <label for="mobile">手机号码：</label>
                                {{--@if(\WinwinAuth::memberUser()->mobile)--}}
                                <input type="text" class="form-control" value="{!! \WinwinAuth::memberUser()->mobile !!}" name="mobile" id="mobile"/>
                                <div class="realname-tip">
                                    <i class="iconfont icon-question"></i><span class="realname-tip-question font-white">&nbsp;为什么要填写手机号?</span>
                                    <div class="realname-tip-text">存款或取款查账时，我们客服将会通过该手机与您取的联系。</div>
                                </div>
                                {{--<div class="infobox" style="margin-left:10px;"><a href="javascript:" onclick="tools.layer.changephone('12345677412')">修改手机号码</a></div>--}}
                                {{--@else--}}
                                {{--<div class="infobox"><a href="javascript:" onclick="tools.layer.bindphone('12345677412')">绑定手机</a></div>--}}
                                {{--@endif--}}
                            </div>
                            <div class="form-inline">
                                <label for="qq_account">QQ&nbsp;账&nbsp;号：</label>
                                <input type="text" id="qq_account" name="qq_account" class="form-control" value="{!! \WinwinAuth::memberUser()->qq_account !!}"  />
                            </div>
                            <div class="form-inline">
                                <label for="wechat">微信账号：</label>
                                <input type="text" id="wechat" name="wechat" class="form-control" value="{!! \WinwinAuth::memberUser()->wechat !!}"  />
                            </div>
                            <div class="form-inline">
                                <label for="email">邮箱地址：</label>
                                @if(\WinwinAuth::memberUser()->email)
                                <input type="text" class="form-control" value="{!! \WinwinAuth::memberUser()->email !!}" disabled/>
                                <div class="infobox" style="margin-left:10px;"><a href="javascript:" onclick="tools.layer.changeemail('{{\WinwinAuth::memberUser()->email}}')">修改邮箱地址</a></div>
                                @else
                                <div class="infobox"><a href="javascript:" onclick="tools.layer.bindemail()">绑定邮箱</a></div>
                                @endif
                            </div>
                        </div>
                        {{-- @if(empty(\WinwinAuth::memberUser()->birthday) or empty(\WinwinAuth::memberUser()->real_name)) --}}
                            <div class="text-center">
                                <button class="btn btn-warning edit security-true">保存</button>
                            </div>
                        {{-- @endif --}}
                    </div>
                </article>
        </div>
    </section>
@endsection

@section('scripts')
{{--    <script src="{!! asset('./app/template_one/js/account-security.js') !!}"></script>--}}
    <script>
        //时间选择
        $("#birth").datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2
        }) ;

        //保存修改信息
        $(".security-true").on('click',function(e){
            e.preventDefault();
            //alert('qqq') ;
            var button = this;
            var id = $("input:hidden[name='player_id']").val();
            var birthday = $("#birth").val();
            var userName =$("#realname").val();
            var reg3 =/^[\u4e00-\u9fa5]{2,40}$/;
            var mobile=$('#mobile').val();
            var qq_account = $('#qq_account').val();
 			var wechat_account = $('#wechat').val();
			 
            var data = {
                'player_id':id,
                'real_name':userName,
                'birthday':birthday,
                'mobile':mobile,
                'qq_account':qq_account,
                'wechat_account':wechat_account
            };

            if((userName.trim() == "" || reg3.test(userName) != true) ){
                layer.tips('请填写正确的姓名', '#realname', {
                    tips: [1, '#ff0000'],
                });
                return;
            }else if(($("#birth").val().trim()=="")){
                layer.tips('请选择您的生日', '#birth', {
                    tips: [1, '#ff0000'],
                    time: 2000
                });
                return;
            }else if(mobile.trim() == "" || match_phone.test(mobile) != true){
                layer.tips('手机格式不正确', '#mobile', {
                    tips: [1, '#ff0000'],
                    time: 2000
                });
                return;
            }else{
                    button.disabled = true;

                    $.ajax({
                        type: 'post',
                        url:"/players.perfectUserInformation",
                        async: true,
                        data:data ,
                        dataType: 'json',
                        success:function(data){
                            if(data.success == true){
                                layer.msg('保存成功!');
                                window.location.href = data.data;
                            }
                        },
                        error: function(xhr){
                            if(xhr.responseJSON){
                                layer.tips(xhr.responseJSON.message, '#'+xhr.responseJSON.field, {
                                    tips: [1, '#ff0000'],
                                    time: 2000
                                });
                                button.disabled = false;
                            }
                        }

                    });
            }
        });
        //获取验证码
        $(document).on('click', '.getmsgcode', function () {
            var _this = $(this);
            var yanzheng = $(this).attr('data-yanzheng');
            var email = $('#email').val();
            if(email.trim() ==""  || match_email.test(email) != true){
                layer.tips('邮箱格式不正确', '#email', {
                    tips: [1, '#ff0000'],
                    time: 2000
                });
            } else {
                $.ajax({
                    type: 'get',
                    async: true,
                    url: "/homes.sendEmailVerificode",
                    data: {
                        'email' : email,
                        'type' : 'changePassword',
                        'yanzheng' : yanzheng,
                        'info' : 'player',
                    },
                    dataType: 'json',
                    success: function(data){
                        if(data.success == true){
                            layer.msg('邮箱证码发送成功，请注意查收',{
                                success: function(layero, index){
                                    $(layero).css('top', '401.5px');
                                }
                            });
                            regCountDown(_this,60,'getmsgcode');
                        }
                    },
                    error: function(xhr){
                        if(xhr.responseJSON){
                            layer.tips(xhr.responseJSON.message, '#email', {
                                tips: [1, '#ff0000'],
                                time: 2000
                            });
                        }
                    }
                });
            }
        });

        //绑定邮箱
        $(document).on('click', '.bindemail-sure', function () {
            var _this = $(this);
            var action = _this.attr('data-action');
            var email = $('#bind-email').val();
            var make_sure = true;
            if(match_email.test(email)!=true){
                $('.msg.font-red').html('邮箱格式不正确');
                make_sure = false;
            }
            if (make_sure){
                $.ajax({
                    url:'/players.bindEmail',
                    type:'get',
                    dataType:'json',
                    data:{email:email},
                    success:function(data){
                        $('.accept-email').html(data.data);
                        if (action == 'sure'){
                            $('.bindemail').hide();
                            $('.ckemail').show();
                        }else if (action == 'change'){
                            $('.msg').text('邮箱激活链接已经发送至新邮箱').show();
                            $('.btn-done').hide();
                            $('.btn-gone').show();
                        }
                    },
                    error:function (xhr) {
                        if (xhr.responseJSON){
                            $('.msg.font-red').html(xhr.responseJSON.message);
                        }
                    }
                });
            }
        });

        var hash = {
            'qq.com': 'http://mail.qq.com',
            'gmail.com': 'http://mail.google.com',
            'sina.com': 'http://mail.sina.com.cn',
            '163.com': 'http://mail.163.com',
            '126.com': 'http://mail.126.com',
            'yeah.net': 'http://www.yeah.net/',
            'sohu.com': 'http://mail.sohu.com/',
            'tom.com': 'http://mail.tom.com/',
            'sogou.com': 'http://mail.sogou.com/',
            '139.com': 'http://mail.10086.cn/',
            'hotmail.com': 'http://www.hotmail.com',
            'live.com': 'http://login.live.com/',
            'live.cn': 'http://login.live.cn/',
            'live.com.cn': 'http://login.live.com.cn',
            '189.com': 'http://webmail16.189.cn/webmail/',
            'yahoo.com.cn': 'http://mail.cn.yahoo.com/',
            'yahoo.cn': 'http://mail.cn.yahoo.com/',
            'eyou.com': 'http://www.eyou.com/',
            '21cn.com': 'http://mail.21cn.com/',
            '188.com': 'http://www.188.com/',
            'foxmail.com': 'http://www.foxmail.com',
            'outlook.com': 'http://www.outlook.com'
        };
        $(document).on('click','.in-email',function(){
            var _mail = $(".accept-email").html().split('@')[1];    //获取邮箱域
            for (var j in hash){
                if(j == _mail){
                    window.location.href= hash[_mail];
                }
            }
        });

        //邮箱绑定 失去焦点与聚集焦点
        $(document).on('focus', '#bind-email', function () {
            $('.bindemail-sure').attr('disabled', true);
        });
        $(document).on('blur', '#bind-email', function () {
            var _this = $(this);
            var email = _this.val();
            var make_sure = true;
            if(match_email.test(email)!=true){
                $('.msg.font-red').html('邮箱格式不正确');
                make_sure = false;
            }
            if (make_sure && $('.bindemail-sure:visible').size()>0){
                $.ajax({
                    url:'/players.bindEmail',
                    type:'get',
                    dataType:'json',
                    data:{email:email,type:'player'},
                    success:function(data){
                        $('.msg.font-red').html('');
                        $('.bindemail-sure').attr('disabled', false);
                    },
                    error:function (xhr) {
                        if (xhr.responseJSON){
                            $('.msg.font-red').html(xhr.responseJSON.message);
                        }
                        _this.focus();
                        $('.bindemail-sure').attr('disabled', true);
                    }
                });
            }
        })
    </script>
@endsection



