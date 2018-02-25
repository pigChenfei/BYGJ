<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{!! WTemplate::title() !!}</title>
    <meta name="keywords" content="{!! WTemplate::keywords() !!}" />
    <meta name="description" content="{!! WTemplate::description() !!}" />
    <!--[if lt IE 9]>
    <script src="{!! asset('./app/template_one/js/html5shiv.min.js') !!}"></script>
    <script src="{!! asset('./app/template_one/js/respond.min.js') !!}"></script>
    <![endif]-->
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/bootstrap.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/template_one/css/winwin_style.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/js/layer/skin/default/layer.css') !!}"/>
</head>
<body style="min-width: auto;">
<div class="masklayer" style="position: unset;margin-top: 20%;">
    <div class="dialog-wrap">
        <div class="dialog signin-page">
            <div class="dialog-head">忘记密码</div>
            <div class="dialog-body">
                <div class="sign-normal">
                    <div class="form-group password">
                        <span class="glyphicon glyphicon-lock"></span>
                        <input type="password" id="password" placeholder="请输入新密码" value="">
                        <span class="glyphicon eye glyphicon-eye-close"></span>
                    </div>
                    <div class="form-group confirm-password">
                        <span class="glyphicon glyphicon-lock"></span>
                        <input type="password" id="confirm-password" placeholder="请确认密码" value="">
                        <span class="glyphicon eye glyphicon-eye-close"></span>
                    </div>
                    <input type="hidden" name="v_code" value="{!! $v_code !!}">
                </div>
                <div class="msg font-red f14"></div>
            </div>
            <div class="dialog-foot">
                <botton class="btn btn-warning tx_forget_sure">确认</botton>
            </div>
        </div>
    </div>
</div>
</body>
<script src="{!! asset('./app/template_one/js/jquery.min.js') !!}"></script>
<script src="{!! asset('./app/template_one/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('./app/js/layer/layer.js') !!}"></script>
<script>
    $(function () {
        var reg2 = /^([a-zA-Z0-9]){6,20}$/;
        $('.eye').click(function(){
            var _this = $(this);
            var _input = _this.prev();
            if (_this.hasClass('glyphicon-eye-close')){
                _this.addClass('glyphicon-eye-open').removeClass('glyphicon-eye-close')
            }else {
                _this.addClass('glyphicon-eye-close').removeClass('glyphicon-eye-open')
            }
            if(_input.attr('type') == 'text'){
                _input.attr('type','password')
            } else{
                _input.attr('type','text')
            }
        });
        $(document).on('click','.tx_forget_sure', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var _this = $(this);
            var pwd1 = $("#password").val(); //密码长度6-20位
            var pwd2 = $("#confirm-password").val();
            var v_code = $("input[name=v_code]").val() ;

            if(pwd1.trim() =="" || reg2.test(pwd1) != true){
                errorMessage('.msg.font-red.f14','密码为6-20字母或者数字')
            }else if(pwd1 != pwd2) {
                errorMessage('.msg.font-red.f14','两次密码不一致')
            }else if(v_code.trim() =="" || v_code.length <= 0) {
                errorMessage('.msg.font-red.f14','校验码不能为空')
            }else{
                _this.removeClass('tx_forget_sure');
                $.ajax({
                    type: 'get',
                    url: "/homes.mobileForgetVerify",
                    data: {
                        'password' : pwd1,
                        'forget_code' : v_code,
                        'info' : 'player',
                    },
                    dataType: 'json',
                    success: function(data){
                        if(data.success == true){
                            layer.msg('密码修改成功');
                        }
                        // window.location.href='/';
                    },
                    error: function(xhr){
                        if(xhr.responseJSON.success == false){
                            errorMessage('.msg.font-red.f14',xhr.responseJSON.message)
                        }
                        _this.addClass('tx_forget_sure');
                    }
                });
            }
        })
    })
    //错误提示
    function errorMessage(_this, message){
        $(_this).html(message);
        return true;
    }
</script>
</html>