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
            <div class="main-wrap">
                <div class="tab-wrap clearfix">
                    <div class="tab-item col-4 bbin" style="position: relative;">
                        <a href="javascript:void(0)"></a>
                        <div class="modal">
                            <div class='btnwrap clearfix'></div>
                        </div>
                    </div>
                    <div class="tab-item col-4 lebo" style="position: relative;">
                        <a href="javascript:void(0)"></a>
                        <div class="modal">
                            <div class='btnwrap clearfix'></div>
                        </div>
                    </div>


                    <div class="tab-item col-4 test3" style="position: relative;background-image:url(/app/template_one/img/index/index-mainimg5.jpg)">
                        <a href="javascript:void(0)"></a>
                        <div class="modal">
                            <div class='btnwrap clearfix'></div>
                        </div>
                    </div>
                    <div class="tab-item col-6 ag" style="position: relative;background-image:url(/app/template_one/img/index/index-mainimg3.jpg)">
                        <a href="javascript:void(0)"></a>
                        <div class="modal">
                            <div class='btnwrap clearfix'></div>
                        </div>
                    </div>
                    <div class="tab-item col-6 ab" style="position: relative;background-image:url(/app/template_one/img/index/index-mainimg4.jpg)">
                        <a href="javascript:void(0)" ></a>
                        <div class="modal">
                            <div class='btnwrap clearfix'></div>
                        </div>
                    </div>
                </div>
                <div class="tab-wrap" hidden>
                    <div class="tab-item col-4 lebo">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 lebo">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 lebo">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                </div>
                <div class="tab-wrap" hidden>
                    <div class="tab-item col-4 bbin">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 bbin">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 bbin">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                </div>
                <div class="tab-wrap" hidden>
                    <div class="tab-item col-4 lebo">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 lebo">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 lebo">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                </div>
                <div class="tab-wrap" hidden>
                    <div class="tab-item col-4 bbin">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 bbin">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-4 bbin">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ag">
                        <a href="javascript:void(0)"></a>
                    </div>
                    <div class="tab-item col-3 ab">
                        <a href="javascript:void(0)"></a>
                    </div>
                </div>
            </div>
        </section>

@endsection

@section('scripts')
<script src="{!! asset('./app/template_one/js/index.js') !!}"></script>
<script>
    $(window).scroll(function() {
        if($(window).scrollTop() != 0) {
            $("header").css({
                'background': '#1a1a1a'
            })
            $(".form-group input").css({
                'background': '#484848'
            });
        } else {
            $("header").css({
                'background': 'rgba(0,0,0,0.1)'
            })
            $(".form-group input").css({
                'background': '#363636'
            });
        }
    });
</script>
@endsection


