//var personInfo = {
//	'user_name' : account_number,
//	'password' : password,
//	'confirm_password' : confirm_password,
//	'real_name' : name,
//	'birthday' : data,
//	'email' : userde,
//	'mobile' : tel,
//	'qq_account' : qq,
//	'wechat' : wechat,
//	'verification_code' : code,
//	'referral_code' : referral_code
//};

//控制tips提示信息的显示与隐藏
$('#registerForm input').blur(function(){
	if($.trim($(this).val())){
		$(this).siblings('.tips').css('display','none');
	}
});

//点击提交按钮，判断输入框的值是否正确
$('.register-btn').click(function(){
	setTimeout(function(){
		if($('.register_item input').hasClass('error')){
			$('.register_item input').each(function(){
				$(this).siblings('.tips').css('display','none');
			});
		}
	},50); 
});
//============================正则验证============================================================

//账号
jQuery.validator.addMethod("account", function (value, element){
	var pattern = /^[0-9a-zA-Z]*[a-zA-Z]+[0-9a-zA-Z]*$/;
	return this.optional(element) || (pattern.test(value));
}, "账号格式不正确(4-11位字符，仅可输入字母和数字，且不能为纯数字)");

//密码
jQuery.validator.addMethod("pwd", function (value, element) {
	var pattern = /^[0-9a-zA-Z]{6,16}$/;
	return this.optional(element) || (pattern.test(value));
}, "密码格式不正确（6-16位数字或字母）");

//真实姓名
jQuery.validator.addMethod("username", function (value, element) {
	var pattern = /^[\u4e00-\u9fa5]{2,20}$/;
	return this.optional(element) || (pattern.test(value));
}, "姓名输入有误(2-20位中文字符，必须与取款银行卡姓名一致)");

//邮箱
jQuery.validator.addMethod("email", function (value, element) {
	var pattern = /^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/;
	return this.optional(element) || (pattern.test(value));
}, "邮箱格式不对");

//手机号
jQuery.validator.addMethod("phone", function (value, element){
	var pattern = /^1(3|4|5|7|8)[0-9]{9}$/;
	return this.optional(element) || (pattern.test(value));
}, "手机号码格式不正确");

//qq
jQuery.validator.addMethod("qq", function (value, element) {
	var pattern = /^[1-9][0-9]{4,}$/;
	return this.optional(element) || (pattern.test(value));
}, "qq格式不正确");

//微信
jQuery.validator.addMethod("wechat", function (value, element) {
	var pattern = /^[a-zA-Z\d_]\w{5,}$/;
	return this.optional(element) || (pattern.test(value));
}, "微信号格式不正确");

//邀请码
jQuery.validator.addMethod("refer", function (value, element) {
	var pattern = /^[a-zA-Z]{6}$/i;
	return this.optional(element) || (pattern.test(value));
}, "邀请码格式不正确（6位英文字符）");

//验证码
jQuery.validator.addMethod("identify", function (value, element) {
	var pattern = /^[0-9]{4}$/;
	return this.optional(element) || (pattern.test(value));
}, "验证码格式不正确");


//====================表单验证与错误提示=======================================

$('#registerForm').validate({
	errorClass: "error",
	validClass: "checked",
	errorElement: "span",
	errorPlacement: function(error, element) {
		// 改变提示元素的位置
		$(element).parent().append(error);
	},
	//每一个元素验证成功的回调
	success: function(label){
		$(label).removeClass("error").siblings('.valid').addClass('availble');
	},
	//每一个元素验证失败的回调
	fail:function(ele){
		$(ele).siblings('.valid').removeClass('availble');
	},
	rules:{
		user_name:{
			required:true,
			account:true,
			minlength:4,
			maxlength:11
//			remote: {
//	          type: "post",
//	          url: "/homes.register",
//	          data: {
//	            user_name: function() {
//	              return $("#user_name").val();
//	            }
//	          },
//	          dataType: "json",
//	          dataFilter: function(data, type) {
//	            if (data == "true")
//	              return true;
//	            else
//	              return false;
//	          }
//	        }
		},
		password:{
			required:true,
			pwd:true,
			minlength:6,
			maxlength:20
		},
		confirm_password:{
			required:true,
			equalTo:'#password'
		},
		// real_name:{
		// 	username:true,
		// 	minlength:2,
		// 	maxlength:20
		// },
		referral_code:{ 
			refer:true
		}, 
		verification_code:{
			identify:true,
			required:true,
			minlength:4, 
			maxlength:4
//			remote:{
//				type: "post",
//		          url: "",
//		          data: {
//		            user_name: function() {
//		              return $("#verification_code").val();
//		            }
//		          },
//		          dataType: "json",
//		          dataFilter: function(data, type) {
//		            if (data == "true")
//		              return true;
//		            else
//		              return false;
//		          }
//			}
		},
		// mobile:{
		// 	required:true,
		// 	phone:true
		// },
		// qq_account:{
		// 	qq:true,
		//
		// },
		// wechat:{
		// 	wechat:true
		// },
		// email:{
		// 	required:true,
		// 	email:true
		// },
		protocol:'required'
		//birthday:'required'
	},
	messages:{
		user_name:{ 
			required:'必填项',
			minlength:'账号最少4位',
			maxlength:'账号最多11位',
			remote:'该账号已存在'
		},
		password:{
			required:'必填项',
			minlength:'密码最少6位',
			maxlength:'密码最多为16位'
		},
		confirm_password:{ 
			required:'必填项',
			equalTo:'两次密码不一致' 
		},
		// real_name:{
		// 	minlength:'姓名最少2个字符',
		// 	maxlength:'姓名最多为20个字符'
		// },
		verification_code:{ 
			required:"必填项",
			minlength:"验证码4位字符",
			maxlength:'验证码4位字符',
			remote:'验证码输入有误'
		},
		// mobile:{
		// 	required:'必填项',
		// 	phone:'手机号码格式不正确'
		// },
		// qq_account:{
		// 	qq:'qq号码格式不正确'
		// },
		// wechat:{
		// 	wechat:'微信号格式不正确'
		// },
		// email:{
		// 	required:'必填项',
		// 	email:'电子邮件格式不正确'
		// },
		protocol:'请同意我们的服务条款'
		//birthday:'请选择出生日期'
	},

	submitHandler:function(form){
		//验证通过后的代码写在这里
		var data = $('form').serialize();
		$.ajax({
			type: 'post',
			url: "/homes.register",
			data: data,
			dataType: 'json',
			success: function (data) {
				if (data.success == true) {
					window.location.href = "/players.account-security";
					return false;
				}
			},
			error:function(xhr){  
				if(xhr.responseJSON.success == false) {
					if(xhr.responseJSON.fields == 'verification_code'){
						layer.tips(xhr.responseJSON.message, '#verification_code', {
							tips: [1, '#ff0000'],
							time: 2000
						});	

					}else{
						layer.tips(xhr.responseJSON.message, '#user_name', {
							tips: [1, '#ff0000'],
							time: 2000
						});
					}
				}
				return false;
			}
		});
    }  
});

//日期选择器
$.jeDate(".datainp",{
    format:"YYYY-MM-DD", 
    isTime:false,
    minDate:$.nowDate(-60*365),
    maxDate:$.nowDate(-17*365),
    isToday:false,
    isClear:false,
    isinitVal:true,
    initAddVal:[-365*30,'DD'] 
});
