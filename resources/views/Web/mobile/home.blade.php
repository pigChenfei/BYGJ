@extends('Web.mobile.layouts.app')

@section('content')
<div class="page-group">
      <div class="page page-current" id="page-index">
        <!--标题栏-->
        <header class="bar bar-nav">
          <h1 class="title"> </h1>
        </header>
        <!--工具栏-->
        <!--<nav class="bar bar-tab"><a class="tab-item active" href="index.html"><span class="icon icon-ww ico-homepage"></span><span class="tab-label">首页</span></a><a class="tab-item" href="purse.html"><span class="icon icon-ww ico-purse"></span><span class="tab-label">钱包</span></a><a class="tab-item" href="discount.html"><span class="icon icon-ww ico-discount"></span><span class="tab-label">优惠</span></a><a class="tab-item" href="@if(!\WinwinAuth::memberUser()) {{ url('/homes.mobileLogin') }} @else {{ url('/players.account-security') }} @endif"><span class="icon icon-ww ico-me"></span><span class="tab-label">我的</span></a></nav>-->
        @include('Web.mobile.layouts.index_nav')
        <!--内容区-->
        <div class="content native-scroll">
          <!--轮播图-->
          <div class="swiper-container swiper-container-horizontal" data-space-between="10">
            <div class="swiper-wrapper">
              @forelse($images as $k => $v)
                <div class="swiper-slide"><a href="javascript:" style="background-image: url({{$v->imageAsset()}});"></a></div>
              @empty
                <div class="swiper-slide"><a href="javascript:" style="background-image: url(img/common/banner1.jpg);"></a></div>
              @endforelse
            </div>
            <div class="swiper-pagination"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>
            <div class="mqbox"><span class="icon icon-ww f-l icon-sound"></span>
              <!--公告栏-->
              <marquee class="f-r" scrollamount="3" direction="left">{!! WTemplate::notice() !!}</marquee>
            </div>
          </div>
           <!--标签页-->
          <div class="buttons-tab"><a class="tab-link button active" href="#tab1">推荐</a><a class="tab-link button" href="#tab2">真人</a><a class="tab-link button" onclick="location.href = '{!! url('homes.slot-machine') !!}'">电游</a><a class="tab-link button" href="#tab4">体育</a><a href="#tab5" class="tab-link button" >彩票</a></div>
          <div class="content-block">
            <div class="tabs">
              <div class="tab active" id="tab1">
                <ul class="image-view clearfix">
                  @foreach($games as $game)
                  <li class="tab-item col-half bg-paleturquoise text-center"><a data-href="{{ route('players.joinElectronicGame', ['game_id'=>$game->game->game_id]) }}" style="background-image: url('{{$game->game->game_icon_path}}');"></a><i class="icon-ww collect {!! $game->collect_info?'icon-collection_fill fontred':'icon-collection' !!}" data-action="{!! $game->collect_info ?0:1 !!}" data-id="{!! $game->id !!}"></i><span>{{ $game->display_name }}</span></li>
                   @endforeach
                </ul>
              </div>
              <div class="tab" id="tab2">
                <ul class="image-view clearfix">
                  <li class="tab-item col-half bg-lavenderblush" ><a style="background-image: url('/app/mobile/img/bbin.jpg');" data-href="{!! url('players.loginBBinHall/live')!!}"></a></li>
                  <li class="tab-item col-half bg-lavenderblush" ><a style="background-image: url('/app/mobile/img/pt.jpg');" data-href="{!! url('players.loginPTGame/bal') !!}"></a></li>
                  <li class="tab-item col-half bg-lavenderblush" ><a style="background-image: url('/app/mobile/img/sunbet.jpg');" data-href="{!! url('players.gameLauncher/SB/Sunbet_Lobby')!!}"></a></li>
{{--                  <li class="tab-item col-half bg-lavenderblush" ><a style="background-image: url('/app/mobile/img/gd.jpg');" data-href="{!! url('players.gameLauncher/GD/Gold_Deluxe_Lobby')!!}"></a></li>--}}
                  <li class="tab-item col-half bg-lavenderblush" ><a  style="background-image: url('/app/mobile/img/mg.jpg');" data-href="{!! url('players.launchItem/1210/1002')!!}"></a></li>
                  <li class="tab-item col-half bg-lavenderblush" ><a style="background-image: url('/app/mobile/img/ag.jpg');" data-href="javascript:"></a></li>
                  <li class="tab-item col-half bg-lavenderblush" ><a style="background-image: url('/app/mobile/img/ag.jpg');" data-href="javascript:"></a></li>
                </ul>
              </div>
              <div class="tab" id="tab3">
                <ul class="image-view clearfix">
                  <li class="tab-item col-half bg-lightsalmon"><a href="javascript:"></a></li>
                </ul>
              </div>
              <div class="tab" id="tab4">
                <ul class="image-view clearfix">
                  <li class="tab-item col-half bg-chocolate"><a data-href="{!! url('players.loginBBinHall/ball') !!}" style="background-image: url('/app/mobile/img/bbinball.jpg');"></a></li>
                  <li class="tab-item col-half bg-chocolate"><a data-href="{!! route('players.loginOneWorkHall') !!}" style="background-image: url('/app/mobile/img/onworksball.jpg');"></a></li>
                </ul>
              </div>
              <div class="tab" id="tab5">
                <ul class="image-view clearfix">
                  <li class="tab-item col-half bg-pink"><a href="javascript:" data-href="{!! url('players.loginVRHall') !!}" style="background-image: url('/app/mobile/img/vrlottery.jpg');"></a></li>
                  <li class="tab-item col-half bg-pink"><a href="javascript:" data-href="{!! url('players.loginBBinHall/Ltlottery') !!}" style="background-image: url('/app/mobile/img/bbinlottery.jpg');"></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="layui-m-layer layui-m-layer2" id="layui-m-layer0" index="0">
      <div class="layui-m-layershade"></div>
      <div class="layui-m-layermain">
        <div class="layui-m-layersection">
          <div class="layui-m-layerchild layui-m-anim-scale">
            <div class="layui-m-layercont"><i></i><i class="layui-m-layerload"></i><i></i>
              <p>加载中...</p>
            </div>
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
  <script type="text/javascript">
    $(".swiper-container").swiper({
      autoplay : 3000,
      loop : true,
      autoplayDisableOnInteraction : false
    });
    $('.col-half a').on('click',function(event){
        var _this = $(this);
      if(event.target === this){
        if(!islogin){
          location.href ="{{ url('/homes.mobileLogin') }}";
        }else {
            $.confirm('为了确保游戏顺利进行,请确认您的余额是否充足！确定进入转账中心，取消直接进入游戏', '温馨提示',
                function () {
                    location.href = '/players.account-transfer';
                },
                function () {
                    location.href = _this.attr('data-href');
                }
            );
        }
      }
    });
    //收藏按钮在收藏和已收藏样式切换
    $(document).on('click','.collect',function(event){
        event.preventDefault();
        event.stopPropagation();
        if (!islogin){
            $.confirm('想要收藏，请先登录', function () {
                location.href ="{{ url('/homes.mobileLogin') }}";
            });
        }else{
            var _this = $(this);
            var action = _this.attr('data-action');
            var carrier_game_id = _this.attr('data-id');
            var action_change = 0;
            if (action == 0){
                action_change = 1;
            }
            _this.removeClass('collect');
            $.ajax({
                type: 'post',
                url: "{{route('players.collectGame')}}",
                data: {action:action,carrier_game_id:carrier_game_id},
                dataType: 'json',
                success: function(data){
                    if(data.success == true){
                        _this.attr('data-action', action_change);
                        _this.toggleClass('icon-collection').toggleClass('icon-collection_fill').toggleClass('fontred');
                    }
                    _this.addClass('collect');
                    tools.tip(data.data);
                },
                error: function(xhr){
                    var o = JSON.parse(xhr.response);
                    if(o.success==false) {
                        tools.tip(o.message);
                    };
                    _this.addClass('collect');
                }
            });
        }
    });
    $('#cp').on('click',function(){
      if(!islogin){
        location.href ="{{ url('/homes.mobileLogin') }}";
      }else {
        location.href = $(this).attr('data-href');
      }
    });
    $(document).on('click', '.modal-overlay', function(){
      $(this).prev().remove();
      $(this).remove();
    })
  </script>
@endsection