<div class="table-wrap">
    <table class="table table-y text-center">
        <tbody>
        <tr>
            <td class="th">总会员数</td>
            <td>{!! $agentMemberSum !!}</td>
        </tr>
        <tr>
            <td class="th">首次存款计数</td>
            <td>{!! $firstDepositNumber !!}</td>
        </tr>
        <tr>
            <td class="th">活跃用户</td>
            <td>{!! $activeMember !!}</td>
        </tr>
        <tr>
            <td class="th">全部取款金额</td>
            <td>{!! $withdrawAmount !!}</td>
        </tr>
        @foreach ($array as $v)
        <tr>
            <td class="th">有效投注额（{{$v['plat']}}）</td>
            <td>{{$v['bettingAmount']}}</td>
        </tr>
        @endforeach
        <tr>
            <td class="th">有效投注额</td>
            <td>{!! $bettingAmountAll !!}</td>
        </tr>
        </tbody>
    </table>
</div>
<div class="table-wrap">
    <table class="table table-y text-center">
        <tbody>
        <tr>
            <td class="th">点击数</td>
            <td>{!! $promoteClicks !!}</td>
        </tr>
        <tr>
            <td class="th">全部存款计数</td>
            <td>{!! $depositNumber !!}</td>
        </tr>
        <tr>
            <td class="th">新增会员</td>
            <td>{!! $agentNewMemberSum !!}</td>
        </tr>
        <tr>
            <td class="th">全部存款金额</td>
            <td>{!! $depositAmount !!}</td>
        </tr>
        @foreach ($array as $v)
        <tr>
            <td class="th">公司总输赢（{{$v['plat']}}）</td>
            <td>{{$v['companyWinAmount']}}</td>
        </tr>
        @endforeach
        <tr>
            <td class="th">公司总输赢</td>
            <td>{!! $companyWinAmountAll !!}</td>
        </tr>
        </tbody>
    </table>
</div>