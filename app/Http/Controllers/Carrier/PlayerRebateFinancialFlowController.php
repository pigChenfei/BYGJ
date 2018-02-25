<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerRebateFinancialFlowDataTable;
use App\Exceptions\CarrierAccountException;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerRebateFinancialFlowRequest;
use App\Http\Requests\Carrier\UpdatePlayerRebateFinancialFlowRequest;
use App\Models\CarrierAgentUser;
use App\Models\Log\AgentBearUndertakenLog;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Repositories\Carrier\PlayerRebateFinancialFlowRepository;
use App\Services\PassPlayerRebateFinancialFlowService;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class PlayerRebateFinancialFlowController extends AppBaseController
{
    /** @var  PlayerRebateFinancialFlowRepository */
    private $playerRebateFinancialFlowRepository;

    public function __construct(PlayerRebateFinancialFlowRepository $playerRebateFinancialFlowRepo)
    {
        $this->playerRebateFinancialFlowRepository = $playerRebateFinancialFlowRepo;
    }

    /**
     * Display a listing of the PlayerRebateFinancialFlow.
     *
     * @param PlayerRebateFinancialFlowDataTable $playerRebateFinancialFlowDataTable
     * @return Response
     */
    public function index(PlayerRebateFinancialFlowDataTable $playerRebateFinancialFlowDataTable)
    {
        return $playerRebateFinancialFlowDataTable->render('Carrier.player_rebate_financial_flows.index');
    }

    /**
     * Show the form for creating a new PlayerRebateFinancialFlow.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_rebate_financial_flows.create');
    }

    /**
     * Store a newly created PlayerRebateFinancialFlow in storage.
     *
     * @param CreatePlayerRebateFinancialFlowRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerRebateFinancialFlowRequest $request)
    {
        $input = $request->all();
        $this->playerRebateFinancialFlowRepository->create($input);
        return $this->sendSuccessResponse();
    }

    /**
     * Display the specified PlayerRebateFinancialFlow.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerRebateFinancialFlow = $this->playerRebateFinancialFlowRepository->findWithoutFail($id);
        if (empty($playerRebateFinancialFlow)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.player_rebate_financial_flows.show')->with('playerRebateFinancialFlow', $playerRebateFinancialFlow);
    }

    /**
     * Show the form for editing the specified PlayerRebateFinancialFlow.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerRebateFinancialFlow = $this->playerRebateFinancialFlowRepository->findWithoutFail($id);
        if (empty($playerRebateFinancialFlow)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.player_rebate_financial_flows.edit')->with('playerRebateFinancialFlow', $playerRebateFinancialFlow);
    }

    /**
     * Update the specified PlayerRebateFinancialFlow in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerRebateFinancialFlowRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerRebateFinancialFlowRequest $request)
    {
        $playerRebateFinancialFlow = $this->playerRebateFinancialFlowRepository->findWithoutFail($id);
        if (empty($playerRebateFinancialFlow)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRebateFinancialFlowRepository->update($request->all(), $id);
        return $this->sendSuccessResponse();
    }


    /**
     * 发放返水
     * @param Request $request
     */
    public function passRebateFinancialFlowLog(Request $request){
        $this->validate($request,[
            'passType' => 'required|in:all,none',
            'logIds'   => 'required|array',
            'logIds.*' => 'integer'
        ],[
            'logIds.required' => '请选择需要处理的返水记录'
        ]);
        $logs = PlayerRebateFinancialFlowNew::unsettledInIds($request->get('logIds'))
            ->where('is_effect', 0)
            ->where('rebate_type', 1)
            ->with(['player.agent.agentLevel'])->get();
        if (empty($logs) || $logs->count() == 0) {
            return $this->sendNotFoundResponse();
        }
        try{
            if($request->get('passType') == 'all'){
                $handleService = new PassPlayerRebateFinancialFlowService($logs);
                $handleService->handle();
            }else if($request->get('passType') == 'none'){
                $logs->each(function(PlayerRebateFinancialFlowNew $log){
                    if ($log->rebate_financial_flow_amount <= 0){
                        throw new \Exception('存在结算金额小于0的记录');
                    }
                    if ($log->rebate_manual_period_hours != 0 && ((time() - strtotime($log->created_at)) > ($log->rebate_manual_period_hours * 3600)) ){
                        throw new \Exception('存在时间过期的记录');
                    }
                    $log->is_already_settled = true;
                    $log->settled_at = Carbon::now();
                    $log->settled_type = 2;
                    $log->update();
                });
            }
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }
    /**
     * Remove the specified PlayerRebateFinancialFlow from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerRebateFinancialFlow = $this->playerRebateFinancialFlowRepository->findWithoutFail($id);
        if (empty($playerRebateFinancialFlow)) {
            return $this->sendNotFoundResponse();
        }
        $this->playerRebateFinancialFlowRepository->delete($id);
        return $this->sendSuccessResponse();
    }
}
