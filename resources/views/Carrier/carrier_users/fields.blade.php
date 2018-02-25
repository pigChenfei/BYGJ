<!-- Username Field -->
<div class="form-group col-sm-12">
    {!! Form::label('username', '账号:').Form::required_pin() !!}
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-12">
    {!! Form::label('password', '密码:').Form::required_pin() !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('team_id', '所属部门:').Form::required_pin() !!}
    <select id="team_id" name="team_id" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($carrierServiceTeams as $key => $value)
            @if(isset($carrierUser) && $carrierUser instanceof \App\Models\CarrierUser && $carrierUser->team_id == $value->id)
                <option value="{!! $value->id !!}" selected>{!! $value->team_name !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->team_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('status', '状态:').Form::required_pin() !!}
    <?php $statusDic = \App\Models\CarrierUser::statusMeta() ?>
    <select name="status" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($statusDic as $key => $value)
            @if(isset($carrierUser) && $carrierUser instanceof \App\Models\CarrierUser && $carrierUser->status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>
<script>
    $(function(){
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
    })
</script>



