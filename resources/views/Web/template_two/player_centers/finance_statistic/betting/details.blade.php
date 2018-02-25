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
                    <h4 class="art-tit">投注详情</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('betting_detail_start'))){{app('request')->input('betting_detail_start')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('betting_detail_end'))){{app('request')->input('betting_detail_end')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <button class="btn btn-warning record-search-betting" style="transform:translateY(-1px);"><i>查询</i></button>
                        <a href="javascript:" class="float-right backquery" onclick="window.history.back()" title="返回投注记录"></a>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th class="text-center">游戏局号</th>
                                <th class="text-center">游戏名称</th>
                                <th class="text-center">投注内容</th>
                                <th class="text-center">下注金额</th>
                                <th class="text-center">有效投注额</th>
                                <th class="text-center">派彩金额</th>
                                <th class="text-center">输赢</th>
                                <th class="text-center">投注时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($betFlowDetails as $item)
                                <tr>
                                    <td>{!! $item->game_flow_code !!}</td>
                                    <td>
                                    @if(is_null($item->game)) 
                                    {!! $item->bet_content !!}
                                    @else
                                    {!! $item->game->game_name !!}
                                    @endif
                                    </td>
                                    <td>{!! $item->bet_content !!}</td>
                                    <td>{!! $item->bet_amount !!}</td>
                                    <td>{!! $item->available_bet_amount !!}</td>
                                    <td>{!! $item->company_payout_amount !!}</td>
                                    <td>{!! 0-$item->company_win_amount !!}</td>
                                    <td>{!! $item->created_at !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$betFlowDetails->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $betFlowDetails->total() }}</i>项，共<i class="pagenum">{{ $betFlowDetails->lastPage() }}</i>页，每页<i class="onpagenum">{{ $betFlowDetails->perPage() }}</i>个</p>
                            </div>
                            {{ $betFlowDetails->appends($data)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
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
            var status = "{!! $data['gamePlatId'] !!}";
            window.location.href = url+'?gamePlatId='+status+'&betting_detail_start='+start_time+'&betting_detail_end='+end_time;
        });
    </script>
@endsection
