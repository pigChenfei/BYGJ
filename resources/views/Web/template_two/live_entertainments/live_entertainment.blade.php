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
					<a href="javascript:void(0)" style="background-image:url({!! asset('./app/template_two/img/live/banner1.jpg') !!})"></a>
				</div>
			@endforelse
		</div>
	</section>
	<div class="banner-pad"></div>

	<section class="index-main">
		<div class="main-wrap">
			<div class="row">
				<div class="cell left">
					<div class="live-img live-1">
						<a class="plum-cell tx_login_game" href="{!! url('players.loginBBinHall/live')!!}">开始游戏</a>
					</div>
					<div class="text-wrap">
						<div class="cell-tit">BBIN厅</div>
						<div class="cell-text">最老牌的博彩平台<br>独创多台下注、金臂下注等<br>特殊玩法</div>
					</div>
				</div>
				<div class="cell right">
					<div class="live-img live-4">
						<a class="plum-cell tx_login_game" href="{!! url('players.gameLauncher/SB/Sunbet_Lobby')!!}">开始游戏</a>
					</div>
					<div class="text-wrap">
						<div class="cell-tit">申博厅</div>
						<div class="cell-text">游戏品种多，<br>玩家可听到荷官对话，<br>画面贴近实地赌场</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="cell left">
					<div class="live-img live-6">
						<a class="plum-cell tx_login_game" href="{!! url('players.loginPTGame/bal') !!}">开始游戏</a>
					</div>
					<div class="text-wrap">
						<div class="cell-tit">PT厅</div>
						<div class="cell-text">来自菲律宾最大赌场的<br>视频直播，专业电投手，<br>呐喊助威</div>
					</div>
				</div>
				<div class="cell right">
					<div class="live-img live-7">
						<a class="plum-cell tx_login_game" href="{!! url('players.launchItem/1172/1001')!!}">开始游戏</a>
					</div>
					<div class="text-wrap">
						<div class="cell-tit">MG厅</div>
						<div class="cell-text">特有的PLAYBOY真人系统， <br>深受欧美玩家喜欢</div>
					</div>
				</div>
			</div>
			<div class="row mb-0">
				<div class="cell left">
					<div class="live-img live-more">
						<!-- <a class="plum-cell" href="javascript:">开始游戏</a> -->
					</div>
					<div class="text-wrap">
						<div class="cell-tit">敬请期待</div>
						<!-- <div class="cell-text">来自菲律宾最大赌场的<br>视频直播，专业电投手，<br>呐喊助威</div> -->
					</div>
				</div>
				<div class="cell right">
					<div class="live-img live-more">
						<!-- <a class="plum-cell" href="javascript:">开始游戏</a> -->
					</div>
					<div class="text-wrap">
						<div class="cell-tit">敬请期待</div>
						<!-- <div class="cell-text">来自菲律宾最大赌场的<br>视频直播，专业电投手，<br>呐喊助威</div> -->
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection


