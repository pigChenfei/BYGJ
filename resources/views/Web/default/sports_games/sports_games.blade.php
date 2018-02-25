@extends('Web.default.layouts.app')

@section('css')
    @include('Web.default.layouts.sports_games_css')
@endsection

@section('header-nav')
    @include('Web.default.layouts.index_nav')
@endsection

@section('content')
    <div class="sports">
            <div>
                <div>
                    <nav class="usercenter-row">
                        <ul class="nav nav-tabs pull-left">
                            <li class="active "><a href="{!! route('players.loginBBinHall',array('ball')) !!}" data-toggle="tab">BBIN体育</a></li>
                            <li><a href="#user-sports1" data-toggle="tab">沙巴体育</a></li>
                        </ul>
                    </nav>
                    <div class="tab-content">
                        <div class="tab-pane active" id="user-sports"></div>
                        <div class="tab-pane" id="user-sports1"></div>
                    </div>
                </div>
            </div>
    </div>
@endsection
