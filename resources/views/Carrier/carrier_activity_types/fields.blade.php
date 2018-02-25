<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('type_name', '类型名称:').Form::required_pin() !!}
    {!! Form::text('type_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Desc Field -->
<div class="form-group col-sm-12">
    {!! Form::label('desc', '类型描述:') !!}
    {!! Form::textarea('desc', null, ['class' => 'form-control','rows' => 5, 'style' => 'resize:none;']) !!}
</div>
