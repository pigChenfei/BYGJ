<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{!! $adminUser->user_id !!}</p>
</div>

<!-- Username Field -->
<div class="form-group">
    {!! Form::label('username', 'Username:') !!}
    <p>{!! $adminUser->username !!}</p>
</div>

<!-- Password Field -->
<div class="form-group">
    {!! Form::label('password', 'Password:') !!}
    <p>{!! $adminUser->password !!}</p>
</div>

<!-- Pwd Salt Field -->
<div class="form-group">
    {!! Form::label('pwd_salt', 'Pwd Salt:') !!}
    <p>{!! $adminUser->pwd_salt !!}</p>
</div>

<!-- Mobile Field -->
<div class="form-group">
    {!! Form::label('mobile', 'Mobile:') !!}
    <p>{!! $adminUser->mobile !!}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{!! $adminUser->email !!}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $adminUser->status !!}</p>
</div>

<!-- Create Time Field -->
<div class="form-group">
    {!! Form::label('create_time', 'Create Time:') !!}
    <p>{!! $adminUser->create_time !!}</p>
</div>

<!-- Last Login Time Field -->
<div class="form-group">
    {!! Form::label('last_login_time', 'Last Login Time:') !!}
    <p>{!! $adminUser->last_login_time !!}</p>
</div>

<!-- Login Ip Field -->
<div class="form-group">
    {!! Form::label('login_ip', 'Login Ip:') !!}
    <p>{!! $adminUser->login_ip !!}</p>
</div>

<!-- Parent Id Field -->
<div class="form-group">
    {!! Form::label('parent_id', 'Parent Id:') !!}
    <p>{!! $adminUser->parent_id !!}</p>
</div>

