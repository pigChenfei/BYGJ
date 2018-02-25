
<!-- Team Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('team_name', '部门名称').Form::required_pin() !!}
    {!! Form::text('team_name', null, ['class' => 'form-control']) !!}
</div>


<!-- Remark Field -->
<div class="form-group col-sm-12">
    {!! Form::label('remark', '备注:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

