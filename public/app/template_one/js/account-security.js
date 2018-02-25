//账户安全JS
$(function () {
    var match_email = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i;
    var reg3 =/^[a-zA-Z0-9]{6,20}$/;
    var reg4 =/^[0-9]{6}$/;
    //登录密码修改显示隐藏
    $(document).on('click', '.account-tab', function () {
        var _this = $(this);
        var number = _this.attr('data-value');
        _this.addClass('active').siblings().removeClass('active');
        $('.account-password').hide();
        $('.'+number).show();
    });
    //获取验证码
    $(document).on('click', '.getmsg', function () {
       var _this = $(this);
       var yanzheng = $(this).attr('data-yanzheng');
       var email = $('#enter-phone').val();
        if(email.trim() ==""  || match_email.test(email) != true){
            alertMessage('.enter-phone-test', '邮箱账号格式不正确')
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
                        regCountDown(_this,60,'getmsg');
                    }
                },
                error: function(xhr){
                    if(xhr.responseJSON){
                        alertMessage('.enter-phone-test', xhr.responseJSON.message)
                    }
                }
            });
        }
    });
    //登录密码常规修改
    $(".changgui-sure").on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var id = $("input:hidden[name='player_id']").val();
        var pass=$(".pass-age").val();
        var pass1=$(".pass-age2").val();
        var pass2=$(".pass-age3").val();
        var type=button.attr('data-type');
        var make_sure = true;

        if (type == 'qukuan'){
            if(pass.trim() ==""  || reg4.test(pass) != true){
                make_sure = false;
                alertMessage('.pass-age', '请输入6位数字')
            }

            if(pass1.trim() =="" || reg4.test(pass1) != true){
                make_sure = false;
                alertMessage('.pass-age2', '请输入6位数字')
            }
        }else{

            if(pass.trim() ==""  || reg3.test(pass) != true){
                make_sure = false;
                alertMessage('.pass-age', '请输入6-20字母或者数字')
            }

            if(pass1.trim() =="" || reg3.test(pass1) != true){
                make_sure = false;
                alertMessage('.pass-age2', '请输入6-20字母或者数字')
            }
        }
        if(pass==pass1){
            make_sure = false;
            alertMessage('.pass-age2', '不能和原密码一样')
        }
        if(pass1!=pass2) {
            make_sure = false;
            alertMessage('.pass-age3', '两次密码不一致')
        }
        if (make_sure) {
            button.removeClass('changgui-sure');
            $.ajax({
                type: 'post',
                async: true,
                url: "/userperfectinformation.resetPassword",
                data: {
                    'player_id' : id,
                    'old_password': pass,
                    'password': pass1,
                    'password_confirmation': pass2,
                    'type': type,
                },
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        layer.msg('密码修改成功',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                        location.reload();
                    }
                },
                error: function(xhr){
                    if(xhr.responseJSON){
                        alertMessage('.pass-age', xhr.responseJSON.message)
                    }
                    button.addClass('changgui-sure');
                }
            });
        }
    });
    //登录密码邮箱验证修改
    $(".phone-sure").on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var id = $("input:hidden[name='player_id']").val();
        var pass1=$(".pass-phone1").val();
        var pass2=$(".pass-phone2").val();
        var email = $('#enter-phone').val();
        var code = $('#msgcode').val();
        var type=button.attr('data-type');
        if(pass1.trim() =="" || reg3.test(pass1) != true){
            alertMessage('.pass-phone1', '请输入6-20字母或者数字')
        }else if(email.trim() ==""  || match_email.test(email) != true) {
            alertMessage('.enter-phone-test', '请输入正确的邮箱账号')
        }else if(pass1!=pass2) {
            alertMessage('.pass-phone2', '两次密码不一致')
        }else if(code.trim() =="" || code.length <= 0) {
            alertMessage('.code', '验证码不能为空')
        }else{
            button.removeClass('phone-sure');
            $.ajax({
                type: 'post',
                async: true,
                url: "/userperfectinformation.resetPassword",
                data: {
                    'code':code,
                    'email':email,
                    'player_id' : id,
                    'password': pass1,
                    'type': type,

                },
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        layer.msg('密码修改成功',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                    }
                    window.location.href = "/";
                },
                error: function(xhr){
                    if(xhr.responseJSON.success == false){
                        alertMessage(xhr.responseJSON.code, xhr.responseJSON.message)
                    }
                    button.addClass('phone-sure');
                }
            });
        }
    });
    //修改手机号
    $(".phone-change-sure").on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var email = $('#enter-phone').val();
        var code = $('#msgcode').val();
        if(email.trim() ==""  || match_email.test(email) != true) {
            alertMessage('.enter-phone-test', '邮箱格式不正确')
        }else if(code.trim() =="" || code.length <= 0) {
            alertMessage('.code', '验证码不能为空')
        }else{
            button.removeClass('phone-change-sure');
            $.ajax({
                type: 'post',
                async: true,
                url: "/userperfectinformation.resetPhone",
                data: {
                    'code':code,
                    'email':email
                },
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        layer.msg('邮箱修改成功,请重新登录!',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                        window.location.href = "/";
                    }
                },
                error: function(xhr){
                    if(xhr.responseJSON.success == false){
                        layer.msg(xhr.responseJSON.message,{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                    }
                    button.addClass('phone-change-sure');
                }
            });
        }
    });
    //PT客户端密码修改
    $(".account-pt-sure").on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var id = $("input:hidden[name='player_id']").val();
        var pass1=$(".pass-game2").val();
        var pass2=$(".pass-game3").val();
        if(pass1.trim() =="" || reg3.test(pass1) != true ){
            alertMessage('.pass-game2', '请输入6-20字母或者数字')
        }else if(pass1!=pass2) {
            alertMessage('.pass-game3', '两次密码不一致')
        }else{
            button.removeClass('account-pt-sure');
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
                        layer.msg('PT密码修改成功!',{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                    }
                },
                error: function(xhr){
                    if(xhr.responseJSON.success == false){
                        alertMessage('.pass-game3', xhr.responseJSON.message)
                    }
                    button.addClass('account-pt-sure');
                }
            });
        }

    });
});

