@extends('Web.default.layouts.app')

@section('css')
    @include('Web.default.layouts.mobiles_css')
@endsection

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
    <div class="Mobile-png">
            <div>
                <div class="Mobile-show"><img src="{!! asset('./app/img/Moblie-show.png') !!}" alt=""/></div>
                <div class="Mobile-right">
                    <p>博赢国际 手机web版</p>
                    <div class="Mobile-some">
                        <div class="pull-left"><img src="{!! asset('./app/img/QR-code.png') !!}" alt="" /><div>扫一扫，二维码开玩</div></div>
                        <div class="pull-right"><p>一户通：一个账号玩遍PC端与手机端打通平台：告别单客户端</p>
                            <div>支持IOS和Android版，玩转AG与PT</div>
                        </div>
                    </div>
                </div>
            </div>
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
    </script>
@endsection
