@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="notice-main notice-main-error err-404">
        <div class="btns-wrap clearfix">
            <a href="{!! url('/') !!}">返回首页</a>
            <a href="{{route('agents.connectUs')}}">联系我们</a>
        </div>
    </section>
@endsection
