@extends(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.app')

@section('content')
    @include(\WinwinAuth::agentUser()->template_agent_admin.'.layouts.member_left')
    <!--会员报表-->
    <article class="memb-excl">
        <div class="art-title"></div>
        <div class="art-body">
            <h4 class="art-tit">会员报表</h4>
            <div class="table-wrap">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-center">账号</th>
                        <th class="text-center">总存款</th>
                        <th class="text-center">总取款</th>
                        <th class="text-center">总优惠</th>
                        <th class="text-center">总投注额</th>
                        <th class="text-center">总有效投注额</th>
                        <th class="text-center">总洗码</th>
                        <th class="text-center">公司总输赢</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($agentPlayer as $item)
                        <tr>
                            <td>{{ $item->user_name }}</td>
                            <td>{{ $item->deposit_total ?: 0.00 }}</td>
                            <td>{{ $item->withdraw_total ?: 0.00 }}</td>
                            <td>{{ $item->depositBenefitAmount ?: 0.00 }}</td>
                            <td>{{ $item->betFlowAll ?: 0.00 }}</td>
                            <td>{{ $item->betFlowAvailable ?: 0.00 }}</td>
                            <td>{{ $item->rebateFinancialFlowAmount ?: 0.00 }}</td>
                            <td>{{ $item->winlose_total ?: 0.00 }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(!$agentPlayer->total())
                <div class="norecord table"><div class="table-cell">暂无记录</div></div>
            @else
                <div class="pagenation-container clearfix">
                    <div class="pageinfo float-left">
                        <p>共<i class="game-count">{{ $agentPlayer->total() }}</i>项，共<i class="pagenum">{{ $agentPlayer->lastPage() }}</i>页，每页<i class="onpagenum">{{ $agentPlayer->perPage() }}</i>个</p>
                    </div>
                    {{ $agentPlayer->links('Web.template_one.pageStyle.template_one', 1) }}
                </div>
            @endif
        </div>
    </article>

@endsection