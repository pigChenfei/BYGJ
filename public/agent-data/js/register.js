
//手机验证规则  
jQuery.validator.addMethod("phone", function (value, element) {
	var pattern = /^(13[0-9]|15[012356789]|17[013678]|18[0-9]|14[57])[0-9]{8}$/;
	return this.optional(element) || (pattern.test(value));
}, "手机号格式不正确");

//账号
jQuery.validator.addMethod("account", function (value, element) {
	var pattern = /^[a-zA-Z]{6,16}$/;
	return this.optional(element) || (pattern.test(value));
}, "账号格式不正确");
//密码
jQuery.validator.addMethod("password", function (value, element) {
	var pattern = /^[0-9a-zA-Z]{6,20}$/;
	return this.optional(element) || (pattern.test(value));
}, "密码格式不正确");

//真实姓名
jQuery.validator.addMethod("username", function (value, element) {
	var pattern = /^[\u4e00-\u9fa5]{2,10}$/;
	return this.optional(element) || (pattern.test(value));
}, "姓名输入有误");

//邮箱
jQuery.validator.addMethod("email", function (value, element) {
	var pattern = /^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/;
	return this.optional(element) || (pattern.test(value));
}, "邮箱格式不对");

//qq
jQuery.validator.addMethod("qq", function (value, element) {
	var pattern = /^[1-9][0-9]{4,}$/;
	return this.optional(element) || (pattern.test(value));
}, "qq格式不对");

//微信
jQuery.validator.addMethod("wechat", function (value, element) {
	var pattern =  /^[\w]{6,20}$/;
	return this.optional(element) || (pattern.test(value));
}, "微信格式不对");

//skype账号
jQuery.validator.addMethod("skype", function (value, element) {
	var pattern =  /^[\w]{6,20}$/;
	return this.optional(element) || (pattern.test(value));
}, "skype格式不对");

//邀请介绍
jQuery.validator.addMethod("introduce", function (value, element) {
	var pattern = /^[0-9a-z\u4e00-\u9fa5]{20,100}$/;
	return this.optional(element) || (pattern.test(value));
}, "邀请介绍格式有误");

//验证码
jQuery.validator.addMethod("refercode", function (value, element) {
	var pattern = /^[0-9]{4}$/;
	return this.optional(element) || (pattern.test(value));
}, "验证码格式不对");


$(function () {
	//验证通过发送请求
	$("#jsForm").validate({
		submitHandler: function(form,e) {
			e.preventDefault();
			var button = this;
			//验证通过后 的js代码写在这里
			var data = $(form).serialize();
			button.disabled = true;
			$.ajax({
				type : 'post',
				url : '/agents.register',
				data : data,
				success: function (data) {
					if (data.success == true) {
						$('#myModal').modal('show');
						setTimeout(function(){
							window.location.href = "/agents.index";
						},3000);
					}
					button.disabled = false;
				},
				error:function(xhr){
					button.disabled = false;
					if(xhr.responseJSON.success == false) {
						$('input[name='+ xhr.responseJSON.field +']').removeClass('valid').addClass('error').next().text(xhr.responseJSON.message)
					}
					return false;
				}

			});
		}
		// rules: {
		// 	account: {
		// 		required: true,
		// 		minlength: 3,
		// 		maxlength: 16,
		// 		remote: {
		// 			type: "post",
		// 			url: "url",
		// 			data: {
		// 				username: function() {
		// 					return $("#account").val();
		// 				}
		// 			},
		// 			dataType: "html",
		// 			dataFilter: function(data, type) {
		// 				if (data == "true")
		// 					return true;
		// 				else
		// 					return false;
		// 			}
		// 		}
		// 	}
		// },
		// success: function(label) {
		// 	//正确时的样式
		// 	label.text(" ").addClass("success");
		// },
		// messages: {
		// 	account: {
		// 		required: "请输入用户名",
		// 		minlength: "用户名长度不能小于3个字符",
		// 		maxlength: "用户名长度不能大于16个字符",
		// 		remote: "用户名已被注册"
		// 	}
		// }
	});

//日期选择
	$.jeDate('#birthday',{
		format:"YYYY-MM-DD",
		isTime:false,
		minDate:"1900-10-19 00:00:00"
	});
});



