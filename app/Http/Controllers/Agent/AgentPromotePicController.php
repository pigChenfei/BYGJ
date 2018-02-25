<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\DataTables\Agent\AgentPromotePicTable;

class AgentPromotePicController extends AppBaseController
{
    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param CarrierAgentUserDataTable $carrierAgentUserDataTable
     * @return Response
     */
    public function index(AgentPromotePicTable $agentPromotePicDataTable)
    {
        return $agentPromotePicDataTable->render('Agent.agent_promote_pic.index');
    }
    
}
