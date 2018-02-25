@extends('Web.mobile.layouts.app')
@section('content')
    <div class="page-group">
      <div class="page page-current" id="page-recommend-friends">
        <header class="bar bar-nav"><a class="icon icon-left back"></a>
          <h1 class="title">推荐好友</h1>
        </header>
        <div class="content native-scroll">
          <div class="list-block">
            <ul>
              <li><a class="item-content item-link" href="{{url('players.myRecommends')}}">
                  <div class="item-inner">
                    <div class="item-title">我要推荐</div>
                  </div></a></li>
              <li><a class="item-content item-link" href="{{url('players.selectmyReferrals')}}">
                  <div class="item-inner">
                    <div class="item-title">我的下线</div>
                  </div></a></li>
              <li><a class="item-content item-link" href="{{url('players.selectAccountStatistics')}}">
                  <div class="item-inner">
                    <div class="item-title">账户统计</div>
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
@endsection