@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/index.css') !!}"/>
@endsection

@section('script')
    <script src="{!! asset('./app/js/js.js') !!}"></script>
@endsection

@section('content')
<main class="About-us">
    <div>
        @include('Web.default.layouts.about_us');
        <div class="About-right">
            <p><i class="About-img"></i><b>合作伙伴</b></p>
            <div>
                <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab commodi consequuntur deleniti et ipsum
                    minus molestiae obcaecati pariatur porro praesentium provident quae quaerat quam, qui quia quis quos
                    reiciendis velit!
                </div>
                <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab commodi consequuntur deleniti et ipsum
                    minus molestiae obcaecati pariatur porro praesentium provident quae quaerat quam, qui quia quis quos
                    reiciendis velit!
                </div>
                <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab commodi consequuntur deleniti et ipsum
                    minus molestiae obcaecati pariatur porro praesentium provident quae quaerat quam, qui quia quis quos
                    reiciendis velit!
                </div>
                <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab commodi consequuntur deleniti et ipsum
                    minus molestiae obcaecati pariatur porro praesentium provident quae quaerat quam, qui quia quis quos
                    reiciendis velit!
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</main>
@endsection