<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $carrierImageCategory->id !!}</p>
</div>

<!-- Category Name Field -->
<div class="form-group">
    {!! Form::label('category_name', 'Category Name:') !!}
    <p>{!! $carrierImageCategory->category_name !!}</p>
</div>

<!-- Carrier Id Field -->
<div class="form-group">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    <p>{!! $carrierImageCategory->carrier_id !!}</p>
</div>

<!-- Parent Category Id Field -->
<div class="form-group">
    {!! Form::label('parent_category_id', 'Parent Category Id:') !!}
    <p>{!! $carrierImageCategory->parent_category_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $carrierImageCategory->created_at !!}</p>
</div>

<!-- Created User Id Field -->
<div class="form-group">
    {!! Form::label('created_user_id', 'Created User Id:') !!}
    <p>{!! $carrierImageCategory->created_user_id !!}</p>
</div>

