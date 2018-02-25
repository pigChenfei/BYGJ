<thead>
<tr role="row">
    <th>序号</th>
    <th>登录IP</th>
    <th>登录域名</th>
    <th>最后登录时间</th>
</tr>
</thead>
<tbody>
@foreach($player->loginLogs as $log)
    <tr role="row">
        <td>{!! $log->log_id !!}</td>
        <td>{!! $log->login_ip !!}</td>
        <td>{!! $log->login_domain !!}</td>
        <td>{!! $log->login_time !!}</td>
    </tr>
@endforeach
</tbody>