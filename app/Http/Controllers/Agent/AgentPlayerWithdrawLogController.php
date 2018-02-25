<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\AgentPlayerWithdrawLogDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class AgentPlayerWithdrawLogController extends AppBaseController
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
    
    
    public function show($player_id,AgentPlayerWithdrawLogDataTable $agentPlayerWithdrawLogDataTable)
    {
        $agentPlayerWithdrawLogDataTable->playerId($player_id);
        return $agentPlayerWithdrawLogDataTable->render('Agent.agent_player_withdraw_log.index');
    }

}
