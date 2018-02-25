<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\AppBaseController;
use App\Models\CarrierUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AgentNews\AgentNewsRelation;

class HomeController extends AppBaseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Agent.home');
    }

    /**
     * 站内信
     *
     * @param Request $request            
     * @return \View
     */
    public function smsSubscriptions()
    {
        $stationLetterList = AgentNewsRelation::with('carrierAgentNews')->unDeleted()
            ->unViewed()
            ->where('carrier_id', \WinwinAuth::agentUser()->carrier_id)
            ->where('agent_id', \WinwinAuth::agentUser()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        
        return view(\WinwinAuth::agentUser()->template_agent_admin . '.sms.sms_subscriptions')->with('stationLetterList', $stationLetterList);
        // return \WTemplate::smsSubscriptions()->with('stationLetterList', $stationLetterList);
    }

    /**
     * 读取消息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function smsRead(Request $request)
    {
        $id = $request->get('sms_id');
        if (empty($id)) {
            return $this->sendResponse('找不到该条站内信');
        }
        $sms = AgentNewsRelation::with('carrierAgentNews')->unDeleted()
            ->where('agent_id', \WinwinAuth::agentUser()->id)
            ->where('carrier_id', \WinwinAuth::agentUser()->carrier_id)
            ->find($id);
        if (empty($sms)) {
            return $this->sendResponse('找不到该条站内信');
        }
        if ($sms['agent_view_status'] == 0) { // 表示未读
            $sms->update([
                'agent_view_status' => 1
            ]);
        }
        return $this->sendResponse($sms);
    }

    /**
     * 删除消息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function smsDestroy(Request $request)
    {
        $sms_id = $request->input('sms_id', '');
        if (empty($sms_id)) {
            return $this->sendResponse('找不到该条站内信');
        }
        if (is_array($sms_id)) { // 批量删除站内信
            \DB::beginTransaction();
            foreach ($sms_id as $id) {
                $del = AgentNewsRelation::where('id', $id)->where('agent_id', \WinwinAuth::agentUser()->id)
                    ->where('carrier_id', \WinwinAuth::agentUser()->carrier_id)
                    ->update([
                    'agent_deleted_status' => 1
                ]); // state 0:正常 ；1：删除
                if ($del === false) { // 其中一条删除失败
                    \DB::rollBack();
                    return $this->sendErrorResponse('删除失败，请重试');
                }
            }
            \DB::commit();
            return $this->sendResponse('删除成功');
        } else { // 单个删除站内信
            $del = AgentNewsRelation::where('id', $sms_id)->where('agent_id', \WinwinAuth::agentUser()->id)
                ->where('carrier_id', \WinwinAuth::agentUser()->carrier_id)
                ->update([
                'agent_deleted_status' => 1
            ]); // state 0:正常 ；1：删除
            if ($del === false) { // 删除文件失败
                return $this->sendErrorResponse('删除失败，请重试');
            }
            return $this->sendResponse('删除成功');
        }
    }
}
