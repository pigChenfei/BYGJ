<!-- Remark Field -->
<div class="form-group col-sm-3">
    {!! Form::label('use_purpose','用途').Form::required_pin() !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-placement="bottom" data-original-title="1、存款：如果该卡用于存款，则必须选择该项，系统中至少应该有一张用于存款的银行卡
            2、取款：如果该卡用于给会员出款，则必须选择该项
            3、库房：如果该卡既不是存款又不用于取款，则可设为库房
            4、注意：系统不允许同一张银行卡既用于存款又用于取款或者库房" ></i>
    <?php
    $usePurposeDic = \App\Models\CarrierPayChannel::usedForPurposeMeta();
    ?>
    <select name="use_purpose" class="form-control disable_search_select2" style="width: 100%;" onChange="tochange(this.value)">
        @foreach($usePurposeDic as $key => $value)
            @if(isset($carrierPayChannel) && $carrierPayChannel instanceof \App\Models\CarrierPayChannel && $carrierPayChannel->use_purpose == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('display_name', '前台展示名称').Form::required_pin() !!}
    {!! Form::text('display_name', null , ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-3 withdraw-all warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose == 3?'hidden':'' !!}>
    {!! Form::label('show', '展示位置').Form::required_pin() !!}
    <?php
    
    $showDic = \App\Models\CarrierPayChannel::showMeta()?>
    <select name="show" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($showDic as $key => $value)
            @if(isset($carrierPayChannel) && $carrierPayChannel instanceof \App\Models\CarrierPayChannel && $carrierPayChannel->show == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('balance_notify_amount','余额限额提醒').Form::required_pin() !!}
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-placement="bottom" data-original-title="该银行卡的余额达到余额限额提醒时，在客服对会员存款审核的界面上，将提醒该卡余额超限
默认=0，代表不提醒，如果设置为10000，则该银行卡余额超过10000时会被提醒" ></i>
    {!! Form::text('balance_notify_amount', null, ['class' => 'form-control']) !!}
</div>




<?php

$companyDic = \App\Models\Def\PayChannelType::SCAN_CODE_COMPANY_PAY?>
<div class="form-group col-sm-4">
    {!! Form::label('pctype_id', '银行类型') !!}
    <select name="" id="paytype" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($payChannelType as $key => $value)
            @if(isset($payc) && $payc->id == $value->id)
            <option value="{!! $value->id !!}" selected>{!! $value->type_name !!}</option>
            @else
            <option value="{!! $value->id !!}">{!! $value->type_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('pay_channel_type_id', '支持方式') !!}
    <select name="" id="paychannel" company_id="{!! $companyDic !!}" class="form-control" style="width: 100%;">
        @foreach($parentPayChannelType as $key => $value)
            @if(isset($parent) && $parent->id == $value->id)
            <option value="{!! $value->id !!}" selected>{!! $value->type_name !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->type_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('def_pay_channel_id', '支付渠道') !!}
    <select name="def_pay_channel_id" id="channeltype" class="form-control" style="width: 100%;">
        @foreach($payChannelList as $key => $value)
            @if(isset($carrierPayChannel) && $carrierPayChannel instanceof \App\Models\CarrierPayChannel && $carrierPayChannel->def_pay_channel_id == $value->id)
                <option value="{!! $value->id !!}" selected>{!! $value->channel_name !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->channel_name !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('owner_name', '持卡人').Form::required_pin() !!}
    {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('account','账号').Form::required_pin() !!}
    {!! Form::text('account', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('card_origin_place','开户行').Form::required_pin() !!}
    {!! Form::text('card_origin_place', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4 withdraw-all warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose == 3?'hidden':'' !!}>
    {!! Form::label('fee_bear_id', '手续费承担方') !!}
    <?php
    
    $feeBearDic = \App\Models\CarrierPayChannel::feeBearMeta()?>
    <select name="fee_bear_id" class="form-control disable_search_select2" style="width: 100%;">
        @foreach($feeBearDic as $key => $value)
            @if(isset($carrierPayChannel) && $carrierPayChannel instanceof \App\Models\CarrierPayChannel && $carrierPayChannel->fee_bear_id == $key)
                <option value="{!! $key !!}" selected>{!! $value !!}</option>
            @else
                <option value="{!! $key !!}">{!! $value !!}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group col-sm-4 withdraw-all warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose == 3?'hidden':'' !!}>
    {!! Form::label('fee_ratio','手续费比例') !!}%
    {!! Form::text('fee_ratio', empty($carrierPayChannel)?0:$carrierPayChannel->fee_ratio, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4 withdraw-all deposit warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose != 1?'hidden':'' !!}>
    {!! Form::label('default_preferential_ratio','存款优惠比例') !!}%
    <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="如果该卡用于存款，每发生一笔存款时，赠送会员的存款优惠比例，默认=0，表示不发放存款优惠
如果设置为1，此时默认比例=1%，假设存款100进入，赠送的存款优惠=100×1%=1" ></i>
    {!! Form::text('default_preferential_ratio', empty($carrierPayChannel)?0:$carrierPayChannel->default_preferential_ratio, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4 withdraw-all deposit warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose != 1?'hidden':'' !!}>
    {!! Form::label('single_day_deposit_limit','单日存款次数限制').Form::required_pin() !!}
    {!! Form::text('single_day_deposit_limit', empty($carrierPayChannel)?0:$carrierPayChannel->single_day_deposit_limit, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4 withdraw-all deposit warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose != 1?'hidden':'' !!}>
    {!! Form::label('single_deposit_minimum','单次存款最小限额').Form::required_pin() !!}
    {!! Form::text('single_deposit_minimum', empty($carrierPayChannel)?0:$carrierPayChannel->single_deposit_minimum, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4 withdraw-all deposit warehouse" {!! isset($carrierPayChannel) && $carrierPayChannel->use_purpose != 1?'hidden':'' !!}>
    {!! Form::label('maximum_single_deposit','单次存款最大限额').Form::required_pin() !!}
    {!! Form::text('maximum_single_deposit', empty($carrierPayChannel)?0:$carrierPayChannel->maximum_single_deposit, ['class' => 'form-control']) !!}
</div>

@if(isset($parent) && $parent['id'] == $companyDic)
    <div class="form-group col-sm-12" id="companys" >
        {!! Form::label('qrcode', '二维码:') !!}
        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="只有在公司扫码支付才有显示" ></i>
        <div class="row">
            <div class="col-sm-6 col-lg-12">
                @include('Components.ImagePicker.index',[
                'onchange' => 'var photo = $(this).find(\'option:selected\').html();$(\'#image\').attr(\'src\',photo);',
                'name' => 'qrcode',
                'default' => isset($carrierPayChannel) ? $carrierPayChannel->qrcode : null,
                'id' => 'payChannelImageSelector'
                ])
                <div class="right" style="margin-bottom:10px;">
                    <div>
                        <img id="image" src="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="form-group col-sm-12" id="companys" style=" display: none">
        {!! Form::label('qrcode', '二维码:') !!}
        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="只有在公司扫码支付才有显示" ></i>
        <div class="row">
            <div class="col-sm-6 col-lg-12">
                @include('Components.ImagePicker.index',[
                'onchange' => 'var photo = $(this).find(\'option:selected\').html();$(\'#image\').attr(\'src\',photo);',
                'name' => 'qrcode',
                'default' => isset($carrierPayChannel) ? $carrierPayChannel->qrcode : null,
                'id' => 'payChannelImageSelector'
                ])
                <div class="right" style="margin-bottom:10px;">
                    <div>
                        <img id="image" src="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<style>
    #photo {
        margin-bottom:10px;
    }
    #image {
        margin-top:5px;
    }
</style>

<script type="text/javascript">
$(function(){
    var photo = $('#payChannelImageSelector').find('option:selected').html();$('#image').attr('src',photo);
})
</script>
<div class="form-group col-sm-12">
    {!! Form::label('remark', '备注') !!}
    {!! Form::textarea('remark', null, ['class' => 'form-control','rows' =>2, 'style' => 'resize:none;']) !!}
</div>

@include('Components.ImagePicker.style')
@include('Components.ImagePicker.scripts')
<script>
    $(function(){
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
        
        //银行类型
        $("#paytype").change(function(){
            var objectModel = {};
            var   value = $(this).val();
            var   paytype = $(this).attr('id');
            var   company_id = $(this).attr('company_id');
            if(company_id == value)
            {
                $("#companys").show();
            }else{
                $("#companys").hide();
            }
            objectModel[paytype] =value;
            $.ajax({
                url:"{!! route('carrierPayChannels.payment') !!}", //你的路由地址
                type:"post",
                dataType:"json",
                data:objectModel,
                timeout:1000,
                success:function(data){
                    $("#paychannel").empty();
                    $("#channeltype").empty();
                    var count = data.length;
                    var i = 0;
                    var b="";
                       for(i=0;i<count;i++){
                           b+="<option value='"+data[i].id+"'>"+data[i].type_name+"</option>";
//                            var y = 0;
//                            var a="";
//                            for(y=0;y<data[i].parent_pay_channel_list.length;y++){
//                                a+="<option value='"+data[i].parent_pay_channel_list[y].id+"'>"+data[i].parent_pay_channel_list[y].channel_name+"</option>";
//                            }
//                            $("#channeltype").append(a);
                       }
                    $("#paychannel").append(b);
                    select_paychannel(data[0].id);
                }
            });
        });
        
        function select_paychannel(i){
            var objectModel = {};
            var   value =i;
            var   paychannel = "paychannel";
            objectModel[paychannel] =value;
            $.ajax({
                url:"{!! route('carrierPayChannels.channelType') !!}", //你的路由地址
                type:"post",
                dataType:"json",
                data:objectModel,
                timeout:30000,
                success:function(data){
                    $("#channeltype").empty();
                    var count = data.length;
                    var i = 0;
                    var b="";
                       for(i=0;i<count;i++){
                           b+="<option value='"+data[i].id+"'>"+data[i].channel_name+"</option>";
                       }
                    $("#channeltype").append(b);

                }
            });
        }
        
//        //支持方式
        $("#paychannel").change(function(){
            var objectModel = {};
            var   value = $(this).val();
            var   paychannel = $(this).attr('id');
            var   company_id = $(this).attr('company_id');
            if(company_id == value)
            {
                $("#companys").show();
            }else{
                $("#companys").hide();
            }
            objectModel[paychannel] =value;
            $.ajax({
                url:"{!! route('carrierPayChannels.channelType') !!}", //你的路由地址
                type:"post",
                dataType:"json",
                data:objectModel,
                timeout:30000,
                success:function(data){
                    $("#channeltype").empty();
                    var count = data.length;
                    var i = 0;
                    var b="";
                       for(i=0;i<count;i++){
                           b+="<option value='"+data[i].id+"'>"+data[i].channel_name+"</option>";
                       }
                    $("#channeltype").append(b);

                }
            });
        });
    });
    function tochange($val) {
        if($val == 1) {
            $('.withdraw-all').show();
        } else if ($val == 2){
            $('.withdraw-all').show();
            $('.deposit').hide();
        } else if ($val == 3){
            $('.withdraw-all').show();
            $('.warehouse').hide();
        }
    }
</script>