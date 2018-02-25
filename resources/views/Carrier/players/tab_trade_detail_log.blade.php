<thead>
<tr>
    <th>序号</th>
    <th>类型</th>
    <th>来源</th>
    <th>金额</th>
    <th>备注</th>
    <th>操作人</th>
    <th>时间</th>
</tr>
</thead>
<tbody>
@foreach($player->accountLogs as $index => $accountLog)
    <tr>
        <td>{!! $index + 1 !!}</td>
        <td>{!! \App\Models\Log\PlayerAccountLog::fundTypeMeta()[$accountLog->fund_type] !!}</td>
        <td>{!! $accountLog->fund_source !!}</td>
        <td>{!! $accountLog->amount !!}</td>
        <td>{!! $accountLog->remark !!}</td>
        <td>{!! isset($accountLog->serviceUser) ? $accountLog->serviceUser->username : '' !!}</td>
        <td>{!! $accountLog->created_at !!}</td>
    </tr>
@endforeach
</tbody>