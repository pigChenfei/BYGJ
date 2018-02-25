<!doctype html>
<html lang="ch">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/src/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>


<div class="container text-center">

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('gameFlow') !!}" target="_blank" class="btn btn-default col-sm-12">游戏投注记录</a>
    </div>

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('playerAccount') !!}" target="_blank" class="btn btn-default col-sm-12">查看会员账户</a>
    </div>

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('loginGame') !!}" target="_blank" class="btn btn-default col-sm-12">登录游戏</a>
    </div>

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('userIsOnline') !!}" target="_blank" class="btn btn-default col-sm-12">判断会员是否在线</a>
    </div>

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('depositToPlayerGameAccount') !!}" target="_blank" class="btn btn-default col-sm-12">存款到游戏账户</a>
    </div>

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('withDrawFromPlayerGameAccount') !!}" target="_blank" class="btn btn-default col-sm-12">从游戏账户取款</a>
    </div>

    <div class="col-sm-12" style="margin: 5px">
        <a href="{!! route('synchronizeGameFlowToDB') !!}" target="_blank" class="btn btn-default col-sm-12">同步游戏投注记录到数据库</a>
    </div>

</div>

</body>
</html>