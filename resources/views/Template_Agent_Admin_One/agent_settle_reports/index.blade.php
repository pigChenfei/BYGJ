@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')
@section('css')
@endsection
@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--会员报表-->
    <article class="memb-excl">
        <div class="art-title"></div>
        <div class="art-body">
            <h4 class="art-tit">佣金报表</h4>
            <div class="table-wrap">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-center">结算期</th>
                        <th class="text-center">成本分摊</th>
                        <th class="text-center">累计上期</th>
                        <th class="text-center">手工调整</th>
                        <th class="text-center">本期佣金</th>
                        <th class="text-center">本期洗码</th>
                        <th class="text-center">实际发放</th>
                        <th class="text-center">结转下月</th>
                        <th class="text-center">审核状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($carrierActivityAudit as $item)
                        <tr>
                            <td>
                                @if(strpos($item->settlePeriods->periods, '至'))
                                <div style="height:20px;line-height:20px;margin-top:13px;">{!! explode('至',$item->settlePeriods->periods)[0] !!}</div>
                                <div style="height:10px;line-height:10px;"><span>一</span></div>
                                <div style="height:20px;line-height:20px;margin-bottom:13px;">{!! explode('至',$item->settlePeriods->periods)[1] !!}</div>
                                    @else
                                {{$item->settlePeriods->periods}}
                                    @endif
                            </td>
                            <td><div class="details_report" data-url="{!! route('agentSettleReports.details',['id'=>$item->id]) !!}">{!! $item->cost_share !!}</div></td>
                            <td><div data-toggle="tooltip" data-original-title="累加上月佣金：{{$item->cumulative_last_month}} 累加上月洗码：{{$item->cumulative_last_month_rebate}}">{!! bcadd($item->cumulative_last_month_rebate, $item->cumulative_last_month, 2) !!}</div></td>
                            <td><div data-toggle="tooltip" data-original-title="手工调整佣金：{{$item->manual_tuneup}} 手工调整洗码金额：{{$item->manual_tuneup_rebate}}">{!! bcadd($item->manual_tuneup_rebate, $item->manual_tuneup, 2) !!}</div></td>
                            <td>{!! $item->this_period_commission or '0.00' !!}</td>
                            <td><div class="details_report" data-url="{!! route('agentSettleReports.rebate',['id'=>$item->id]) !!}">{!! $item->rebate_amount or '0.00' !!}</div></td>
                            <td><div data-toggle="tooltip" data-original-title="实际发放佣金：{{$item->actual_payment}} 手工调整洗码金额：{{$item->actual_payment_rebate}}">{!! bcadd($item->actual_payment, $item->actual_payment_rebate, 2) !!}</div></td>
                            <td><div data-toggle="tooltip" data-original-title="佣金结转：{{$item->transfer_next_month}} 洗码结转：{{$item->transfer_next_month_rebate}}">{!! bcadd($item->transfer_next_month, $item->transfer_next_month_rebate, 2) !!}</div></td>
                            <td>{!! $item->status == 3 ? '已经结算':'未结算' !!}</td>
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
                    {{ $carrierActivityAudit->links('Web.template_one.pageStyle.template_one', 1) }}
                </div>
            @endif
        </div>
    </article>

    <div class="masklayer" style="display: none">
        <div class="dialog-wrap" style="width: 600px;padding: 0;padding-top: 45px;">
            <div class="dialog-body">
                <iframe width="450" height="300" frameborder='no' border='0' src="" marginwidth='0' marginheight='0' scrolling='0' allowtransparency='yes'></iframe>
            </div>
            <!--关闭-->
            <div class="dialog-close" onclick="$(this).siblings('.dialog-body').find('iframe').attr('src','').parents('.masklayer').hide();"></div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            $(document).on('click', '.details_report', function () {
                var url = $(this).attr('data-url');
                $('.masklayer').show();
                $('.masklayer iframe').attr('src', url);
            });
            $("[data-toggle='tooltip']").tooltip();
        })
    </script>
@endsection