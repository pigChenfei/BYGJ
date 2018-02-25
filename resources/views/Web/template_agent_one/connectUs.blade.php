@extends('Web.'.\WinwinAuth::currentWebCarrier()->template_agent.'.layouts.app')

@section('content')
        <section class="banner-index concatus big" style="background-image: url('{!! $image?$image->imageAsset():asset('./app/template_one/img/agency/concatus.jpg') !!}')">
            <div class="concatus-wrap">
                <div class="info-wrap text-center">
                    {!! $webConf !!}
                </div>
            </div>
        </section>
@endsection