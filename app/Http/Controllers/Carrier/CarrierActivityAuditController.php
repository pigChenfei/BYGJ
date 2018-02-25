<?php
namespace App\Http\Controllers\Carrier;

use App\Exceptions\CarrierRuntimeException;
use App\Http\Requests\Carrier\CreateCarrierActivityAuditRequest;
use App\Http\Requests\Carrier\UpdateCarrierActivityAuditRequest;
use App\Models\Activity\ActivityPassReviewBonusFactory\ActivityPassReviewBonusFactory;
use App\Models\CarrierActivity;
use App\Models\CarrierActivityAudit;
use App\Models\Player;
use App\Repositories\Carrier\CarrierActivityAuditRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use App\DataTables\Carrier\CarrierActivityAuditDataTable;
use App\Http\Requests\Carrier\CreatePlayerAccountAdjustLogRequest;
use Response;
use App\Models\Log\CarrierQuotaConsumptionLog;
use App\Models\Log\PlayerAccountAdjustLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Log\PlayerWithdrawFlowLimitLog;

class CarrierActivityAuditController extends AppBaseController
{

    /** @var  CarrierActivityAuditRepository */
    private $carrierActivityAuditRepository;

    public function __construct(CarrierActivityAuditRepository $carrierActivityAuditRepo)
    {
        $this->carrierActivityAuditRepository = $carrierActivityAuditRepo;
    }

    /**
     * Display a listing of the CarrierActivityAudit.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CarrierActivityAuditDataTable $carrierActivityAuditDataTable)
    {
        return $carrierActivityAuditDataTable->render('Carrier.carrier_activity_audits.index');
    }

    /**
     * Show the form for creating a new CarrierActivityAudit.
     *
     * @return Response
     */
    public function create()
    {
        return view('carrier_activity_audits.create');
    }

    /**
     * Store a newly created CarrierActivityAudit in storage.
     *
     * @param CreateCarrierActivityAuditRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierActivityAuditRequest $request)
    {
        $input = $request->all();
        
        $carrierActivityAudit = $this->carrierActivityAuditRepository->create($input);
        
        Flash::success('Carrier Activity Audit saved successfully.');
        
        return redirect(route('carrierActivityAudits.index'));
    }

    /**
     * Display the specified CarrierActivityAudit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierActivityAudit = $this->carrierActivityAuditRepository->findWithoutFail($id);
        
        if (empty($carrierActivityAudit)) {
            Flash::error('Carrier Activity Audit not found');
            
            return redirect(route('carrierActivityAudits.index'));
        }
        
        return view('carrier_activity_audits.show')->with('carrierActivityAudit', $carrierActivityAudit);
    }

    /**
     * Show the form for editing the specified CarrierActivityAudit.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierActivityAudit = $this->carrierActivityAuditRepository->findWithoutFail($id);
        
        if (empty($carrierActivityAudit)) {
            Flash::error('Carrier Activity Audit not found');
            
            return redirect(route('carrierActivityAudits.index'));
        }
        
        return view('Carrier.carrier_activity_audits.edit')->with('carrierActivityAudit', $carrierActivityAudit);
    }

    /**
     * Update the specified CarrierActivityAudit in storage.
     *
     * @param int $id
     * @param UpdateCarrierActivityAuditRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierActivityAuditRequest $request)
    {
        $carrierActivityAudit = $this->carrierActivityAuditRepository->with(
            [
                'activity',
                'player'
            ])->findWithoutFail($id);
        if (empty($carrierActivityAudit)) {
            return $this->sendNotFoundResponse();
        }
        if ($carrierActivityAudit->status != CarrierActivityAudit::STATUS_AUDIT) {
            return $this->sendErrorResponse('该活动已经审核过了');
        }
        if ($request->get('passed')) {
            // 将活动本身的红利规则计算出来加到用户身上,
            // 如果有调整红利,再次计算
            $activity = $carrierActivityAudit->activity;
            if (! $activity) {
                throw new CarrierRuntimeException('活动不存在');
            }
            $player = $carrierActivityAudit->player;
            if (! $player) {
                throw new CarrierRuntimeException('玩家不存在');
            }
            try {
                $carrierFactory = ActivityPassReviewBonusFactory::createFactory($carrierActivityAudit);
                \DB::transaction(
                    function () use ($carrierFactory, $player, $request, $carrierActivityAudit) {
                        // 处理活动的的红利数据
                        $carrierFactory->handleBonus($player);
                        // 处理调整的红利数据
                        $adjustBonus = $request->get('adjust_is_plus') == 1 ? $request->get('amount') : (- 1 *
                             $request->get('amount'));
                        $withdrawLimit = $request->get('withdraw_limit_amount', 0);
                        if ($adjustBonus) {
                            $carrierFactory->modifyDepositLogBonus($player, $adjustBonus);
                            $carrierFactory->newBonusRecord($player, $adjustBonus);
                        }
                        if ($withdrawLimit) {
                            $carrierFactory->newWithdrawLimitLog($player, $withdrawLimit, null);
                        }
                        $carrierActivityAudit->remark = $request->get('remark');
                        $carrierActivityAudit->status = CarrierActivityAudit::STATUS_ADOPT;
                        $carrierActivityAudit->update();
                    });
                return $this->sendSuccessResponse();
            } catch (\Exception $e) {
                return $this->sendErrorResponse($e->getMessage());
            }
        } else {
            $carrierActivityAudit->remark = $request->get('remark');
            $carrierActivityAudit->status = CarrierActivityAudit::STATUS_REFUSE;
            $carrierActivityAudit->update();
            return $this->sendSuccessResponse();
        }
    }

    /**
     * Remove the specified CarrierActivityAudit from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierActivityAudit = $this->carrierActivityAuditRepository->findWithoutFail($id);
        
        if (empty($carrierActivityAudit)) {
            Flash::error('Carrier Activity Audit not found');
            
            return redirect(route('carrierActivityAudits.index'));
        }
        
        $this->carrierActivityAuditRepository->delete($id);
        
        Flash::success('Carrier Activity Audit deleted successfully.');
        
        return redirect(route('carrierActivityAudits.index'));
    }

    /**
     * 显示调整红利界面
     *
     * @param Request $request
     * @return mixed
     */
    public function bonusEdit($id)
    {
        $carrierActivityAudit = $this->carrierActivityAuditRepository->findWithoutFail($id);
        if (empty($carrierActivityAudit)) {
            return $this->sendNotFoundResponse();
        }
        $player_id = $carrierActivityAudit['player_id'];
        $carrierGamePlatList = \App\Models\Map\CarrierGamePlat::with('gamePlat')->get(); // 获取游戏平台ID
        try {
            $carrierFactory = ActivityPassReviewBonusFactory::createFactory($carrierActivityAudit);
            list ($bonus, $withdrawFlowLimit) = $carrierFactory->getBonusAndWithdrawLimitFlow(
                Player::findOrFail($player_id));
            return view('Carrier.carrier_activity_audits.bonus_edit')->with(
                [
                    'carrierActivityAudit' => $carrierActivityAudit,
                    'carrierGamePlatList' => $carrierGamePlatList,
                    'player_id' => $player_id,
                    'bonus' => $bonus,
                    'withdrawFlowLimit' => $withdrawFlowLimit
                ]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    public function refuseActivityAuditModal($id)
    {
        $carrierActivityAudit = $this->carrierActivityAuditRepository->findWithoutFail($id);
        if (empty($carrierActivityAudit)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_activity_audits.refuse')->with(
            [
                'carrierActivityAudit' => $carrierActivityAudit
            ]);
    }
}
