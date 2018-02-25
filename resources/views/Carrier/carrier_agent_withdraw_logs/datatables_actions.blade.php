<div class='btn-group'>
    <div class='btn-group'>
        @if($status == \App\Models\Log\CarrierAgentWithdrawLog::STATUS_WAITING_REVIEWED)
            <a  onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentWithdrawLogs.refuseModal',$id)) !!}" class='btn btn-danger btn-xs'>
                <i class="fa fa-trash-o"></i>
                拒绝
            </a>
            <button onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentWithdrawLogs.payModal',$id)) !!}" class='btn btn-success btn-xs'>
                <i class="fa  fa-check-square-o"></i>
                通过
            </button>
        @endif
    </div>
</div>
