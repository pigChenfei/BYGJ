<?php

namespace App\Http\Controllers\Carrier;
use App\Repositories\Carrier\CarrierAgentSettleLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\DataTables\Carrier\CarrierAgentSettleHistoryLogDataTable;


class CarrierAgentSettleHistoryLogController extends AppBaseController
{
    /** @var  CarrierAgentCommissionSettleLogRepository */
    private $carrierAgentSettleLogRepository;

    public function __construct(CarrierAgentSettleLogRepository $carrierAgentSettleLogRepo)
    {
        $this->carrierAgentSettleLogRepository = $carrierAgentSettleLogRepo;
    }

    /**
     * Display a listing of the CarrierAgentCommissionSettleLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CarrierAgentSettleHistoryLogDataTable $carrierAgentSettleHistoryLogDataTable)
    {
        return $carrierAgentSettleHistoryLogDataTable->render('Carrier.carrier_agent_commission_settle_history_logs.index');
    }
    
}
