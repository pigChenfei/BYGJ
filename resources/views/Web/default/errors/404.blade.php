@extends('Web.default.layouts.app')

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
    <div class="back-404">
        <div><span id="mes">5</span> <span>s</span> 后系统自动跳转...</div>
        <a class="btn btn-primary go-home-404">
            返回首页
        </a>
        <a class="btn btn-primary go-back-404">
            返回上一页
        </a>
    </div>
@endsection
@section('scripts')
    <script>
        var i = 5;
        var intervalid;
        intervalid = setInterval("fun()", 1000);
        function fun() {
            if (i == 0) {
                window.location.href = "{!! route('/') !!}";
                clearInterval(intervalid);
            }
            document.getElementById("mes").innerHTML = i;
            i--;
        }
        $(".go-home-404").click(function(){
            window.location.href = "{!! route('/') !!}";
        });
        $(".go-back-404").click(function(){
            if(history.back()==undefined){
                window.location.href = "{!! route('/') !!}";
            }else{
                history.back();
            }
        })
    </script>
@endsection
