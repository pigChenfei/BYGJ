@extends('Web.agents.layouts.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset('./agent-data/css/index.css') !!}"/>
    <style>
        @media only screen and (max-width:768px ) {
            #navbar-collapse #myTab,#navbar-collapse #myTab li{
                display: inline-block !important;
            }
        }
    </style>
@endsection


@section('script')
    <script src="{!! asset('./agent-data/js/index.js') !!}"></script>
@endsection


@section('content')
    <!--联系我们-->
    <div class="tab-pane fade in" id="connectUs">

    </div>
    @include('Web.agents.layouts.footer')
@endsection