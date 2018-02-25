<?php

namespace App\Http\Controllers\Carrier;
use App\DataTables\Carrier\CarrierAgentAuditDataTable;
use App\Http\Requests\Carrier;
use Illuminate\Http\Request;
use App\Repositories\Carrier\CarrierAgentLevelRepository;
use App\Http\Requests\Carrier\CreateCarrierAgentUserRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentUserRequest;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use App\Models\CarrierTemplate;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierAgentAuditController extends AppBaseController
{
    /** @var  CarrierAgentUserRepository */
    private $carrierAgentUserRepository;

    public function __construct(CarrierAgentUserRepository $carrierAgentUserRepo)
    {
        $this->carrierAgentUserRepository = $carrierAgentUserRepo;
    }

    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param CarrierAgentUserDataTable $carrierAgentUserDataTable
     * @return Response
     */
    public function index(CarrierAgentAuditDataTable $carrierAgentAuditDataTable)
    {
        return $carrierAgentAuditDataTable->render('Carrier.carrier_agent_audit.index');
    }
    
    /**
    * 代理审核
    * @param type $id
    * @param Request $request
    * @return type
    */
    public function audit($id,CarrierAgentLevelRepository $repository)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
           return $this->renderNotFoundPage();
        }
        $type = \App\Models\CarrierAgentLevel::where('id',$carrierAgentUser['agent_level_id'])->first();//获取代理名称数据
        $carrierAgentLevelName = \App\Models\CarrierAgentLevel::where('type',$type['type'])->get();
        $templates =CarrierTemplate::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->with('templates')->whereHas('templates', function ($query) {
            $query->where('type', 4);
        })->get();
        return view('Carrier.carrier_agent_audit.audit',[
            'templates'=>$templates,
            'carrierAgentUser'=>$carrierAgentUser,
            'type'=>$type,
            'carrierAgentLevelName'=>$carrierAgentLevelName
        ]);
    }
    
    /**
     * 保存审核
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveAudit($id,Request $request)
    {
        
        if(!empty($request->get('agent_level_id')))
        {
            
            if($request->get('agent_level_id'))
            {
                $data['agent_level_id'] = $request->get('agent_level_id');
            }else{
                $error_respon = array('success' => false, 'message' => '代理名称不能为空');
                return $error_respon;
            }
            $data['audit_status'] = $request->get('audit_status'); //客服审核状态 1已审核 =0审核中 2拒绝
            $data['template_agent_admin'] = $request->get('template_agent_admin');
            $data['status'] = 1;
            if(empty($request->get('customer_remark')))
            {
                $data['customer_remark'] = "客服通过";
            }else{
                $data['customer_remark'] = $request->get('customer_remark');    
            }
            $data['customer_time'] = date('Y-m-d h:i:s');
            $carrierAgentUser = $this->carrierAgentUserRepository->update($data, $id);
            if (empty($carrierAgentUser)) {
                return $this->sendNotFoundResponse();
            }
            return $this->sendSuccessResponse( route('carrierAgentAudits.index'));
        }
    }
}
