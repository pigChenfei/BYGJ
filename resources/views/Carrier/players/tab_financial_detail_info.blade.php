<table style="margin-top: 40px" class="table table-bordered table-hover table-responsive">
    <tbody>
    <tr role="row">
        <th>存款次数</th>
        <td>{!! $static['depositCount'] !!}</td>
        <th>存款额</th>
        <td>{!! sprintf('%.2f',$static['depositAmount']) !!}</td>
        <th>取款次数</th>
        <td>{!! $static['withdrawCount'] !!}</td>
        <th>取款额</th>
        <td>{!! sprintf('%.2f',$static['withdrawAmount']) !!}</td>
    </tr>
    <tr role="row">
        <th>存款优惠</th>
        <td>{!! sprintf('%.2f',$static['depositBenefitAmount']) !!}</td>
        <th>手续费</th>
        <td>{!! sprintf('%.2f',$static['feeAmount']) !!}</td>
        <th>红利</th>
        <td>{!! sprintf('%.2f',$static['bonusAmount']) !!}</td>
        <th>洗码</th>
        <td>{!! sprintf('%.2f',$static['rebateFinancialFlowAmount']) !!}</td>
    </tr>
    <tr role="row">
        <th>投注额</th>
        <td>{!! sprintf('%.2f',$static['betAmount']) !!}</td>
        <th>派彩</th>
        <td>{!! sprintf('%.2f',$static['payoutAmount']) !!}</td>
        <th>有效投注</th>
        <td>{!! sprintf('%.2f',$static['availableBetAmount']) !!}</td>
        <th>公司总输赢</th>
        <td>{!! sprintf('%.2f',$static['winLoseAmount']) !!}</td>

    </tr>

    </tbody>

</table>
