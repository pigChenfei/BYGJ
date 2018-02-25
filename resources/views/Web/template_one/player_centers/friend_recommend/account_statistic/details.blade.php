@extends('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.app')

@section('css')
    <link rel="stylesheet" href="{!! asset('./app/'.\WinwinAuth::currentWebCarrier()->template.'/css/member_center.css') !!}"/>
@endsection

@section('header-nav')
    @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.index_nav')
@endsection

@section('content')
    <section class="member-container">
        <div class="member-wrap clearfix">
        @include('Web.'.\WinwinAuth::currentWebCarrier()->template.'.layouts.member_left')
        <!--账目统计-->
            <article class="accounting">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">账户统计详情</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <button class="btn btn-warning record-search" style="transform:translateY(-1px);"><i>查询</i></button>
                        <a href="javascript:" class="float-right backquery" onclick="window.history.back()" title="返回账户统计"></a>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th class="text-center">会员账号</th>
                                <th class="text-center">存款额</th>
                                <th class="text-center">投注总额</th>
                                <th class="text-center">有效投注额</th>
                                <th class="text-center">结算类型</th>
                                <th class="text-center">结算金额</th>
                                <th class="text-center">结算时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($statisticDetails->items() as $detail)
                                <tr>
                                    <td>{!! $detail->user_name !!}</td>
                                    <td>{!! $detail->related_player_deposit_amount !!}</td>
                                    <td>{!! $detail->related_player_bet_amount !!}</td>
                                    <td>{!! $detail->related_player_validate_bet_amount !!}</td>
                                    <td>{!! $detail->rewardType()[$detail->reward_type] !!}</td>
                                    <td>{!! $detail->reward_amount !!}</td>
                                    <td>{!! $detail->created_at !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$statisticDetails->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $statisticDetails->total() }}</i>项，共<i class="pagenum">{{ $statisticDetails->lastPage() }}</i>页，每页<i class="onpagenum">{{ $statisticDetails->perPage() }}</i>个</p>
                            </div>
                            {{ $statisticDetails->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection


