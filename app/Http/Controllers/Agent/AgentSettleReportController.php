<?php

namespace App\Http\Controllers\Agent;

use App\Http\Requests\Carrier\CreateCarrierAgentSettleLogRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentSettleLogRequest;
use App\Models\CarrierAgentUser;
use App\Models\Conf\CarrierCommissionAgentPlatformFee;
use App\Models\Log\AgentBearUndertakenLog;
use App\Models\Log\CarrierAgentSettleDetailLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Models\Log\PlayerWithdrawLog;
use App\Repositories\Carrier\CarrierAgentSettleLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\DataTables\Agent\AgentSettleReportDataTable;
use App\Models\Log\CarrierAgentSettleLog;
use App\Models\Log\CarrierAgentSettlePeriodsLog;
use App\Models\Def\GamePlat;
use App\Models\Log\AgentRebateFinancialFlow;

class AgentSettleReportController extends AppBaseController
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
    public function index(AgentSettleReportDataTable $agentSettleReportDataTable)
    {
        $carrierActivityAudit = CarrierAgentSettleLog::with(['settlePeriods'])->where('agent_id',\WinwinAuth::agentUser()->id);
        $carrierActivityAudit = $carrierActivityAudit->orderBy('created_at', 'desc')->paginate(10);
        if (\WinwinAuth::agentUser()->template_agent_admin == 'Agent'){
            return $agentSettleReportDataTable->render(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.index');
        } elseif (\WinwinAuth::agentUser()->template_agent_admin == 'Template_Agent_Admin_One'){
            return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.index', compact('carrierActivityAudit'));
        } else {
            return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.index', compact('carrierActivityAudit'));
        }
        return false;
    }

    /**
     * 成本分摊
     * @param type $id
     */
    public function details($id)
    {
//        $settleLog = CarrierAgentSettleLog::where(['id'=>$id, 'agent_id' => \WinwinAuth::agentUser()->id])->first();
//        $periodsLog = CarrierAgentSettlePeriodsLog::where(['id'=>$settleLog->periods_id, 'agent_id' => \WinwinAuth::agentUser()->id])->first();
//        $agentUser = CarrierAgentUser::with(['players','agentLevel.commissionAgentConf'])->find(\WinwinAuth::agentUser()->id);
//
//        foreach ($agentUser->players as $k => $item) {
//            $playerIds[] = $item['player_id'];
//        }
//
//        //存款优惠
//        $agentUser->benefit_amount = PlayerDepositPayLog::select(\DB::raw('SUM(benefit_amount) as benefit_amount'))->whereBetween('created_at', [$periodsLog->start_time, $periodsLog->end_time])->whereIn('player_id',$playerIds)->where(['carrier_id'=>\WinwinAuth::agentUser()->carrier_id])->get();
//        //有效投注
//        $agentUser->available_betFlow = PlayerBetFlowLog::select(\DB::raw('SUM(available_bet_amount) as available_bet_amount'))->whereBetween('created_at', [$periodsLog->start_time, $periodsLog->end_time])->where('bet_flow_available',PlayerBetFlowLog::BET_FLOW_AVAILABLE)->whereIn('player_id',$playerIds)->where(['carrier_id'=>\WinwinAuth::agentUser()->carrier_id])->get();
//
////        dump($gamePlat);
//        return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.details')->with(compact('settleLog','agentUser'));
        $carrierId = \WinwinAuth::agentUser()->carrier_id;
        $carrierAgentCommissionSettleLog = CarrierAgentSettleLog::where(['id'=>$id, 'agent_id' => \WinwinAuth::agentUser()->id])->first();
        $carrierCommissionSettlePeriods = CarrierAgentSettlePeriodsLog::where('id', $carrierAgentCommissionSettleLog->periods_id)->where('agent_id',\WinwinAuth::agentUser()->id)->where('carrier_id', $carrierId)->first();
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
        $rebateAmountTotal = $depositAmountTotal = $bonusAmountTotal = $inFeeAmountTotal = $outFeeAmountTotal = sprintf("%.2f", 0); // 洗码总额 优惠总额 红利总额 存款手续费总额 取款手续费总额
        if (! empty($playerIds)) {
            $underTakens = AgentBearUndertakenLog::select(\DB::raw('SUM(amount) as amount'), 'undertaken_type')->whereBetween('created_at', $whereTime)
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
            $totlalFee = PlayerDepositPayLog::select(\DB::raw('SUM(bonus_amount) as bonus_amount'), \DB::raw('SUM(benefit_amount) as benefit_amount'), \DB::raw('SUM(fee_amount) as fee_amount'))->whereBetween('created_at', $whereTime)
                ->whereIn('id', $playerIds)
                ->where('carrier_id', $carrierId)
                ->where('status', 1)
                ->first();
            if (! empty($totlalFee)) {
                // 红利金额 红利总额
                $bonusAmountTotal = $totlalFee['bonus_amount'];
                $depositAmountTotal = $totlalFee['benefit_amount']; // 存款优惠
                $inFeeAmountTotal = $totlalFee['fee_amount']; // 存款手续费
            }
            $outFeeAmountTotal = sprintf("%.2f", PlayerWithdrawLog::whereBetween('created_at', $whereTime)->whereIn('player_id', $playerIds)
                ->where('carrier_id', $carrierId)
                ->where('status', 1)
                ->sum('fee_amount'));

            $rebateAmountTotal = sprintf("%.2f", PlayerRebateFinancialFlowNew::whereBetween('settled_at', $whereTime)->where('is_already_settled', 1)
                ->whereIn('player_id', $playerIds)
                ->where('carrier_id', $carrierId)
                ->sum('rebate_financial_flow_amount'));
        }
        return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.details')->with([
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

    public function commission(Request $request)
    {
        $level = $request->input('level', 0);
        $carrierActivityAudit = CarrierAgentSettleDetailLog::with(['outAgent','settlePeriods.settlePeriods'])->where('in_agent_id',\WinwinAuth::agentUser()->id);
        if ($level){
            $carrierActivityAudit = $carrierActivityAudit->where('level', $level);
        }
        $carrierActivityAudit = $carrierActivityAudit->orderBy('created_at', 'desc')->paginate(10);


        $parameter['level'] = $level;

        $arr = config('constants.agent_level');

        return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.commission', compact('carrierActivityAudit','arr','parameter'));

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

    public function rebate($id)
    {
        $carrierId = \WinwinAuth::agentUser()->carrier_id;
        $carrierAgentCommissionSettleLog = CarrierAgentSettleLog::where(['id'=>$id, 'agent_id' => \WinwinAuth::agentUser()->id])->first();
        $carrierCommissionSettlePeriods = CarrierAgentSettlePeriodsLog::where('id', $carrierAgentCommissionSettleLog->periods_id)->where('agent_id',\WinwinAuth::agentUser()->id)->where('carrier_id', $carrierId)->first();
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
        $rebateFinancialFlow = AgentRebateFinancialFlow::select(\DB::raw('SUM(amount) as rebateFinancialFlow'), \DB::raw('SUM(cathectic) as cathectic_amount'), \DB::raw('SUM(available_cathectic) as available_cathectic_amount'), 'game_plat_id')->whereBetween('created_at', $whereTime)
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

        return view(\WinwinAuth::agentUser()->template_agent_admin.'.agent_settle_reports.rebate')->with('rebates', $rebates)->with('platFormFee', $platFormFee);
    }
}
