var match_email = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
var match_phone = /^(0|86|17951)?(13[0-9]|15[012356789]|17[1678]|18[0-9]|14[57])[0-9]{8}$/i;
$(function(){
    var reg3 = /^([a-zA-Z0-9]){4,11}$/;
    var reg2 = /^([a-zA-Z0-9]){6,20}$/;
    var Txlogin = new Txlogins();
    var loginErrNum = 0;
    $(document).on('click', '.tx_login_game', function(e){
        e.preventDefault();
        e.stopPropagation();
        var _this = $(this);
        var _url = _this.attr('data-url');
        if (_url == undefined || _url.length < 1){
            _url = _this.attr('href');
        }
        _this.removeClass('tx_login_game');
        $.ajax({
            url:'/homes.isonline',
            dataType:'json',
            success:function(data){
                if (data.success == true){
                    if (_url != undefined && _url.length > 0){
                        //window.location.href = _url;
                        tools.layer.game(_url,'/players.account-transfer');
                        _this.addClass('tx_login_game');
                    }
                }
            },
            error:function(xhr){
                $('body').append(Txlogin.unlogin());
                _this.addClass('tx_login_game');
            }
        })
    });
    //去登录页面
    $(document).on('click', '.tx_tologin', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.masklayer .dialog-wrap').html(Txlogin.tologin());
        $('.masklayer .authcode').hide()
    });
    //去注册页面
    $(document).on('click', '.tx-kaihu', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('body').append(Txlogin.toregisterOne());
    });
    /*确认登录*/
    $(document).on('click', '.tx_login_sure', function(e){
        function checkerrNum(num){
            if(num>=3){
                $('.masklayer .authcode').show()
            }
        }
        e.preventDefault();
        e.stopPropagation();
        var _this = $(this);
        var user1 = $("#username").val();
        var user2 = $("#password").val();
        var user3 = $("#authcode").val();
        if(reg3.test(user1)!=true){
            errorMessage('.msg.font-red.f14','账号格式有误')
        }else if(reg2.test(user2) != true){
            errorMessage('.msg.font-red.f14','密码格式有误')
        }else{
            var data = new Object();
            data.user_name = user1;
            data.password = user2;
            if ($('.masklayer .authcode').css('display') != 'none'){
                data.TxloginVericode = user3
            }
            _this.removeClass('tx_login_sure');
            //ajax请求
            $.ajax({
                type: 'post',
                async: true,
                url: "/homes.login",
                data: data,
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        location.reload();
                        return false;
                    }
                    if(data.success == false){
                        errorMessage('.msg.font-red.f14',data.message);
                        _this.addClass('tx_login_sure');
                    }
                },
                error: function(xhr){
                    loginErrNum++;
                    checkerrNum(loginErrNum);
                    if(xhr.responseJSON.success == false){
                        errorMessage('.msg.font-red.f14',xhr.responseJSON.message);
                        _this.addClass('tx_login_sure');
                        return false;
                    }
                }
            });
        }
    });
    //立即注册页面
    $(document).on('click', '.tx_toregister', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.masklayer .dialog-wrap').html(Txlogin.toregister());
    });
    //确认立即注册
    $(document).on('click', '.tx_register_sure', function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var user_name =$("#username").val();
        var password = $("#password").val();
        var referral_code =$('#referral_code').val();
        var confirm_password = $("#confirm-password").val();
        var code = $("#authcode").val();
        if (user_name.trim() == "" || reg3.test(user_name) != true) {
            errorMessage('.msg.font-red.f14','账号是4到11位数字或字母组合')
        }else if (reg2.test(password) != true) {
            errorMessage('.msg.font-red.f14','密码是6到20位数字或字母组合')
        }else if(password != confirm_password){
            errorMessage('.msg.font-red.f14','两次密码不一致')
        }else {
            button.removeClass('tx_register_sure');
            $.ajax({
                type: 'post',
                url: "/homes.register",
                data: {
                    'verification_code' : code,
                    'user_name' : user_name,
                    'password' : password,
                    'confirm_password' : confirm_password,
                    'referral_code':referral_code
                },
                dataType: 'json',
                success: function (xhr) {
                    if (xhr.success == true) {
                        layer.msg('注册成功!',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                        window.location.reload();
                    }
                },
                error:function(xhr){
                    if(xhr.responseJSON.success == false) {
                        errorMessage('.msg.font-red.f14',xhr.responseJSON.message);
                    }
                    button.addClass('tx_register_sure');
                }
            });
        }
    });
    //去忘记密码页面
    $(document).on('click', '.tx_forget', function(e){
        e.preventDefault();
        e.stopPropagation();
        if($('.masklayer:visible').size() <= 0){
            $('body').append(Txlogin.toregisterOne());
        }
        $('.masklayer .dialog-wrap').html(Txlogin.toforget());
    });
    //去忘记密码页面
    $(document).on('click', '.tx_forgetOne', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('body').append(Txlogin.toforgetOne());
    });
    //获取验证码
    $(document).on('click', '.getmsgs', function () {
        var _this = $(this);
        var yanzheng = _this.attr('data-yanzheng');
        var email = $('#usernameForget').val();
        if(email.trim() ==""  || match_email.test(email) != true){
            errorMessage('.msg.font-red.f14','邮箱账号格式不正确')
        } else {
            $.ajax({
                type: 'get',
                async: true,
                url: "/homes.sendEmailVerificode",
                data: {
                    'email' : email,
                    'type' : 'changePassword',
                    'yanzheng' : yanzheng,
                },
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        layer.msg('邮箱证码发送成功，请注意查收',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                        regCountDown(_this,60,'getmsgs');
                    }
                },
                error: function(xhr){
                    if(xhr.responseJSON){
                        errorMessage('.msg.font-red.f14',xhr.responseJSON.message)
                    }
                }
            });
        }
    });
    //提交忘记密码重置密码
    $(document).on('click', '.tx-forget-sure', function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var email = $('#usernameForget').val();
        var pwd1 = $("#passwordForget").val(); //密码长度8——20位
        var pwd2 = $("#confirm-passwordForget").val();
        var code = $("#msgcode").val() ;

        if(pwd1.trim() =="" || reg2.test(pwd1) != true){
            errorMessage('.msg.font-red.f14','密码为6-20字母或者数字')
        }else if(email.trim() ==""  || match_email.test(email) != true) {
            errorMessage('.msg.font-red.f14','请输入正确的邮箱账号')
        }else if(pwd1 != pwd2) {
            errorMessage('.msg.font-red.f14','两次密码不一致')
        }else if(code.trim() =="" || code.length <= 0) {
            errorMessage('.msg.font-red.f14','验证码不能为空')
        }else{
            button.removeClass('tx-forget-sure');
            $.ajax({
                type: 'get',
                async: true,
                url: "/homes.modifyPassword",
                data: {
                    'email' : email,
                    'password' : pwd1,
                    'code' : code
                },
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        layer.msg('密码重置成功,请去登录',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                    }
                    location.reload();
                },
                error: function(xhr){
                    if(xhr.responseJSON.success == false){
                        errorMessage('.msg.font-red.f14',xhr.responseJSON.message)
                    }
                    button.addClass('tx-forget-sure');
                }
            });
        }

    }) ;
});
function Txlogins(){
    //未登录处理
    var _unlogin = ' <div class="dialog unlogged" >\n' +
        '<div class="dialog-head"></div>\n' +
        '<div class="dialog-body text-center">\n' +
        '<h4>系统检测到您还没有登录</h4>\n' +
        '</div><div class="dialog-foot clearfix">\n' +
        '<button class="btn btn-warning2 float-left" onclick="location.href=\'/\'" style="width: 140px;">返回首页</button>\n' +
        '<button class="btn btn-warning float-right tx_tologin" style="width: 140px;">立即登录</button>\n' +
        '</div><div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div></div>';
    this.unlogin = function(){
        return models(_unlogin);
    };

    var _tologin = '<div class="dialog login-page">\n' +
        '                <div class="dialog-head">会员安全登录</div>\n' +
        '                <div class="dialog-body">\n' +
        '                    <div class="form-group username">\n' +
        '                        <span class="glyphicon glyphicon-user"></span>\n' +
        '                        <input type="text" id="username" placeholder="账号"/>\n' +
        '                    </div>\n' +
        '                    <div class="form-group password">\n' +
        '                        <span class="glyphicon glyphicon-lock"></span>\n' +
        '                        <input type="password" id="password" placeholder="密码" value=""/>\n' +
        '                        <span class="glyphicon eye glyphicon-eye-close"></span>\n' +
        '                    </div>\n' +
        '                    <div class="form-group authcode clearfix">\n' +
        '                        <span ></span>\n' +
        '                        <input type="text" id="authcode" placeholder="验证码" value=""/>\n' +
        '                        <div class="code-wrap">\n' +
        '                            <a href="javascript:void(0)" title="点击更换验证码"><img src="/captcha" onclick="this.src=\'/captcha?r=\'+Math.random();"/></a>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="msg font-red f14"></div>\n' +
        '                </div>\n' +
        '                <div class="dialog-foot">\n' +
        '                    <botton class="btn btn-warning tx_login_sure">登录</botton>\n' +
        '                    <div class="tips clearfix">\n' +
        '                        <span class="regist-now float-left">还没有账户？<a href="javascript:void(0)" class="tx_toregister">立即注册</a></span>\n' +
        '                        <span class="forgetpwd float-right"><a href="javascript:void(0)" class="tx_forget">忘记密码?</a></span>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div><div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div></div>';
    //去登录页面
    this.tologin = function(){
        return _tologin;
    };
    var _toregister = '<div class="dialog signin-page">\n' +
        '                <div class="dialog-head">会员注册</div>\n' +
        '                <div class="dialog-body">\n' +
        '                    <div class="sign-normal">\n' +
        '                        <div class="form-group username">\n' +
        '                            <span class="glyphicon glyphicon-user"></span>\n' +
        '                            <input type="text" id="username" placeholder="账号为4-11位字母或数字的组合"/>\n' +
        '                        </div>\n' +
        '                        <div class="form-group password">\n' +
        '                            <span class="glyphicon glyphicon-lock"></span>\n' +
        '                            <input type="password" id="password" placeholder="密码为6-20位字母或数字的组合" value=""/>\n' +
        '                            <span class="glyphicon eye glyphicon-eye-close"></span>\n' +
        '                        </div>\n' +
        '                        <div class="form-group confirm-password">\n' +
        '                            <span class="glyphicon glyphicon-lock"></span>\n' +
        '                            <input type="password" id="confirm-password" placeholder="请确认密码" value=""/>\n' +
        '                            <span class="glyphicon eye glyphicon-eye-close"></span>\n' +
        '                        </div>\n' +
                '                <div class="form-group username">\n' +
        '                            <span class="glyphicon glyphicon-lock"></span>\n' +
        '                            <input type="text" id="referral_code" placeholder="邀请码" value=""/>\n' +
        '                        </div>\n' +
        '                        <div class="form-group authcode clearfix">\n' +
        '                            <span ></span>\n' +
        '                            <input type="text" id="authcode" placeholder="验证码" value=""/>\n' +
        '                            <div class="code-wrap">\n' +
        '                                <a href="javascript:void(0)" title="点击更换验证码"><img src="/captcha" onclick="this.src=\'/captcha?r=\'+Math.random();"/></a>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="msg font-red f14"></div>\n' +
        '                </div>\n' +
        '                <div class="dialog-foot">\n' +
        '                    <botton class="btn btn-warning tx_register_sure">立即注册</botton>\n' +
        '                    <div class="tips clearfix">\n' +
        '                        <span class="login-now">已有账号？<a href="javascript:void(0)" class="tx_tologin">点击登录</a></span>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div><div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div></div>';
    this.toregister = function(){
        return _toregister;
    };
    this.toregisterOne = function(){
        return models(_toregister);
    };
    var _toforget = '<div class="dialog login-page step-one" >\n' +
        '                <div class="dialog-head">验证邮箱</div>\n' +
        '                <div class="dialog-body">\n' +
        '                    <div class="form-group username">\n' +
        '                        <span class="glyphicon glyphicon-envelope"></span>\n' +
        '                        <input type="text" id="usernameForget" placeholder="输入您的邮箱"/>\n' +
        '                    </div>\n' +
        '                    <div class="form-group authcode msgcode clearfix">\n' +
        '                        <span class="glyphicon glyphicon-comment"></span>\n' +
        '                        <input type="text" id="msgcode" placeholder="输入邮箱验证码" value=""/>\n' +
        '                        <div class="sendmsg-box">\n' +
        '                            <button class="btn btn-warning no-send getmsgs code" data-yanzheng="yanzheng">发送验证码</button>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
    '                        <div class="form-group password">\n' +
    '                            <span class="glyphicon glyphicon-lock"></span>\n' +
    '                            <input type="password" id="passwordForget" placeholder="请输入新密码" value=""/>\n' +
    '                            <span class="glyphicon eye glyphicon-eye-close"></span>\n' +
    '                        </div>\n' +
    '                        <div class="form-group confirm-password">\n' +
    '                            <span class="glyphicon glyphicon-lock"></span>\n' +
    '                            <input type="password" id="confirm-passwordForget" placeholder="请确认密码" value=""/>\n' +
    '                            <span class="glyphicon eye glyphicon-eye-close"></span>\n' +
    '                        </div>\n' +
        '                    <div class="msg font-red f14 show-error">\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '                <div class="dialog login-page step-two" >\n' +
        '                    <div class="dialog-foot">\n' +
        '                        <botton class="btn btn-warning check-phone tx-forget-sure">确定</botton>\n' +
        '                        <div class="tips clearfix">\n' +
        '                            <span class="regist-now float-left">如有问题？<a href="javascript:void(0)">联系客服</a></span>\n' +
        '                            <span class="forgetpwd float-right">已有账号？<a href="javascript:void(0)" class="tx_tologin">立即登录</a></span>\n' +
        '                        </div>\n' +
        '                        <div class="tips clearfix">\n' +
        '                            <span class="customer-service"></span>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div>';
    this.toforget = function(){
        return _toforget;
    };
    this.toforgetOne = function(){
        return models(_toforget);
    };
}
//模板嵌入
function models(content){
    var model = '<div class="masklayer" ><div class="dialog-wrap">'+content+'</div></div></div>';
    return model;
}
//错误提示
function errorMessage(_this, message){
    $(_this).html(message);
    return true;
}