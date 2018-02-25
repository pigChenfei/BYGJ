@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/bg_container.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
    <main style="min-height: 595px;">
      <nav class="usercenter-row">
          <dl style="padding-bottom: 25px;"><b><span>客户服务</span> > <span>短信订阅</span></b></dl>
          <div class="text-mssages"></div>
          <div></div>
       </nav>
        <form class="message-with" action="{!! route('players.deposit') !!}" id="" method='post' name="" >
            <div class="">
                <div><input type="checkbox"/>存款</div>
                <div><input type="checkbox"/>修改密码</div>
                <div><input type="checkbox"/>域名变更</div>
                <div><input type="checkbox"/>修改电话</div>
                <div><input type="checkbox"/>取款</div>
                <div><input type="checkbox"/>修改银行卡资料</div>
                <div><input type="checkbox"/>优惠活动通知</div>
                <div><input type="checkbox"/>网站问候</div>
                <div><input type="checkbox"/>优惠</div>
                <div><input type="checkbox"/>修改账户姓名</div>
                <div><input type="checkbox"/>网站更改收款账号</div>
                <div><input type="checkbox"/>特殊短信</div>
                <div class="clearfix"></div>
            </div>
            <div>
                <div class="btn btn-warning all-checkbox" style="background-color: #f00;width: 100px;height: 36px;font-size: 14px;margin-left: 420px;">全选</div>
               <input class="btn btn-warning messages-go" type="submit" style="width: 100px;height: 36px;font-size: 14px;background-color: #f00;margin-left: 40px;" value="保存"></div>
            </div>
        </nav>
    </main>
@endsection

@section('scripts')
    <script src="{!! asset('./app/js/Customer-Service.js') !!}"></script>
@endsection

