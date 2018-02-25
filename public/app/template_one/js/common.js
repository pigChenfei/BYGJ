$(function(){
    var match_email = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
    var match_phone = /^(0|86|17951)?(13[0-9]|15[012356789]|17[1678]|18[0-9]|14[57])[0-9]{8}$/i;
    $('#myCarousel').carousel();

	$(".has-nav").hover(function(){
		$(this).find('.nav-sm').show();
	},function(){
		$(this).find('.nav-sm').hide();
	});
	
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
/*登录*/
$(".dbl").click(function (){
    var user1 = $(".admin-poke input[name=username]").val();
    var user2 = $(".admin-poke input[name=password]").val();
    // var user3 = $(".admin-poke>input:nth-child(3)").val();
    var reg3 = /^([a-zA-Z0-9]){4,11}$/;
    var reg2 = /^([a-zA-Z0-9]){6,16}$/;
    if(reg3.test(user1)!=true){
        layer.tips('账号格式有误', '.admin-poke input[name=username]', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(reg2.test(user2) != true){
        layer.tips('密码格式有误', '.admin-poke input[name=password]', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
        //ajax请求
        $.ajax({
            type: 'post',
            async: true,
            url: "/homes.login",
            data: {
                'user_name': user1,
                'password': user2,
                // 'loginVericode' : user3
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    window.location.href = "players.account-security";
                    return false;
                }
                if(data.success == false){
                    layer.tips(data.message, '.admin-poke input[name=username]', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }
            },
            error: function(xhr){
                if(xhr.responseJSON.success == false){
                    if(xhr.responseJSON.fields =='loginVericode'){
                        layer.tips(xhr.responseJSON.message, '.admin-poke>input:nth-child(3)', {
                            tips: [1, '#ff0000'],
                            time: 2000
                        });
                    }else{
                        layer.tips(xhr.responseJSON.message, '.admin-poke input[name=username]', {
                            tips: [1, '#ff0000'],
                            time: 2000
                        });
                    }
                    return false;
                }
            }
        });
    }
});

// 安全退出
$(".Logging-Out").click(function(){
    layer.confirm('确定要退出吗？', {
            btn: ['确定','取消'], //按钮
            success: function(layero, index){
                var _this = $(layero);
                _this.css('top', '401.5px');
                _this.find('.layui-layer-content').css('color', '#333').css('text-align', 'center');
            }
        },function () {
            window.location.href = "players.logout";
        },function () {

        }
    );
});

//刷新主账户
//刷新
    $("#mainAccountRefresh").on("click", function (e) {
        e.preventDefault();
        $.ajax({
            url: "/players.accountRefresh",
            dataType:'json',
            success: function (data) {
                layer.msg('刷新成功',{
                    success: function(layero, index){
                        var _this = $(layero);
                        _this.css('top', '401.5px');
                    }
                });
                $('.balance').html(data.data);
                return false;
            },
            error: function (xhr) {
                if (xhr.responseJSON.success == false) {
                    layer.msg(xhr.responseJSON.message);
                }
                return false;
            }
        });
    });

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

    //电子游艺效果展示
    var $game = $('.tile-show .game-item');
    var $color = $('.video-game-container .game-list-container').css('border-color');
    var $border = $color == 'rgb(72, 72, 72)' ? '2px solid rgb(72, 72, 72)' : '1px solid rgb(229, 229, 229)';
    if($game.size()%5!=0){
        $game.eq($game.size()-1).css({
            'border-right': $border
        })
        if($game.size()>5){
            for(var i = 0; i<(5-$game.size()%5); i++){
                $game.eq($game.size()-(3+i)).css({
                    'border-bottom': $border
                })
            }
        }
    }
});
function bindEvent(className1,className2,eve,func){
    //className1 父元素class名
    //className2 子元素class名
    //eve 委派事件名
    //func 委派执行函数
    $(className1).undelegate(className2,eve).delegate(className2,eve,func);
}

/**/
function addActive(ele){
    $(ele).off('click').on('click',function(){
        var _that = $(this),
            inx = _that.index();
        if (_that.hasClass('active')) {
            if(_that.find('.glyphicon').size() > 1 ){
                var	hideEle = _that.find('.glyphicon:hidden');
                _that.find('.glyphicon').hide();
                hideEle.show();
            }else{
                return false;
            }
        } else{
            _that.addClass('active').siblings().removeClass('active');
            _that.parents('article').find('.art-body > div').eq(inx).show().siblings().hide();
        }
    })
    return $(ele)
}
//错误提示
function alertMessage(tip, message) {
    $(tip).next().html('<i class="glyphicon glyphicon-warning-sign font-red"></i><span class="tip">'+message+'</span>');
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

var tools = Object.create(null)
tools.layer = Object.create(null)
// 进入游戏充值提醒
tools.layer.game = function(urlFirst, urlSecond){
    var html = '<div class="masklayer gamemask">'+
                    '<div class="dialog-wrap">'+
                        '<div class="dialog login-page">'+
                            '<div class="dialog-head">确认进入游戏</div>'+
                            '<div class="dialog-body">'+
                                '<h4>'+
                                    '为了确保游戏顺利进行,请确认您的游戏余额是否充足！'+
                                '</h4>'+
                            '</div>'+
                            '<div class="dialog-foot">'+
                                '<botton class="btn btn-danger2 btn-gone" onclick="window.open(\''+urlSecond+'\');$(\'.masklayer\').remove();">转账中心</botton>'+
                                '<botton class="btn btn-warning btn-play" onclick="window.open(\''+urlFirst+'\');$(\'.masklayer\').remove();">进入游戏</botton>'+
                            '</div>'+
                        '</div>'+
                        '<div class="dialog-close" onclick="$(\'.masklayer\').remove();"></div>'+
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
                                    '<span>如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
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
                                    '<span>如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="dialog-close" onclick="$(\'.masklayer\').remove();"></div>'+
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
                                '<div class="msg font-red f12 font-danger" style="display:none">错误提示</div>' +
                            '</div>'+
                            '<div class="dialog-foot">'+
                                '<botton class="btn btn-warning btn-sure">确定</botton>'+
                                '<div class="tips clearfix">'+
                                    '<span>如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="dialog-close" onclick="$(\'.masklayer\').remove();"></div>'+
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
                                    '<span>如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="dialog-close" onclick="$(\'.masklayer\').remove();"></div>'+
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
                                    '<span>如有疑问，可通过 <a href="javascript:void(0)">联系客服</a> 完成验证</span>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="dialog-close" onclick="$(\'.masklayer\').remove();"></div>'+
                    '</div>'+
                '</div>';
    $('body').append($(html));
}