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
        <!--存款记录-->
            <article class="saverecord">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">存款记录</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <label for="">状态：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle status" data-value="@if(null !=app('request')->input('status') && app('request')->input('status') != ''){{app('request')->input('status')}}@endif" id="dn-draw" data-toggle="dropdown"/><i>@if(null !=app('request')->input('status') && app('request')->input('status') != ''){{$orderStatus[app('request')->input('status')]}}@else全部@endif</i></button>
                            <span class="caret"></span>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value=""  href="javascript:void(0)">全部</a>
                                </li>
                                @foreach($orderStatus as $k => $status)
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
                                <th class="text-center">存款编号</th>
                                <th class="text-center">存款时间</th>
                                <th class="text-center">存款类型</th>
                                <th class="text-center">存款金额</th>
                                <th class="text-center">手续费</th>
                                <th class="text-center">优惠金额</th>
                                <th class="text-center">实际到账</th>
                                <th class="text-center">处理时间</th>
                                <th width="10%" class="text-center">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playerDepositPaylog as $playerDeposit)
                                <tr>
                                    <td>{!! $playerDeposit->pay_order_number !!}</td>
                                    <td title="{{$playerDeposit->created_at}}" >
                                    	<div class="realname-tip">
                                            <span class="realname-tip-question font-white">{!! substr($playerDeposit->created_at,0, 10) !!}</span>
                                            <div class="realname-tip-text">{!! $playerDeposit->created_at !!}</div>
                                        </div>
                                    </td>
                                    <td>{!! $playerDeposit->carrierPayChannel->payChannel->payChannelType->type_name !!}</td>
                                    <td>{!! $playerDeposit->amount !!}</td>
                                    <td>{!! $playerDeposit->is_fee_amt == 1 ? $playerDeposit->fee_amount : sprintf('%.2f',0) !!}</td>
                                    <td>{!! $playerDeposit->benefit_amount !!}</td>
                                    <td>{!! $playerDeposit->finally_amount !!}</td>
                                    <td title="{{$playerDeposit->operate_time}}">
                                   	 	<div class="realname-tip">
                                            <span class="realname-tip-question font-white">{!! substr($playerDeposit->operate_time,0, 10) !!}</span>
                                            <div class="realname-tip-text">{!! $playerDeposit->operate_time !!}</div>
                                        </div>
                                    </td>
                                    <td>{!! $playerDeposit::orderStatusMeta()[$playerDeposit->status] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$playerDepositPaylog->total())
                    <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $playerDepositPaylog->total() }}</i>项，共<i class="pagenum">{{ $playerDepositPaylog->lastPage() }}</i>页，每页<i class="onpagenum">{{ $playerDepositPaylog->perPage() }}</i>个</p>
                            </div>
                            {{ $playerDepositPaylog->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function () {

        })
    </script>
@endsection

