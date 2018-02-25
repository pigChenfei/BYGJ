<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Birthday Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_birthday_conf_status', 'Player Birthday Conf Status:') !!}
    {!! Form::number('player_birthday_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Realname Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_realname_conf_status', 'Player Realname Conf Status:') !!}
    {!! Form::number('player_realname_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Palyer Email Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_email_conf_status', 'Palyer Email Conf Status:') !!}
    {!! Form::number('player_email_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Phone Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_phone_conf_status', 'Player Phone Conf Status:') !!}
    {!! Form::number('player_phone_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Qq Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_qq_conf_status', 'Player Qq Conf Status:') !!}
    {!! Form::number('player_qq_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Wechat Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_wechat_conf_status', 'Player Wechat Conf Status:') !!}
    {!! Form::number('player_wechat_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Consignee Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_consignee_conf_status', 'Player Consignee Conf Status:') !!}
    {!! Form::number('player_consignee_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Receiving Address Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_receiving_address_conf_status', 'Player Receiving Address Conf Status:') !!}
    {!! Form::number('player_receiving_address_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Type Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_type_conf_status', 'Agent Type Conf Status:') !!}
    {!! Form::number('agent_type_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Realname Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_realname_conf_status', 'Agent Realname Conf Status:') !!}
    {!! Form::number('agent_realname_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Birthday Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_birthday_conf_status', 'Agent Birthday Conf Status:') !!}
    {!! Form::number('agent_birthday_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Email Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_email_conf_status', 'Agent Email Conf Status:') !!}
    {!! Form::number('agent_email_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Phone Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_phone_conf_status', 'Agent Phone Conf Status:') !!}
    {!! Form::number('agent_phone_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Qq Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_qq_conf_status', 'Agent Qq Conf Status:') !!}
    {!! Form::number('agent_qq_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Skype Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_skype_conf_status', 'Agent Skype Conf Status:') !!}
    {!! Form::number('agent_skype_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Wechat Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_wechat_conf_status', 'Agent Wechat Conf Status:') !!}
    {!! Form::number('agent_wechat_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Promotion Mode Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_promotion_mode_conf_status', 'Agent Promotion Mode Conf Status:') !!}
    {!! Form::number('agent_promotion_mode_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Promotion Url Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_promotion_url_conf_status', 'Agent Promotion Url Conf Status:') !!}
    {!! Form::number('agent_promotion_url_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Promotion Idea Conf Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_promotion_idea_conf_status', 'Agent Promotion Idea Conf Status:') !!}
    {!! Form::number('agent_promotion_idea_conf_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierRegisterBasicConfs.index') !!}" class="btn btn-default">Cancel</a>
</div>
