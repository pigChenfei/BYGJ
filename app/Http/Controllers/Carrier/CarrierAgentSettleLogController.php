<?php
namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateCarrierAgentSettleLogRequest;
use App\Repositories\Carrier\CarrierAgentSettleLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;
use App\DataTables\Carrier\CarrierAgentSettleLogDataTable;
use App\Models\CarrierAgentUser;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\AgentBearUndertakenLog;
use Illuminate\Support\Facades\DB;
use App\Models\Log\CarrierAgentSettleLog;
use App\Models\Log\CarrierAgentSettlePeriodsLog;
use App\Models\CarrierAgentLevel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\AgentRebateFinancialFlow;
use Carbon\Carbon;
use App\Models\Conf\CarrierCommissionAgentPlatformFee;
use App\Models\Log\PlayerWithdrawLog;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Models\Conf\CarrierRebateFinancialFlowAgent;
use App\Models\Log\CarrierAgentSettleDetailLog;
use App\Models\Log\AgentAccountAdjustLog;

class CarrierAgentSettleLogController extends AppBaseController
{

    /** @var  CarrierAgentSettleLogRepository */
    private $carrierAgentSettleLogRepository;

    public function __construct(CarrierAgentSettleLogRepository $carrierAgentSettleLogRepo)
    {
        $this->carrierAgentSettleLogRepository = $carrierAgentSettleLogRepo;
    }

    /**
     * Display a listing of the CarrierAgentSettleLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CarrierAgentSettleLogDataTable $carrierAgentSettleLogDataTable)
    {
        return $carrierAgentSettleLogDataTable->render('Carrier.carrier_agent_commission_settle_logs.index');
    }

    /**
     * Show the form for creating a new CarrierAgentSettleLog.
     * log_player_bet_flow
     *
     * @return Response
     */
    public function create()
    {
        $periods = date("Y-m", mktime(0, 0, 0, date("m"), 0, date("Y"))); // 月份
        return view('Carrier.carrier_agent_commission_settle_logs.create')->with(
            [
                'periods' => $periods
            ]);
    }

    /**
     * Store a newly created CarrierAgentSettleLog in storage.
     *
     * @param CreateCarrierAgentSettleLogRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->get('type') == CarrierAgentSettleLog::LAST_WEEK) {
            $time = time("Y-m-d H:i:s") - 86400 * 7;
            $start_time = date('Y-m-d H:i:s',
                mktime(0, 0, 0, date('m', $time), date('d', $time) - date('N', $time) + 1, date('Y', $time))); // 上周
            $end_time = date('Y-m-d H:i:s',
                mktime(23, 59, 59, date('m', $time), date('d', $time) - date('N', $time) + 7, date('Y', $time))); // 上周
            $periods = "" . $start_time . "至" . $end_time . "";
            
            $last_start_time = date('Y-m-d H:i:s',
                mktime(0, 0, 0, date('m', $time), date('d', $time) - date('N', $time) + (- 6), date('Y', $time))); // 上上个月时间
            $last_end_time = date('Y-m-d H:i:s',
                mktime(23, 59, 59, date('m', $time), date('d', $time) - date('N', $time) + 0, date('Y', $time))); // 上上个月时间
            $last_time = "" . $last_start_time . "至" . $last_end_time . "";
        } else if ($request->get('type') == CarrierAgentSettleLog::LAST_MONTH) {
            $start_time = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, 1, date("Y"))); // 上个月的月初时间
            $end_time = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0, date("Y"))); // 上个月的月末时间
            $periods = date("Y-m", mktime(0, 0, 0, date("m"), 0, date("Y")));
            
            $last_time = date("Y-m", mktime(0, 0, 0, date("m") - 2, 1, date("Y"))); // 上上个月时间
        } else if ($request->get('type') == CarrierAgentSettleLog::FIRST_HALF_MONTH) {
            $start_time = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, 1, date("Y"))); // 上个月的月初时间
            $end_time = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0, date("Y"))); // 上个月的月末时间
            $periods = date("Y-m", mktime(0, 0, 0, date("m"), 0, date("Y")));
            
            $last_time = date("Y-m", mktime(0, 0, 0, date("m") - 2, 1, date("Y"))); // 上上个月时间
        }
        $carrierId = \WinwinAuth::carrierUser()->carrier_id;
        $whereTime = array(
            $start_time,
            $end_time
        );
        $carrierCommissionSettlePeriodsLog = CarrierAgentSettlePeriodsLog::where('periods', $periods)->where(
            'carrier_id', $carrierId)->first();
        
        // 洗码结算
        // $agentRebateFinancialFlow = AgentRebateFinancialFlow::whereBetween('created_at', $whereTime)->where('is_settled', 0)
        // ->where('carrier_id', $carrierId)
        // ->get();
        
        $carrierBetFlow = PlayerBetFlowLog::with('player.agent')->whereHas('player.agent',
            function ($query) {
                $query->whereNotNull('agent_level_id')
                    ->orWhere('is_default', 0);
            })
            ->whereBetween('created_at', $whereTime)
            ->where('bet_flow_available', PlayerBetFlowLog::BET_FLOW_AVAILABLE)
            ->where('carrier_id', $carrierId)
            ->get();
        if (empty($carrierBetFlow)) {
            return $this->sendErrorResponse('暂无结算数据。');
        }
        $agentIds = $this->listKeys($carrierBetFlow->toArray(), 'player.agent.id');
        if (empty($agentIds)) {
            return $this->sendErrorResponse('暂无结算数据。');
        }
        $agentUserWith = [
            'players',
            'agentLevel.commissionAgentConf'
        ];
        $agentUser = CarrierAgentUser::whereIn('id', $agentIds)->with($agentUserWith)
            ->where('carrier_id', $carrierId)
            ->where('is_default', 0)
            ->where('status', 1)
            ->where('audit_status', 1)
            ->get();
        foreach ($agentUser as $key => $value) {
            // 定义变量
            $cumulative_last_month = $amt = $cumulative_last_month_rebate = $game_plat_win_amount = $available_bet_amount = $allWinAmt = $gamePlatFormFee = $rebateAmountTotal = $this_period_commission = $available_players = $available_member = 0.00;
            // 累加上月
            $lastPeriodsWhere = [
                'periods' => $last_time,
                'agent_id' => $value->id
            ];
            $lastPeriods = CarrierAgentSettlePeriodsLog::where($lastPeriodsWhere)->where('carrier_id', $carrierId)
                ->with('commissionSettle')
                ->first();
            if ($lastPeriods != null && ! is_null($lastPeriods) && ! empty($lastPeriods)) {
                $cumulative_last_month = $lastPeriods->commissionSettle->transfer_next_month; // 累加上月佣金
                $cumulative_last_month_rebate = $lastPeriods->commissionSettle->transfer_next_month_rebate; // 累加上月洗码
            }
            $playerIds = $this->listKeys($value['players'], 'player_id');
            // 根据存款额和投注额判断是否是有效会员
            // 公司输赢 累加值（各个平台的输赢×各个平台抽佣比例） $agentUser[$key]['game_plat_win_amount']
            $winAmount = PlayerBetFlowLog::select(DB::raw('SUM(company_win_amount) as company_win_amount'),
                'game_plat_id', DB::raw('SUM(available_bet_amount) as available_bet_amount'))->whereBetween('created_at',
                $whereTime)
                ->whereIn('player_id', $playerIds)
                ->where('carrier_id', $carrierId)
                ->groupBy('game_plat_id')
                ->get();
            
            if ($value['agentLevel']['type'] == CarrierAgentLevel::COMMISSION_AGETN) { // 只有佣金代理才需要计算平台费
                foreach ($winAmount as $winAmt) {
                    $platFormFee = CarrierCommissionAgentPlatformFee::where('carrier_id', $carrierId)->where(
                        'agent_level_id', $value['agent_level_id'])
                        ->where('computing_mode', 1)
                        ->where('carrier_game_plat_id', $winAmt->game_plat_id)
                        ->first();
                    $allWinAmt = bcadd($allWinAmt, $winAmt->company_win_amount, 2);
                    $available_bet_amount = bcadd($winAmt['available_bet_amount'], $available_bet_amount, 2);
                    if (! empty($platFormFee)) {
                        $ratefee = bcmul($winAmt->company_win_amount, bcdiv($platFormFee->platform_fee_rate, 100, 5), 2);
                        if (bccomp($ratefee, $platFormFee->platform_fee_max, 2) == 1) { // 大于对上限 取上限值
                            $ratefee = $platFormFee->platform_fee_max;
                        }
                        if (bccomp($ratefee, 0.00, 2) == 1) {
                            $gamePlatFormFee = bcadd($ratefee, $gamePlatFormFee, 2);
                        }
                    }
                }
            }
            // 成本分摊 存款优惠=累加（代理所有玩家存款优惠）×存款优惠承担比例×总佣金抽佣比例
            // 红利=累加（代理所有玩家红利）×红利承担比例×总佣金抽佣比例
            // 返水=累加（代理所有玩家返水）×返水承担比例×总佣金抽佣比例 （出账）
            $bearUndertakenWhere = [
                'agent_id' => $value['id'],
                'carrier_id' => $carrierId,
                'is_settled' => 0
            ];
            $bearUndertaken = AgentBearUndertakenLog::whereBetween('created_at', $whereTime)->where(
                $bearUndertakenWhere)
                ->whereNull('settled_at')
                ->sum('amount');
            // 存款额
            $playerDepositPaylogs = PlayerDepositPayLog::select(DB::raw('SUM(benefit_amount) as benefit_amount'),
                DB::raw('SUM(bonus_amount) as bonus_amount'), DB::raw('SUM(fee_amount) as fee_amount'),
                DB::raw('COUNT(DISTINCT player_id) as available_players'))->whereBetween('created_at', $whereTime)
                ->whereIn('player_id', $playerIds)
                ->where('carrier_id', $carrierId)
                ->first();
            // 洗码金额
            $playerRebateAmt = PlayerRebateFinancialFlowNew::where('is_already_settled', 1)->whereIn('player_id',
                $playerIds)
                ->where('carrier_id', $carrierId)
                ->sum('rebate_financial_flow_amount') ?? 0.00;
            $allOutAmount = bcadd(
                bcadd($playerDepositPaylogs['benefit_amount'],
                    bcadd($playerDepositPaylogs['bonus_amount'], $playerDepositPaylogs['fee_amount'], 2), 2),
                $playerRebateAmt, 2);
            $available_players = $playerDepositPaylogs['available_players'];
            $available_member_count_conf = $available_bet_amount_conf = 0.00;
            if ($value['agentLevel']['type'] == CarrierAgentLevel::COMMISSION_AGETN) {
                $available_member_count_conf = $value['agentLevel']['commissionAgentConf']['available_member_count'];
                $available_bet_amount_conf = $value['agentLevel']['commissionAgentConf']['available_member_monthly_bet_amount'];
            } elseif ($value['agentLevel']['type'] == CarrierAgentLevel::REBATE_FINANCIAL_FLOW_AGENT) {
                $available_member_count_conf = $value['agentLevel']['rebateFinancialFlowAgentBaseConf']['available_member_count'];
                $available_bet_amount_conf = $value['agentLevel']['rebateFinancialFlowAgentBaseConf']['available_member_monthly_bet_amount'];
            }
            if ($available_players >= $available_member_count_conf &&
                 bccomp($available_bet_amount, $available_bet_amount_conf, 2) >= 0) {
                // 有效会员
                $available_member = "" . $available_players . "(已达标)";
            } else {
                // 有效会员
                $available_member = "" . $available_players . "(未达标，有效会员>=" . $available_member_count_conf . ",且有效投注额>=" .
                     $available_bet_amount_conf . ")";
            }
            if (bccomp($allWinAmt, 0.00, 2) > 0 && $value['agentLevel']['type'] == CarrierAgentLevel::COMMISSION_AGETN) { // 公司赢了 并且是佣金代理
                $amt = bcsub(bcsub($allWinAmt, $gamePlatFormFee, 2), $allOutAmount, 2); // 公司净盈利值 = 公司总成本 - 平台费 - 存款手续费 - 存款优惠 - 存款红利
                $commisonAgentConf = $value['agentLevel']['commissionAgentConf'];
                if (! empty($commisonAgentConf['commission_step_ratio'])) {
                    $stepCommissionRatio = json_decode($commisonAgentConf['commission_step_ratio'], true);
                    foreach ($stepCommissionRatio as $scr) {
                        if (bccomp($allWinAmt, $scr['flowAmount'], 2) > 0 && $available_players > $scr['availableMember']) {
                            $this_period_commission = bcmul($amt, bcdiv($scr['flowRate'], 100, 5), 2);
                        }
                    }
                } else if (bccomp($available_bet_amount, $available_bet_amount_conf, 2) > 0 &&
                     $available_players >= $available_member_count_conf) {
                    $this_period_commission = bcmul($amt,
                        bcdiv($value['agentLevel']['commissionAgentConf']['commission_ratio'], 100, 5), 2); // 本期佣金 佣金收入=总输赢×总佣金抽佣比例
                }
                // else {
                // $this_period_commission = - $bearUndertaken;
                // }
                // else { // if ($value['agent']['agentLevel']['type'] == CarrierAgentLevel::REBATE_FINANCIAL_FLOW_AGENT) { // 洗码代理 占成代理
                // $$this_period_commission = 0; // $agentUser[$key]['game_plat_win_amount'][0]['company_win_amount'];
                // }
            }
            \Log::info(
                "代理编号" . $value['id'] . "公司总输赢：" . $allWinAmt . '平台费：' . $gamePlatFormFee . '公司出账：' . $allOutAmount .
                     '净输赢:' . $amt . '当期佣金：' . $this_period_commission);
            // else {
            // $this_period_commission = $bearUndertaken == 0.00 || empty($bearUndertaken) ? 0.00 : - $bearUndertaken;
            // }
            // $this_period_commission = bcsub($this_period_commission, $bearUndertaken, 2); // 最终佣金 = 佣金当前值 - 成本分摊
            $rebateFinancialFlowWhere = array(
                'agent_id' => $value['id'],
                'carrier_id' => $carrierId,
                'is_settled' => 0
            );
            // 洗码金额 （进账）
            $rebateFinancialFlow = AgentRebateFinancialFlow::select(DB::raw('SUM(amount) as rebateFinancialFlow'),
                'game_plat_id')->whereBetween('created_at', $whereTime)
                ->where($rebateFinancialFlowWhere)
                ->whereNull('settled_at')
                ->groupBy('game_plat_id')
                ->get();
            foreach ($rebateFinancialFlow as $rff) {
                $rffWhere = array(
                    'carrier_id' => $carrierId,
                    // 'computing_mode_2' => 1,
                    'agent_level_id' => $value['agent_level_id'],
                    'carrier_game_plat_id' => $rff['game_plat_id']
                );
                if ($value['agentLevel']['type'] == CarrierAgentLevel::COMMISSION_AGETN) {
                    $rffconf = CarrierCommissionAgentPlatformFee::where($rffWhere)->where('computing_mode_2', 1)->first();
                } elseif ($value['agentLevel']['type'] == CarrierAgentLevel::REBATE_FINANCIAL_FLOW_AGENT) {
                    $rffconf = CarrierRebateFinancialFlowAgent::where($rffWhere)->first();
                }
                if (empty($rffconf) || is_null($rffconf)) {
                    continue;
                }
                $rebateAmount = $rff['rebateFinancialFlow'];
                if (bccomp($rebateAmount, $rffconf->agent_rebate_financial_flow_max_amount, 2) > 0) {
                    $rebateAmount = $rffconf->agent_rebate_financial_flow_max_amount;
                }
                $rebateAmountTotal = bcadd($rebateAmount, $rebateAmountTotal, 2);
            }
            
            // 实际佣金计算
            $agentUser[$key]['commissions'] = $this_period_commission;
            
            if (empty($carrierCommissionSettlePeriodsLog)) {
                \DB::beginTransaction();
                try {
                    $settelPeriods = new CarrierAgentSettlePeriodsLog();
                    $settelPeriods->carrier_id = $carrierId;
                    $settelPeriods->agent_id = $value['id'];
                    $settelPeriods->periods = $periods;
                    $settelPeriods->start_time = $start_time;
                    $settelPeriods->end_time = $end_time;
                    $settelPeriods->save(); // 保存当前结转期数
                    
                    $settlePeriodsMaxId = CarrierAgentSettlePeriodsLog::lastSettlePeriodsId();
                    $settel = new CarrierAgentSettleLog();
                    $settel->carrier_id = $carrierId;
                    $settel->agent_id = $value['id'];
                    $settel->periods_id = $settlePeriodsMaxId;
                    $settel->available_member_number = $available_member; // 有效会员数(0未达标,最少多少)
                    $settel->game_plat_win_amount = $allWinAmt; // 公司输赢
                    $settel->available_player_bet_amount = $available_bet_amount; // 有效会员投注额
                    $settel->cost_share = $bearUndertaken; // 成本分摊
                    $settel->cumulative_last_month = $cumulative_last_month; // 累加上月佣金
                    $settel->cumulative_last_month_rebate = $cumulative_last_month_rebate; // 累加上月洗码
                    $settel->manual_tuneup = 0.00; // 手工调整
                    $settel->this_period_commission = $this_period_commission; // 本期佣金
                    $settel->rebate_amount = $rebateAmountTotal;
                    $settel->actual_payment = 0.00; // 实际发放
                    $settel->transfer_next_month = 0.00; // 转结下月
                    $settel->status = 1; // 状态
                    $settel->save();
                    
                    $settleMaxId = CarrierAgentSettleLog::lastSettlePeriodsId();
                    $data_info['is_settled'] = 1;
                    $data_info['log_agent_settled_id'] = $settleMaxId;
                    $data_info['settled_at'] = Carbon::now();
                    AgentRebateFinancialFlow::whereBetween('created_at', $whereTime)->where($rebateFinancialFlowWhere)->update(
                        $data_info);
                    AgentBearUndertakenLog::where($bearUndertakenWhere)->whereBetween('created_at', $whereTime)
                        ->whereNull('settled_at')
                        ->update(
                        [
                            'is_settled' => 1,
                            'settled_at' => Carbon::now(),
                            'log_agent_settled_id' => $settleMaxId
                        ]);
                    \DB::commit();
                } catch (\Exception $e) {
                    \DB::rollBack();
                    \Log::error('代理【' . $agentUser['username'] . '】结算单异常处理',
                        [
                            $e->getMessage()
                        ]);
                }
            } else {
                return $this->sendErrorResponse($periods . '已经结算过了。');
            }
        }
        return $this->sendSuccessResponse(route('carrierAgentSettleLogs.index'));
    }

    /**
     * 公司输赢报表
     */
    public function gamePlatWinAmount($id)
    {
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        return view('Carrier.carrier_agent_commission_settle_logs.game_plat_win_amount')->with(
            'carrierAgentCommissionSettleLog', $carrierAgentCommissionSettleLog);
    }

    /**
     * 洗码记录
     *
     * @param unknown $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function rebateList($id)
    {
        $carrierId = \WinwinAuth::carrierUser()->carrier_id;
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        $carrierCommissionSettlePeriods = CarrierAgentSettlePeriodsLog::where('id',
            $carrierAgentCommissionSettleLog->periods_id)->where('carrier_id', $carrierId)->first();
        $start_time = $carrierCommissionSettlePeriods->start_time;
        $end_time = $carrierCommissionSettlePeriods->end_time;
        $agentUserWith = [
            'players',
            'agentLevel.commissionAgentConf'
        ];
        $whereTime = array(
            $start_time,
            $end_time
        );
        $agentUser = CarrierAgentUser::where('id', $carrierAgentCommissionSettleLog->agent_id)->with($agentUserWith)
            ->where('carrier_id', $carrierId)
            ->first();
        $rebateFinancialFlowWhere = array(
            'agent_id' => $agentUser->id,
            'carrier_id' => $carrierId,
            'is_settled' => 1
        );
        $rebateFinancialFlow = AgentRebateFinancialFlow::select(DB::raw('SUM(amount) as rebateFinancialFlow'),
            DB::raw('SUM(cathectic) as cathectic_amount'),
            DB::raw('SUM(available_cathectic) as available_cathectic_amount'), 'game_plat_id')->whereBetween(
            'created_at', $whereTime)
            ->where($rebateFinancialFlowWhere)
            ->whereNotNull('settled_at')
            ->groupBy('game_plat_id')
            ->get();
        $rebates = array();
        foreach ($rebateFinancialFlow as $rff) {
            $rebates[$rff->game_plat_id]['rebateFinancialFlow'] = $rff['rebateFinancialFlow'];
            $rebates[$rff->game_plat_id]['cathectic_amount'] = $rff['cathectic_amount'];
            $rebates[$rff->game_plat_id]['available_cathectic_amount'] = $rff['available_cathectic_amount'];
        }
        $platFormFee = CarrierCommissionAgentPlatformFee::with('carrierGamePlat.gamePlat')->where('computing_mode_2', 1)
            ->where('carrier_id', $carrierId)
            ->where('agent_level_id', $agentUser->agent_level_id)
            ->get();
        
        return view('Carrier.carrier_agent_commission_settle_logs.rebate')->with('rebates', $rebates)->with(
            'platFormFee', $platFormFee);
    }

    /**
     * 成本分摊
     */
    public function costShare($id)
    {
        $carrierId = \WinwinAuth::carrierUser()->carrier_id;
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        $carrierCommissionSettlePeriods = CarrierAgentSettlePeriodsLog::where('id',
            $carrierAgentCommissionSettleLog->periods_id)->where('carrier_id', $carrierId)->first();
        $start_time = $carrierCommissionSettlePeriods->start_time;
        $end_time = $carrierCommissionSettlePeriods->end_time;
        $agentUserWith = [
            'players',
            'agentLevel.commissionAgentConf'
        ];
        $whereTime = [
            $start_time,
            $end_time
        ];
        $agentUser = CarrierAgentUser::where('id', $carrierAgentCommissionSettleLog->agent_id)->with($agentUserWith)
            ->where('carrier_id', $carrierId)
            ->first();
        // 存款优惠比例
        if ($agentUser->isCommissionAgent()) {
            $depositRatio = $agentUser->agentLevel->commissionAgentConf->deposit_preferential_undertake_ratio;
            $depositRatioMax = $agentUser->agentLevel->commissionAgentConf->deposit_fee_undertake_max;
            // 红利比例
            $bonusRatio = $agentUser->agentLevel->commissionAgentConf->bonus_undertake_ratio;
            $bonusRatioMax = $agentUser->agentLevel->commissionAgentConf->bonus_undertake_max;
            // 洗码比例
            $rebateRatio = $agentUser->agentLevel->commissionAgentConf->rebate_financial_flow_undertake_ratio;
            $rebateRatioMax = $agentUser->agentLevel->commissionAgentConf->rebate_financial_flow_undertake_max;
        } else {
            $depositRatio = $depositRatioMax = $bonusRatio = $bonusRatioMax = $rebateRatio = $rebateRatioMax = 0.00;
        }
        $playerIds = $this->listKeys($agentUser->players, 'player_id');
        $rebateAmount = $depositAmount = $bonusAmount = $inFeeAmount = $outFeeAmount = sprintf("%.2f", 0); // 洗码承担 优惠承担 红利承担 存款手续费承担 取款手续费承担
        $rebateAmountTotal = $depositAmountTotal = $bonusAmountTotal = $inFeeAmountTotal = $outFeeAmountTotal = sprintf(
            "%.2f", 0); // 洗码总额 优惠总额 红利总额 存款手续费总额 取款手续费总额
        if (! empty($playerIds)) {
            // $totalRebateAmount = sprintf("%.2f", $rebateAmount * ($rebateRatio / 100));
            $underTakens = AgentBearUndertakenLog::select(DB::raw('SUM(amount) as amount'), 'undertaken_type')->whereBetween(
                'created_at', $whereTime)
                ->where('agent_id', $agentUser->id)
                ->where('carrier_id', $carrierId)
                ->where('is_settled', 1)
                ->whereNotNull('settled_at')
                ->groupBy('undertaken_type')
                ->get();
            foreach ($underTakens as $ut) {
                switch ($ut->undertaken_type) {
                    case 1:
                        $depositAmount = $ut->amount;
                        break;
                    case 2:
                        $rebateAmount = $ut->amount;
                        break;
                    case 3:
                        $bonusAmount = $ut->amount;
                        break;
                    case 4:
                        $outFeeAmount = $ut->amount;
                        break;
                    case 5:
                        $inFeeAmount = $ut->amount;
                        break;
                }
            }
            // 优惠金额 优惠总额
            $totlalFee = PlayerDepositPayLog::select(DB::raw('SUM(bonus_amount) as bonus_amount'),
                DB::raw('SUM(benefit_amount) as benefit_amount'), DB::raw('SUM(fee_amount) as fee_amount'))->whereBetween(
                'created_at', $whereTime)
                ->whereIn('player_id', $playerIds)
                ->where('carrier_id', $carrierId)
                ->where('status', 1)
                ->first();
            if (! empty($totlalFee)) {
                // 红利金额 红利总额
                $bonusAmountTotal = $totlalFee['bonus_amount'] ?? sprintf("%.2f", 0);
                $depositAmountTotal = $totlalFee['benefit_amount'] ?? sprintf("%.2f", 0); // 存款优惠
                $inFeeAmountTotal = $totlalFee['fee_amount'] ?? sprintf("%.2f", 0); // 存款手续费
            }
            $outFeeAmountTotal = sprintf("%.2f",
                PlayerWithdrawLog::whereBetween('created_at', $whereTime)->whereIn('player_id', $playerIds)
                    ->where('carrier_id', $carrierId)
                    ->where('status', 1)
                    ->sum('fee_amount'));
            // PlayerBetFlowLog::whereBetween('created_at', $whereTime)->where('player_id', $playerIds)
            // ->where('bet_flow_available', PlayerBetFlowLog::BET_FLOW_AVAILABLE)
            // ->where('carrier_id', $carrierId)
            // ->sum('');
            $rebateAmountTotal = sprintf("%.2f",
                PlayerRebateFinancialFlowNew::whereBetween('settled_at', $whereTime)->where('is_already_settled', 1)
                    ->whereIn('player_id', $playerIds)
                    ->where('carrier_id', $carrierId)
                    ->sum('rebate_financial_flow_amount'));
        }
        return view('Carrier.carrier_agent_commission_settle_logs.cost_share')->with(
            [
                'carrierAgentCommissionSettleLog' => $carrierAgentCommissionSettleLog,
                'rebateAmount' => $rebateAmount,
                'depositAmount' => $depositAmount,
                'inFeeAmount' => $inFeeAmount,
                'outFeeAmount' => $outFeeAmount,
                'bonusAmount' => $bonusAmount,
                'depositRatio' => $depositRatio,
                'bonusRatio' => $bonusRatio,
                'rebateRatio' => $rebateRatio,
                'totalRebateAmount' => $rebateAmountTotal,
                'totalDepositAmount' => $depositAmountTotal,
                'totalBonusAmount' => $bonusAmountTotal,
                'totalOutFeeAmount' => $outFeeAmountTotal,
                'totalInFeeAmount' => $inFeeAmountTotal
            ]);
    }

    /**
     * 手工调整
     */
    public function manualTuneup($id)
    {
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        return view('Carrier.carrier_agent_commission_settle_logs.manual_tuneup')->with(
            'carrierAgentCommissionSettleLog', $carrierAgentCommissionSettleLog);
    }

    /**
     * 保存手工调整
     */
    public function saveManualTuneup($id, Request $request)
    {
        $log = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        if (empty($log)) {
            return $this->sendNotFoundResponse();
        }
        $data['manual_tuneup'] = $request->get("manual_tuneup", 0.00);
        $data['manual_tuneup_rebate'] = $request->get("manual_tuneup_rebate", 0.00);
        // 本期佣金=游戏平台佣金-成本分摊+累加上月+手工调整
        $data['this_period_commission'] = bcadd($log['this_period_commission'], $data['manual_tuneup'], 2);
        $data['rebate_amount'] = bcadd($log['rebate_amount'], $data['manual_tuneup_rebate'], 2);
        \App\Models\Log\CarrierAgentSettleLog::where([
            'id' => $id
        ])->update($data);
        
        if ($request->ajax()) {
            return self::sendResponse([
                ""
            ], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentSettleLogs.index'));
    }

    /**
     * 实际发放
     */
    public function actualPayment($id)
    {
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        return view('Carrier.carrier_agent_commission_settle_logs.actual_payment')->with(
            'carrierAgentCommissionSettleLog', $carrierAgentCommissionSettleLog);
    }

    /**
     * 保存实际发放
     */
    public function saveActualPayment($id, Request $request)
    {
        $log = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        if (empty($log)) {
            return $this->sendNotFoundResponse();
        }
        $data['actual_payment'] = $request->get("actual_payment", 0.00);
        $data['actual_payment_rebate'] = $request->get("actual_payment_rebate", 0.00);
        
        // 结转下月=本期佣金-实际发放
        $data['transfer_next_month'] = bcsub(bcadd($log['this_period_commission'], $log['cumulative_last_month'], 2),
            $data['actual_payment'], 2);
        $data['transfer_next_month_rebate'] = bcsub(
            bcadd($log['rebate_amount'], $log['cumulative_last_month_rebate'], 2), $data['actual_payment_rebate'], 2);
        CarrierAgentSettleLog::where([
            'id' => $id
        ])->update($data);
        
        if ($request->ajax()) {
            return self::sendResponse([
                ""
            ], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentSettleLogs.index'));
    }

    /**
     * 初审
     *
     * @param type $id
     * @return type
     */
    public function theTrial($id)
    {
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        return view('Carrier.carrier_agent_commission_settle_logs.the_trial')->with('carrierAgentCommissionSettleLog',
            $carrierAgentCommissionSettleLog);
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function saveTheTrial($id, Request $request)
    {
        $log = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        if (empty($log)) {
            return $this->sendNotFoundResponse();
        }
        $data['actual_payment'] = $request->get("actual_payment", 0.00);
        $data['actual_payment_rebate'] = $request->get("actual_payment_rebate", 0.00);
        
        // 结转下月=本期佣金-实际发放
        $data['transfer_next_month'] = bcsub(bcadd($log['this_period_commission'], $log['cumulative_last_month'], 2),
            $data['actual_payment'], 2);
        $data['transfer_next_month_rebate'] = bcsub(
            bcadd($log['rebate_amount'], $log['cumulative_last_month_rebate'], 2), $data['actual_payment_rebate'], 2);
        $data['remark'] = $request->get("remark");
        $data['status'] = 2;
        CarrierAgentSettleLog::where([
            'id' => $id
        ])->update($data);
        if ($request->ajax()) {
            return self::sendResponse([
                ""
            ], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentSettleLogs.index'));
    }

    /**
     * 复审
     *
     * @param type $id
     * @return type
     */
    public function reviewTrial($id)
    {
        $carrierAgentCommissionSettleLog = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        return view('Carrier.carrier_agent_commission_settle_logs.review_trial')->with(
            'carrierAgentCommissionSettleLog', $carrierAgentCommissionSettleLog);
    }

    /**
     * 保存复审
     *
     * @param type $id
     * @return type
     */
    public function saveReviewTrial($id, Request $request)
    {
        $log = $this->carrierAgentSettleLogRepository->findWithoutFail($id);
        if (empty($log)) {
            return $this->sendNotFoundResponse();
        }
        $data['actual_payment'] = $request->get("actual_payment", 0.00);
        $data['actual_payment_rebate'] = $request->get("actual_payment_rebate", 0.00);
        
        // 结转下月=本期佣金-实际发放
        $data['transfer_next_month'] = bcsub(bcadd($log['this_period_commission'], $log['cumulative_last_month'], 2),
            $data['actual_payment'], 2);
        $data['transfer_next_month_rebate'] = bcsub(
            bcadd($log['rebate_amount'], $log['cumulative_last_month_rebate'], 2), $data['actual_payment_rebate'], 2);
        $data['remark'] = $request->get("remark");
        $data['status'] = 3;
        
        \DB::beginTransaction();
        try {
            $amount = bcadd($log->actual_payment, $log->actual_payment_rebate, 2);
            // 如果是佣金代理，且不为默认嗲里
            CarrierAgentSettleLog::where([
                'id' => $id
            ])->update($data);
            $agentUser = CarrierAgentUser::with('agentLevel')->where(
                [
                    'id' => $log->agent_id
                ])->first();
            $nowAgentName = ! empty($agentUser->realname) ? $agentUser->realname : $agentUser->username;
            $commSetting = array();
            if (\WinwinAuth::currentWebCarrier()->is_multi_agent == 1 && $agentUser->is_default == 0 &&
                 ! is_null($agentUser->agentLevel) && $agentUser->agentLevel->is_multi_agent == 1 &&
                 $agentUser->agentLevel->is_running == 1 &&
                 $agentUser->agentLevel->type == CarrierAgentLevel::COMMISSION_AGETN) {
                // 获取上5级代理
                $upAgents = $agentUser->getAllUpAgents($agentUser);
                \Log::info($log->agent_id . '所有上级代理', $upAgents);
                $commissions = $agentUser->agentLevel->agentLevelCommission;
                if (count($commissions) > 0 && count($upAgents) > 0) {
                    foreach ($commissions as $commission) {
                        $commSetting[$commission->level] = array(
                            'ratio' => bcdiv($commission->commission_ratio, 100, 7),
                            'ratio_max' => $commission->commission_max
                        );
                    }
                    \Log::info($log->agent_id . '多级代理配置', $commSetting);
                    foreach ($upAgents as $key => $upAgent) {
                        if (array_key_exists($key + 1, $commSetting)) {
                            $ernd = bcmul($amount, $commSetting[$key + 1]['ratio'], 2);
                            if (bccomp($ernd, 0.00, 2) > 0) {
                                if (bccomp($commSetting[$key + 1]['ratio_max'], 0.00, 2) > 0 &&
                                     bccomp($ernd, $commSetting[$key + 1]['ratio_max'], 2) >= 0) {
                                    $ernd = $commSetting[$key + 1]['ratio_max'];
                                }
                                $insert_log_data = [
                                    'carrier_id' => $agentUser->carrier_id,
                                    'in_agent_id' => $upAgent->id,
                                    'out_agent_id' => $agentUser->id,
                                    'agent_settle_id' => $log->id,
                                    'commission_money' => $ernd,
                                    'commission_rate' => $commSetting[$key + 1]['ratio'],
                                    'level' => $key + 1
                                ];
                                CarrierAgentSettleDetailLog::create($insert_log_data);
                                $adjustLog = array(
                                    'agent_id' => $upAgent->id,
                                    'carrier_id' => $upAgent->carrier_id,
                                    'adjust_type' => AgentAccountAdjustLog::ADJUST_TYPE_COMMISSION,
                                    'operator' => \WinwinAuth::carrierUser()->id,
                                    'amount' => $ernd,
                                    'remark' => '下级代理贡献佣金'
                                );
                                AgentAccountAdjustLog::create($adjustLog);
                                $addMount = bcadd($upAgent->amount, $ernd, 2);
                                $upAgent->update(
                                    [
                                        'amount' => $addMount
                                    ]);
                                // $agentName = ! empty($upAgent->realname) ? $upAgent->realname : $upAgent->username;
                                // $consumption_source = '下级代理佣金提成支出';
                                // $remark = '代理【' . $agentName . '】下【' . ($key + 1) . '】级代理【' . $nowAgentName . '】佣金提成';
                                // CarrierQuotaConsumptionLog::createLog($agentUser->carrier_id, $ernd, $consumption_source, $remark);
                            }
                        }
                    }
                }
            }
            
            $agentUserdata['amount'] = bcadd($agentUser['amount'], $amount, 2);
            CarrierAgentUser::where([
                'id' => $log->agent_id
            ])->update($agentUserdata);
            $logData = array(
                'agent_id' => $agentUser->id,
                'carrier_id' => $agentUser->carrier_id,
                'adjust_type' => AgentAccountAdjustLog::ADJUST_TYPE_COMMISSION,
                'operator' => \WinwinAuth::carrierUser()->id,
                'amount' => $amount,
                'remark' => '代理佣金及洗码获取总额'
            );
            AgentAccountAdjustLog::create($logData);
            // $consumption_source = '代理佣金支出';
            // $remark = '代理【' . $nowAgentName . '】代理佣金金额';
            // CarrierQuotaConsumptionLog::createLog($agentUser->carrier_id, $amount, $consumption_source, $remark);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            if ($request->ajax()) {
                return self::sendErrorResponse('网络异常...', 'ok');
            }
            return $this->sendErrorResponse(route('carrierAgentSettleLogs.index'));
        }
        if ($request->ajax()) {
            return self::sendResponse([
                ""
            ], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentSettleLogs.index'));
    }

    /**
     * 重新计算代理结算单
     *
     * @return type
     */
    public function reSettlement()
    {
        return view('Carrier.carrier_agent_commission_settle_logs.re_settlement');
    }

    /**
     * 保存重新计算代理结算单
     *
     * @return type
     */
    public function saveReSettlement(Request $request)
    {
        $carrierAgentCommissionSettleLog = CarrierAgentSettleLog::where(
            [
                'carrier_id' => \WinwinAuth::carrierUser()->carrier_id
            ])->where('status', '!=', CarrierAgentSettleLog::SET_COMPLETED_STATUS)->get();
        $periodsIds = array();
        foreach ($carrierAgentCommissionSettleLog as $key => $value) {
            $periodsIds[] = $value['periods_id'];
        }
        $agentIds = null;
        foreach ($carrierAgentCommissionSettleLog as $key => $value) {
            $agentIds[] = $value['agent_id'];
        }
        if ($periodsIds == null) {
            return $this->sendErrorResponse('暂无结算数据。');
        }
        if ($agentIds == null) {
            return $this->sendErrorResponse('暂无结算数据。');
        }
        DB::beginTransaction();
        try {
            $updata_data = [
                'is_settled' => 0,
                'settled_at' => null,
                'log_agent_settled_id' => 0
            ];
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            AgentRebateFinancialFlow::where('carrier_id', \WinwinAuth::carrierUser()->carrier_id)->whereIn('agent_id',
                $agentIds)
                ->whereIn('log_agent_settled_id', $periodsIds)
                ->where('is_settled', '=', 1)
                ->update($updata_data); //
            AgentBearUndertakenLog::where('carrier_id', \WinwinAuth::carrierUser()->carrier_id)->whereIn('agent_id',
                $agentIds)
                ->whereIn('log_agent_settled_id', $periodsIds)
                ->where('is_settled', 1)
                ->update($updata_data);
            CarrierAgentSettlePeriodsLog::where(
                [
                    'carrier_id' => \WinwinAuth::carrierUser()->carrier_id
                ])->whereIn('id', $periodsIds)->delete();
            CarrierAgentSettleLog::where(
                [
                    'carrier_id' => \WinwinAuth::carrierUser()->carrier_id
                ])->where('status', '!=', CarrierAgentSettleLog::SET_COMPLETED_STATUS)->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendResponse([
                "失败"
            ], 'error');
        }
        if ($request->ajax()) {
            return self::sendResponse([
                ""
            ], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentSettleLogs.index'));
    }

    /**
     * 返回一位数组
     *
     * @param unknown $data
     * @param string $key
     * @return array
     */
    private function listKeys($data, $key)
    {
        $result = array();
        foreach ($data as $d) {
            $result[] = array_get($d, $key);
        }
        
        return array_unique($result);
    }
}
