<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\PlayerRebateFinancialFlowDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;

class PlayerRebateFinancialFlowController extends AppBaseController
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
     * @param PlayerRebateFinancialFlowDataTable $playerRebateFinancialFlowDataTable
     * @return Response
     */
    
    
    public function show($player_id,PlayerRebateFinancialFlowDataTable $playerRebateFinancialFlowDataTable)
    {
        $playerRebateFinancialFlowDataTable->playerId($player_id);
        return $playerRebateFinancialFlowDataTable->render('Agent.agent_player_financial_flow_log.index');
    }

}
