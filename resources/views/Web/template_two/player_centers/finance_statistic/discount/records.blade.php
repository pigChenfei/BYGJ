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
        <!--优惠记录-->
            <article class="discount-record">
                <div class="art-title"></div>
                <div class="art-body">
                    <h4 class="art-tit">优惠记录</h4>
                    <div class="query">
                        <label for="">开始时间：</label>
                        <input type="text" class="form-control start-time" value="@if(!empty(app('request')->input('start_time'))){{app('request')->input('start_time')}}@endif" placeholder="选择开始时间" readonly/>
                        <label for="">结束时间：</label>
                        <input type="text" class="form-control end-time" value="@if(!empty(app('request')->input('end_time'))){{app('request')->input('end_time')}}@endif" placeholder="选择结束时间" readonly disabled />
                        <label for="">状态：</label>
                        <div class="dropdown" style="display: inline-block;">
                            <button class="btn dropdown-toggle status" data-value="@if(null !=app('request')->input('status') && app('request')->input('status') != ''){{app('request')->input('status')}}@endif" id="dn-draw" data-toggle="dropdown"/><i>@if(null !=app('request')->input('status') && app('request')->input('status') != ''){{$carrierActivityStatus[app('request')->input('status')]}}@else全部@endif</i></button>
                            <ul class="dropdown-menu xiala" role="menu" aria-labelledby="dn-draw">
                                <li role="presentation" class="transferFrom">
                                    <a role="menuitem" tabindex="-1" data-value=""  href="javascript:void(0)">全部</a>
                                </li>
                                @foreach($carrierActivityStatus as $k => $status)
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
                                <th class="text-center">优惠编号</th>
                                <th class="text-center">优惠名称</th>
                                <th class="text-center">优惠类型</th>
                                <th class="text-center">限制平台</th>
                                <th class="text-center">红利金额</th>
                                <th class="text-center">流水要求</th>
                                <th class="text-center">审核时间</th>
                                <th class="text-center">备注</th>
                                <th class="text-center">状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($carrierActivityAudit as $item)
                                <tr>
                                    <td>{!! $item->id !!}</td>
                                    <td class="text-center">
                                        @if(isset($item->activity))
                                            <div class="realname-tip">
                                                <span class="realname-tip-question font-white">{!! str_limit($item->activity->name,7) !!}</span>
                                                <div class="realname-tip-text">{!! $item->activity->name !!}</div>
                                            </div>
                                        @else
                                            活动已被删
                                        @endif
                                    </td>
                                    <td> @if(isset($item->activity))
                                            {!! $item->activity->actType->type_name !!}
                                        @else
                                            活动已被删
                                        @endif</td>
                                    <td>
                                        @if(isset($item->activity))
                                            <div class="realname-tip">
                                                <span class="realname-tip-question font-white">@if(count($item->activity->activityWithdrawFlowLimitGamePlats)>0)@foreach($item->activity->activityWithdrawFlowLimitGamePlats as $v)@if($loop->index <3){!! str_limit($v->gamePlat->game_plat_name, 3) !!}|@endif @endforeach @else 无平台限制 @endif</span>
                                                <div class="realname-tip-text">@if(count($item->activity->activityWithdrawFlowLimitGamePlats)>0)@foreach($item->activity->activityWithdrawFlowLimitGamePlats as $v){!! $v->gamePlat->game_plat_name !!}、@endforeach @else 无平台限制 @endif</div>
                                            </div>
                                        @else
                                            活动已被删
                                        @endif
                                    </td>
                                    <td>{!! $item->process_bonus_amount !!}</td>
                                    <td>{!! $item->process_withdraw_flow_limit !!}</td>
                                    <td>
                                    	<div class="realname-tip">
                                            <span class="realname-tip-question font-white">{!! substr($item->updated_at,0, 10) !!}</span>
                                            <div class="realname-tip-text" style="width:120px;">{!! $item->updated_at !!}</div>
                                        </div>
									</td>
                                    <td>
                                    	<div class="realname-tip">
                                            <span class="realname-tip-question font-white">{!! str_limit($item->remark, 4) !!}</span>
                                            <div class="realname-tip-text">{!! $item->remark !!}</div>
                                        </div>
									</td>
                                    <td>{!! $item->statusMeta()[$item->status] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$carrierActivityAudit->total())
                        <div class="norecord table"><div class="table-cell">暂无记录</div></div>
                    @else
                        <div class="pagenation-container clearfix">
                            <div class="pageinfo float-left">
                                <p>共<i class="game-count">{{ $carrierActivityAudit->total() }}</i>项，共<i class="pagenum">{{ $carrierActivityAudit->lastPage() }}</i>页，每页<i class="onpagenum">{{ $carrierActivityAudit->perPage() }}</i>个</p>
                            </div>
                            {{ $carrierActivityAudit->appends($parameter)->links('Web.'.\WinwinAuth::currentWebCarrier()->template.'.pageStyle.template_one', 1) }}
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

