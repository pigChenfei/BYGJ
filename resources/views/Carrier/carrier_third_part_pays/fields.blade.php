<!-- Pay Channel Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('def_pay_channel_id', '三方支付平台:') !!}
    <select name="def_pay_channel_id" class="form-control disable_search_select2" style="width: 100%;" onchange="selectOnchang(this)">
        <option value="0">请选择</option>
        @foreach($payChannelList as $key => $value)
            @if(isset($carrierThirdPartPay) &&  $carrierThirdPartPay->def_pay_channel_id == $value->id)
                <option value="{!! $value->id !!}" selected>{!! $value->payChannelType->type_name.'--'.$value->channel_name !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->payChannelType->type_name.'--'.$value->channel_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('merchant_number', '商户ID:') !!}
    {!! Form::text('merchant_number', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group col-sm-12 is_need_private_key" @if(isset($carrierThirdPartPay) &&  $carrierThirdPartPay->defPayChannel->is_need_private_key == 0) style="display: none;" @endif>
    {!! Form::label('private_key', '证书或密钥:') !!}
    {!! Form::textarea('private_key', isset($carrierThirdPartPay)?$carrierThirdPartPay->private_key:'', ['class' => 'form-control','rows' => 3, 'style' => 'resize:none;']) !!}
</div>

<div class="form-group col-sm-12 is_need_vir_card" @if(isset($carrierThirdPartPay) &&  $carrierThirdPartPay->defPayChannel->is_need_vir_card == 0) style="display: none;" @endif>
    {!! Form::label('vir_card_no_in', '账号:') !!}
    {!! Form::text('vir_card_no_in', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12 is_need_identify_code" @if(isset($carrierThirdPartPay) &&  $carrierThirdPartPay->defPayChannel->is_need_identify_code == 0) style="display: none;" @endif>
    {!! Form::label('merchant_identify_code', '商户识别码:') !!}
    {!! Form::text('merchant_identify_code', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12 is_need_domain" @if(isset($carrierThirdPartPay) &&  $carrierThirdPartPay->defPayChannel->is_need_domain == 0) style="display: none;" @endif>
    {!! Form::label('merchant_bind_domain', '三方绑定商场域名:') !!}
    {!! Form::text('merchant_bind_domain', null, ['class' => 'form-control']) !!}
    <span style=" color: red;">
        例如:pay.shop.com，不需要带http://
    </span>
</div>

<div class="form-group col-sm-12 is_need_good_name" @if(isset($carrierThirdPartPay) &&  $carrierThirdPartPay->defPayChannel->is_need_good_name == 0) style="display: none;" @endif>
    {!! Form::label('good_name', '商品名称:') !!}
    {!! Form::text('good_name', null, ['class' => 'form-control']) !!}
</div>

<script>
    $(function(){
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
    })
</script>
<script type="text/JavaScript">
    function selectOnchang(obj) {
        var value = obj.options[obj.selectedIndex].value;
        if (value != 0){
            $.ajax({
                url:"{!! route('carrierThirdPartPays.getInfo') !!}" ,
                data:{id:value} ,
                dataType:'json' ,
                success:function(data){
                    $.each(data.data, function(index,value){
                        if ($.inArray(index,['is_need_private_key', 'is_need_vir_card','is_need_domain','is_need_good_name','is_need_identify_code']>=0)){
                            if (value == 0){
                                $('.'+index).hide()
                            }else{
                                $('.'+index).show()
                            }
                        }
                    })
                },
                error:function (xhr) {
                    toastr.error(xhr.responseJSON.message || '编辑失败', '出错啦!')
                }
            });
        }
    }
</script>