<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\DataTables\Agent\AgentWithdrawLogDataTable;
use App\Models\Log\AgentWithdrawLog;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class AgentWithdrawLogController extends AppBaseController
{

    public function index(AgentWithdrawLogDataTable $agentWithdrawLogDataTable, Request $request)
    {
        $start = $request->input('start_time','');
        $end = $request->input('end_time','');
        $status = $request->input('status','');
        $parameter = array(
            'end_time' => $start,
            'start_time' => $end,
            'status' => $status,
        );
        $agentWithdrawLog = AgentWithdrawLog::select("*")->where(['agent_id'=>\WinwinAuth::agentUser()->id]);

        if ($start){
            $agentWithdrawLog = $agentWithdrawLog->whereDate('created_at', '>=', $start);
        }
        if ($end){
            $agentWithdrawLog = $agentWithdrawLog->whereDate('created_at', '<=', $end);
        }
        if ($status){
            $agentWithdrawLog = $agentWithdrawLog->where('status', $status);
        }
        $agentWithdrawLog = $agentWithdrawLog->orderBy('created_at', 'desc')->paginate(10);

        $arr = AgentWithdrawLog::statusMeta();

        if ($request->ajax()){
            return view('Template_Agent_Admin_One.agent_withdraw_log.table', compact('agentWithdrawLog', 'arr'));
        }

        if (\WinwinAuth::agentUser()->template_agent_admin == 'Agent'){
            return $agentWithdrawLogDataTable->render(\WinwinAuth::agentUser()->template_agent_admin.'.agent_withdraw_log.index');
        } elseif (\WinwinAuth::agentUser()->template_agent_admin == 'Template_Agent_Admin_One'){
            return view('Template_Agent_Admin_One.agent_withdraw_log.index', compact('agentWithdrawLog', 'arr','parameter'));
        } else {
            return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_withdraw_log.index', compact('agentWithdrawLog', 'arr','parameter'));
        }
        return false;
    }

}
