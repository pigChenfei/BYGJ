<!-- Username Field -->
<div class="form-group col-sm-6">
    {!! Form::label('username', 'Username:') !!}
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Pwd Salt Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pwd_salt', 'Pwd Salt:') !!}
    {!! Form::text('pwd_salt', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Create Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('create_time', 'Create Time:') !!}
    {!! Form::date('create_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Login Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_login_time', 'Last Login Time:') !!}
    {!! Form::date('last_login_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Login Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login_ip', 'Login Ip:') !!}
    {!! Form::number('login_ip', null, ['class' => 'form-control']) !!}
</div>

<!-- Parent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_id', 'Parent Id:') !!}
    {!! Form::number('parent_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('adminUsers.index') !!}" class="btn btn-default">Cancel</a>
</div>
