<?php

namespace App\Http\Controllers\Agent;
use App\Http\Requests\Carrier;
use Illuminate\Http\Request;
use App\Repositories\Agent\AgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class AgentProfileController extends AppBaseController
{
    /** @var  CarrierAgentUserRepository */
    private $agentUserRepository;

    public function __construct(AgentUserRepository $agentUserRepo)
    {
        $this->agentUserRepository = $agentUserRepo;
    }

    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param CarrierAgentUserDataTable $carrierAgentUserDataTable
     * @return Response
     */
    public function index(AgentUserDataTable $agentUserDataTable)
    {
        return $agentUserDataTable->render('Carrier.agent_users.index');
    }

}
