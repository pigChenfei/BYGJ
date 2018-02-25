@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
    
@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="notice-main notice-main-error link-failure">
        <div class="btns-wrap clearfix" style="margin-left:-70px;">
            <a href="{!! url('/') !!}">返回首页</a>
        </div>
    </section>
@endsection
