@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/index.css') !!}"/>
@endsection

@section('script')
    <script src="{!! asset('./app/js/bootstrap.min.js') !!}"></script>
@endsection

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
<div class="clearfix"></div>
<div>
 <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="min-width: 1098px;;">
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <img src="{!! asset('./app/img/home_banner.png') !!}" alt="First slide">
            </div>
            <div class="item">
                <img src="{!! asset('./app/img/home_banner.png') !!}" alt="Second slide">
            </div>
            <div class="item">
                <img src="{!! asset('./app/img/home_banner.png') !!}" alt="Third slide">
            </div>
            <div class="item">
                <img src="{!! asset('./app/img/home_banner.png') !!}" alt="fourth slide">
            </div>
        </div>
        <ol class="carousel-indicators">
            <li data-slide-to="0" class="active" data-target="#carousel-example-generic" >AG最佳真人娱乐</li>
            <li data-slide-to="1" data-target="#carousel-example-generic">AG最佳真人娱乐</li>
            <li data-slide-to="2" data-target="#carousel-example-generic">AG最佳真人娱乐</li>
            <li data-slide-to="3" data-target="#carousel-example-generic">AG最佳真人娱乐</li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>
<content> 
    <div style="margin-top: 20px;">
        <div class="focus pull-left" id="focus">
            <div class="left">
                <ul>
                    <li class="active" style="opacity:1; filter:alpha(opacity=100);"><p>图一</p><img src="{!! asset('./app/img/home_entertain1.png') !!}"/></li>
                    <li><p>图二</p><img src="{!! asset('./app/img/home_entertain2.png') !!}"/></li>
                    <li><p>图三</p><img src="{!! asset('./app/img/home_entertain1.png') !!}"/></li>
                    <li><p>图四</p><img src="{!! asset('./app/img/home_entertain2.png') !!}"/></li>
                </ul>
            </div>
            <div class="right">
                <div>
                    <div>真人娱乐</div>
                    <p>live CASINO</p> 
                </div>
                <ul class="frequently">
                    {{--<li class="active"><i class="i"></i><a href="{!! route('players.launchItem','1892','1002') !!}">MG厅</a></li>--}}
                    {{--<li class="active"><i class="i"></i><a href="{!! route('players.launchItem',array('1892','1002')) !!}">MG厅</a></li>
                    <li><i></i><a href="{!! route('players.loginPTGame','bal') !!}">PT真人</a></li>
                    <li><i></i>AG旗航厅</li>
                    <li><i></i>AG国际厅</li>--}}
                    <li><a target="_blank" href="{!! route('players.loginPTGame','bal') !!}">PT真人</a></li>
                    <li><a target="_blank" href="/players.loginBBinHall">BBIN真人</a></li>
                    <li><a target="_blank" href="{!! route('players.gameLauncher',array('SB','Sunbet_Lobby')) !!}">SUNBET真人</a></li>
                    <li><a target="_blank" href="{!! route('players.gameLauncher',array('GD','Gold_Deluxe_Lobby')) !!}">GD真人</a></li>
                    <li><a target="_blank" href="{!! route('players.launchItem',array('1172','1001')) !!}">MG真人</a></li>
                </ul>
                <p class="more"> <i></i><a href="{!! route('homes.live-entertainment') !!}">查看更多>></a></p>
            </div>
        </div>
        <div class="focus pull-right" id="cocus">
            <div class="left">
                <ul>
                    <li class="active" style="opacity:1; filter:alpha(opacity=100);"><p>图一</p><img src="{!! asset('./app/img/home_game1.png') !!}"/></li>
                    <li><p>图二</p><img src="{!! asset('./app/img/home_game1.png') !!}"/></li>
                    <li><p>图三</p><img src="{!! asset('./app/img/home_game1.png') !!}"/></li>
                    <li><p>图四</p><img src="{!! asset('./app/img/home_game1.png') !!}"/></li>
                </ul>
            </div>
            <div class="right">
                <div>
                    <div>电子游戏</div>
                    <p>SLOT MACHINE</p>
                </div>
                <ul class="frequently">
                    <li class="active"><i class="i"></i>MG老虎机</li>
                    <li> <i></i>PT老虎机</li>
                </ul>
                <p class="more"><i></i><a href="{!! route('homes.slot-machine') !!}">查看更多>></a></p>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div  class="content-center">
        <div>
        	<img src="{!! asset('./app/img/home_fish.png') !!}" alt=""/>
	        <div class="cover"> 
	        	<a class="btn btn-warning game" href="{!! route('homes.ag-fish') !!}">开始游戏 >></a>
	        </div>
        </div>
        <div>
        	<img src="{!! asset('./app/img/home_lottery.png') !!}" alt=""/>
        	<div class="cover">
        		<a class="btn btn-warning game" href="{!! route('homes.lottery-betting') !!}">开始游戏 >></a>
        	</div>        	
        </div>
        <div style="margin-right: 0;">
        	<img src="{!! asset('./app/img/home_sports.png') !!}" alt=""/>
        	<div class="cover">
        		<a class="btn btn-warning game" href="{!! route('homes.sports-games') !!}">开始游戏 >></a>
        	</div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="content-footer">
        <div><img src="{!! asset('./app/img/download.png') !!}" alt=""/></div>
        <div class="content-footer-nav"> 
            <div>
                <a href="">
                    <i class="w-ck1"></i>
                    <div>如何存款</div>
                </a>
            </div>
            <div>
                <a href="">
                    <i class="w-ck2"></i>
                    <div>如何提现</div>
                </a>
            </div>
            <div>
                <a href="">
                    <i class="w-ck3"></i>
                    <div>客户端下载</div>
                </a>
            </div>
            <div>
                <a href="">
                    <i class="w-ck4"></i>
                    <div>在线客服</div>
                </a>
            </div>
        </div>
    </div>
</content>
@endsection

@section('scripts')
<script>
 
    $(".nav-nav li ul li a").mouseover(function(){
        $(".back-nav>div").css("display","none");
    });
    $(".nav-nav li ul li a").mouseout(function(){
        $(".back-nav>div").css("display","block");
    });

	$('.content-center div').hover(function(){
		$(this).find('.cover').show();
	},function(){
		$(this).find('.cover').hide();
	});
	
	$('.carousel-indicators li').click(function(){
		$(this).css('background-color','#2ac0ff').siblings().css('background-color','rgba(0,0,0,0.5)');
	})
</script>

<script src="{!! asset('./app/js/index.js.php') !!}"></script>

<style>

    .layui-layer-title{
        display: block!important;
        text-align: center;
        padding-left: 80px;
        font-weight: 600;

    }
    .layui-layer-dialog {
        width: 188px;
    }
    .layui-layer-content{

        word-break: break-all;
        text-align: center;
    }
</style>
@endsection


