<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $carrier->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $carrier->name !!}</p>
</div>

<!-- Site Url Field -->
<div class="form-group">
    {!! Form::label('site_url', 'Site Url:') !!}
    <p>{!! $carrier->site_url !!}</p>
</div>

<!-- Is Forbidden Field -->
<div class="form-group">
    {!! Form::label('is_forbidden', 'Is Forbidden:') !!}
    <p>{!! $carrier->is_forbidden !!}</p>
</div>

<!-- Remain Quota Field -->
<div class="form-group">
    {!! Form::label('remain_quota', 'Remain Quota:') !!}
    <p>{!! $carrier->remain_quota !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $carrier->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $carrier->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $carrier->deleted_at !!}</p>
</div>

