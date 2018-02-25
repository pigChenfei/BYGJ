@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/index.css') !!}"/>
@endsection

@section('script')
    <script src="{!! asset('./app/js/js.js') !!}"></script>
@endsection

@section('content')
<main class="About-us">
    <div>
        @include('Web.default.layouts.about_us');
        <div class="About-right">
            <p><i class="About-img"></i><b>联系我们</b></p>
            <div>
                <div>
                    <p>87*24小时在线客服，全天等候您的光临</p>
                    <p>电话：00639060010888 00639060020888</p>
                    <p>客服邮箱：winwin@ig588.com</p>
                    <p>客服skpye:winwin.ig88.com</p>
                </div>
             </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</main>
@endsection

