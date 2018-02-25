//账户安全JS
window.onload=function(){
    $(".account-security>div>input[type='text']").each(function(){
	    if($(this).val()==""){
	        $(".security-true").css("display","block");
	    }else{
	        $(".security-true").css("display","none");
	    }
	});
	if($(".security-name").val() !=""){
		$('.security-name').attr("disabled",true); 
	}else{
        $('.security-name').attr("disabled",false); 
    };
	if($(".security-tel").val() != ""){
		$('.security-tel').attr("disabled",true); 
	}else{
        $('.security-tel').attr("disabled",false); 
    };
	if($(".security-e-mail").val() != ""){
		$('.security-e-mail').attr("disabled",true); 
	}else{
        $('.security-e-mail').attr("disabled",false); 
    };
	 if($(".fete-day").val() != ""){
		$('.fete-day').attr("disabled",true); 
	}else{
        $('.fete-day').attr("disabled",false); 
    }
	if($(".security-QQ").val() !=""){
		$('.security-QQ').attr("disabled",true); 
	}else{
        $('.security-QQ').attr("disabled",false); 
    }
	if($(".security-wechat").val() !=""){
		$('.security-wechat').attr("disabled",true); 
	}else{
        $('.security-wechat').attr("disabled",false); 
    }
}

$(".nav-nav li ul li a").mouseover(function(){
    $(".back-nav>div").css("display","none");
});
$(".nav-nav li ul li a").mouseout(function(){
    $(".back-nav>div").css("display","block");
});

$(".bank-sure>img").click(function(){
    $(this).addClass("bank-true").siblings().removeClass("bank-true");
});

function resetCod(){
    $('#J_getCode').hide();
    $('#J_second').html('60');
    $('#J_resetCode').show();
    var second = 60;
    var timer = null;
    timer = setInterval(function(){
        second -= 1;
        if(second >0 ){
            $('#J_second').html(second);
        }else{
            clearInterval(timer);
            $('#J_getCode').show();
            $('#J_resetCode').hide();
        }
    },1000);
}

function resetCo(){
    $('#J_getCod').hide();
    $('#J_secon').html('60');
    $('#J_resetCod').show();
    var second = 60;
    var timer = null;
    timer = setInterval(function(){
        second -= 1;
        if(second >0 ){
            $('#J_secon').html(second);
        }else{
            clearInterval(timer);
            $('#J_getCod').show();
            $('#J_resetCod').hide();
        }
    },1000);
}

function resetCode(){
    $('#J_getCo').hide();
    $('#J_seco').html('60');
    $('#J_resetCo').show();
    var second = 60;
    var timer = null;
    timer = setInterval(function(){
        second -= 1;
        if(second >0 ){
            $('#J_seco').html(second);
        }else{
            clearInterval(timer);
            $('#J_getCo').show();
            $('#J_resetCo').hide();
        }
    },1000);
}

//修改邮箱弹框
$(".Modify-the-mail").click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['365px', '300px'], //宽高
        title:'修改邮箱地址',
        content:$('#tel-mail')
    });
});

$(".Modify-the-passr").click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['270px', '280px'], //宽高
        title:'修改取款密码',
        content:$('#pass-many')
    });
});

$(".Modify-the-passw").click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['270px', '280px'], //宽高
        title:'修改登录密码',
        content:$('#pass-xu')
    });
});

$(".Modify-the-passo").click(function(){
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['270px', '250px'], //宽高
        title:'修改PT专用密码',
        content:$('#pass-gane')
    });
});

$("#true-width").click(function() {
    var userde = $(".with-name").val();
    var reg3 =/^[\u4e00-\u9fa5]{2,40}$/;
    if (userde == "") {
        layer.tips('请输入开户人姓名', '.with-name', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(reg3.test(userde)!= true){
        layer.tips('请输入真实姓名', '.with-name', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if (Number($(".with-kade").val().length) < 16) {
        layer.tips('卡号长度不对', '.with-kade', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if((isNaN(Number($(".with-kade").val().length) < 16))){
        layer.tips('请输入正确卡号', '.with-kade', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if ($(".subsidiary-bank").val() == "") {
        layer.tips('分行不能为空', '.subsidiary-bank', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    } else {
        $('#true-width').click(function(){
            layer.alert('银行卡添加成功');
        });
    }
});

$(".tel-alter-btn4").on('click',function(e){
    e.preventDefault();
    var button = this;
    var id = $("input:hidden[name='player_id']").val();
    var pass=$(".pass-many1").val();
    var pass1=$(".pass-many2").val();
    var pass2=$(".pass-many3").val();
    if( pass.trim() =="" || isNaN(Number(pass)) || pass.length !==6 ){
        layer.tips('请输入6位数字', '.pass-many1', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass1.trim()=="" || pass1.length !==6 ||isNaN(Number(pass1))){
        layer.tips('请输入6位数字', '.pass-many2', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass1==pass){
        layer.tips('新密码不能是原密码', '.pass-many2', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass1!==pass2) {
        layer.tips('两次密码不一致', '.pass-many3', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
        button.disabled = true;
        $.ajax({
            type: 'post',
            async: true,
            url: "/userperfectinformation.resetWithdrawPassword",
            data: {
                'player_id' : id,
                'old_password': pass,
                'password': pass1,
                'password_confirmation': pass2
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    layer.msg('密码修改成功!');
                    window.location.href = data.data;
                }
            },
            error: function(xhr){
                if(xhr.responseJSON){
                    layer.tips(xhr.responseJSON.message, '.show-withdraw>input:nth-child(2)', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }
                button.disabled = false;
            }
        });
    }
});

$(".tel-alter-btn3").on('click',function(e){
    e.preventDefault();
    var button = this;
    var id = $("input:hidden[name='player_id']").val();
    var pass1=$(".pass-game2").val();
    var pass2=$(".pass-game3").val();
    var reg3 =/[a-zA-Z0-9]{6,16}/;

    if(pass1.trim() =="" || reg3.test(pass1) != true ){
        layer.tips('请输入6-16字母或者数字', '.pass-game2', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass1!=pass2) {
        layer.tips('两次密码不一致', '.pass-game3', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
        button.disabled = true;
        $.ajax({
            type: 'post',
            async: true,
            url: "/userperfectinformation.resetPtPassword",
            data: {
                'player_id' : id,
                'password': pass1,
                'password_confirmation': pass2
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    layer.msg('密码修改成功!');
                    window.location.href = data.data;
                }
            },
            error: function(xhr){
                if(xhr.responseJSON){
                    layer.tips(xhr.responseJSON.message, '.show-error>input:nth-child(2)', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }
                button.disabled = false;
            }
        });
    }

});

$(".tel-alter-btn2").on('click',function(e){
    e.preventDefault();
    var button = this;
    var id = $("input:hidden[name='player_id']").val();
    var pass=$(".pass-age").val();
    var pass1=$(".pass-age2").val();
    var pass2=$(".pass-age3").val();
    var reg3 =/[a-zA-Z0-9]{6,16}/;

    if(pass.trim() ==""  || reg3.test(pass) != true){
        layer.tips('请输入6-16字母或者数字', '.pass-age', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass.trim() =="" || reg3.test(pass1) != true){
        layer.tips('请输入6-16字母或者数字', '.pass-age2', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass==pass1){
        layer.tips('不能和原密码一样', '.pass-age2', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if(pass1!=pass2) {
        layer.tips('两次密码不一致', '.pass-age3', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else{
        button.disabled = true;
        $.ajax({
            type: 'post',
            async: true,
            url: "/userperfectinformation.resetPassword",
            data: {
                'player_id' : id,
                'old_password': pass,
                'password': pass1,
                'password_confirmation': pass2
            },
            dataType: 'json',
            success: function(data){
                if(data.success == true){
                    layer.msg('密码修改成功,请重新登录!');
                    window.location.href = "/";
                }
            },
            error: function(xhr){
                if(xhr.responseJSON){
                    layer.tips(xhr.responseJSON.message, '.show-error>input:nth-child(2)', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                }
                button.disabled = false;
            }
        });
    }
});

$(".tel-alter-btn1").click(function(){
    var userde=$(".email-yan").val();
    var mail=/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/;
    var userde1=$(".email-yan3").val();

    if(userde.trim() == "" || mail.test(userde)!=true){
        layer.tips('请输入正确的邮箱', '.email-yan', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if($(".email-yan2").val().trim() == "" ||  isNaN(Number($(".email-yan2").val()))|| $(".email-yan2").val().length!=6){
        layer.tips('请输入正确验证码', '.email-yan2', {
            tips: [1, '#ff0000'],
            time: 2000
        });
    }else if( userde1.trim() == "" || mail.test(userde1)!=true) {
        layer.tips('请输入正确的邮箱', '.email-yan3', {
            tips: [1, '#ff0000'],
            time: 2000
        })
    }else if ( userde==userde1){
        layer.tips('新邮箱地址不能是原邮箱', '.email-yan3', {
            tips: [1, '#ff0000'],
            time: 2000
        })
    }else{
        layer.alert('邮箱修改成功');
    }
});

$(".security-true").on('click',function(e){
 	e.preventDefault();
    var button = this;
    var id = $("input:hidden[name='player_id']").val();
    var sex =$("input[type='radio']:checked").val();
    var birthday = $(".fete-day").val();
 	var mail=/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/;
    var QQ=$(".security-QQ").val();
    var userName =$(".security-name").val();
    var userde=$(".security-e-mail").val();
    var tel=$(".security-tel").val();
    var consignee=$(".consignee-input").val();
    var consignee_address=$(".consignee-address").val();
    var reg3 =/^[\u4e00-\u9fa5]{2,40}$/;
    var weichat=$(".security-wechat").val();
    var mobile=/^1[3-8]\d{9}$/;
    var reg=/^[a-zA-Z\d_]{5,}$/;
		    
    if((userName.trim() == "" || reg3.test(userName) != true) && $('.security-name').attr("vereist") == "yes"){
    	layer.tips('请填写正确的姓名', '.security-name', {
            tips: [1, '#ff0000'],
        });
    	return;
    }else if((tel.trim() == "" || mobile.test(tel) != true )&& $('.security-tel').attr("vereist") == "yes"){
    	layer.tips('请填写正确的手机号', '.security-tel', {
            tips: [1, '#ff0000'],
            time: 2000
        });
        return;
    }else if((userde.trim() == "" || mail.test(userde)!=true)&& $('.security-e-mail').attr("vereist") == "yes"){
        layer.tips('请输入正确的邮箱', '.security-e-mail', {
            tips: [1, '#ff0000'],
            time: 2000
        });
        return;
    }else if(($(".fete-day").val().trim()=="")&& $('.fete-day').attr("vereist") == "yes"){
    	layer.tips('请选择您的生日', '.fete-day', {
            tips: [1, '#ff0000'],
            time: 2000
        });
        return;
    }    
    
    if($('.security-QQ').attr("vereist") == "yes"){
    	if(QQ.trim() == "" || isNaN(Number(QQ)) || QQ.length<5){
    		layer.tips('请输入正确的QQ号', '.security-QQ', {
            	tips: [1, '#ff0000'],
            	time: 2000
        	});
        	return;
    	}
    }else if($('.security-QQ').attr("vereist") != "yes" && QQ.trim() != ""){
    	if(isNaN(Number(QQ)) || QQ.length<5){
    		layer.tips('请输入正确的QQ号', '.security-QQ', {
            	tips: [1, '#ff0000'],
            	time: 2000
        	});
        	return;
    	}
    }
     
    if($('.security-wechat').attr("vereist") == "yes"){
        if(weichat.trim()=="" || reg.test(weichat) != true){
            layer.tips('请输入正确的微信号', '.security-wechat', {
                tips: [1, '#ff0000'],
                time: 2000
            });
            return;
        }
    }else if($('.security-wechat').attr("vereist") != "yes" && weichat.trim() != ""){
        if( reg.test(weichat) != true){
            layer.tips('请输入正确的微信号', '.security-wechat', {
                tips: [1, '#ff0000'],
                time: 2000
            });
            return;
        }
	   	else{            
	        button.disabled = true;
	        $.ajax({
	            type: 'post',
	            url:"/players.perfectUserInformation",
	            async: true,
	            data:{
	                'player_id':id,
	                'real_name':userName,
	                'mobile':tel,
	                'email':userde,
	                'sex':sex,
	                'birthday':birthday,
	                'qq_account':QQ,
	                'wechat':weichat,
	            } ,
	            dataType: 'json',
	            success:function(data){
	                if(data.success == true){
	                    layer.msg('保存成功!');
	                    window.location.href = data.data;
	                }
	            },
	            error: function(xhr){
	                if(xhr.responseJSON){
	                    layer.tips(xhr.responseJSON.message, '#'+xhr.responseJSON.field+'>input:nth-child(2)', {
		                    tips: [1, '#ff0000'],
		                    time: 2000
	                    });
	                    button.disabled = false;
	                }
	            }
	
	        });
        }
    }else {
        button.disabled = true;
        $.ajax({
            type: 'post',
            url:"/players.perfectUserInformation",
            async: true,
            data:{
                'player_id':id,
                'real_name':userName,
                'mobile':tel,
                'email':userde,
                'sex':sex,
                'birthday':birthday,
                'qq_account':QQ,
                'wechat':weichat,
            } ,
            dataType: 'json',
            success:function(data){
                if(data.success == true){
                    layer.msg('保存成功!');
                    window.location.href = data.data;
                }
            },
            error: function(xhr){
                if(xhr.responseJSON){
                    layer.tips(xhr.responseJSON.message, '#'+xhr.responseJSON.field+'>input:nth-child(2)', {
                        tips: [1, '#ff0000'],
                        time: 2000
                    });
                    button.disabled = false;
                }
            }
        });            
	}
});
    
//日期选择
$.jeDate(".datainp",{
    format:"YYYY-MM-DD",
    isTime:false,
    minDate:"1900-09-19 00:00:00"
});

