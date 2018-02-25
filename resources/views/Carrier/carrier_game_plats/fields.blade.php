<!-- Game Plat Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('status', '状态:').Form::required_pin() !!}
    <?php $statusMeta = \App\Models\Map\CarrierGamePlat::statusMeta(); ?>
    <select name="status" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($statusMeta as $key => $value)
            @if(isset($carrierGamePlat) && $carrierGamePlat instanceof \App\Models\Map\CarrierGamePlat && $carrierGamePlat->status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>
<!-- Sort Field -->
<div class="form-group col-sm-12">
    {!! Form::label('sort', '排序:') !!}
    {!! Form::text('sort', null, ['class' => 'form-control']) !!}
</div>
