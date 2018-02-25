@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/index.css') !!}">
@endsection

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
    <summary>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="2000" style="  min-width: 1098px;" >
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <img src="{!! asset('./app/img/lottery.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="{!! asset('./app/img/lottery.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="{!! asset('./app/img/lottery.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
                <div class="item">
                    <img src="{!! asset('./app/img/lottery.png') !!}">
                    <div class="carousel-caption">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </summary>
    <div style="background-color: #eee;padding: 20px 0px 50px;">
	    <div class="Lottery-betting">
	            <div role="tabpane1" >
	                <ul class="nav nav-tabs" role="tablist">
	                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">KG彩票</a></li>
	                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">BBIN彩票</a></li>
	                </ul>
	                <div class="tab-content Lottery-betting-nav">
	                    <div role="tabpanel" class="tab-pane  active" id="home">
	                        <div><div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/></div><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>KG快乐彩</p></div>
	                    </div>
	                    <div role="tabpanel" class="tab-pane" id="profile">
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                        <div><img src="{!! asset('./app/img/KG.png') !!}" alt=""/><p>PT实时彩</p></div>
	                    </div>
	                </div>
	                <div class="clearfix"></div>
	            </div>
	    </div>
    </div>
@endsection

@section('scripts')
    <script>
        /*特效*/
        $(function(){
            var tLen = 0,
                    vNum = 8,
                    mNum = 1,
                    mTime = 235,
                    iShow = $(".show .itemshow ul"),
                    iItems = $(".show .itemshow ul li"),
                    mLen = iItems.eq(0).width() * mNum,
                    cLen = (iItems.length - vNum) * iItems.eq(0).width();

            iShow.css({width:iItems.length*iItems.eq(0).width()+'px'});
            //下一张
            $(".show .prev").on({
                click:function(){
                    if(tLen < cLen){
                        if((cLen - tLen) > mLen){
                            iShow.animate({left:"-=" + mLen + "px"}, mTime);
                            tLen += mLen;
                        }else{
                            iShow.animate({left:"-=" + (cLen - tLen) + "px"}, mTime);
                            tLen += (cLen - tLen);
                        }
                    }
                }
            });
            //上一张
            $(".show .next").on({
                click:function () {
                    if(tLen > 0){
                        if(tLen > mLen){
                            iShow.animate({left: "+=" + mLen + "px"}, mTime);
                            tLen -= mLen;
                        }else{
                            iShow.animate({left: "+=" + tLen + "px"}, mTime);
                            tLen = 0;
                        }
                    }
                }
            })
        });
        /**/
        $(".live-entertainment>div").hover(function(){
            $(this).addClass('live-kiv-js').siblings().removeClass('live-kiv-js');
        });
        $(".nav-nav li ul li a").mouseover(function(){
            $(".back-nav>div").css("display","none");
        });
        $(".nav-nav li ul li a").mouseout(function(){
            $(".back-nav>div").css("display","block");
        });
    </script>
@endsection
