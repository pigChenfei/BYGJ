<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Agent;
use App\Repositories\Agent\AgentUserRepository;
use App\DataTables\Agent\AgentSubDataTable;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class AgentSubController extends AppBaseController
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
     * @param AgentSubDataTable $agentSubDataTable
     * @return Response
     */
    public function index(AgentSubDataTable $agentSubDataTable)
    {
        return $agentSubDataTable->render('Agent.agent_subs.index');
    }

}
