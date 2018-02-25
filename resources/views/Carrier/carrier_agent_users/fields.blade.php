<div class="form-group col-sm-4">
    {!! Form::label('username', '代理账号:').Form::required_pin() !!}
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('realname', '姓名:').Form::required_pin() !!}
    {!! Form::text('realname',null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('password', '密码:').Form::required_pin() !!}
    <input type="password" class="form-control" name="password" placeholder="Password">
</div>

<div class="form-group col-sm-6">
    {!! Form::label('type', '代理类型:').Form::required_pin() !!}
    <?php
    $typeDic = \App\Models\CarrierAgentLevel::typeMeta()?>
    <select id="type" name="" class="form-control carrier_bank_cards_select2" style="width: 100%;">
        @foreach($typeDic as $key => $value)
            <option value="{!! $key !!}">{!! $value !!}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('agent_level_id', '代理名称').Form::required_pin() !!}
    <select name="agent_level_id" id="agent_level_id" class="selectpicker show-tick form-control">
        @foreach($carrierAgentLevel as $key => $value)
            <option value="{!! $value->id !!}">{!! $value->level_name !!}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-sm-4">
    {!! Form::label('amount', '代理账户余额:') !!}
    {!! Form::text('amount', 0.00, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('tgcode', '邀请码:') !!}
    {!! Form::text('tgcode', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('skype', 'skype账号:') !!}
    {!! Form::text('skype', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('qq', 'QQ:') !!}
    {!! Form::text('qq', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('wechat', '微信:') !!}
    {!! Form::text('wechat', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('mobile', '手机号:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('email', '邮箱:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
    {!! Form::label('template_agent_admin', '代理加盟中心模板').Form::required_pin() !!}
    <select name="template_agent_admin"  class="selectpicker show-tick form-control">
        @foreach($templates as $t)
            <option value="{!! $t->templates->value !!}">{!! $t->templates->alias !!}</option>
        @endforeach
    </select>
</div>

<!-- Carrier Field -->
<div class="form-group col-sm-12">
    {!! Form::label('customer_remark', '客服备注:') !!}
    {!! Form::textarea('customer_remark', null, ['class' => 'form-control','rows' => 5, 'style' => 'resize:none;']) !!}
</div>
<script>
$(function(){
    $("#type").change(function(){
        var objectModel = {};
        var   value = $(this).val();
        var   type = $(this).attr('id');
        objectModel[type] =value;
        $.ajax({
            url:"{!! route('carrierAgentUsers.dataAgentLevel') !!}", //你的路由地址
            type:"post",
            dataType:"json",
            data:objectModel,
            timeout:30000,
            success:function(data){
                $("#agent_level_id").empty();
                var count = data.length;
                var i = 0;
                var b="";
    //                b+="<option value=''>"+"请选择..."+"</option>";
                   for(i=0;i<count;i++){
                       b+="<option value='"+data[i].id+"'>"+data[i].level_name+"</option>";
                   }
                $("#agent_level_id").append(b);

            }
        });
    });
})
</script>