<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierAgentWithdrawLogDataTable;
use App\Http\Requests\Carrier\CreateCarrierAgentWithdrawLogRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentWithdrawLogRequest;
use App\Repositories\Carrier\CarrierAgentWithdrawLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Log\CarrierAgentWithdrawLog;
use Carbon\Carbon;
use App\Models\CarrierPayChannel;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\AgentBearUndertakenLog;
use App\DataTables\Carrier\CarrierAgentWithdrawLogVerifyDataTable;

class CarrierAgentWithdrawLogVerifyController extends AppBaseController
{

    /** @var  CarrierAgentWithdrawLogRepository */
    private $carrierAgentWithdrawLogRepository;

    public function __construct(CarrierAgentWithdrawLogRepository $carrierAgentWithdrawLogRepo)
    {
        $this->carrierAgentWithdrawLogRepository = $carrierAgentWithdrawLogRepo;
    }

    /**
     * Display a listing of the CarrierAgentWithdrawLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CarrierAgentWithdrawLogVerifyDataTable $carrierAgentWithdrawLogDataTable)
    {
        return $carrierAgentWithdrawLogDataTable->render('Carrier.carrier_agent_withdraw_logs.index', [
            'title' => '代理取款审核'
        ]);
    }
}
