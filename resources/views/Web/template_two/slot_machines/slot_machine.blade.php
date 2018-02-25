@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')
@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/video_game.css') !!}"/>
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
                	<a href="javascript:void(0)" style="background-image:url({!! asset('./app/template_two/img/videogame/banner1.jpg') !!})"></a>
                </div>
            @endforelse
        </div>
    </section>

    <section class="video-game-container">
        <article class="game-nav-container">
            <div class="game-menu-wrap clearfix">
                <ul class="game-menu list-unstyled">
                    <li><a href="{!! route('homes.slot-machine') !!}">全部游戏</a></li>
                    <li><a href="{!! route('homes.slot-machine', ['is_recommend' => 1]) !!}" @if(app('request')->input('is_recommend')) style="color: #a671ff" @endif>推荐游戏</a></li>
                    <li><a href="{!! route('homes.slot-machine', ['is_new' => 1]) !!}" @if(app('request')->input('is_new')) style="color: #a671ff" @endif>最新游戏</a></li>
                    {{--<li><a href="javascript:void(0)">特惠游戏</a></li>--}}
                    @if(\WinwinAuth::memberUser())
                        <li><a href="{!! route('homes.slot-machine', ['is_mine' => 1]) !!}" @if(app('request')->input('is_mine')) style="color: #a671ff" @endif>我的游戏</a></li>
                    @endif
                </ul>
                <div class="game-search-box">
                    <div class="form-inline">
                        <label for="search">查找游戏：</label>
                        <input type="text" class="form-control" name="search" id="search" value="{{ app('request')->input('gameName') }}" placeholder="请输入游戏名称查找"/>
                        <button class="btn btn-default btn-gradient-red" onclick="
                                var search = $('#search').val();
                                location.href='/homes.slot-machine?gameName='+search
                                " data-node="gameName">搜索</button>
                    </div>
                </div>
            </div>
            <div class="game-nav">
                <div class="game-nav-line game-nav-platform clearfix">
                    <label>游戏平台：</label>
                    <ul class="list-unstyled">
                        <li class="@if(empty(app('request')->input('main_game_plat'))) active @endif link-box" data-node="main_game_plat" data-value="0"><a href="javascript:void(0)">全部</a></li>
                        @foreach($main_game_plat_array as $v)
                            <li class="link-box @if(app('request')->input('main_game_plat') == $v->main_game_plat_id) active @endif" data-node="main_game_plat" data-value="{{$v->main_game_plat_id}}"><a href="javascript:void(0)">{{ str_replace('电子游戏','',$v->game_plat_name) }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="game-nav-line game-nav-type clearfix">
                    <label>游戏类型：</label>
                    <ul class="list-unstyled">
                        <li class="link-box @if(empty(app('request')->input('game_mcategory'))) active @endif" data-node="game_mcategory" data-value="0"><a href="javascript:void(0)">全部</a></li>
                        @foreach($game_mcategory_array as $k => $v)
                        <li class="link-box @if(app('request')->input('game_mcategory') == $k) active @endif" data-node="game_mcategory" data-value="{{$k}}"><a href="javascript:void(0)">{{ $v }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="game-nav-line game-nav-line clearfix">
                    <label>赔付线数：</label>
                    <ul class="list-unstyled">
                        <li class="link-box @if(empty(app('request')->input('game_lines'))) active @endif" data-node="game_lines" data-value="0"><a href="javascript:void(0)">全部</a></li>
                        @foreach($game_lines_array as $k => $v)
                            <li class="link-box @if(app('request')->input('game_lines') == $k) active @endif" data-node="game_lines" data-value="{{$k}}"><a href="javascript:void(0)">{{$v}}线</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </article>
        <article class="game-list-container">
            <div class="game-list-showterm">
                <div class="checkbox-wrap">
                    <label class="link-box" data-node="gold_pool" data-value="{{empty(app('request')->input('gold_pool'))?1:0}}"><input type="checkbox" @if(!empty(app('request')->input('gold_pool'))) checked @endif id="bonus-pool">&nbsp;奖金池</label>
                </div>
                <div class="checkbox-wrap">
                    <label class="link-box" data-node="is_demo" data-value="{{empty(app('request')->input('is_demo'))?1:0}}"><input type="checkbox" @if(!empty(app('request')->input('is_demo'))) checked @endif id="can-try">&nbsp;支持试玩</label>
                </div>
                <div class="show-type-box float-right">
                    <a href="javascript:void(0)" class="glyphicon glyphicon-th link-box @if(empty(app('request')->input('show_box'))) active @endif" data-node="show_box" data-value="0"></a>
                    <a href="javascript:void(0)" class="glyphicon glyphicon-th-list link-box @if(app('request')->input('show_box') == 1) active @endif" data-node="show_box" data-value="1"></a>
                </div>
                <div class="float-right">
                    <div class="popular float-right link-box @if($sort_popular != 99) active @endif" data-node="sort_popular" data-value="{{$sort_popular or 0}}">
                        <a href="javascript:void(0)">人气</a>
                        <a href="javascript:void(0)" class="glyphicon glyphicon-triangle-bottom" @if($sort_popular == 2) style="display: none;" @endif></a>
                        <a href="javascript:void(0)" class="glyphicon glyphicon-triangle-top" @if($sort_popular == 1) style="display: none;" @endif></a>
                    </div>
                    <div class="reward-rate float-right link-box @if($sort_reward != 99) active @endif" data-node="sort_reward" data-value="{{$sort_reward or 0}}">
                        <a href="javascript:void(0)">返奖率</a>
                        <a href="javascript:void(0)" class="glyphicon glyphicon-triangle-bottom" @if($sort_reward == 2) style="display: none;" @endif></a>
                        <a href="javascript:void(0)" class="glyphicon glyphicon-triangle-top" @if($sort_reward == 1) style="display: none;" @endif></a>
                    </div>
                </div>
            </div>
            <!--游戏列表-->
            <!--平铺展示-->
            @if(empty(app('request')->input('show_box')))
            <div class="game-list tile-show clearfix">
                @foreach($ptGameList as $v)
                <div class="game-item">
                    <div class="hide-wrap">
                        <div class="game-title inaline text-center">{{$v->display_name}}（{{str_replace('电子游戏','',$v->game->gamePlat->game_plat_name)}}）</div>
                        <div class="game-img" @if($v->game->game_icon_path)style="background-image: url({{asset($v->game->game_icon_path)}});"@endif>
                        </div>
                        <div class="num-wrap clearfix">
                            <div class="popular-tab">
                                <em>人气</em>
                                <i>{{ $v->game->game_popularity }}</i>
                            </div>
                            <div class="reward-tab">
                                <em>返奖率</em>
                                <i>{{ $v->game->return_award_rate }}%</i>
                            </div>
                        </div>
                        <div class="game-line"></div>
                        <div class="btn-wrap text-center">
                            <button class="btn btn-warning tx_login_game" data-url="{{ route('players.joinElectronicGame', ['game_id'=>$v->game_id]) }}">进入游戏</button>
                            @if($v->game->is_demo == 1)
                            <button class="btn btn-warning2 tx_login_game" data-url="{{route('players.joinDemoElectronicGame', ['game_id'=>$v->game_id])}}">免费试玩</button>
                            @else
                            <button class="btn btn-warning3">不支持试玩</button>
                            @endif
                        </div>
                    </div>
                    <div class="show-wrap">
                        <div class="game-title inaline text-center">{{$v->display_name}}（{{str_replace('电子游戏','',$v->game->gamePlat->game_plat_name)}}）</div>
                        <div class="game-img" @if($v->game->game_icon_path)style="background-image: url({{asset($v->game->game_icon_path)}});"@endif>
                            <div class="collect @if(\WinwinAuth::memberUser() && $v->collect($v->id,\WinwinAuth::memberUser()->player_id)) active @endif collect-game" data-action="{{ \WinwinAuth::memberUser() && $v->collect($v->id,\WinwinAuth::memberUser()->player_id) ?0:1 }}" data-id="{{ $v->id }}">
                                <div class="collect-main"></div>
                            </div>
                        </div>
                        <div class="num-wrap clearfix">
                            <div class="popular-tab">
                                <em>人气</em>
                                <i>{{ $v->game->game_popularity }}</i>
                            </div>
                            <div class="reward-tab">
                                <em>返奖率</em>
                                <i>{{ $v->game->return_award_rate }}%</i>
                            </div>
                        </div>
                        <div class="game-line"></div>
                        <div class="btn-wrap text-center">
                            <button class="btn btn-warning tx_login_game" data-url="{{ route('players.joinElectronicGame', ['game_id'=>$v->game_id]) }}">进入游戏</button>
                            @if($v->game->is_demo == 1)
                            <button class="btn btn-warning2 tx_login_game" data-url="{{route('players.joinDemoElectronicGame', ['game_id'=>$v->game_id])}}">免费试玩</button>
                            @else
                            <button class="btn btn-warning3">不支持试玩</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                @if(!empty($ptGameList->total()))
            <div class="game-list details-show">
                <div class="table table-header">
                    <div class="table-cell game-name"><i>游戏名称</i></div>
                    <div class="table-cell game-platform"><i>平台</i></div>
                    <div class="table-cell payline"><i>赔付线</i></div>
                    <div class="table-cell game-type"><i>游戏类型</i></div>
                    <div class="table-cell game-try"><i>试玩</i></div>
                    <div class="table-cell bonus-pool"><i>奖金池</i></div>
                    <div class="table-cell sort-mode"><i>其他</i></div>
                    <div class="table-cell action"><i>操作</i></div>
                </div>
                @foreach($ptGameList as $v)
                    <div class="table table-item">
                        <div class="table-cell game-name tx_login_game"  data-url="{{ route('players.joinElectronicGame', ['game_id'=>$v->game_id]) }}" style="cursor: pointer">{{$v->display_name}}</div>
                        <div class="table-cell game-platform">{{str_replace('电子游戏','',$v->game->gamePlat->game_plat_name)}}</div>
                        <div class="table-cell payline">{{$v->game->game_lines?$game_lines_array[$v->game->game_lines]:'0'}}</div>
                        <div class="table-cell game-type">{{$v->game->game_mcategory?$game_mcategory_array[$v->game->game_mcategory]:'暂未设置'}}</div>
                        <div class="table-cell game-try">{{ $v->game->is_demo == 0 ? '否':'是'}}</div>
                        <div class="table-cell bonus-pool">{{ $v->game->gold_pool}}元</div>
                        <div class="table-cell sort-mode">
                            <div class="table-cell popular">
                                <em>人气</em> <i>{{ $v->game->game_popularity }}</i>
                            </div>
                            <div class="table-cell reward-rate">
                                <em>返奖率</em> <i>{{ $v->game->return_award_rate }}%</i>
                            </div>
                        </div>
                        <div class="table-cell action collect-game @if(\WinwinAuth::memberUser() && $v->collect($v->id,\WinwinAuth::memberUser()->player_id)) active @endif" data-action="{{ \WinwinAuth::memberUser() && $v->collect($v->id,\WinwinAuth::memberUser()->player_id) ?0:1 }}" data-id="{{ $v->id }}">
                            <span class="glyphicon glyphicon-star-empty"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <i>收藏</i>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
            @endif
        </article>

        @if(empty($ptGameList->total()))
        <article class="game-list no-game">

        </article>
        @endif
        @if(!empty($ptGameList->total()))
        <article class="pagenation-container clearfix">
            <div class="pageinfo float-left">
                <p>共<i class="game-count">{{ $ptGameList->total() }}</i>个游戏，共<i class="pagenum">{{ $ptGameList->lastPage() }}</i>页，每页<i class="onpagenum">{{ $ptGameList->perPage() }}</i>个</p>
            </div>
            {{ $ptGameList->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
        </article>
            @endif
    </section>
 
    <script>
        $(function () {
            var url = "{!! app('request')->fullUrl() !!}";
            // 链接跳转
            $(document).on('click','.link-box',function(event){
                event.preventDefault();
                event.stopPropagation();
                var alias = $(this).attr('data-node');
                var val = $(this).attr('data-value');
                var reg = '';
                if(url.indexOf('gameName')>0) {
                    var gameName = new RegExp( "gameName=([^&]*)(&|$)", "i");
                    url = url.replace(gameName,"");
                }
                if(url.indexOf('page')>0) {
                    var page = new RegExp( "page=([^&]*)(&|$)", "i");
                    url = url.replace(page,"");
                }
                if(url.indexOf('?')>0) {
                    if($.inArray(alias,['sort_popular','sort_reward']) >= 0) {
                        reg = new RegExp("sort_([^=]*)=([^&]*)(&|$)", "i");
                    } else {
                        reg = new RegExp( alias + "=([^&]*)(&|$)", "i");
                    }
                    url = url.replace(reg, "");
                    window.location.href=url+"&"+alias+"="+val;
                } else {
                    window.location.href=url+"?"+alias+"="+val;
                }

            });
            // 收藏或取消收藏
            $(document).on('click','.collect-game',function(event){
                event.preventDefault();
                event.stopPropagation();
                var _this = $(this);
                var action = _this.attr('data-action');
                var carrier_game_id = _this.attr('data-id');
                var action_change = 0;
                if (action == 0){
                    action_change = 1;
                }
                _this.removeClass('collect-game');
                $.ajax({
                    type: 'post',
                    url: "{{route('players.collectGame')}}",
                    data: {action:action,carrier_game_id:carrier_game_id},
                    dataType: 'json',
                    success: function(data){
                        if(data.success == true){
                            _this.attr('data-action', action_change);
                            if (action == 0){
                                _this.removeClass('active');
                            }else{
                                _this.addClass('active');
                            }
                        }
                        _this.addClass('collect-game');
                        layer.msg(data.data,{
                            success: function(layero, index){
                                var _this = $(layero);
                                _this.css('top', '401.5px');
                            }
                        });
                    },
                    error: function(xhr){
                        if(xhr.responseJSON.success == false) {
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
            $('#search').focus(function () {
               $(this).val('');
            });

            //分页链接跳转
            $(document).on('click','.pagination li a',function(event){
                event.preventDefault();
                event.stopPropagation();
                var hrefurl = $(this).attr("href");
                var reg = new RegExp("page=([^&]*)(&|$)");
                var r = hrefurl.substr(1).match(reg);
                var page =r[1];
                if(url.indexOf('?')>0) {
                    url = url.replace(reg, "");
                    window.location.href=url+"&page="+page;
                } else {
                    window.location.href=url+"?page="+page;
                }
            });
            
            // 进入游戏查询弹框
            //tools.layer.game();
        })
    </script>
@endsection
