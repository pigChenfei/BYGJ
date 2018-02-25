<div class="form-group col-sm-12">
    {!! Form::label('card_owner_name', '开户人') !!}
    {!! Form::text('card_owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('card_account', '卡号') !!}
    {!! Form::text('card_account', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('card_type', '银行类型:') !!}
    <select id="card_type" name="card_type" class="selectpicker show-tick form-control" >

        @foreach($banks as $bank)
                <option value="{!! $bank->type_id !!}" >{!! $bank->bank_name !!}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('card_birth_place', '分行名称') !!}
    {!! Form::text('card_birth_place', null, ['class' => 'form-control']) !!}
</div>
