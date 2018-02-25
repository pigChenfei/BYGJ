@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')

@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="notice-main bind-email-success">
        <div class="msg-wrap">
            24小时客服在线&nbsp;&nbsp;助您畅游博赢国际
        </div>
        <div class="btns-wrap clearfix">
            <a href="{{$home}}">返回首页</a>
            <a href="{{$center}}">会员中心</a>
        </div>
    </section>
@endsection
