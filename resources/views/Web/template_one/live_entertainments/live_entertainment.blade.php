@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
	<link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/live_entertainment.css') !!}"/>
	<style>.index-main .main-wrap .tab-wrap .col-3 {
    height: 250px;
    margin: 5px 0;
}
.index-main .main-wrap .tab-wrap .col-3 .move{
	transition: transform .5s
}
.index-main .main-wrap .tab-wrap .col-3:hover .move{
	transform: translateX(-6px);
}
.index-main .main-wrap .tab-wrap .bbin, .index-main .main-wrap .tab-wrap .lebo {
    
    background-position-y: -370px;
}
.noting:hover{
    background-position : 0 0 !improtant;
}</style>
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
					<a href="javascript:void(0)" style="background-image:url({!! asset('./app/template_one/img/live/banner1.jpg') !!})"></a>
				</div>
			@endforelse
		</div>
	</section>
	<div class="banner-pad"></div>

	<section class="index-main">
        	<div class="main-wrap">
        		<div class="tab-wrap clearfix">
                    <div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/by-bg.jpg');">
                        <a class="tx_login_game move" style="background-image:url('/app/template_one/img/live/by.png');" href="{!! url('players.loginBBinHall/live')!!}"></a>
                    </div>
					<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/sb-bg.jpg');">
						<a class="tx_login_game move" style="background-image:url('/app/template_one/img/live/sb.png');" href="{!! url('players.gameLauncher/SB/Sunbet_Lobby')!!}"></a>
					</div>
					{{--<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/gd-bg.jpg');">--}}
        				{{--<a class="tx_login_game move" style="background-image:url('/app/template_one/img/live/gd.png');" href="{!! url('players.gameLauncher/GD/Gold_Deluxe_Lobby')!!}"></a>--}}
        			{{--</div>--}}
        			<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/pt-bg.jpg');">
        				<a class="tx_login_game move" style="background-image:url('/app/template_one/img/live/pt.png');" href="{!! url('players.loginPTGame/bal') !!}"></a>
        			</div>
					<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/mg-bg.jpg');">
						<a class="tx_login_game move" style="background-image:url('/app/template_one/img/live/mg.png');" href="{!! url('players.launchItem/1172/1001')!!}"></a>
					</div>
        			<div class="tab-item col-3 ml-0" style="background-image:url('/app/template_one/img/live/ag.jpg');">
        				<a href="javascript:void(0)"></a>
        			</div>
        			<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/ag.jpg');">
        				<a href="javascript:void(0)"></a>
        			</div>
        			<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/ag.jpg');">
        				<a href="javascript:void(0)"></a>
        			</div>
        			<div class="tab-item col-3" style="background-image:url('/app/template_one/img/live/ag.jpg');">
        				<a href="javascript:void(0)"></a>
        			</div>
        		</div>
        	</div>
        </section>
@endsection


