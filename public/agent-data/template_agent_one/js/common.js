$(function(){
    var match_email = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
    var reg3 = /^([a-zA-Z0-9]){4,11}$/;
    var reg2 = /^([a-zA-Z0-9]){6,20}$/;
    /*登录*/
    $(".agent-login").click(function (){
        var username = $("input:text[name='username_header']").val();
        var password = $("input:password[name='password_header']").val();
        if(reg3.test(username)!=true){
            layer.tips('账号格式有误', '#username', {
                tips: [1, '#ff0000'],
                time: 2000
            });
        }else if(reg2.test(password) != true){
            layer.tips('密码格式有误', '#password', {
                tips: [1, '#ff0000'],
                time: 2000
            });
        }else{
            //ajax请求
            $.ajax({
                type: 'post',
                async: true,
                url: "/agents.login",
                data: {
                    'username': username,
                    'password': password,
                },
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        location.reload();
                        return false;
                    }
                    if(data.success == false){
                        layer.tips(data.message, '#username', {
                            tips: [1, '#ff0000'],
                            time: 2000
                        });
                    }
                },
                error: function(xhr){
                    if(xhr.responseJSON.success == false){

                        layer.tips(xhr.responseJSON.message, '#username', {
                            tips: [1, '#ff0000'],
                            time: 2000
                        });
                        return false;
                    }

                }
            });
        }
    });
    // 安全退出
    $(".agent-loginOut").click(function(){
        layer.confirm('确定要退出吗？', {
                btn: ['确定','取消'], //按钮
                success: function(layero, index){
                    var _this = $(layero);
                    _this.css('top', '401.5px');
                    _this.find('.layui-layer-content').css('color', '#333').css('text-align', 'center');
                }
            },function () {
                window.location.href = "/agents.loginOut";
            },function () {

            }
        );
    });
    //忘记密码弹框
    $(document).on('click', '.tx_agent_forget', function(){
        $('.masklayer').show();
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
                    'info' : 'agent'
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
    $(document).on('click', '.tx-agent-forget-sure', function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var email = $('#usernameForget').val();
        var pwd1 = $("#passwordForget").val();
        var pwd2 = $("#confirm-passwordForget").val();
        var code = $("#msgcode").val() ;

        if(pwd1.trim() =="" || reg2.test(pwd1) != true){
            errorMessage('.msg.font-red.f14','密码为6-20字母或者数字组合')
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
                    'code' : code,
                    'info' : 'agent'
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

    //密码显示隐藏
    $(document).on('click', '.glyphicon.eye', function () {
        var _this = $(this);
        if (_this.hasClass('glyphicon-eye-close')){
            _this.addClass('glyphicon-eye-open').removeClass('glyphicon-eye-close');
            _this.prev().attr('type', 'text')
        }else{
            _this.addClass('glyphicon-eye-close').removeClass('glyphicon-eye-open');
            _this.prev().attr('type', 'password')
        }
    });
    //去忘记密码页面
    $(document).on('click', '.tx_forget', function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.masklayer.forget').show();
    });
    //提交忘记密码重置密码
    $(document).on('click', '.tx-agent-forget-sure', function(e){
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
                    'code' : code,
                    'info' : 'agent',
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
                    setTimeout(function(){
                        location.reload();
                    },2000);
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

//错误提示
function errorMessage(_this, message){
    $(_this).html(message);
    return true;
}

//计时器
function regCountDown(elem,grap,cla) {
    var i= grap;
    var countDown=setInterval(function(){
        elem.removeClass(cla);
        elem.removeClass("btn-warning");
        if(i>0){
            elem.html("重发"+i+"s");
            elem.addClass("btn-default").css('background', '#ccc');
            elem.addClass("btn-default");
            i--;
        }else if(i == 0){
            clearInterval(countDown);
            elem.addClass(cla).html('重新获取');
            elem.addClass('btn-warning').css('background', '#d8a659');
            i=grap;
        }
    },1000);
    return false;
}


$(function(){
	$("input").keyup(function(event){ 
		var _that = $(this)
	    if(event.which == 13) {
	    	if(_that.val() == ''){
	    		return false;
	    	}else {
	    		if(!_that.parents('header')){
	    			return false;
	    		}else {
	    			$('.btn-login').click();
	    		}
	    	}
	    }
	});
})

var tools = Object.create(null)
tools.layer = Object.create(null)
// 进入游戏充值提醒
tools.layer.game = function(urlFirst, urlSecond){
    var html = '<div class="masklayer gamemask">'+
        '<div class="dialog-wrap">'+
        '<div class="dialog login-page">'+
        '<div class="dialog-head">确认进入游戏</div>'+
        '<div class="dialog-body">'+
        '<div class="msg f14">'+
        '为了确保游戏顺利进行,请确认您的余额是否充足！'+
        '</div>'+
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-danger2 btn-play" onclick="window.open(\''+urlFirst+'\');$(\'.masklayer\').remove();">进入游戏</botton>'+
        '<botton class="btn btn-warning btn-gone" onclick="window.open(\''+urlSecond+'\');$(\'.masklayer\').remove();">>进入转账中心</botton>'+
        '</div>'+
        '</div>'+
        '<div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div>'+
        '</div>'+
        '</div>';
    $('body').append($(html));
}
// 绑定邮箱
tools.layer.bindemail = function(){
    var html = '<div class="masklayer">'+
        '<div class="dialog-wrap">'+
        '<div class="dialog bindemail">'+
        '<div class="dialog-head">绑定邮箱</div>'+
        '<div class="dialog-body">'+
        '<div class="form-group email">'+
        '<span class="glyphicon glyphicon-envelope"></span>'+
        '<input type="text" id="bind-email" placeholder="请输入您的邮箱地址"/>'+
        '</div>' +
        '<div class="msg font-red f12"></div>' +
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-warning btn-sure bindemail-sure"  data-action="sure">确定</botton>'+
        '<div class="tips clearfix">'+
        '<span class="login-now">如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="dialog ckemail" style="display:none;">'+
        '<div class="dialog-head">邮箱验证</div>'+
        '<div class="dialog-body">'+
        '<div class="info f18">邮箱激活链接已发送至' +
        '<i class="font-red accept-email"></i>' +
        '</div>'+
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-warning btn-gone in-email">进入邮箱</botton>'+
        '<div class="tips clearfix">'+
        '<span class="login-now">如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div>'+
        '</div>'+
        '</div>';
    $('body').append($(html));
}
// 绑定手机号
tools.layer.bindphone = function(phone){
    var html = '<div class="masklayer">'+
        '<div class="dialog-wrap">'+
        '<div class="dialog bindphone">'+
        '<div class="dialog-head">绑定手机</div>'+
        '<div class="dialog-body">'+
        '<div class="form-group phone">'+
        '<span class="glyphicon glyphicon-envelope"></span>'+
        '<input type="text" id="phone" placeholder="请输入您的手机号"/>'+
        '</div>' +
        '<div class="form-group authcode msgcode clearfix">'+
        '<span class="glyphicon glyphicon-comment"></span>'+
        '<input type="text" id="msgcode" placeholder="输入短信验证码" value=""/>'+
        '<div class="sendmsg-box">'+
        '<button class="btn btn-warning no-send" style="display: none;">发送验证码</button>'+
        '<button class="btn btn-default sended disabled">重发(17s)</button>'+
        '</div>'+
        '</div>'+
        '<div class="msg font-red f12">错误提示</div>' +
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-warning btn-sure">确定</botton>'+
        '<div class="tips clearfix">'+
        '<span class="login-now">如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div>'+
        '</div>'+
        '</div>';
    $('body').append($(html));
}
// 修改手机号
tools.layer.changephone = function(num){
    var html = '<div class="masklayer">'+
        '<div class="dialog-wrap">'+
        '<div class="dialog ckuser">'+
        '<div class="dialog-head">账号安全验证</div>'+
        '<div class="dialog-body">'+
        '<div class="info f18">短信验证码发送至' +
        '<i class="font-red">' + num + '</i>' +
        '</div>'+
        '<div class="form-group authcode msgcode clearfix">'+
        '<span class="glyphicon glyphicon-comment"></span>'+
        '<input type="text" id="msgcode" placeholder="输入短信验证码" value=""/>'+
        '<div class="sendmsg-box">'+
        '<button class="btn btn-warning no-send" style="display: none;">发送验证码</button>'+
        '<button class="btn btn-default sended disabled">重发(17s)</button>'+
        '</div>'+
        '</div>'+
        '<div class="msg font-red f12">已发送，请注意查收！</div>' +
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-warning btn-done" onclick="$(\'.ckuser\').hide();$(\'.cgphone\').show();" >下一步</botton>'+
        '<div class="tips clearfix">'+
        '<span>如原手机号码丢失或无法使用，请 <a href="javascript:void(0)">联系客服</a></span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        // 修改手机号码
        '<div class="dialog cgphone" style="display:none;">'+
        '<div class="dialog-head">修改手机号码</div>'+
        '<div class="dialog-body">'+
        '<div class="form-group phone">'+
        '<span class="glyphicon glyphicon-phone"></span>'+
        '<input type="text" id="phone" placeholder="请输入您的手机号码">'+
        '</div>'+
        '<div class="form-group authcode msgcode clearfix">'+
        '<span class="glyphicon glyphicon-comment"></span>'+
        '<input type="text" id="msgcode" placeholder="输入短信验证码" value=""/>'+
        '<div class="sendmsg-box">'+
        '<button class="btn btn-warning no-send">发送验证码</button>'+
        '<button class="btn btn-default sended disabled" style="display: none;">重发(17s)</button>'+
        '</div>'+
        '</div>'+
        '<div class="msg font-red f12" style="display:none;"></div>' +
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-warning btn-done">确定</botton>'+
        '<div class="tips clearfix">'+
        '<span class="f12">如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div>'+
        '</div>'+
        '</div>';
    $('body').append($(html));
}
// 修改邮箱
tools.layer.changeemail= function(email){
    var html = '<div class="masklayer">'+
        '<div class="dialog-wrap">'+
        '<div class="dialog ckuser">'+
        '<div class="dialog-head">修改邮箱地址</div>'+
        '<div class="dialog-body">'+
        '<div class="info f18">已绑定邮箱' + email + '</div>'+
        '<div class="form-group email">'+
        '<span class="glyphicon glyphicon-envelope"></span>'+
        '<input type="text" id="bind-email" placeholder="请输入您的新邮箱地址"/>'+
        '</div>' +
        '<div class="msg font-red f12"></div>' +
        '</div>'+
        '<div class="dialog-foot">'+
        '<botton class="btn btn-warning btn-done bindemail-sure" data-action="change">确定</botton>'+
        '<span class="accept-email" style="display:none;"></span>' +
        '<botton class="btn btn-warning btn-gone in-email" style="display:none;">进入邮箱</botton>'+
        '<div class="tips clearfix">'+
        '<span class="f12">如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="dialog-close" onclick="$(this).parents(\'.masklayer\').remove();"></div>'+
        '</div>'+
        '</div>';
    $('body').append($(html));
}