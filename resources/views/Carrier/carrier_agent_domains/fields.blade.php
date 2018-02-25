<!-- Agent Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('agentname', '代理账号:').Form::required_pin() !!}
    <select id="agent_id" name="agent_id" class="form-control bank_type_select2" style="width: 100%;">
        @foreach($carrierAgentUsers as $value)
            @if(isset($carrierAgentDomain) && $carrierAgentDomain instanceof \App\Models\CarrierAgentDomain && $carrierAgentDomain->agent_id == $value->id)
                <option value="{!! $value->id !!}" selected>{!! $value->username !!}</option>
            @else
                <option value="{!! $value->id !!}">{!! $value->username !!}</option>
            @endif
        @endforeach
    </select>
</div>

<!-- Website Field -->
<div class="form-group col-sm-12">
    {!! Form::label('website', '代理域名:').Form::required_pin() !!}
    {!! Form::text('website', null, ['class' => 'form-control']) !!}
    <span style=" color: red;">
        绑定域名须知：</br>
        如（www.baidu.com或者baidu.com泛域名请输入*.baidu.com），不要http:// </br>
         1、运营商在此处首先添加需要绑定的域名，如需要分配多个邀请域名，需要绑定多个。</br>
         2、添加成功之后，将此域名提交给博赢国际在线客服；</br>
         3、将该域名的NS地址修改成博赢国际指定的地址；</br>
         4、等待博赢国际客服协助完成代理独立域名的绑定生效。</br>
         5、优先匹配二级域名和根域名，如无法找到匹配泛域名（泛域名包含根域名）。</br>
         注意：不能随意修改绑定域名的NS地址，一旦修改绑定将失效！</br>
    </span>
</div>

<script>
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $(function(){
        $('.bank_type_select2').select2({selectOnClose: true});
    })
</script>