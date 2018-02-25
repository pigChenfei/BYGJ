@extends('Web.'.\WinwinAuth::currentWebCarrier()->template_agent.'.layouts.app')

@section('content')
    <section class="banner-index" style="background-image:url({!! $image?$image->imageAsset():asset('./app/template_one/img/agency/banner-agreement.jpg') !!})"></section>
    <section class="joinus-main">
        {!! $webConf !!}
    </section>
@endsection