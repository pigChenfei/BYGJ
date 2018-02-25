<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class AgentCommissionReportController extends AppBaseController
{
    
    public function index(AgentPlayerDataTable $agentPlayerDataTable)
    {
        return $agentPlayerDataTable->render('Agent.agent_player.index');
    }
    
}
