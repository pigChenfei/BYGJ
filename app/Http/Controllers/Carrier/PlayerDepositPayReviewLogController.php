<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerDepositPayReviewLogDataTable;
use App\Exceptions\CarrierRuntimeException;
use App\Helpers\Caches\PlayerInfoCacheHelper;
use App\Http\Requests\Carrier\CreatePlayerDepositPayLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerDepositPayLogRequest;
use App\Jobs\JudgePlayerHasAutoJoinActivity;
use App\Jobs\PlayerUpgradeLevelHandle;
use App\Models\CarrierActivity;
use App\Models\CarrierActivityAudit;
use App\Models\CarrierPayChannel;
use App\Models\Log\AgentBearUndertakenLog;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Repositories\Carrier\PlayerDepositPayLogRepository;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class PlayerDepositPayReviewLogController extends AppBaseController
{

    /** @var  PlayerDepositPayLogRepository */
    private $playerDepositPayLogRepository;

    public function __construct(PlayerDepositPayLogRepository $playerDepositPayLogRepo)
    {
        $this->playerDepositPayLogRepository = $playerDepositPayLogRepo;
    }

    /**
     * Display a listing of the PlayerDepositPayLog.
     *
     * @param PlayerDepositPayReviewLogDataTable $PlayerDepositPayReviewLogDataTable
     * @return Response
     */
    public function index(PlayerDepositPayReviewLogDataTable $PlayerDepositPayReviewLogDataTable)
    {
        return $PlayerDepositPayReviewLogDataTable->render('Carrier.player_deposit_pay_review_logs.index');
    }

    /**
     * Show the form for creating a new PlayerDepositPayLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_deposit_pay_review_logs.create');
    }

    /**
     * Store a newly created PlayerDepositPayLog in storage.
     *
     * @param CreatePlayerDepositPayLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerDepositPayLogRequest $request)
    {
        $input = $request->all();
        
        $playerDepositPayLog = $this->playerDepositPayLogRepository->create($input);
        
        Flash::success('Player Deposit Pay Log saved successfully.');
        
        return redirect(route('playerDepositPayLogs.index'));
    }

    /**
     * Display the specified PlayerDepositPayLog.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($playerDepositPayLog)) {
            Flash::error('Player Deposit Pay Log not found');
            
            return redirect(route('playerDepositPayLogs.index'));
        }
        return view('Carrier.player_deposit_pay_review_logs.show')->with('playerDepositPayLog', $playerDepositPayLog);
    }

    /**
     *
     * @param
     *            $id
     * @return mixed
     */
    public function showReviewDepositLogModal($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->with(
            [
                'player',
                'playerBankCard',
                'relatedCarrierActivity'
            ])->findWithoutFail($id);
        if (empty($playerDepositPayLog)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.player_deposit_pay_review_logs.review')->with('playerDepositPayLog', $playerDepositPayLog);
    }

    /**
     * 会员存款审核
     *
     * @param
     *            $id
     * @param Request $request
     */
    public function reviewDepositLog($id, Request $request)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->with(
            [
                'carrierPayChannel',
                'player.agent.agentLevel'
            ])->findWithoutFail($id);
        if (empty($playerDepositPayLog)) {
            return $this->sendNotFoundResponse();
        }
        if ($playerDepositPayLog->canReview() == true) {
            return $this->sendErrorResponse('该订单不能审核');
        }
        $this->validate($request, [
            'is_received_deposit_amount' => 'required|in:0,1'
        ]);
        $isReceiveDepositAmount = $request->get('is_received_deposit_amount');
        \DB::beginTransaction();
        try {
            if ($isReceiveDepositAmount) {
                $playerDepositPayLog->status = PlayerDepositPayLog::ORDER_STATUS_PAY_SUCCEED;
                $playerDepositPayLog->operate_time = Carbon::now();
                $playerDepositPayLog->review_user_id = \WinwinAuth::carrierUser()->id;
                $playerDepositPayLog->save();
                \DB::commit();
                return $this->sendSuccessResponse();
            } else {
                $playerDepositPayLog->status = PlayerDepositPayLog::ORDER_STATUS_SERVER_REVIEW_NO_PASSED;
                $playerDepositPayLog->operate_time = Carbon::now();
                $playerDepositPayLog->review_user_id = \WinwinAuth::carrierUser()->id;
                $playerDepositPayLog->update();
                \DB::commit();
                return $this->sendSuccessResponse();
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified PlayerDepositPayLog.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($playerDepositPayLog)) {
            return $this->sendNotFoundResponse();
        }
        
        return view('Carrier.player_deposit_pay_review_logs.edit')->with('playerDepositPayLog', $playerDepositPayLog);
    }

    /**
     * Update the specified PlayerDepositPayLog in storage.
     *
     * @param int $id
     * @param UpdatePlayerDepositPayLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerDepositPayLogRequest $request)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);
        if (empty($playerDepositPayLog)) {
            return $this->sendNotFoundResponse();
        }
        
        $this->playerDepositPayLogRepository->update($request->all(), $id);
        return redirect(route('playerDepositPayLogs.index'));
    }

    /**
     * Remove the specified PlayerDepositPayLog from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerDepositPayLog = $this->playerDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($playerDepositPayLog)) {
            Flash::error('Player Deposit Pay Log not found');
            
            return redirect(route('playerDepositPayLogs.index'));
        }
        
        $this->playerDepositPayLogRepository->delete($id);
        
        Flash::success('Player Deposit Pay Log deleted successfully.');
        
        return redirect(route('playerDepositPayLogs.index'));
    }
}
