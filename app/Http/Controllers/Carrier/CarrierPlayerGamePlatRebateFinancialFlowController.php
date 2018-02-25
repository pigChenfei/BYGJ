<?php

namespace App\Http\Controllers\Carrier;

use App\Entities\CacheConstantPrefixDefine;
use App\Http\Requests\Admin;
use App\Http\Requests\Carrier\UpdateCarrierPlayerLevelRebateFinancialFlowRequest;
use App\Repositories\Carrier\CarrierPlayerGamePlatRebateFinancialFlowRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CarrierPlayerGamePlatRebateFinancialFlowController extends AppBaseController
{
    /** @var  CarrierPlayerGamePlatRebateFinancialFlowRepository */
    private $carrierPlayerGamePlatRebateFinancialFlowRepository;

    public function __construct(CarrierPlayerGamePlatRebateFinancialFlowRepository $carrierPlayerGamePlatRebateFinancialFlowRepo)
    {
        $this->carrierPlayerGamePlatRebateFinancialFlowRepository = $carrierPlayerGamePlatRebateFinancialFlowRepo;
    }
    /**
     * Show the form for editing the specified CarrierPlayerGamePlatRebateFinancialFlow.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierPlayerGamePlatRebateFinancialFlow = $this->carrierPlayerGamePlatRebateFinancialFlowRepository->findWithoutFail($id);

        if (empty($carrierPlayerGamePlatRebateFinancialFlow)) {
            Flash::error('Carrier Player Game Plat Rebate Financial Flow not found');

            return redirect(route('carrierPlayerGamePlatRebateFinancialFlows.index'));
        }

        return view('Carrier.carrier_player_levels.game_plats_flow_edit')->with('carrierPlayerGamePlatRebateFinancialFlow', $carrierPlayerGamePlatRebateFinancialFlow);
    }

    /**
     * Update the specified CarrierPlayerGamePlatRebateFinancialFlow in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierPlayerLevelRebateFinancialFlowRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierPlayerLevelRebateFinancialFlowRequest $request)
    {

        $carrierPlayerGamePlatRebateFinancialFlow = $this->carrierPlayerGamePlatRebateFinancialFlowRepository->findWithoutFail($id);

        if (empty($carrierPlayerGamePlatRebateFinancialFlow)) {

            return $this->sendNotFoundResponse();

        }

        $this->carrierPlayerGamePlatRebateFinancialFlowRepository->update($request->all(), $id);
        \WLog::info('清除洗码配置缓存KEY:'.CacheConstantPrefixDefine::CARRIER_PLAYER_LEVEL_REBATE_FINANCIAL_FLOW_CONFIGURE_CACHE_PREFIX.$carrierPlayerGamePlatRebateFinancialFlow->carrier_player_level_id.'_'.$carrierPlayerGamePlatRebateFinancialFlow->carrier_game_plat_id);
        \Cache::forget(CacheConstantPrefixDefine::CARRIER_PLAYER_LEVEL_REBATE_FINANCIAL_FLOW_CONFIGURE_CACHE_PREFIX.$carrierPlayerGamePlatRebateFinancialFlow->carrier_player_level_id.'_'.$carrierPlayerGamePlatRebateFinancialFlow->carrier_game_plat_id);
        return $this->sendSuccessResponse(route('carrierPlayerLevels.index'));

    }

}
