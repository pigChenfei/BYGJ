<table class="table table-responsive" id="adminUsers-table">
    <thead>
        <th>Username</th>
        <th>Password</th>
        <th>Pwd Salt</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Status</th>
        <th>Create Time</th>
        <th>Last Login Time</th>
        <th>Login Ip</th>
        <th>Parent Id</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($adminUsers as $adminUser)
        <tr>
            <td>{!! $adminUser->username !!}</td>
            <td>{!! $adminUser->password !!}</td>
            <td>{!! $adminUser->pwd_salt !!}</td>
            <td>{!! $adminUser->mobile !!}</td>
            <td>{!! $adminUser->email !!}</td>
            <td>{!! $adminUser->status !!}</td>
            <td>{!! $adminUser->create_time !!}</td>
            <td>{!! $adminUser->last_login_time !!}</td>
            <td>{!! $adminUser->login_ip !!}</td>
            <td>{!! $adminUser->parent_id !!}</td>
            <td>
                {!! Form::open(['route' => ['adminUsers.destroy', $adminUser->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('adminUsers.show', [$adminUser->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('adminUsers.edit', [$adminUser->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>