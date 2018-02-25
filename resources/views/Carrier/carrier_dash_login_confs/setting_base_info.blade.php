
<div class="row">
    <div class="col-sm-4">
        <!-- Site Title Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('forbidden_login_comment', '禁止管理员登录提示原因').Form::required_pin() !!}
            {!! Form::text('forbidden_login_comment', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Site Key Words Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('carrier_login_failed_count_when_locked', '管理员登录错误锁定次数').Form::required_pin() !!}
            {!! Form::number('carrier_login_failed_count_when_locked', null, ['class' => 'form-control']) !!}
        </div>

    </div>
    <div class="col-sm-4">
        <!-- Net Bank Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_register_forbidden_user_names', '会员注册限制账号') !!}
            {!! Form::text('player_register_forbidden_user_names', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Third Part Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_forbidden_register_comment', '会员禁止注册原因') !!}
            {!! Form::text('player_forbidden_register_comment', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Atm Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('player_forbidden_login_comment', '会员禁止登录原因') !!}
            {!! Form::text('player_forbidden_login_comment', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('player_login_failed_count_when_locked', '会员登录错误几次锁定') !!}
            {!! Form::number('player_login_failed_count_when_locked', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('player_login_failed_locked_time', '会员登录错误锁定时间') !!}
            {!! Form::number('player_login_failed_locked_time', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-sm-4">
        <!-- Net Bank Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_register_forbidden_user_names', '代理注册限制账号') !!}
            {!! Form::text('agent_register_forbidden_user_names', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Third Part Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_forbidden_register_comment', '代理禁止注册原因') !!}
            {!! Form::text('agent_forbidden_register_comment', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Atm Deposit Comment Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_forbidden_login_comment', '代理禁止登录原因') !!}
            {!! Form::text('agent_forbidden_login_comment', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-12">
            {!! Form::label('agent_login_failed_count_when_locked', '代理登录失败几次锁定') !!}
            {!! Form::text('agent_login_failed_count_when_locked', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group col-sm-12">
            {!! Form::label('agent_login_failed_locked_time', '代理登录失败锁定时间') !!}
            {!! Form::text('agent_login_failed_locked_time', null, ['class' => 'form-control']) !!}
        </div>
    </div>

</div>



<div class="form-group col-sm-12">
    {!! Form::button('保存当前设置', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>


