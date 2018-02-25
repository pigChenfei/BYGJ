<!-- Account Field -->

<div class="form-group col-sm-4">
    {!! Form::label('act_type_id', '银行类型') !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%; padding-left: 0px;">
        <option value="1">三方</option>
        <option value="2">公司</option>
        <option value="3">点卡</option>
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('act_type_id', '支持方式') !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%; padding-left: 0px;">
        <option value="1">在线支付</option>
        <option value="2">扫码支付</option>
        <option value="3">在线支付/扫码支付</option>
        <option value="4">银行转账</option>
        <option value="5">扫码支付（公司）</option>
        <option value="6">点卡支付</option>
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('act_type_id', '银行选择') !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%; padding-left: 0px;">
        <option value="1">中国银行</option>
        <option value="2">招商银行</option>
        <option value="3">工商银行</option>
        <option value="4">华夏银行</option>
        <option value="5">广发银行</option>
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('status', '持卡人').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('use_purpose','账号').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','开户行').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('act_type_id', '手续费承担方') !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%; padding-left: 0px;">
        <option value="1">公司</option>
        <option value="2">会员</option>
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','手续费比例') !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','存款优惠比例') !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','单日存款次数限制').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','单次存款最小限额').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','单次存款最大限额').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('pay_support_channel','二维码').Form::required_pin() !!}
    <span data-toggle="tooltip" title="" class="badge bg-yellow" data-original-title="只有在公司扫码支付才有显示">
        <font><font class="">?</font></font>
    </span>
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-4">
    {!! Form::label('pay_support_channel','余额限额提醒').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('account', '状态').Form::required_pin() !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%; padding-left: 0px;">
        <option value="1">启用</option>
        <option value="2">禁用</option>
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('remark', '展示位置').Form::required_pin() !!}
    <select name="act_type_id" class="form-control disable_search_select2" style="width: 100%; padding-left: 0px;">
        <option value="1">网页版/手机版</option>
        <option value="2">网页版</option>
        <option value="3">手机版</option>
    </select>
</div>
<div class="form-group col-sm-12">
    {!! Form::label('remark', '备注') !!}
    {!! Form::textarea('remark', null, ['class' => 'form-control','rows' =>2, 'style' => 'resize:none;']) !!}
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