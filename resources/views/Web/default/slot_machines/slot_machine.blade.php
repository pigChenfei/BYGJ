@extends('Web.default.layouts.app')

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')

    <main class="slot-machine">
        <div>
            <div class="pull-left">
                <div class="solt-left">
                    <div></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
                <div>
                    <div ></div>
                    <div>
                        <i class="back-platform"></i>
                        <p><b>PT平台</b></p>
                        <p><a href="#">点击进入</a></p>
                    </div>
                </div>
            </div>
            <div class="pull-right slot-machine-main">
                <div></div>
                <div>
                    <div class="pull-left" style="margin-left: 10px;">
                    	<!--<img src="{!! asset('./app/img/slot_slide1-1.png') !!}" alt=""/>-->
                    	<div id="slot_Carousel" class="carousel slide" data-ride="carousel" style="width: 893px;">
		                    <ol class="carousel-indicators">
		                        <li data-target="#slot_Carousel" data-slide-to="0" class="active"></li>
		                        <li data-target="#slot_Carousel" data-slide-to="1"></li>
		                        <li data-target="#slot_Carousel" data-slide-to="2"></li>
		                        <li data-target="#slot_Carousel" data-slide-to="3"></li>
		                    </ol>
		                    <div class="carousel-inner" role="listbox">
		                        <div class="item active">
		                            <img src="{!! asset('./app/img/slot_slide1-1.png') !!}" alt=""/>
		                        </div>
		                        <div class="item">
		                            <img src="{!! asset('./app/img/slot_slide1-1.png') !!}" alt=""/>
		                        </div>
		                	</div>
		            	</div>
                    </div>                
                    <div class="clearfix"></div>
                    <div class="slot-machine-ul">
                        <div class="back-slot-machine-ul"></div>
                        <div class="slot-machine-select">
                            <div>
                                <ul class="list-inline pull-left" >
                                    <li>热门游戏</li>
                                    <li>奖金池游戏</li>
                                    <li>最新游戏</li>
                                    <li>全部游戏</li>
                                </ul>
                                <div class="pull-right">
                                    <input type="text" id="searchPtGame" placeholder="搜索游戏" maxlength="16"/><i class="back-magnifier"></i>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <select name="" id="">
                                    <option value="">游戏类型</option>
                                </select>
                                <select name="" id="">
                                    <option value="">赔付线数</option>
                                    <option value="">1-4线</option>
                                    <option value="">5-9线</option>
                                    <option value="">15-25线</option>
                                </select>
                                <select name="" id="">
                                    <option value="">会员类型</option>
                                    <option value="">小额会员</option>
                                    <option value="">中级会员</option>
                                    <option value="">高富帅会员</option>
                                </select>
                                <select name="" id="">
                                    <option value="">游戏风格</option>
                                    <option value="">卡通</option>
                                    <option value="">冒险</option>
                                    <option value="">神话</option>
                                </select>
                                <span class="btn btn-primary" style="background-color: #02acff">重新选择</span>
                            </div>
                        </div>
                    </div>
                    <div id="gameList">
                        @include('Web.default.slot_machines.slot_machine_list')
                    </div>
                    {{--<div class="slot-machine-img" id="gamePlat">

                    </div>
                    <div style="position: absolute; top: 983px; left: 768px;" id="pagination">{!! $ptGameList->links() !!}</div>--}}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </main>

@endsection

@section('scripts')
@endsection
