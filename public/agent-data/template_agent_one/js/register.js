
//账号
jQuery.validator.addMethod("account", function (value, element) {
    var pattern = /^[a-zA-Z0-9]{4,11}$/;
    return this.optional(element) || (pattern.test(value));
}, "账号格式不正确");

//密码
jQuery.validator.addMethod("pwd", function (value, element) {
    var pattern = /^[0-9a-zA-Z]{6,20}$/;
    return this.optional(element) || (pattern.test(value));
}, "密码格式不正确");


//真实姓名
jQuery.validator.addMethod("username", function (value, element) {
    var pattern = /^[\u4e00-\u9fa5a-zA-Z]{2,10}$/;
    return this.optional(element) || (pattern.test(value));
}, "姓名输入有误");

//邮箱
jQuery.validator.addMethod("email", function (value, element) {
    var pattern = /^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/;
    return this.optional(element) || (pattern.test(value));
}, "邮箱格式不对");
//手机验证规则
jQuery.validator.addMethod("phone", function (value, element) {
    var pattern = /^(13[0-9]|15[012356789]|17[013678]|18[0-9]|14[57])[0-9]{8}$/;
    return this.optional(element) || (pattern.test(value));
}, "手机号格式不正确");

//验证码
jQuery.validator.addMethod("refercode", function (value, element) {
    var pattern = /^[0-9]{4}$/;
    return this.optional(element) || (pattern.test(value));
}, "验证码格式不对");

$(function(){
    $.validator.setDefaults({
        errorClass:'msg font-red f14',
        errorElement:'div',
        errorPlacement : function(error, element) {//error为错误提示对象，element为出错的组件对象
            if (element.is(":radio")){
                error.css("display","block").appendTo(element.parent().parent()) ;
            }else{
                if (element.parent().find('div.msg.font-red.f14').length > 0){
                    element.parent().find('div.msg.font-red.f14').remove();
                    element.parent().append(error) ;
                } else {
                    element.parent().append(error) ;//默认是加在 输入框的后面。这个else必须写。不然其他非radio的组件 就无法显示错误信息了。
                }
            }
        }

    });
    $("#jsForm").validate({
        submitHandler: function(form,e) {
            e.preventDefault();
            e.stopPropagation();
            var button = $(this);
            //验证通过后 的js代码写在这里
            var data = $(form).serialize();
            button.attr('disabled', true);
            $.ajax({
                type : 'post',
                url : '/agents.register',
                data : data,
                success: function (data) {
                    layer.msg('操作成功，请等待审核',{
                        success: function(layero, index){
                            var _this = $(layero);
                            _this.css('top', '401.5px');
                        }
                    });
                    // location.reload();
                   setTimeout(function(){
                       location.reload();
                   },2000);
                },
                error:function(xhr){
                    button.attr('disabled', false);
                    if(xhr.responseJSON.field == 'agent_level'){
                        layer.msg(xhr.responseJSON.message,{
                            success: function(layero, index){
                                $(layero).css('top', '401.5px');
                            }
                        });
                    }else{
                        var errEle = $('input[name='+ xhr.responseJSON.field +']').parent();
                        if (errEle.find('div.msg.font-red.f14').length > 0) {
                            errEle.find('div.msg.font-red.f14').html(xhr.responseJSON.message);
                        }else{
                            errEle.append('<div class="msg font-red f14">'+xhr.responseJSON.message+'</div>');
                        }
                    }
                    if (xhr.responseJSON.field == 'refercode'){
                        $('#jsForm').find('img').attr('src', '/captcha?r='+Math.random());
                    }
                    if (!xhr.responseJSON.field){
                        layer.msg(xhr.responseJSON.message,{
                            success: function(layero, index){
                                $(layero).css('top', '401.5px');
                            }
                        });
                    }
                    return false;
                }

            });
        },
        success: function (div) {
            div.remove();
        }
    });
    //联系方式选择
    $(document).on('click', '.content-type', function(){
       var _this = $(this);
       var type = _this.attr('data-value');
       var mes = _this.find('a').text();
       $('.sm').attr('name', type);
       _this.parent().prev().html(mes);
    });
    //代理类型选择联动
    $(document).on('click', '.agent_level_one', function(){
       var _this = $(this);
        var mes = _this.find('a').text();
       var type = _this.attr('data-value');
       var dataMes = '';
        $.ajax({
            type : 'post',
            url : '/agents.dataAgentLevel',
            data : {type:type},
            dataType : 'json',
            success: function (data) {
                $('#erjidaili').css('display','inline-block');
                $('input[name=agent_level_id]').val('');
                _this.parent().prev().html(mes);
                $('#agent_level_two').prev().html('--请选择--');
                $.each(data.data, function (index, value) {
                    dataMes += ' <li role="presentation" class="agent_level_two" data-value="'+value.id+'">'+
                        '<a role="menuitem" tabindex="-1" href="javascript:void(0)">'+value.level_name+'</a>\n' +
                        '                                </li>';
                })
                $('#agent_level_two').html(dataMes);
            },
            error:function(xhr){
                layer.msg('操作失败，请重试',{
                    success: function(layero, index){
                        var _this = $(layero);
                        _this.css('top', '401.5px');
                    }
                });
            }

        });
    });
    //代理类型选择
    $(document).on('click', '.agent_level_two', function(){
        var _this = $(this);
        var id = _this.attr('data-value');
        var mes = _this.find('a').text();
        $('input[name=agent_level_id]').val(id);
        _this.parent().prev().html(mes);
    });
});