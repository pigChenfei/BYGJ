<!-- User Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_name', 'User Name:') !!}
    {!! Form::text('user_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<!-- Real Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('real_name', 'Real Name:') !!}
    {!! Form::text('real_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Pay Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_password', 'Pay Password:') !!}
    {!! Form::text('pay_password', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Score Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score', 'Score:') !!}
    {!! Form::number('score', null, ['class' => 'form-control']) !!}
</div>

<!-- Main Account Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('main_account_amount', 'Main Account Amount:') !!}
    {!! Form::number('main_account_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Login Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login_ip', 'Login Ip:') !!}
    {!! Form::text('login_ip', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_id', 'Agent Id:') !!}
    {!! Form::number('agent_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Level Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_level_id', 'Player Level Id:') !!}
    {!! Form::number('player_level_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Level Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('level_id', 'Level Id:') !!}
    {!! Form::number('level_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Wrong Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password_wrong_time', 'Password Wrong Time:') !!}
    {!! Form::date('password_wrong_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Login Domain Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login_domain', 'Login Domain:') !!}
    {!! Form::text('login_domain', null, ['class' => 'form-control']) !!}
</div>

<!-- Qq Account Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qq_account', 'Qq Account:') !!}
    {!! Form::text('qq_account', null, ['class' => 'form-control']) !!}
</div>

<!-- Birthday Field -->
<div class="form-group col-sm-6">
    {!! Form::label('birthday', 'Birthday:') !!}
    {!! Form::date('birthday', null, ['class' => 'form-control']) !!}
</div>

<!-- Register Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('register_ip', 'Register Ip:') !!}
    {!! Form::text('register_ip', null, ['class' => 'form-control']) !!}
</div>

<!-- Login At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login_at', 'Login At:') !!}
    {!! Form::date('login_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('players.index') !!}" class="btn btn-default">Cancel</a>
</div>
