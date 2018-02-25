@extends('Web.default.layouts.app')

@section('css')
    @include('Web.default.layouts.ag_fishs_css')
@endsection

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')


    <div class="AG-fish-game">

        <div class="AG-fish-game-btn"></div>

    </div>

@endsection

@section('scripts')

    <script>
        $(".nav-nav li ul li a").mouseover(function(){
            $(".back-nav>div").css("display","none");
        });
        $(".nav-nav li ul li a").mouseout(function(){
            $(".back-nav>div").css("display","block");
        });
        $(".AG-fish-game>div").click(function(){
            layer.msg('加载中', {
                icon: 16
                ,shade: 0.01
            });
        });
    </script>
@endsection

