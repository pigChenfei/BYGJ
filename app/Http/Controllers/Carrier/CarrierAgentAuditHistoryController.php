<?php

namespace App\Http\Controllers\Carrier;
use App\DataTables\Carrier\CarrierAgentAuditHistoryDataTable;
use App\Http\Requests\Carrier;
use Illuminate\Http\Request;
use App\Repositories\Carrier\CarrierAgentLevelRepository;
use App\Http\Requests\Carrier\CreateCarrierAgentUserRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentUserRequest;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierAgentAuditHistoryController extends AppBaseController
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
    public function index(CarrierAgentAuditHistoryDataTable $carrierAgentAuditHistoryDataTable)
    {
        return $carrierAgentAuditHistoryDataTable->render('Carrier.carrier_agent_audit_history.index');
    }
}
