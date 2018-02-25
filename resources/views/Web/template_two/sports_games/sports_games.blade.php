@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/sports_bet.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section id="myCarousel" class="carousel slide" data-riad="carousel" data-interval="2500">
        <!-- 轮播（Carousel）指标 -->
        <ol class="carousel-indicators">
            @forelse($images as $k => $v)
                <li data-target="#myCarousel" data-slide-to="{{$k}}" @if($loop->first)class="active"@endif></li>
            @empty
                <li data-target="#myCarousel" data-slide-to="0"></li>
            @endforelse
        </ol>
        <!-- 轮播（Carousel）项目 -->
        <div class="carousel-inner">
            @forelse($images as $k => $v)
                <div class="item @if($loop->first) active @endif">
                    <a @if (!isset($v->url_type)) href="javascript:void(0)" @elseif($v->url_type == 0) href="{{$v->url_link}}" target="_blank" @elseif($v->url_type == 1) class="tx_login_game" href="{{$v->url_link}}" @endif style="background-image:url({{$v->imageAsset()}})"></a>
                </div>
            @empty
                <div class="item active">
                	<a href="javascript:void(0)" style="background-image:url({!! asset('./app/template_one/img/sport/banner1.jpg') !!})"></a>
                </div>
            @endforelse
        </div>
    </section>

    <section class="sport-main">
        <div class="main-wrap clearfix">
            <div class="tab-item sport2">
                <div class="joingame">
                    <div class="logo"></div>
                    <p>沙巴体育是亚洲最热门体育投注平台之一，赛事丰富齐全，深受玩家喜爱。</p>
                    <a class="btn btn-warning tx_login_game" href="{!! route('players.loginOneWorkHall') !!}">进入游戏</a>
                </div>
            </div>
            <div class="tab-item ml-20 sport1">
                <div class="joingame">
                    <div class="logo"></div>
                    <p>波音体育是亚洲最热门体育投注平台之一，赛事丰富齐全，深受玩家喜爱。</p>
                    <a class="btn btn-warning tx_login_game" href="{!! url('players.loginBBinHall/ball') !!}">进入游戏</a>
                </div>
            </div>
        </div>
    </section>
@endsection
