/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/13
 * Time: 22:35
 */
@extends('Web.default.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/css/layer.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('./app/css/account.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.default.layouts.players_center_nav')
@endsection

@section('content')
    <div class="bg_container" style="padding-top: 10px;">
        <div class="Member-Center" style="margin:20px auto;">
            <ul class="nav nav-tabs" style="background-color: #eee;">
                <li class="active"><a href="#my-recommends" data-toggle="tab" id="myRecommends">我要推荐</a></li>
                <li><a href="#my-referrals" data-toggle="tab" id="myReferrals">我的下线</a></li>
                <li><a href="#account-statistics" data-toggle="tab" id="accountStatistics">账目统计</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="my-recommends">

                </div>
                <div class="tab-pane" id="my-referrals" style="margin-left: 20px;">

                </div>
                <div class="tab-pane" id="account-statistics">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{!! asset('./app/js/Copy.UI-master/assets/import/jquery.zclip.min.js') !!}"></script>
    <script src="{!! asset('./app/js/financial-statement.js') !!}"></script>
    <script src="{!! asset('./app/js/friend-recommendation.js') !!}"></script>
@endsection

