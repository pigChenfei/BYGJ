//验证码变换
$('.captchaChange').on('click', function(){
	captcha($(this));
});
//验证码
function captcha(element){
	$.ajax({
		url : "agents.captcha",
		dataType : 'json',
		success : function(resp){
			if(resp.success){
				$(element).html(resp.data);
				return ;
			}
		},
		error : function(){
			return ;
		}
	});
}
/*登录*/
$("#btn_login").click(function (){
	var username = $("input:text[name='username']").val();
	var password = $("input:password[name='password']").val();
	var refercode =$("input:text[name='refercode']").val();
	var reg3 = /^([a-z0-9]){4,11}$/;
	var reg2 = /^([a-z0-9]){6,16}$/;
	if(reg3.test(username)!=true){
		layer.tips('账号格式有误', '#username', {
			tips: [1, '#ff0000'],
			time: 2000
		});
	}else if(reg2.test(password) != true){
		layer.tips('密码格式有误', '#pwd', {
			tips: [1, '#ff0000'],
			time: 2000
		});
	}/*else if(refercode.trim() == "" || refercode.length != 4 ){
		layer.tips('验证码有误', '#refercode', {
			tips: [1, '#ff0000'],
			time: 2000
		});
	}*/else{
		//ajax请求
		$.ajax({
			type: 'post',
			async: true,
			url: "/agents.login",
			data: {
				'username': username,
				'password': password,
				'refercode' : refercode
			},
			dataType: 'json',
			success: function(data){
				if(data.success == true){
					window.location.href = "agent/admin";
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
					if(xhr.responseJSON.fields =='refercode'){
						layer.tips(xhr.responseJSON.message, '#refercode', {
							tips: [1, '#ff0000'],
							time: 2000
						});
					}else{
						layer.tips(xhr.responseJSON.message, '#username', {
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
