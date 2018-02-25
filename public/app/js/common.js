

$(document).ready(function(){
    $("body").css("font-family", "Microsoft YaHei");
    $("input").css("font-family","Microsoft YaHei");
    $(".refresh").rotate({ 
   bind: 
     { 
        click: function(){
            $(this).rotate({ angle:0,animateTo:360, easing: $.easing.easeInOutExpo })
        }
     } 
});
   
});

/*回车登录*/
document.onkeyup = function(e){      //onkeyup是javascript的一个事件、当按下某个键弹起 var _key;                                                 //的时触发
    if (e == null) { // ie
        _key = event.keyCode;
    } else { // firefox              //获取你按下键的keyCode
        _key = e.which;          //每个键的keyCode是不一样的
    }
    if(_key == 13){   //判断keyCode是否是13，也就是回车键(回车的keyCode是13)
        if($("#Modal_login").css("display")=="block"){
            $("#Modal_login form input[type='submit']").click()
        }else{
            $(".dbl").click() //验证成功触发一个
        }
    }
};
$(".admin-poke>input:nth-child(1)").blur(function(){
     var user1 = $(".admin-poke>input:nth-child(1)").val();
      var reg3 = /^([a-z0-9]){4,11}$/;
      if(user1.trim() != '' && reg3.test(user1)!=true){
        layer.tips('账号格式有误', '.admin-poke>input:nth-child(1)', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }
});
$(".admin-poke>input:nth-child(2)").blur(function(){
     var reg2 = /^([a-z0-9]){6,16}$/;
     var user2 = $(".admin-poke>input:nth-child(2)").val();
     if(user2.trim() != '' && reg2.test(user2) != true){
        layer.tips('密码格式有误', '.admin-poke>input:nth-child(2)', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }
});
// $(".admin-poke>input:nth-child(3)").blur(function(){
//      var user3 = $(".admin-poke>input:nth-child(3)").val();
//       if(user3.trim() != "" && user3.length != 4){
//         layer.tips('验证码有误', '.admin-poke>input:nth-child(3)', {
//             tips: [1, '#ff0000'],
//             time: 2000
//         });
//     }
// })

/*登录*/
$(".dbl").click(function (){
    var user1 = $(".admin-poke>input:nth-child(1)").val();
    var user2 = $(".admin-poke>input:nth-child(2)").val();
    var user3 = $(".admin-poke>input:nth-child(3)").val();
    var reg3 = /^([a-z0-9]){4,11}$/;
    var reg2 = /^([a-z0-9]){6,16}$/;
    if(reg3.test(user1)!=true){
        layer.tips('账号格式有误', '.admin-poke>input:nth-child(1)', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(reg2.test(user2) != true){
        layer.tips('密码格式有误', '.admin-poke>input:nth-child(2)', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }/*else if(user3.trim() == "" || user3.length != 4){
        layer.tips('验证码有误', '.admin-poke>input:nth-child(3)', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }*/else{
        //ajax请求
        $.ajax({
            type: 'post',
            async: true,
            url: "/homes.login",
            data: {
                'user_name': user1,
                'password': user2,
                'loginVericode' : user3
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    window.location.href = "players.account-security";
                    return false;
                }
                if(data.success == false){
                    layer.tips(data.message, '.admin-poke>input:nth-child(1)', {
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
                        layer.tips(xhr.responseJSON.message, '.admin-poke>input:nth-child(1)', {
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

//刷新主账户
//刷新
$("#mainAccountRefresh").on("click", function (e) {
    e.preventDefault();
    $.ajax({
        url: "/players.accountRefresh",
        success: function (data) {
            layer.msg('刷新成功');
            $('.mainAccountAmount').html(data.data);
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

//验证码变换
$('.captchaChange').on('click', function(){
    captcha($(this));
});
//验证码
function captcha(element){
    $.ajax({
        url : "homes.captcha",
        dataType : 'json',
        success : function(resp){
            if(resp.success){
                $(element).html(resp.data);
                code = resp.data;
                return;
            }
        },
        error : function(){
            return ;
        }
    });
}


/*鼠标移入下拉*/
$("#nav li").hover(function(){
    $(this).children("ul").fadeIn();
},function(){ 
	$(this).children("ul").css('display','none');
});


$(".Logging-Out").click(function(){
    layer.confirm('确定要退出吗？', {
        btn: ['确定','取消'] //按钮
    },function () {
        window.location.href = "players.logout";
        },function () {

        }
    );
});

$("marquee").click(function(){
    layer.alert('   尊敬的博赢国际会员您好！全新版本已上线，给您带来全新的体验效果！祝您好运！！！')
});

//导航
function menuFix() {
    var sfEls = document.getElementById("nav").getElementsByTagName("li");
    for (var i=0; i<sfEls.length; i++) {
        sfEls[i].onmouseover=function() {
            this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onMouseDown=function() {
            this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onMouseUp=function() {
            this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onmouseout=function() {
            this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"),"");
        }
    }

    window.onload=menuFix;
}

/*注册弹窗*/
$('.forget').click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['645px', '340px'], //宽高
        content: $('#forget')
    });
});

/*首页图片切换*/
$('.content-footer-nav>div:nth-child(1)').mouseover(function(){
    $('.w-ck1').css('background'," url('../app/img/b-ck1.png')");
    $('.content-footer-nav>div:nth-child(1)>a').css('color','#2ac0ff')
});
$('.content-footer-nav>div:nth-child(1)').mouseout(function(){
    $('.w-ck1').css('background'," url('../app/img/w-ck1.png')");
    $('.content-footer-nav>div:nth-child(1)>a').css('color','#fff')
});
$('.content-footer-nav>div:nth-child(2)').mouseover(function(){
    $('.w-ck2').css('background'," url('../app/img/b-ck3.png')");
    $('.content-footer-nav>div:nth-child(2)>a').css('color','#2ac0ff')
});
$('.content-footer-nav>div:nth-child(2)').mouseout(function(){
    $('.w-ck2').css('background'," url('../app/img/w-ck3.png')");
    $('.content-footer-nav>div:nth-child(2)>a').css('color','#fff')
});
$('.content-footer-nav>div:nth-child(3)').mouseover(function(){
    $('.w-ck3').css('background'," url('../app/img/b-ck4.png')");
    $('.content-footer-nav>div:nth-child(3)>a').css('color','#2ac0ff')
});
$('.content-footer-nav>div:nth-child(3)').mouseout(function(){
    $('.w-ck3').css('background'," url('../app/img/w-ck4.png')");
    $('.content-footer-nav>div:nth-child(3)>a').css('color','#fff')
});
$('.content-footer-nav>div:nth-child(4)').mouseover(function(){
    $('.w-ck4').css('background'," url('../app/img/b-ck2.png')");
    $('.content-footer-nav>div:nth-child(4)>a').css('color','#2ac0ff')
});
$('.content-footer-nav>div:nth-child(4)').mouseout(function(){
    $('.w-ck4').css('background'," url('../app/img/w-ck2.png')");
    $('.content-footer-nav>div:nth-child(4)>a').css('color','#fff')
});


/*忘记密码切换*/
/*从右往左切*/
$(".forget-password-li1").click(function(){
    if($((".forget-password-li2")).css("border-bottom") ==("1px solid rgb(255, 183, 0)")){
        $('.forget-password-main').css('display',"block");
        $('.forget-password-main1').css('display',"none");
        $('.forgt-password-img1').css('background','url(../app/img/forgt-password1.png)');
        $('.forgt-password-img3').css('background','url(../app/img/forgt-password3.png)');
        $('.forget-password-img2').css('background','url(../app/img/forgt-password22.png)');
        $('.forget-password-li2').css('border-bottom','1px solid #ddd');
        $('.forget-password-li1').css('border-bottom','1px solid #ffb700');
    }
});

/*从左往右切*/
$('.password1-to-go').click(function(){
    var btton=$(this).html();
    var bottom="请您输入您的"+btton+"码";
    var reg2 =/^(13[0-9]|15[012356789]|17[013678]|18[0-9]|14[57])[0-9]{8}$/;
    var reg3 =/[a-z0-9]{4,11}/;
    var userde = $(".password-name").val();
    if (userde == "") {
        layer.tips('账号不能为空', '.password-name', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(reg3.test(userde)!=true){
        layer.tips('账号输入不对', '.password-name', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
	    $('.forget-password-main').css('display',"none");
	    $('.forget-password-main1').css('display',"block");
	    $('.forgt-password-img1').css('background','url(../app/img/forgt-password11.png)');
	    $('.forgt-password-img3').css('background','url(../app/img/forgt-password33.png)');
	    $('.forget-password-img2').css('background','url(../app/img/forgt-password2.png)');
	    $('.forget-password-li1').css('border-bottom','1px solid #ddd');
	    $('.forget-password-li2').css('border-bottom','1px solid #ffb700');
    }
    $(".tel").html($(".pass-word").val());
    $(".to-text").attr("placeholder",bottom);
});

//重置密码第一步
$('.password1-to-go1').click(function(){
    var userde =Number( $(".to-text").val());;
    if (userde == "") {
        layer.tips('不能为空', '.to-text', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else {
        $('.forget-password-main1').css('display', "none");
        $('.forget-password-main2').css('display', "block");
        $('.forgt-password-img1').css('background', 'url(../app/img/forgt-password11.png)');
        $('.forget-password-img2').css('background', 'url(../app/img/forgt-password22.png)');
        $('.forget-password-img3').css('background', 'url(../app/img/forgt-password3.png)');
        $('.forget-password-li3').css('border-bottom', '1px solid #ffb700');
        $('.forget-password-li2').css('border-bottom', '1px solid #ddd');
    }
});
 
//重置密码第二步
$(".password1-to-go2").click(function(){
    var userde = $(".to-pass").val();
    var reg3 =/[a-zA-Z0-9]{6,16}/;
    if(reg3.test(userde)!= true){
        layer.tips('密码要6到16位数字或者英文', '.to-pass', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if( $(".to-pass1").val()!==userde){
        layer.tips('两次密码不一样', '.to-pass1', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
        layer.confirm('确定保存了吗？', {
            btn: ['确定'] 
        }, function(){
            layer.msg('提交成功', {icon: 1});
            window.location.href='../usercenter/usercenter-deposit.html;'
        }, function(){

        });
    }
});

/*注册*/

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

/*个人中心*/
$(function(){
    $("#nav-main li").hover(function(){
        $(this).children("a").toggleClass("on");
    })
});

function mainFix() {
    var sfEls = document.getElementById("nav").getElementsByTagName("li");
    for (var i=0; i<sfEls.length; i++) {
        sfEls[i].onmouseover=function() {
            this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onMouseDown=function() {
            this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onMouseUp=function() {
            this.className+=(this.className.length>0? " ": "") + "sfhover";
        }
        sfEls[i].onmouseout=function() {
            this.className=this.className.replace(new RegExp("( ?|^)sfhover\\b"),"");
        }
    }

    window.onload=mainFix;
}
/*日历*/
function playproduction() {
    language = document.getElementById("language").value;
    game = document.getElementById("game").value;
    window.open("http://cache.download.banner.greenjade88.com/casinoclient.html?language=" + language + "&game=" + game);

}
function productionlink() {
    language = document.getElementById("language").value;
    game = document.getElementById("game").value;
    document.getElementById("productionlink").innerHTML = ("http://cache.download.banner.greenjade88.com/casinoclient.html?language=" + language + "&game=" + game);
}

/*鼠标移上去，晃动*/
var rector=3
var stopit=0
var a=1
function init(which){
    stopit=0
    shake=which
    shake.style.left=0
    shake.style.top=0
}
function rattleimage(){
    if ((!document.all&&!document.getElementById)||stopit==1)
        return
    if (a==1){
        shake.style.top=parseInt(shake.style.top)+rector
    }
    else if (a==2){
        shake.style.left=parseInt(shake.style.left)+rector
    }
    else if (a==3){
        shake.style.top=parseInt(shake.style.top)-rector
    }
    else{
        shake.style.left=parseInt(shake.style.left)-rector
    }
    if (a<4)
        a++
    else
        a=1
    setTimeout("rattleimage()",50)
}
function stoprattle(which){
    stopit=1
    which.style.left=0
    which.style.top=0
}


