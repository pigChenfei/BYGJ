<!-- Account Field -->

<div class="form-group col-sm-12">
    {!! Form::label('card_type_id', '银行卡类型').Form::required_pin() !!}
    <select name="card_type_id" class="form-control bank_type_select2" style="width: 100%;">
        @foreach($allBankTypes as $bankType)
            @if(isset($carrierBankCard) && $bankType instanceof \App\Models\BankType && $carrierBankCard instanceof \App\Models\CarrierBankCard && $carrierBankCard->bankType == $bankType->type_id)
                <option value="{!! $bankType->type_id !!}" selected>{!! $bankType->bank_name !!}</option>
            @else
                <option value="{!! $bankType->type_id !!}">{!! $bankType->bank_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('account', '账号').Form::required_pin() !!}
    {!! Form::text('account', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('status', '状态').Form::required_pin() !!}
    <?php $statusDic = \App\Models\CarrierBankCard::statusMeta() ?>;
    <select name="status" class="form-control carrier_bank_cards_select2" style="width: 100%;">
        @foreach($statusDic as $key => $value)
            @if(isset($carrierBankCard) && $carrierBankCard instanceof \App\Models\CarrierBankCard && $carrierBankCard->status == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('use_purpose','用途').Form::required_pin() !!}
    <?php $usePurposeDic = \App\Models\CarrierBankCard::usedForPurposeMeta(); ?>
    <select name="use_purpose" class="form-control carrier_bank_cards_select2" style="width: 100%;">
        @foreach($usePurposeDic as $key => $value)
            @if(isset($carrierBankCard) && $carrierBankCard instanceof \App\Models\CarrierBankCard && $carrierBankCard->use_purpose == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('pay_support_channel','支付渠道').Form::required_pin() !!}
    <?php $paySupportDic = \App\Models\CarrierBankCard::paySupportChannelsMeta(); ?>
    <select name="pay_support_channel" class="form-control carrier_bank_cards_select2" style="width: 100%;">
        @foreach($paySupportDic as $key => $value)
            @if(isset($carrierBankCard) && $carrierBankCard instanceof \App\Models\CarrierBankCard && $carrierBankCard->pay_support_channel == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>


<!-- Owner Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('owner_name', '持卡人姓名').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Origin Place Field -->
<div class="form-group col-sm-12">
    {!! Form::label('card_origin_place', '开户行地址').Form::required_pin() !!}
    {!! Form::text('card_origin_place', null, ['class' => 'form-control']) !!}
</div>

<!-- Default Preferential Ratio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('default_preferential_ratio', '默认优惠比例') !!}
    {!! Form::number('default_preferential_ratio', 0, ['class' => 'form-control']) !!}
</div>

<!-- Balance Notify Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('balance_notify_amount', '余额限额提醒') !!}
    {!! Form::number('balance_notify_amount', 0, ['class' => 'form-control']) !!}
</div>

<!-- Qrcode Field -->
<div class="form-group col-sm-12">
    {!! Form::label('qrcode', '扫码支付二维码') !!}
    @include('Components.ImagePicker.index',['name' => 'qrcode'])
</div>


<!-- Remark Field -->
<div class="form-group col-sm-12">
    {!! Form::label('remark', '备注') !!}
    {!! Form::textarea('remark', null, ['class' => 'form-control','rows' => 5, 'style' => 'resize:none;']) !!}
</div>

<script>
    $(function(){
        $('.carrier_bank_cards_select2').select2({
            minimumResultsForSearch: Infinity
        });
        $('.bank_type_select2').select2();
    })
</script>

@include('Components.ImagePicker.scripts')