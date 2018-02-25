@extends('Web.agents.layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset('./agent-data/css/index.css') !!}"/>
    <style>
        @media only screen and (max-width:768px ) {
            #navbar-collapse #myTab,#navbar-collapse #myTab li{
                display: inline-block !important;
            }
        }
    </style>
@endsection


@section('script')
    <script src="{!! asset('./agent-data/js/index.js') !!}"></script>
@endsection


@section('content')
    <!--页面主内容-->
    <main id="myTabContent" class="tab-content">
        <!--首页-->
        <div class="tab-pane fade in active" id="home">
            <!--轮播图-->
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                    <li data-target="#myCarousel" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="{!! asset('./agent-data/img/index/banner_index.png') !!}" alt="picture1">
                        <div class="carousel-btn"><a href="{!! route('agents.registerPage') !!}"><img src="{!! asset('./agent-data/img/btn_register.png') !!}"/></a></div>
                    </div>
                    <div class="item">
                        <img src="{!! asset('./agent-data/img/index/banner_index.png') !!}" alt="picture2">
                        <div class="carousel-btn"><a href="{!! route('agents.registerPage') !!}"><img src="{!! asset('./agent-data/img/btn_register.png') !!}"/></a></div>
                    </div>
                    <div class="item">
                        <img src="{!! asset('./agent-data/img/index/banner_index.png') !!}" alt="picture3">
                        <div class="carousel-btn"><a href="{!! route('agents.registerPage') !!}"><img src="{!! asset('./agent-data/img/btn_register.png') !!}"/></a></div>
                    </div>
                    <div class="item">
                        <img src="{!! asset('./agent-data/img/index/banner_index.png') !!}" alt="picture4">
                        <div class="carousel-btn"><a href="{!! route('agents.registerPage') !!}"><img src="{!! asset('./agent-data/img/btn_register.png') !!}"/></a></div>
                    </div>
                </div>
                <a class="carousel-control slide_prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                </a>
                <a class="carousel-control slide_next" href="#myCarousel" role="button" data-slide="next" style="left: 85%;">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </a>
            </div>
            <!--轮播图结束-->
        </div>
    </main>
   @include('Web.agents.layouts.footer')
@endsection

