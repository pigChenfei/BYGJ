<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPayChannels.edit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    
    @if(isset($pay_channel['pay_channel_type']['parent_pay_channel_type']['id']) && $pay_channel['pay_channel_type']['parent_pay_channel_type']['id'] != 4)
        @if(isset($binded_third_part_pay_id) && $binded_third_part_pay_id != 0)
            <a class='btn btn-warning btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPayChannels.unbundList', $id)) !!}">
                <i class="glyphicon fa fa-unlock-alt">解除三方支付</i>
            </a>
        @else
            <a class='btn btn-primary btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPayChannels.payList', $id)) !!}">
                <i class="glyphicon fa fa-lock">绑定三方支付</i>
            </a>
        @endif
    @endif
</div>
{!! Form::close() !!}

