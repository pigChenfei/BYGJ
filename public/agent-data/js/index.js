//登录
$("#form_login").submit(function(e){
	e.preventDefault();
	var pattern_user = /^[a-zA-Z]{6,16}$/;
	var pattern_pwd = /^[0-9a-zA-Z]{6,20}$/;
	//var pattern_code = /^[0-9a-zA-Z]{4}$/;
	
	var user = pattern_user.test($.trim($('#account').val()));
	var pwd = pattern_pwd.test($.trim($('#pwd').val()));
	//var code = pattern_code.test($.trim($('#code').val()));
	if(user && pwd){
		var data = $(this).serialize();
		console.log(data);
		console.log('成功');
	}else{
		console.log('输入有误');
	}

	
});


