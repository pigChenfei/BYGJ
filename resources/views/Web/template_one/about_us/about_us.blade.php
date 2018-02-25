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

        @include('Web.default.layouts.about_us')

        <div class="About-right">
            <p><i class="About-img"></i><b>关于我们</b></p>
            <div>
                <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab commodi consequuntur deleniti et ipsum
                    minus molestiae obcaecati pariatur porro praesentium provident quae quaerat quam, qui quia quis quos
                    reiciendis velit!
                </div>
                <div>Adipisci aspernatur beatae blanditiis consequuntur cum, debitis dolor eligendi esse est, eveniet ex
                    ipsum itaque laborum mollitia nesciunt, nihil perferendis rem soluta temporibus totam? Error
                    mollitia natus praesentium tempora ullam!
                </div>
                <div>Nemo officia provident sit unde. Consequatur culpa, fuga inventore quae tempore veritatis! Cum
                    doloremque, eos error eveniet illum minus quam quasi vel veritatis. Alias aperiam deserunt eum, nisi
                    tempora vitae.
                </div>
                <div>Accusantium architecto atque harum itaque molestiae, omnis quis quisquam repudiandae. Alias aut
                    blanditiis cupiditate delectus, deserunt error expedita harum illum laboriosam minima, modi mollitia
                    nulla saepe sed, sit vero voluptates?
                </div>
                <div>Aperiam at dolor fugiat harum iure, molestiae nemo omnis, recusandae sed sequi ullam voluptatum. Ab
                    architecto ipsam iusto maxime nobis possimus recusandae sed totam voluptatem. Architecto ea
                    laboriosam possimus provident.
                </div>
                <div>A cupiditate deleniti, doloremque ipsa magni nemo nisi odit, officia perferendis placeat quibusdam
                    quo, sequi ullam veniam voluptas. Blanditiis dicta harum maxime omnis perferendis, praesentium
                    repellat saepe sequi sint vel.
                </div>
                <div>Accusantium asperiores aspernatur autem blanditiis, delectus dolor dolorem dolores doloribus ea
                    eligendi est eveniet fugiat ipsum itaque minus necessitatibus nihil reiciendis similique. Adipisci
                    eos impedit ipsa magni quasi voluptatem voluptates!
                </div>
                <div>Ab accusamus aperiam cupiditate deleniti dignissimos dolorum enim exercitationem natus
                    reprehenderit? Aut eius, ipsa magni natus nesciunt placeat praesentium quia quibusdam reiciendis
                    repellat repellendus, sunt temporibus velit, veritatis vitae! Maiores.
                </div>    </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</main>
@endsection

