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
        <!--投注记录-->
            <article class="bet-record">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">投注记录</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <label for="">平台：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle status" data-value="@if(null !=app('request')->input('game_plat_id') && app('request')->input('game_plat_id') != ''){{app('request')->input('game_plat_id')}}@endif" id="dn-draw" data-toggle="dropdown"/><i>@if(null !=app('request')->input('game_plat_id') && app('request')->input('game_plat_id') != ''){{\App\Models\Def\GamePlat::find(app('request')->input('game_plat_id'))['game_plat_name']}}@else全部@endif</i></button>
                            <span class="caret"></span>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value=""  href="javascript:void(0)">全部</a>
                                </li>
                                @foreach($gamePlat as $k => $status)
                                    <li role="presentation" class="transferFrom">
                                        <a role="menuitem" tabindex="-1" data-value='{!! $status->game_plat_id !!}' href="javascript:void(0)">{{ $status->game_plat_name }}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        <button class="btn btn-warning float-right record-search-betting"><i>查询</i></button>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th class="text-center">游戏平台</th>
                                <th class="text-center">投注次数</th>
                                <th class="text-center">投注额</th>
                                <th class="text-center">有效投注额</th>
                                <th class="text-center">派彩金额</th>
                                <th class="text-center">公司总输赢</th>
                                <th class="text-center">投注详情</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($betFlowLogs as $item)
                                <tr>
                                    <td>{!! $item->gamePlat->game_plat_name !!}</td>
                                    <td>{!! $item->count !!}</td>
                                    <td>{!! $item->bet_water !!}</td>
                                    <td>{!! $item->effective_bet !!}</td>
                                    <td>{!! $item->payout !!}</td>
                                    <td>{!! $item->income * -1 !!} </td>
                                    <td class="particulars-td"><b><a href="{!! route('players.bettingDetails', ['gamePlatId'=>$item->game_plat_id]) !!}" style="color: #d8a659;">查看详情</a></b></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$betFlowLogs->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $betFlowLogs->total() }}</i>项，共<i class="pagenum">{{ $betFlowLogs->lastPage() }}</i>页，每页<i class="onpagenum">{{ $betFlowLogs->perPage() }}</i>个</p>
                            </div>
                            {{ $betFlowLogs->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // 报表搜索查询
        var url = "{!! app('request')->url() !!}";
        $(document).on('click','.record-search-betting',function(event){
            event.preventDefault();
            event.stopPropagation();
            var start_time = $('.start-time').val();
            var end_time = $('.end-time').val();
            var status = $('.status').attr('data-value');
            window.location.href = url+'?game_plat_id='+status+'&start_time='+start_time+'&end_time='+end_time;
        });
    </script>
@endsection

