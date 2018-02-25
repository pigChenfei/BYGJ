<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\AgentPlayerDepositLogDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class AgentPlayerDepositLogController extends AppBaseController
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
     * @param AgentPlayerDepositLogDataTable $agentPlayerDepositLogDataTable
     * @return Response
     */
    
    
    public function show($player_id,AgentPlayerDepositLogDataTable $agentPlayerDepositLogDataTable)
    {
        $player = \App\Models\Player::where(['player_id'=>$player_id])->first();
        $agentPlayerDepositLogDataTable->playerId($player_id,$player['user_name']);
        return $agentPlayerDepositLogDataTable->render('Agent.agent_player_deposit_log.index');
    }

}
