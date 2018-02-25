<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\AgentPlayerBetLogDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class AgentPlayerBetLogController extends AppBaseController
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
     * @param AgentPlayerBetLogDataTable $agentPlayerBetLogDataTable
     * @return Response
     */
    
    
    public function show($player_id,AgentPlayerBetLogDataTable $agentPlayerBetLogDataTable)
    {
        $agentPlayerBetLogDataTable->playerId($player_id);
        return $agentPlayerBetLogDataTable->render('Agent.agent_player_bet_log.index');
    }

}
