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
        <!--取款记录-->
            <article class="drawrecord">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">取款记录</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <label for="">状态：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle status" data-value="@if(!empty(app('request')->input('status'))){{app('request')->input('status')}}@endif" id="dn-draw" data-toggle="dropdown"/><i>@if(!empty(app('request')->input('status'))){{$withdrawStatus[app('request')->input('status')]}}@else全部@endif</i></button>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value=""  href="javascript:void(0)">全部</a>
                                </li>
                                @foreach($withdrawStatus as $k => $status)
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
                                <th class="text-center">取款编号</th>
                                <th class="text-center">取款申请时间</th>
                                <th class="text-center">取款金额</th>
                                <th class="text-center">手续费</th>
                                <th class="text-center">实际出款金额</th>
                                <th class="text-center">处理时间</th>
                                <th class="text-center">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($playerWithdrawLog as $item)
                                <tr>
                                    <td>{!! $item->order_number !!}</td>
                                    <td>{!! $item->created_at!!}</td>
                                    <td>{!! $item->apply_amount !!}</td>
                                    <td>{!! $item->fee_amount !!}</td>
                                    <td>{!! $item->finally_withdraw_amount !!}</td>
                                    <td>{!! $item->withdraw_succeed_at!!}</td>
                                    <td>{!! $item->statusMeta()[$item->status] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$playerWithdrawLog->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $playerWithdrawLog->total() }}</i>项，共<i class="pagenum">{{ $playerWithdrawLog->lastPage() }}</i>页，每页<i class="onpagenum">{{ $playerWithdrawLog->perPage() }}</i>个</p>
                            </div>
                            {{ $playerWithdrawLog->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
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

