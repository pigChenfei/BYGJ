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
        <!--洗码记录-->
            <article class="transfer-record">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">洗码记录</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <label for="">状态：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle status" data-value="@if(null != app('request')->input('status') && app('request')->input('status') != ''){{app('request')->input('status')}}@endif" id="dn-draw" data-toggle="dropdown"/><i>@if(null != app('request')->input('status') && app('request')->input('status') != ''){{$rebateFinancialStatus[app('request')->input('status')]}} @else全部@endif</i></button>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value=""  href="javascript:void(0)">全部</a>
                                </li>
                                @foreach($rebateFinancialStatus as $k => $status)
                                    <li role="presentation" class="transferFrom">
                                        <a role="menuitem" tabindex="-1" data-value='{!! $k !!}' href="javascript:void(0)">{{ $status }}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        <button class="btn btn-warning float-right record-search"><i>查询</i></button>
                    </div>
                    <div class="table-wrap">
                        <table class="table text-center">
                            <thead>
                            <tr>
                                <th class="text-center">洗码编号</th>
                                <th class="text-center">平台</th>
                                <th class="text-center">有效投注额</th>
                                <th class="text-center">洗码金额</th>
                                <th class="text-center">派发时间</th>
                                <th class="text-center">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playerRebateFinancialFlow as $item)
                                <tr>
                                    <td>{!! $item->id !!}</td>
                                    <td>{!! $item->gamePlat->game_plat_name !!}</td>
                                    <td>{!! $item->bet_flow_amount !!}</td>
                                    <td>{!! $item->rebate_financial_flow_amount !!}</td>
                                    <td>{!! $item->settled_at !!}</td>
                                    <td>
                                        @if($item->is_already_settled)
                                            <font color="#e23031">{!! $item->statusMeta()[$item->is_already_settled] !!}</font>
                                        @else
                                            {!! $item->statusMeta()[$item->is_already_settled] !!}
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$playerRebateFinancialFlow->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $playerRebateFinancialFlow->total() }}</i>项，共<i class="pagenum">{{ $playerRebateFinancialFlow->lastPage() }}</i>页，每页<i class="onpagenum">{{ $playerRebateFinancialFlow->perPage() }}</i>个</p>
                            </div>
                            {{ $playerRebateFinancialFlow->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    {{--<script src="{!! asset('./app/js/account-security.js') !!}"></script>--}}
@endsection

