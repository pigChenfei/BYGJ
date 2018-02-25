<table class="table table-bordered table-hover table-responsive">
    <thead>
    <tr>
        <th>账号</th>
        <th>真实姓名</th>
        <th>手机号</th>
        <th>邮箱</th>
        <th>QQ</th>
        <th>注册IP</th>
        <th>上次登录时间</th>
        <th>注册时间</th>
    </tr>
    </thead>
    <tbody>
    @foreach($carrierSubAgentUser as $value)
        <tr role="row">
            <td>{!! $value->username !!}</td>
            <td>{!! $value->realname !!}</td>
            <td>{!! $value->mobile !!}</td>
            <td>{!! $value->email !!}</td>
            <td>{!! $value->qq !!}</td>
            <td>{!! $value->register_ip !!}</td>
            <td>{!! $value->login_time !!}</td>
            <td>{!! $value->created_at !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
