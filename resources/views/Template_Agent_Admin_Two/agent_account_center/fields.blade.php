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

<!-- Realname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('realname', 'Realname:') !!}
    {!! Form::text('realname', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Level Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_level_id', 'Agent Level Id:') !!}
    {!! Form::number('agent_level_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_number', 'Player Number:') !!}
    {!! Form::number('player_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Skype Field -->
<div class="form-group col-sm-6">
    {!! Form::label('skype', 'Skype:') !!}
    {!! Form::text('skype', null, ['class' => 'form-control']) !!}
</div>

<!-- Qq Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qq', 'Qq:') !!}
    {!! Form::text('qq', null, ['class' => 'form-control']) !!}
</div>

<!-- Wechat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('wechat', 'Wechat:') !!}
    {!! Form::text('wechat', null, ['class' => 'form-control']) !!}
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

<!-- Promotion Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('promotion_code', 'Promotion Code:') !!}
    {!! Form::text('promotion_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Account Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_account', 'Card Account:') !!}
    {!! Form::text('card_account', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_type', 'Card Type:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('card_type', false) !!}
        {!! Form::checkbox('card_type', '1', null) !!} 1
    </label>
</div>

<!-- Card Owner Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_owner_name', 'Card Owner Name:') !!}
    {!! Form::text('card_owner_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Birth Place Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_birth_place', 'Card Birth Place:') !!}
    {!! Form::text('card_birth_place', null, ['class' => 'form-control']) !!}
</div>

<!-- Parent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_id', 'Parent Id:') !!}
    {!! Form::number('parent_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Audit Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('audit_status', 'Audit Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('audit_status', false) !!}
        {!! Form::checkbox('audit_status', '1', null) !!} 1
    </label>
</div>

<!-- Is Default Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_default', 'Is Default:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_default', false) !!}
        {!! Form::checkbox('is_default', '1', null) !!} 1
    </label>
</div>

<!-- Customer Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_remark', 'Customer Remark:') !!}
    {!! Form::text('customer_remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Customer Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_time', 'Customer Time:') !!}
    {!! Form::date('customer_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Login Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login_time', 'Login Time:') !!}
    {!! Form::date('login_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Register Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('register_ip', 'Register Ip:') !!}
    {!! Form::text('register_ip', null, ['class' => 'form-control']) !!}
</div>

<!-- Remember Token Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remember_token', 'Remember Token:') !!}
    {!! Form::text('remember_token', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('agentCenters.index') !!}" class="btn btn-default">Cancel</a>
</div>
