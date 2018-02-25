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
<!--合营模式-->
<div class="tab-pane fade in public" id="pattern">
    <div class="container">
        <div class="banner">
            <img src="{!! asset('./agent-data/img/banner_pattern.png') !!}"/>
        </div>
        <div>
            <h3>合营模式</h3>
            <p>博赢国际招全国代理商加盟共同发展，欢迎业内同行和各界人士加盟！博赢国际将以最先进的合作平台硬件设施和优质的客户服务，为您搭建通往成功的桥梁，与您携手迈入黄金殿堂！我们提供多种合作模式，共同发展！</p>
        </div>
        <div>
            <h3>推广代理模式</h3>
            <p>推广代理模式是目前网络博彩界最流行的合作方式。只要您拥有一定的推广资源，包括网站／论坛／联盟／QQ群／媒体等，无需任何资金投入，只需以广告或分享资讯的方式，将我们提供给您的代理链接推荐给志趣相同的网友或身边的朋友，即可获得相应收入。每月您都可获得下线会员游戏所产生公司净盈利的35%（最高55%），真正的安坐家中乐享收益，真正的0风险项目，还在犹豫什么，赶快加入推广行列吧。</p>
        </div>
    </div>
</div>
@include('Web.agents.layouts.footer')
@endsection