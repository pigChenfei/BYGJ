@extends('Web.'.\WinwinAuth::currentWebCarrier()->template_agent.'.layouts.app')

@section('content')
    <section class="banner-index big" style="background-image:url({!! $webConf?$webConf->imageAsset():asset('./app/template_one/img/agency/banner-index.jpg') !!})"></section>
@endsection