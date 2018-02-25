<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Forbidden Login Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('forbidden_login_comment', 'Forbidden Login Comment:') !!}
    {!! Form::text('forbidden_login_comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Register Forbidden User Names Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_register_forbidden_user_names', 'Player Register Forbidden User Names:') !!}
    {!! Form::text('player_register_forbidden_user_names', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Forbidden Login Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_forbidden_login_comment', 'Player Forbidden Login Comment:') !!}
    {!! Form::text('player_forbidden_login_comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Forbidden Register Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_forbidden_register_comment', 'Player Forbidden Register Comment:') !!}
    {!! Form::text('player_forbidden_register_comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Register Forbidden User Names Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_register_forbidden_user_names', 'Agent Register Forbidden User Names:') !!}
    {!! Form::text('agent_register_forbidden_user_names', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Forbidden Login Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_forbidden_login_comment', 'Agent Forbidden Login Comment:') !!}
    {!! Form::text('agent_forbidden_login_comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Forbidden Register Comment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_forbidden_register_comment', 'Agent Forbidden Register Comment:') !!}
    {!! Form::text('agent_forbidden_register_comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierDashLoginConfs.index') !!}" class="btn btn-default">Cancel</a>
</div>
