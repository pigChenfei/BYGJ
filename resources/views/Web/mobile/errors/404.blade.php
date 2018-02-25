@extends('Web.mobile.layouts.app')

@section('content')
    <style>
        .notice-main{
            height: 100%;
            width: 100%;
            background:#282828 url({!! asset('/app/template_one/img/common/404.jpg') !!}) no-repeat center;
            position: relative;
        }
        .btns-wrap{
            position: absolute;
            bottom: 50px;
            left: 50%;
            margin-left: -170px;
        }
        .btns-wrap a{
            width: 140px;
            height: 36px;
            padding: 0;
            line-height: 36px;
            border-radius: 20px;
            text-align: center;
            margin: 0 15px;
            display: block;
            color: rgba(0,0,0,0.8);
            float: left;
            background: #FFF;
        }
    </style>
    <section class="notice-main">
        <div class="btns-wrap clearfix">
            <a href="{!! url('/') !!}">返回首页</a>
            <a href="{{route('agents.connectUs')}}">联系我们</a>
        </div>
    </section>
@endsection
