@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-personal-center">
        <!--工具栏-->
        @include('Web.mobile.layouts.index_nav')
        <!--内容区-->
        <div class="content native-scroll" id="page-personal-center">
          <div class="card userpic unlogin logined">
            <div class="card-content-inner"><img src="{!! asset('./app/mobile/img/icons/pic.png') !!}">
              <div class="hyinfos">
                <p><span>{{\WinwinAuth::memberUser()->playerLevel?\WinwinAuth::memberUser()->playerLevel->level_name:'普通会员'}}</span></p>
                <p><span>账号:</span><span class="nick">{{$player->user_name}} </span></p>
              </div>
            </div>
          </div>
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link" href="{!! route('players.account-info') !!}">
                  <div class="item-media"><i class="icon icon-ww icon-personal_info"></i></div>
                  <div class="item-inner">
                    <div class="item-title">个人信息</div>
                  </div></a></li>
              <li>
                <a class="item-content item-link" href="{!! url('players.selectChangepwd') !!}">
                  <div class="item-media"><i class="icon icon-ww icon-change_password"></i></div>
                  <div class="item-inner">
                    <div class="item-title">修改密码</div>
                  </div></a>
                </li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link" href="{{url('players.selectTab')}}">
                  <div class="item-media"><i class="icon icon-ww icon-financial_statements"></i></div>
                  <div class="item-inner">
                    <div class="item-title">财务报表</div>
                  </div></a></li>
              <li><a class="item-content item-link" href="{{url('players.mobilefriends')}}">
                  <div class="item-media"><i class="icon icon-ww icon-recommend_friend"></i></div>
                  <div class="item-inner">
                    <div class="item-title">推荐好友</div>
                  </div></a></li>
              <li><a class="item-content item-link" href="{{url('players.sms-subscriptions')}}">
                  <div class="item-media"><i class="icon icon-ww icon-message"></i></div>
                  <div class="item-inner">
                    <div class="item-title">站内信息</div>
                  </div></a></li>
            </ul>
          </div>
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link" href="{!! route('homes.contactCustomer',['type'=>'about']) !!}">
                  <div class="item-media"><i class="icon icon-ww icon-aboutus"></i></div>
                  <div class="item-inner">
                    <div class="item-title">关于我们</div>
                  </div></a></li>
              <li><a class="item-content item-link" href="{!! route('homes.contactCustomer',['type'=>'contact']) !!}">
                  <div class="item-media"><i class="icon icon-ww icon-mobile"></i></div>
                  <div class="item-inner">
                    <div class="item-title">联系我们</div>
                  </div></a></li>
              <li><a class="item-content item-link login-out" href="javascript:;">
                  <div class="item-media"><i class="icon icon-ww icon-exit"></i></div>
                  <div class="item-inner">
                    <div class="item-title">退出登录</div>
                  </div></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('script')
  <script src="//g.alicdn.com/sj/lib/zepto/zepto.min.js"></script>
  <script>$.config = {router: false};</script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js"></script>
  <script src="//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js"></script>
  <script src="{!! asset('./app/mobile/js/common.min.js') !!}"></script>
    <script>
        $('.login-out').on('click', function () {
            $.confirm('确定要退出登录吗?', function () {
                location.href='/players.logout'
            });
        })
    </script>
@endsection