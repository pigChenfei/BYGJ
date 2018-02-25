@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/index.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section id="myCarousel" class="carousel slide" data-riad="carousel" data-interval="5000">
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
                    <a href="javascript:void(0)" style="background-image:url({!! asset('./app/template_one/img/index/banner.jpg') !!})"></a>
                </div>
            @endforelse
        </div>
        <div class="pmd">
            <div class="pmd-wrap">
                <div class="audioico glyphicon glyphicon-volume-up float-left"></div>
                <!-- <div class="text-box float-left">
                    <div id="scroll" class="scroll clearfix">
                        <ul class="list-unstyled" style="position: absolute;">
                            <li><a href="javascript:void(0)"></a></li>
                        </ul>
                    </div>
                </div> -->
                <marquee class="text-box float-left" direction="left" onmouseout="this.start();" onmouseover="this.stop();">
				    <span class="notice-content"><a href="javascript:void(0)">{!! \WinwinAuth::currentWebCarrier()->webSiteConf->site_notice !!}</a></span>
		        </marquee>
            </div>
        </div>
    </section>
    <section class="index-main">
        <div class="main-wrap clearfix">
            <div class="live-wrap float-left mb-30">
                <div class="live-head clearfix">
                    <div class="navs float-left clearfix">
                        <i class="iconfont icon-live_entertainment iconfont-f30 float-left"></i>
                        <span class="tit float-left">真人娱乐</span>
                        <ul class="f16">
                            <li>
                                <a class="active tx_login_game" target="_blank" href="{!! route('players.loginBBinHall',array('live')) !!}">BBIN</a>
                            </li>
                            <li>
                                <a target="_blank" href="{!! route('players.gameLauncher',array('SB','Sunbet_Lobby')) !!}" class="tx_login_game">申博</a>
                            </li>
                            <li>
                                <a target="_blank" href="{!! route('players.loginPTGame','bal') !!}" class="tx_login_game">PT厅</a>
                            </li>
                            <li>
                                <a target="_blank" href="{!! route('players.launchItem',array('1172','1001')) !!}" class="tx_login_game">MG厅</a>
                            </li>
                            <li>
                                <a target="_blank" href="{!! route('homes.live-entertainment') !!}">更多
                                    <i class="iconfont icon-more iconfont-f12"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="live-body clearfix">
                    <div class="item item1 hot" style="background-image:url(/app/template_two/img/index/live-img1.jpg)">
                        <a target="_blank" href="{!! route('players.loginBBinHall',array('live')) !!}" class="tx_login_game"></a>
                        <div class="hotmark">
                            <em>HOT</em>
                        </div>
                    </div>
                    <div class="item item2 hot" style="background-image:url(/app/template_two/img/index/live-img2.jpg)">
                        <a target="_blank" href="{!! route('players.gameLauncher',array('SB','Sunbet_Lobby')) !!}" class="tx_login_game"></a>
                        <div class="hotmark">
                            <em>HOT</em>
                        </div>
                    </div>
                    <div class="item item2" style="background-image:url(/app/template_two/img/index/live-img3.jpg)">
                        <a target="_blank" href="{!! route('players.loginPTGame','bal') !!}" class="tx_login_game"></a>
                    </div>
                    <div class="item item2 mt-10" style="background-image:url(/app/template_two/img/index/live-img4.jpg)">
                        <a target="_blank" href="{!! route('players.launchItem',array('1172','1001')) !!}" class="tx_login_game"></a>
                    </div> 
                    <div class="item item2 mt-10" style="background-image:url(/app/template_two/img/index/live-img5.jpg)">
                        <a target="_blank" href="javascript:"></a>
                    </div>
                </div>
            </div>
            <div class="game-wrap float-left mb-30">
                <div class="live-head">
                    <div class="navs clearfix">
                        <i class="iconfont icon-slot_machines iconfont-f30 float-left"></i>
                        <span class="tit float-left">电子游艺</span>
                        <ul class="f16">
                            <li>
                                <a target="_blank" href="{!! route('homes.slot-machine') !!}">更多
                                    <i class="iconfont icon-more iconfont-f12"></i>
                                </a>
                            </li>
                            <li class="mr-5">
                                <i class="iconfont icon-hot iconfont-f20"></i>
                            </li>
                            <li>低至一分钱即可转一次</li>
                        </ul>
                    </div>
                </div>
                <div class="live-body clearfix">
                    <div class="item item1" style="background-image:url(/app/template_two/img/videogame/game-img1.jpg)">
                        <a target="_blank" class="tx_login_game" href="/players.joinElectronicGame/795"></a>
                    </div>
                    <div class="item item2" style="background-image:url(/app/template_two/img/videogame/game-img2.jpg)">
                        <a target="_blank" class="tx_login_game" href="/players.joinElectronicGame/4"></a>
                    </div>
                    <div class="item item2 mt-10" style="background-image:url(/app/template_two/img/videogame/game-img3.jpg)">
                        <a target="_blank" class="tx_login_game" href="/players.joinElectronicGame/75"></a>
                    </div>
                </div>
            </div>
            <div class="fish-wrap float-left ml-10 mb-30">
                <div class="live-head">
                    <div class="navs clearfix">
                        <i class="iconfont icon-fishs iconfont-f30 float-left"></i>
                        <span class="tit float-left">BBIN捕鱼</span>
                        <ul class="f16">
                            <li>
                                <a target="_blank" href="{!! route('homes.ag-fish') !!}">更多
                                    <i class="iconfont icon-more iconfont-f12"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="live-body clearfix">
                    <div class="item item2 ml-0" style="background-image:url(/app/template_two/img/fish/fish-img1.jpg)">
                        <a target="_blank" href="{!! url('players.joinElectronicGame/114') !!}" class="tx_login_game"></a>
                    </div>
                    <div class="item item2 mt-10 ml-0" style="background-image:url(/app/template_two/img/fish/fish-img2.jpg)">
                        <a target="_blank" href="{!! url('players.joinElectronicGame/113') !!}" class="tx_login_game"></a>
                    </div>
                </div>
            </div>
            <div class="sports-wrap float-left">
                <div class="live-head">
                    <div class="navs clearfix">
                        <i class="iconfont icon-sports iconfont-f30 float-left"></i>
                        <span class="tit float-left">体育投注</span>
                        <ul class="f16">
                            <li>
                                <a target="_blank" href="{!! route('homes.sports-games') !!}">更多
                                    <i class="iconfont icon-more iconfont-f12"></i>
                                </a>
                            </li>
                            <li class="mr-5">
                                <i class="iconfont icon-hot iconfont-f20"></i>
                            </li>
                            <li>五大联赛随时投注</li>
                        </ul>
                    </div>
                </div>
                <div class="live-body clearfix">
                    <div class="item item3" style="background-image:url(/app/template_two/img/sport/sport-img1.jpg)">
                        <a target="_blank" href="{!! route('players.loginOneWorkHall') !!}" class="tx_login_game"></a>
                    </div>
                </div>
            </div>
            <div class="lottery-wrap float-left ml-10">
                <div class="live-head">
                    <div class="navs clearfix">
                        <i class="iconfont icon-lottery iconfont-f30 float-left"></i>
                        <span class="tit float-left">彩票投注</span>
                        <ul class="f16">
                            <li>
                                <a target="_blank" href="{!! route('homes.lottery-betting') !!}">更多
                                    <i class="iconfont icon-more iconfont-f12"></i>
                                </a>
                            </li>
                            <li class="mr-5">
                                <i class="iconfont icon-hot iconfont-f20"></i>
                            </li>
                            <li>全球最高赔率</li>
                        </ul>
                    </div>
                </div>
                <div class="live-body clearfix">
                    <div class="item item3" style="background-image:url(/app/template_two/img/lottery/lottery-img1.jpg)">
                        <a target="_blank" href="{!! url('players.loginVRHall') !!}" class="tx_login_game"></a>
                    </div>
                </div>
            </div>
        </div>
        </section>

@endsection

@section('scripts')
<script src="{!! asset('./app/template_one/js/index.js') !!}"></script>
@endsection


