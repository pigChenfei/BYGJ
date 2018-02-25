@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
    
@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="notice-main bind-email-fail">
        <div class="msg-wrap">
            如有疑问，请 <a href="javascript:">联系客服</a>
        </div>
        <div class="btns-wrap clearfix">
            <a href="{{$home}}">返回首页</a>
            <a href="{{$center}}">会员中心</a>
        </div>
    </section>
@endsection
