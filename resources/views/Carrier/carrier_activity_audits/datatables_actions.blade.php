<div class='btn-group'>
    @if($status == \App\Models\CarrierActivityAudit::STATUS_AUDIT)
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierActivityAudits.bonusEdit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">通过</i>
    </a>
    <button class='btn btn-danger btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierActivityAudits.refuseModal',$id)) !!}">
        <i class="glyphicon glyphicon-edit">拒绝</i>
    </button>
    @endif
</div>