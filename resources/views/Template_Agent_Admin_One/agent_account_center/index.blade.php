@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--会员报表-->
    <article class="usercenter">
        <div class="art-title"></div>
        <div class="art-body">
            <h4 class="art-tit">个人信息</h4>
            <div class="memb-box text-center clearfix">
                <div class="float-left" style="width:40%;">
                    <div class="form-inline">
                        <label>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
                        <input type="text" class="form-control" value="{!! $agentAccountCenter->username !!}" readonly>
                    </div>
                    <div class="form-inline">
                        <label>真实姓名：</label>
                        <input type="text" class="form-control" value="{!! $agentAccountCenter->realname !!}" readonly>
                    </div>
                    <div class="form-inline">
                        <label>代理模式：</label>
                        <input type="text" class="form-control" value="{!! $carrierAgentLevelType !!}" readonly>
                    </div>
                    
                    <div class="form-inline">
                        <label>邀&nbsp;&nbsp;请&nbsp;&nbsp;人：</label>

                        <input type="text" class="form-control" value="{{ !is_null($agentAccountCenter->parentAgent) && $agentAccountCenter->parentAgent->is_default == 0 ? $agentAccountCenter->parentAgent->username : '系统推荐'}}" readonly>

                    </div>
                    <div class="form-inline">
                        <label>邀&nbsp;&nbsp;请&nbsp;&nbsp;码：</label>
                        <input type="text" class="form-control" value="{!! $agentAccountCenter->promotion_code !!}" readonly>
                    </div>
                    <div class="form-inline">
                        <label>注册日期：</label>
                        <input type="text" class="form-control" value="{!! substr($agentAccountCenter->created_at, 0, 10) !!}" readonly>
                    </div>
                </div>
                <div class="float-left" style="width:54%;margin-left:2%;">
                    <div class="form-inline">
                        <label>手机号码：</label>
                        <input type="text" class="form-control" value="{!! $agentAccountCenter->mobile?substr_replace($agentAccountCenter->mobile, '****', 3, 4):'' !!}" readonly>
                        {{--<a href="javascript:" class="tx_agent_change" data-type="shouji">修改手机号码</a>--}}
                    </div>
                    <div class="form-inline">
                        <label>邮箱地址：</label>
                        <input type="text" class="form-control" value="{!! $agentAccountCenter->email?substr_replace($agentAccountCenter->email, '***', 2, 2):'' !!}" readonly>
                        <a href="javascript:" onclick="tools.layer.changeemail('{{$agentAccountCenter->email}}')">修改邮箱地址</a>
                    </div>
                    <div class="form-inline">
                        <label>登录密码：</label>
                        <input type="text" class="form-control" value="******" readonly>
                        <a href="javascript:" class="tx_agent_change" data-type="denglu">修改登录密码</a>
                    </div>
                    <div class="form-inline">
                        <label>取款密码：</label>
                        <input type="text" class="form-control" value="******" readonly>
                        <a href="javascript:" class="tx_agent_change" data-type="qukuan">修改取款密码</a>
                    </div>
                    <div class="form-inline">
                        <label>余&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额：</label>
                        <input type="text" class="form-control" value="{!! $agentAccountCenter->amount !!} 元" readonly>
                        <a href="{!! route('agentWithdraws.index') !!}">佣金取款</a>
                    </div>
                </div>
            </div>
            <div class="memb-box">
                <div class="tg-website">
                    <span style="color: rgba(255, 255, 255, 0.8)" style="display:inline-block;vertical-align:middle;">邀请链接：<input type="text" id="recommend-link" value="{!! $agentAccountCenter->promotion_url !!}" readonly/></span>
                    <button class="btn btn-warning" style="display:inline-block;vertical-align:middle;margin-left:18px;" onclick="jsCopy()">点击复制</button>
                    <div class="tooltip right" role="tooltip">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">复制成功！</div>
                    </div>
                </div>
            </div>
            
        </div>
        <input type="hidden" name="password-type">
        <div class="masklayer" style="display: none">
            <div class="dialog-wrap">
                <!--手机验证码-->
                <div class="dialog-head">修改密码</div>
                <div class="dialog-body">
                    <div class="form-group phone">
                        <span class="glyphicon glyphicon-phone"></span>
                        <input type="text" id="usernameForget" placeholder="输入您的邮箱账号"/>
                    </div>
                    <div class="form-group authcode msgcode clearfix">
                        <span class="glyphicon glyphicon-comment"></span>
                        <input type="text" id="msgcode" placeholder="输入邮箱验证码" value=""/>
                        <div class="sendmsg-box">
                            <button class="btn btn-warning no-send getmsgs code" data-yanzheng="yanzheng">发送验证码</button>
                        </div>
                    </div>
                    <div class="form-group password">
                        <span class="glyphicon glyphicon-lock"></span>
                        <input type="password" id="passwordForget" placeholder="新密码为6-20字母或者数字" value=""/>
                        <span class="glyphicon eye glyphicon-eye-close"></span>
                    </div>
                    <div class="form-group confirm-password">
                        <span class="glyphicon glyphicon-lock"></span>
                        <input type="password" id="confirm-passwordForget" placeholder="确认新密码" value=""/>
                        <span class="glyphicon eye glyphicon-eye-close"></span>
                    </div>
                    <div class="msg font-red f14 mb-20"></div>
                    <button class="btn btn-warning agent-sure-xiugai" style="padding: 5px 12px">确定</button>
                </div>
                <!--关闭-->
                <div class="dialog-close" onclick="$(this).parents('.masklayer').hide();"></div>
            </div>
        </div>
    </article>
@endsection

@section('script')
    <script>
        $(function () {
            var match_email = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
            var reg2 = /^([a-z0-9]){6,20}$/;
            var reg4 =/^[0-9]{6}$/;
            //提交修改密码
            $(document).on('click', '.agent-sure-xiugai', function(e){
                e.preventDefault();
                e.stopPropagation();
                var button = $(this);
                var email = $('#usernameForget').val();
                var pwd1 = $("#passwordForget").val();
                var pwd2 = $("#confirm-passwordForget").val();
                var code = $("#msgcode").val() ;
                var type = $('input[name=password-type]').val();
                var make_sure = true;

                if (type == 'qukuan'){
                    if(pwd1.trim() =="" || reg4.test(pwd1) != true){
                        make_sure = false;
                        errorMessage('.msg.font-red.f14','密码为6位数字')
                    }
                }else{
                    if(pwd1.trim() =="" || reg2.test(pwd1) != true){
                        make_sure = false;
                        errorMessage('.msg.font-red.f14','密码为6-20字母或者数字')
                    }
                }
                if(email.trim() ==""  || match_email.test(email) != true) {
                    make_sure = false;
                    errorMessage('.msg.font-red.f14','请输入正确的邮箱账号')
                }
                if(pwd1 != pwd2) {
                    make_sure = false;
                    errorMessage('.msg.font-red.f14','两次密码不一致')
                }
                if(code.trim() =="" || code.length <= 0) {
                    make_sure = false;
                    errorMessage('.msg.font-red.f14','验证码不能为空')
                }
                if(make_sure){
                    button.removeClass('tx-forget-sure');
                    $.ajax({
                        type: 'post',
                        async: true,
                        url: "/agent/admin/agentAccountCenters/modifyPassword",
                        data: {
                            'email' : email,
                            'password' : pwd1,
                            'code' : code,
                            'type' : type,
                        },
                        dataType: 'json',
                        success: function(data){
                            if(data.success == true){
                                layer.msg('密码修改成功',{
                                    success: function(layero, index){
                                        var _this = $(layero);
                                        _this.css('top', '401.5px');
                                    }
                                });
                            }
                            location.reload();
                        },
                        error: function(xhr){
                            console.log(xhr);
                            if(xhr.responseJSON.success == false){
                                errorMessage('.msg.font-red.f14',xhr.responseJSON.message)
                            }
                            button.addClass('tx-forget-sure');
                        }
                    });
                }

            }) ;
            //忘记密码弹框
            $(document).on('click', '.tx_agent_change', function(){
                var _this = $(this);
                var type = _this.attr('data-type');
                $('input[name=password-type]').val(type);
                $('.dialog-head').text('修改'+ (type=="qukuan" ? '取款' : '登录') +'密码');
                $('#passwordForget').attr('placeholder',(type=="qukuan" ? '新密码为6位数字' : '新密码为6-20字母或者数字'));
                $('.masklayer').show();
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
                        url:'{{route('agentAccountCenters.bindEmail')}}',
                        type:'get',
                        dataType:'json',
                        data:{email:email},
                        success:function(data){
                            console.log(data);
                            console.log(2222);
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
                            console.log(xhr);
                            console.log(1111);
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
                        url:'{{route('agentAccountCenters.bindEmail')}}',
                        type:'get',
                        dataType:'json',
                        data:{email:email,type:'agent'},
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
        });
        function jsCopy() {
            var e = document.getElementById("recommend-link");//对象是contents
            e.select(); //选择对象
            tag = document.execCommand("Copy"); //执行浏览器复制命令
            if (tag) {
                $(".tooltip").css('opacity', '1');
                setTimeout(function () {
                    $(".tooltip").fadeOut(800);
                }, 1000)
            }
        }
    </script>
@endsection