@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/discount.css') !!}"/>
@endsection
@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section id="myCarousel" class="carousel slide" data-riad="carousel" data-interval="2500">
        <!-- 轮播（Carousel）指标 -->
        <ol class="carousel-indicators">
            @forelse($images as $k => $v)
                <li data-target="#myCarousel" data-slide-to="{{$k}}" @if($loop->first)class="active"@endif></li>
            @empty
                <li data-target="#myCarousel" data-slide-to="0"></li>
            @endforelse
        </ol>
        <!-- 轮播（Carousel）项目 -->
        <div class="carousel-inner">
            @forelse($images as $k => $v)
                <div class="item @if($loop->first) active @endif">
                    <a @if (!isset($v->url_type)) href="javascript:void(0)" @elseif($v->url_type == 0) href="{{$v->url_link}}" target="_blank" @elseif($v->url_type == 1) class="tx_login_game" href="{{$v->url_link}}" @endif style="background-image:url({{$v->imageAsset()}})"></a>
                </div>
            @empty
                <div class="item active">
					<a href="javascript:void(0)" style="background-image:url({!! asset('./app/template_one/img/discount/banner1.jpg') !!})"></a>
                </div>
            @endforelse
        </div>
    </section>

    <section class="discount-main">
        <div class="main-wrap clearfix">
            <aside class="float-left">
                <div class="item active type-select" data-value="0"><a href="javasript:void(0)">全部优惠<span class="glyphicon glyphicon-menu-right"></span></a></div>
                @foreach($actType as $key => $value)
                <div class="item type-select" data-value="{!! $value->id !!}"><a href="javasript:void(0)">{!! $value->type_name !!}<span class="glyphicon glyphicon-menu-right"></span></a></div>
                @endforeach
            </aside>
            <article class="coupon-wrap float-left">
                <div class="coupon-box clearfix">
                    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.special_offers.special_offer_list')
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function(){
            $(document).off('click', '.check').on('click', '.check', function () {
                $(this).parents(".item").find('.coupon-detail').slideToggle();
            });
            $(document).off('click', '.packup').on('click', '.packup', function () {
                $(this).parents(".coupon-detail").slideUp();
            });
            $(document).on('click', '.type-select', function () {
                var _this = $(this);
                var type = _this.attr('data-value');
                $.ajax({
                    url:"{!! route('homes.special-offer') !!}",
                    type: 'get',
                    async: true,
                    data: {
                        'type' : type
                    },
                    dataType: 'html',
                    success: function(data){
                        $('.coupon-box').html(data);
                        _this.addClass('active').siblings().removeClass('active');
                    },
                    error: function(xhr){
                        layer.msg('请求失败，请重试',{
                            success: function(layero, index){
                                $(layero).css('top', '401.5px');
                            }
                        });
                    }
                })

            });
            $(".participation").click(function(){
                var _me = $(this);
                var act_id =_me.attr('act_id');
                var originValue = this.innerHTML;
                _me.html("正在申请...").removeClass("participation");
                _me.attr("disabled",true); //设置按钮禁用
                $.ajax({
                    url:"players.applyParticipate",
                    data:{
                        'act_id' : act_id
                    },
                    type:"POST",
                    success:function(data){
                        if(data.success == true){
                            _me.html("待审核").removeClass("button-fill-yellow").addClass("button-fill-purple");
                            _me.attr("disabled",true); //设置按钮禁用
                            layer.msg(data.message,{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                        }else if(data.success == false){
                            _me.html(originValue);
                            _me.attr("disabled",false);
                            layer.msg('申请失败，请重试',{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                        }
                    },
                    error:function(xhr){
                        _me.html(originValue);
                        _me.attr("disabled",false);
                        if(xhr.responseJSON.success ==false){
                            layer.msg(xhr.responseJSON.message,{
                                success: function(layero, index){
                                    var _this = $(layero);
                                    _this.css('top', '401.5px');
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
