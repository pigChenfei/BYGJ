{!! Form::open(['route' => ['carrierPlayerLevels.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPlayerLevels.edit', $id)) !!}">
        <i class="fa fa-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    <a class="btn btn-primary btn-xs" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierPlayerLevels.bankCardAll',$id)) !!}">
        <i class="fa fa-cc-visa">选择银行卡</i>
    </a>
    <a class="btn btn-xs btn-warning" href="{!! route('CarrierPlayerLevels.rebateFlowShow',$id) !!}">
        <i class="fa fa-money">
            设置洗码
        </i>
    </a>
</div>
{!! Form::close() !!}
