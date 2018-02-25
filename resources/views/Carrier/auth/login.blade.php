<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>博赢国际运营商登录</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/src/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/src/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="/src/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="/src/css/AdminLTE.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="/src/css/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition login-page">
<div id="bg_blur" style="
    margin-bottom: 30px;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    position: absolute;
    top: 0;
    right: 0;
    bottom: -50px;
    left: 0;
    z-index: -999;
    filter: blur(10px);
"></div>
<div class="login-box">
    <div class="login-logo">
        <a style="color: whitesmoke" href="{{ url('/home') }}"><b>运营商后台 </b>博赢国际</a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        {{--<p class="login-box-msg">开始登录</p>--}}
        <form method="post" class="form" action="{{ Route('login') }}">
            {!! csrf_field() !!}

            <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
                <label for="username" class="form">账号:</label>
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="账户名">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password">密码:</label>
                <input type="password" class="form-control" placeholder="密码" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="row">
                {{--<div class="col-xs-8">--}}
                    {{--<div class="checkbox icheck">--}}
                        {{--<label>--}}
                            {{--<input type="checkbox" name="remember"> 阅读--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        {{--<a href="{{ url('/password/reset') }}">忘记密码</a><br>--}}
        {{--<a href="{{ url('/register') }}" class="text-center">注册新账号</a>--}}

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="/src/js/jquery-1.12.0.min.js"></script>
<script src="/src/js/bootstrap.min.js"></script>
<script src="/src/js/icheck.min.js"></script>

<!-- AdminLTE App -->
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        var date = (new Date()).toDateString();
        var field = "beingWallPaper" + date;
        if(!localStorage || !localStorage[field]){
            $.get('{!! route('beingWallPaper') !!}',{},function(resp){
                if(resp){
                    document.getElementById('bg_blur').style.backgroundImage = "url('" + resp[0] +"')";
                    localStorage[field] = resp[0];
                }
            },'json');
        }else{
            document.getElementById('bg_blur').style.backgroundImage = "url('" + localStorage[field] +"')";
        }
    });
</script>
</body>
</html>
