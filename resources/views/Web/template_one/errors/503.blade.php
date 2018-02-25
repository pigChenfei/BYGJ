@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="notice-main notice-main-error err-503">
        <div class="btns-wrap clearfix">
            <a href="{!! url('/') !!}">返回首页</a>
            <a href="javascript:" onclick="location.reload();">刷新</a>
        </div>
    </section>
@endsection
