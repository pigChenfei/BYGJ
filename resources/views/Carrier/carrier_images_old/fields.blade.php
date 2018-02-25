<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Uploaded User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('uploaded_user_id', 'Uploaded User Id:') !!}
    {!! Form::number('uploaded_user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image_path', 'Image Path:') !!}
    {!! Form::text('image_path', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Category Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image_category', 'Image Category:') !!}
    {!! Form::number('image_category', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Size Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image_size', 'Image Size:') !!}
    {!! Form::text('image_size', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierImages.index') !!}" class="btn btn-default">Cancel</a>
</div>
