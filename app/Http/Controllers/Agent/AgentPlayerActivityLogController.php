<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\AgentPlayerActivityLogDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class AgentPlayerActivityLogController extends AppBaseController
{
    /** @var  AgentUserRepository */
    private $agentUserRepository;

    public function __construct(AgentUserRepository $agentUserRepo)
    {
        $this->agentUserRepository = $agentUserRepo;
    }

    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param AgentPlayerAccountLogDataTable $agentPlayerDepositLogDataTable
     * @return Response
     */
    
    
    public function show($player_id,AgentPlayerActivityLogDataTable $agentPlayerActivityLogDataTable)
    {
        $agentPlayerActivityLogDataTable->playerId($player_id);
        return $agentPlayerActivityLogDataTable->render('Agent.agent_player_activity_log.index');
    }

}
