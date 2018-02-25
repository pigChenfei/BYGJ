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
            <p><i class="About-img"></i><b>常见问题</b></p>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                一般优惠适用条款
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <div><i></i>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, architecto autem beatae
                                dicta, dolorum id ipsam maxime nulla odio qui quod sequi sint tenetur unde veniam
                                voluptate voluptatem. Cupiditate, veniam!
                            </div>
                            <div><i></i>Consequuntur quis ullam voluptatem voluptates voluptatibus voluptatum. Aut debitis
                                magnam qui sequi tenetur. At atque eos ex hic, illo nostrum sit? Esse id quod sint?
                                Doloribus laborum neque praesentium tempore?
                            </div>
                            <div><i></i>Accusamus ad doloribus ducimus, esse ex exercitationem expedita fuga harum impedit
                                maxime minima minus modi nihil odit, officiis perspiciatis placeat quas quasi quo rem
                                repellendus temporibus unde velit vero voluptatibus!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                               存款帮助
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            <div><i></i>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, architecto autem beatae
                                dicta, dolorum id ipsam maxime nulla odio qui quod sequi sint tenetur unde veniam
                                voluptate voluptatem. Cupiditate, veniam!
                            </div>
                            <div><i></i>Consequuntur quis ullam voluptatem voluptates voluptatibus voluptatum. Aut debitis
                                magnam qui sequi tenetur. At atque eos ex hic, illo nostrum sit? Esse id quod sint?
                                Doloribus laborum neque praesentium tempore?
                            </div>
                            <div><i></i>Accusamus ad doloribus ducimus, esse ex exercitationem expedita fuga harum impedit
                                maxime minima minus modi nihil odit, officiis perspiciatis placeat quas quasi quo rem
                                repellendus temporibus unde velit vero voluptatibus!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingThree">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                           取款帮助
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                        <div class="panel-body">
                            <div><i></i>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, architecto autem beatae
                                dicta, dolorum id ipsam maxime nulla odio qui quod sequi sint tenetur unde veniam
                                voluptate voluptatem. Cupiditate, veniam!
                            </div>
                            <div><i></i>Consequuntur quis ullam voluptatem voluptates voluptatibus voluptatum. Aut debitis
                                magnam qui sequi tenetur. At atque eos ex hic, illo nostrum sit? Esse id quod sint?
                                Doloribus laborum neque praesentium tempore?
                            </div>
                            <div><i></i>Accusamus ad doloribus ducimus, esse ex exercitationem expedita fuga harum impedit
                                maxime minima minus modi nihil odit, officiis perspiciatis placeat quas quasi quo rem
                                repellendus temporibus unde velit vero voluptatibus!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingfourth">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefourth" aria-expanded="false" aria-controls="collapsefourth">
                            最小投注金额是多少？
                            </a>
                        </h4>
                    </div>
                    <div id="collapsefourth" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfourth">
                        <div class="panel-body">
                            <div><i></i>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, architecto autem beatae
                                dicta, dolorum id ipsam maxime nulla odio qui quod sequi sint tenetur unde veniam
                                voluptate voluptatem. Cupiditate, veniam!
                            </div>
                            <div><i></i>Consequuntur quis ullam voluptatem voluptates voluptatibus voluptatum. Aut debitis
                                magnam qui sequi tenetur. At atque eos ex hic, illo nostrum sit? Esse id quod sint?
                                Doloribus laborum neque praesentium tempore?
                            </div>
                            <div><i></i>Accusamus ad doloribus ducimus, esse ex exercitationem expedita fuga harum impedit
                                maxime minima minus modi nihil odit, officiis perspiciatis placeat quas quasi quo rem
                                repellendus temporibus unde velit vero voluptatibus!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingfifth">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefifth" aria-expanded="false" aria-controls="collapsefifth">
                                最高投注金额是多少？
                            </a>
                        </h4>
                    </div>
                    <div id="collapsefifth" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingfifth">
                        <div class="panel-body">
                            <div><i></i>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, architecto autem beatae
                                dicta, dolorum id ipsam maxime nulla odio qui quod sequi sint tenetur unde veniam
                                voluptate voluptatem. Cupiditate, veniam!
                            </div>
                            <div><i></i>Consequuntur quis ullam voluptatem voluptates voluptatibus voluptatum. Aut debitis
                                magnam qui sequi tenetur. At atque eos ex hic, illo nostrum sit? Esse id quod sint?
                                Doloribus laborum neque praesentium tempore?
                            </div>
                            <div><i></i>Accusamus ad doloribus ducimus, esse ex exercitationem expedita fuga harum impedit
                                maxime minima minus modi nihil odit, officiis perspiciatis placeat quas quasi quo rem
                                repellendus temporibus unde velit vero voluptatibus!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingsixth">
                        <h4 class="panel-title">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsesixth" aria-expanded="false" aria-controls="collapsesixth">
                                最高投注金额是多少？
                            </a>
                        </h4>
                    </div>
                    <div id="collapsesixth" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingsixth">
                        <div class="panel-body">
                            <div><i></i>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, architecto autem beatae
                                dicta, dolorum id ipsam maxime nulla odio qui quod sequi sint tenetur unde veniam
                                voluptate voluptatem. Cupiditate, veniam!
                            </div>
                            <div><i></i>Consequuntur quis ullam voluptatem voluptates voluptatibus voluptatum. Aut debitis
                                magnam qui sequi tenetur. At atque eos ex hic, illo nostrum sit? Esse id quod sint?
                                Doloribus laborum neque praesentium tempore?
                            </div>
                            <div><i></i>Accusamus ad doloribus ducimus, esse ex exercitationem expedita fuga harum impedit
                                maxime minima minus modi nihil odit, officiis perspiciatis placeat quas quasi quo rem
                                repellendus temporibus unde velit vero voluptatibus!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</main>
@endsection