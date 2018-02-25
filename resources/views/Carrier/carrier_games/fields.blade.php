<div class="form-group col-sm-12">
    {!! Form::label('display_name', '前台显示名称:').Form::required_pin() !!}
    {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
</div>
<!-- Sort Field -->
<div class="form-group col-sm-12">
    {!! Form::label('sort', '排序') !!}
    {!! Form::text('sort', null, ['class' => 'form-control']) !!}
</div>