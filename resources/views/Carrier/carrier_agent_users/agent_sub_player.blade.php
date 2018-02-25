<table class="table table-bordered table-hover table-responsive">
    <thead>
    <tr>
        <th>账号</th>
        <th>姓名</th>
        <th>手机号</th>
        <th>邮箱</th>
        <th>QQ</th>
        <th>注册IP</th>
        <th>注册地址</th>
        <th>注册时间</th>
    </tr>
    </thead>
    <tbody>
    @foreach($player as $value)
        <tr role="row">
            <td>{!! $value->user_name !!}</td>
            <td>{!! $value->real_name !!}</td>
            <td>{!! $value->mobile !!}</td>
            <td>{!! $value->email !!}</td>
            <td>{!! $value->qq_account !!}</td>
            <td>{!! $value->register_ip !!}</td>
            <td></td>
            <td>{!! $value->created_at !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
