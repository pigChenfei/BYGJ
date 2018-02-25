<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\CarrierAccountException;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Log\PlayerRebateFinancialFlowNew;
use App\Services\PassPlayerRebateFinancialFlowService;

class PlayerRebateFinancialFlowController extends AppBaseController
{

    /**
     * 实时洗码
     * @return \View
     */
    public function rebateFinancialFlow(Request $request)
    {
        $type = $request->get('type', '');
        $mobile = $request->get('mobile', '');
        $perPage = $request->get('perPage', 10);
        $playerRebateFinancialFlow = PlayerRebateFinancialFlowNew::with(['gamePlat','player.playerLevel.rebateFinancialFlow'])
            ->where('is_already_settled', 0)
            ->where('is_effect', 0)
            ->where('rebate_type', 2)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
        //可结算总金额
        $settleAmountTotal = PlayerRebateFinancialFlowNew::unsettled()->where([
            'rebate_type' => 2,
            'is_effect' => 0,
        ])->sum('rebate_financial_flow_amount');
        if($request->ajax()){
            if($type){
                return \WTemplate::rebateFinancialFlowList()->with('playerRebateFinancialFlow', $playerRebateFinancialFlow);
            }
            if($mobile){
                return $this->sendResponse($playerRebateFinancialFlow);
            }
            return \WTemplate::rebateFinancialFlowRecord()->with(['playerRebateFinancialFlow'=> $playerRebateFinancialFlow, 'settleAmountTotal'=>$settleAmountTotal]);
        }
        return \WTemplate::rebateFinancialFlowRecord()->with(['playerRebateFinancialFlow'=> $playerRebateFinancialFlow, 'settleAmountTotal'=>$settleAmountTotal]);
    }

    /**
     * 结算
     * @param Request $request
     * @return mixed
     */
    public function settleMoney(Request $request){
        $playerRebateFinanceFlowId = $request->get('playerRebateFinanceFlowId');

        if(isset($playerRebateFinanceFlowId) && !$playerRebateFinanceFlowId){
            return $this->sendErrorResponse('参数异常');
        }
        if($playerRebateFinanceFlowId){
            $rebateFinancialFlowLog = PlayerRebateFinancialFlowNew::unsettled()
                ->where([
                    'id' => $playerRebateFinanceFlowId,
                    'rebate_type' => 2,
                    'is_effect' => 0,
                ])->get();
        }else{
            $rebateFinancialFlowLog = PlayerRebateFinancialFlowNew::unsettled()
                ->where([
                'rebate_type' => 2,
                'is_effect' => 0,
            ])->get();
        }
        if ($rebateFinancialFlowLog->count() <= 0){
            return $this->sendErrorResponse('参数错误');
        }
        try{

            $handleService = new PassPlayerRebateFinancialFlowService($rebateFinancialFlowLog);
            $handleService->handle();

            //总可结算金额
            $settleAmountTotal = PlayerRebateFinancialFlowNew::unsettled()->where([
                'rebate_type' => 2,
                'is_effect' => 0,
            ])->sum('rebate_financial_flow_amount');

        }catch(\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
        return $this->sendResponse($settleAmountTotal,'结算成功');

    }
}
