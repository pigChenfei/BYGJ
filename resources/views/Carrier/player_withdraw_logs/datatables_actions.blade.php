<div class='btn-group'>
    <div class='btn-group'>
        @if($status == \App\Models\Log\PlayerWithdrawLog::STATUS_WAITING_REVIEWED)
            <a onclick="{!! TableScript::addOrEditModalShowEventScript(route('playerWithdrawLogs.show',$id)) !!}" class='btn btn-default btn-xs'>
                <i class="glyphicon glyphicon-eye-open"></i>
                流水检查
            </a>
            <button onclick="{!! TableScript::addOrEditModalShowEventScript(route('playerWithdrawLogs.payModal',$id)) !!}" class='btn btn-success btn-xs'>
                <i class="fa  fa-check-square-o"></i>
                通过
            </button>
            <a  onclick="{!! TableScript::addOrEditModalShowEventScript(route('playerWithdrawLogs.refuseModal',$id)) !!}" class='btn btn-danger btn-xs'>
                <i class="fa fa-trash-o"></i>
                拒绝
            </a>
        @endif
    </div>
</div>
