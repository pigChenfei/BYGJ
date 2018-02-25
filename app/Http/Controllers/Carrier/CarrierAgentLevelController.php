<?php
namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateCarrierAgentLevelRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentLevelRequest;
use App\Models\CarrierAgentUser;
use App\Models\Conf\CarrierAgentLevelCommission;
use App\Repositories\Carrier\CarrierCommissionAgentRepository;
use App\Http\Requests\Carrier\UpdateCarrierCommissionAgentRequest;
use App\Repositories\Carrier\CarrierAgentLevelRepository;
use App\Repositories\Carrier\CarrierPlayerLevelRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\DataTables\Carrier\CarrierAgentLevelDataTable;
use Flash;
use Response;

class CarrierAgentLevelController extends AppBaseController
{

    /** @var  CarrierAgentLevelRepository */
    private $agentLevelRepository;

    public function __construct(CarrierAgentLevelRepository $agentLevelRepo)
    {
        $this->agentLevelRepository = $agentLevelRepo;
    }

    /**
     * Display a listing of the AgentLevel.
     *
     * @param Request $request
     * @return Response
     */
    public function index(CarrierAgentLevelDataTable $carrierAgentLevelDataTable)
    {
        return $carrierAgentLevelDataTable->render('Carrier.carrier_agent_levels.index');
    }

    /**
     * Show the form for creating a new AgentLevel.
     *
     * @return Response
     */
    public function create(CarrierPlayerLevelRepository $repository)
    {
        $carrierPlayerLevels = $repository->allPlayerLevels(); // 获取会员层级数据
        return view('Carrier.carrier_agent_levels.create')->with('carrierPlayerLevels', $carrierPlayerLevels);
    }

    /**
     * Store a newly created AgentLevel in storage.
     *
     * @param CreateCarrierAgentLevelRequest $request
     *            CreateCarrierPlayerLevelRequest
     * @return Response
     */
    public function store(CreateCarrierAgentLevelRequest $request)
    {
        $input = $request->all();
        
        $input['carrier_id'] = \Auth::user()->carrier_id;

        if (\WinwinAuth::currentWebCarrier()->is_multi_agent == 0){
            $input['is_multi_agent'] = 0;
        }
        
        $carrierAgentLevel = $this->agentLevelRepository->create($input);
        
        if ($request->ajax()) {
            
            return self::sendResponse([], 'ok');
        }
        
        Flash::success('Carrier Agent Level saved successfully.');
        
        return redirect(route('carrierAgentLevels.index'));
    }

    /**
     * Display the specified AgentLevel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $agentLevel = $this->agentLevelRepository->findWithoutFail($id);
        
        if (empty($agentLevel)) {
            Flash::error('Carrier Agent Level not found');
            return redirect(route('carrierAgentLevels.index'));
        }
        
        return view('Carrier.carrier_agent_levels.show')->with('agentLevel', $agentLevel);
    }

    /**
     * Show the form for editing the specified AgentLevel.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id, CarrierPlayerLevelRepository $repository)
    {
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($id);
        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        $carrierid = \Auth::user()->carrier_id;
        $carrierPlayerLevels = $repository->allPlayerLevels($carrierid); // 获取会员层级数据
        return view('Carrier.carrier_agent_levels.edit', [
            'carrierAgentLevel' => $carrierAgentLevel,
            'carrierPlayerLevels' => $carrierPlayerLevels
        ]);
    }

    /**
     * Update the specified AgentLevel in storage.
     *
     * @param int $id
     * @param UpdateCarrierAgentLevelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierAgentLevelRequest $request)
    {
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($id);
        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;
        if (\WinwinAuth::currentWebCarrier()->is_multi_agent == 0){
            $input['is_multi_agent'] = 0;
        }
        $this->agentLevelRepository->update($input, $id);
        return $this->sendSuccessResponse(route('carrierAgentLevels.index'));
    }

    /**
     * Remove the specified AgentLevel from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $agentLevel = $this->agentLevelRepository->findWithoutFail($id);
        if (empty($agentLevel)) {
            if ($request->ajax()) {
                return self::sendError('无法找到数据', 404);
            }
            Flash::error('无法找到该数据');
            return redirect(route('CarrierAgentLevels.index'));
        }
        $agent = CarrierAgentUser::where('agent_level_id',$id)->first();
        if (!empty($agent)) {
            return $this->sendErrorResponse('其下有代理，不能直接删除', 404);
        }
        $this->agentLevelRepository->delete($id);
        if ($request->ajax()) {
            return self::sendResponse([], 'success');
        }
        Flash::success('删除成功.');
        return redirect(route('CarrierAgentLevels.index'));
    }

    /**
     * Show the form for editing the specified AgentLevel.
     * 佣金设置
     *
     * @param int $id
     *
     * @return Response
     */
    public function commissionAgent($id, CarrierCommissionAgentRepository $commissionAgentRepository)
    {
        $input['agent_level_id'] = $id;
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $carrierCommissionAgent = $commissionAgentRepository->commissionAgentFind($input);
        if (empty($carrierCommissionAgent)) {
            return $this->sendNotFoundResponse();
        }
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($carrierCommissionAgent['agent_level_id']);
        return view('Carrier.carrier_agent_levels.commission_agent', [
            'carrierAgentLevel' => $carrierAgentLevel,
            'carrierCommissionAgent' => $carrierCommissionAgent
        ]);
    }

    /**
     * Update the specified AgentLevel in storage.
     * 保存佣金设置
     *
     * @param int $id
     * @param UpdateCarrierAgentLevelRequest $request
     *
     * @return Response
     */
    public function saveCommissionAgent($id, UpdateCarrierCommissionAgentRequest $request, CarrierCommissionAgentRepository $commissionAgentRepository)
    {
        $carrierAgentLevel = $commissionAgentRepository->update($request->all(), $id);
        if ($request->ajax()) {
            return self::sendResponse([], 'ok');
        }
        return redirect(route('carrierAgentLevels.index'));
    }

    /**
     * 禁用启用
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveStatus($id, Request $request)
    {
        $data['is_running'] = $request->get('is_running');
        $carrierAgentLevel = $this->agentLevelRepository->update($data, $id);
        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse(route('carrierAgentLevels.index'));
    }

    /**
     * 平台费
     *
     * @param type $id
     * @param
     *
     * @return type
     */
    public function platformFee($id)
    {
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($id);
        
        $data['agent_level_id'] = $carrierAgentLevel['id'];
        $agentpa = \App\Models\Conf\CarrierCommissionAgentPlatformFee::where($data)->whereHas('carrierGamePlat.gamePlat')
            ->with("carrierGamePlat.gamePlat")
            ->get();
        
        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_agent_levels.commission_platform_fee')->with([
            'carrierAgentLevel' => $carrierAgentLevel,
            'agentpa' => $agentpa
        ]);
    }

    public function savePlatformFee($id, Request $request)
    {
        foreach ($_POST['setid'] as $k => $v) {
            $ar[] = array(
                $v,
                $_POST['platform_fee_rate'][$k],
                $_POST['platform_fee_max'][$k],
                array_key_exists('computing_mode', $_POST) && array_key_exists($k, $_POST['computing_mode']) ? $_POST['computing_mode'][$k] : 0,
                $_POST['agent_rebate_financial_flow_rate'][$k],
                $_POST['agent_rebate_financial_flow_max_amount'][$k],
                array_key_exists('computing_mode_2', $_POST) && array_key_exists($k, $_POST['computing_mode_2']) ? $_POST['computing_mode_2'][$k] : 0
            );
        }
        foreach ($ar as $key => $value) {
            if ($value[3] == \App\Models\Conf\CarrierCommissionAgentPlatformFee::BET_COMPUTING_MODE) {
                if ($value[1] < 51) {
                    $data['platform_fee_rate'] = $value[1];
                } else {
                    $error_respon = array(
                        'success' => false,
                        'message' => '平台费比例 不能大于 50'
                    );
                    return $error_respon;
                }
                $data['platform_fee_max'] = $value[2];
            } else {
                $data['platform_fee_rate'] = $value[1] = 0;
                $data['platform_fee_max'] = $value[2] = 0;
            }
            if ($value[6] == \App\Models\Conf\CarrierCommissionAgentPlatformFee::BET_COMPUTING_MODE) {
                if ($value[4] < 51) {
                    $data['agent_rebate_financial_flow_rate'] = $value[4];
                } else {
                    $error_respon = array(
                        'success' => false,
                        'message' => '洗码比例 不能大于 50'
                    );
                    return $error_respon;
                }
                $data['agent_rebate_financial_flow_max_amount'] = $value[5];
            } else {
                $data['agent_rebate_financial_flow_rate'] = $value[4] = 0;
                $data['agent_rebate_financial_flow_max_amount'] = $value[5] = 0;
            }
            $data['computing_mode'] = $value[3];
            $data['computing_mode_2'] = $value[6];
            $ids['id'] = $value[0];
            \App\Models\Conf\CarrierCommissionAgentPlatformFee::where($ids)->update($data);
        }
        if ($request->ajax()) {
            return self::sendResponse([], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentLevels.index'));
    }

    /**
     * 洗码代理
     *
     * @param type $id
     * @return type conf_carrier_rebate_financial_flow_agent
     */
    public function rebateFinancialFlowAgent($id)
    {
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($id);
        $data['agent_level_id'] = $carrierAgentLevel['id'];
        $agentpa = \App\Models\Conf\CarrierRebateFinancialFlowAgent::where($data)->whereHas("carrierGamePlat.gamePlat")
            ->with("carrierGamePlat.gamePlat")
            ->get();
        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        $data_info['agent_level_id'] = $carrierAgentLevel['id'];
        $data_info['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
        $carrierRebateFinancialFlowAgentBaseInfo = \App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo::where($data_info)->first();
        return view('Carrier.carrier_agent_levels.rebate_financial_flow_agent')->with([
            'carrierAgentLevel' => $carrierAgentLevel,
            'agentpa' => $agentpa,
            'carrierRebateFinancialFlowAgentBaseInfo' => $carrierRebateFinancialFlowAgentBaseInfo
        ]);
    }

    public function saveRebateFinancialFlowAgent($id, Request $request)
    {
        foreach ($_POST['setid'] as $k => $v) {
            $ar[] = array(
                $v,
                $_POST['agent_rebate_financial_flow_rate'][$k],
                $_POST['agent_rebate_financial_flow_max_amount'][$k]
            );
        }
        foreach ($ar as $key => $value) {
            if ($value[1] < 6) {
                $data['agent_rebate_financial_flow_rate'] = $value[1];
            } else {
                $error_respon = array(
                    'success' => false,
                    'message' => '洗码比例 不能大于 5'
                );
                return $error_respon;
            }
            $data['agent_rebate_financial_flow_max_amount'] = $value[2];
            // $data['computing_mode_2'] = 1;
            $ids['id'] = $value[0];
            \App\Models\Conf\CarrierRebateFinancialFlowAgent::where($ids)->update($data);
//            \App\Models\Conf\CarrierRebateFinancialFlowAgent::updateOrCreate();
        }
        
        $data_info['available_member_count'] = $request->get('available_member_count');
        $data_info['is_player_rebate_financial_adapt_carrier_conf'] = $request->get('is_player_rebate_financial_adapt_carrier_conf');
        $data_info['available_member_monthly_bet_amount'] = $request->get('available_member_monthly_bet_amount');
        $where_data['id'] = $request->get('rebateFinancialFlowAgentBaseInfo_id');
        \App\Models\Conf\CarrierRebateFinancialFlowAgentBaseInfo::where($where_data)->update($data_info);
        
        if ($request->ajax()) {
            return self::sendResponse([], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentLevels.index'));
    }

    /**
     * 占成代理
     *
     * @param type $id
     * @return type
     */
    public function costTakeAgent($id)
    {
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($id);
        
        $data['agent_level_id'] = $carrierAgentLevel['id'];
        $data['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
        $carrierCostTakeAgent = \App\Models\Conf\CarrierCostTakeAgent::where($data)->first();
        $carrierCostTakeAgentPlatformFee = \App\Models\Conf\CarrierCostTakeAgentPlatformFee::where($data)->with("carrierGamePlat.gamePlat")->get();
        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_agent_levels.cost_take_agent')->with([
            'carrierAgentLevel' => $carrierAgentLevel,
            'carrierCostTakeAgent' => $carrierCostTakeAgent,
            'carrierCostTakeAgentPlatformFee' => $carrierCostTakeAgentPlatformFee
        ]);
    }

    public function saveCostTakeAgent($id, Request $request)
    {
        foreach ($_POST['setid'] as $k => $v) {
            $ar[] = array(
                $v,
                $_POST['platform_fee_rate'][$k],
                $_POST['platform_fee_max'][$k]
            );
        }
        foreach ($ar as $key => $value) {
            if ($value[1] < 6) {
                $data1['platform_fee_rate'] = $value[1];
            } else {
                $error_respon = array(
                    'success' => false,
                    'message' => '洗码比例 不能大于 5'
                );
                return $error_respon;
            }
            $data1['platform_fee_max'] = $value[2];
            $ids['id'] = $value[0];
            \App\Models\Conf\CarrierCostTakeAgentPlatformFee::where($ids)->update($data1);
        }
        if ($request->get('deposit_fee_undertake_ratio') < 101) {
            $data_info['deposit_fee_undertake_ratio'] = $request->get('deposit_fee_undertake_ratio');
        } else {
            $error_respon = array(
                'success' => false,
                'message' => '存款手续费承担比例不能大于100'
            );
            return $error_respon;
        }
        $data_info['deposit_fee_undertake_max'] = $request->get('deposit_fee_undertake_max');
        if ($request->get('deposit_preferential_undertake_ratio') < 101) {
            $data_info['deposit_preferential_undertake_ratio'] = $request->get('deposit_preferential_undertake_ratio');
        } else {
            $error_respon = array(
                'success' => false,
                'message' => '存款优惠承担比例 不能大于 100'
            );
            return $error_respon;
        }
        $data_info['deposit_preferential_undertake_max'] = $request->get('deposit_preferential_undertake_max');
        if ($request->get('rebate_financial_flow_undertake_ratio') < 101) {
            $data_info['rebate_financial_flow_undertake_ratio'] = $request->get('rebate_financial_flow_undertake_ratio');
        } else {
            $error_respon = array(
                'success' => false,
                'message' => '洗码承担比例 不能大于 100'
            );
            return $error_respon;
        }
        $data_info['rebate_financial_flow_undertake_max'] = $request->get('rebate_financial_flow_undertake_max');
        if ($request->get('bonus_undertake_ratio') < 101) {
            $data_info['bonus_undertake_ratio'] = $request->get('bonus_undertake_ratio');
        } else {
            $error_respon = array(
                'success' => false,
                'message' => '红利承担比例 不能大于 100'
            );
            return $error_respon;
        }
        $data_info['bonus_undertake_max'] = $request->get('bonus_undertake_max');
        $data_info['can_player_join_activity'] = $request->get('can_player_join_activity');
        $data_info['is_player_rebate_financial_adapt_carrier_conf'] = $request->get('is_player_rebate_financial_adapt_carrier_conf');
        if ($request->get('cost_take_ration') < 101) {
            $data_info['cost_take_ration'] = $request->get('cost_take_ration');
        } else {
            $error_respon = array(
                'success' => false,
                'message' => '占成比例 不能大于 100'
            );
            return $error_respon;
        }
        $data_info['protection_fund'] = $request->get('protection_fund');
        $where_data['id'] = $request->get('carrierCostTakeAgent_id');
        \App\Models\Conf\CarrierCostTakeAgent::where($where_data)->update($data_info);
        
        if ($request->ajax()) {
            return self::sendResponse([], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentLevels.index'));
    }

    /**
     * 抽佣
     *
     * @param type $id
     * @param
     *
     * @return type
     */
    public function agentLevelRebate($id)
    {
        $carrierAgentLevel = $this->agentLevelRepository->findWithoutFail($id);

        $data['agent_level_id'] = $carrierAgentLevel['id'];
        $agentpa = CarrierAgentLevelCommission::where($data)->orderBy('level', 'asc')
            ->get();

        if (empty($carrierAgentLevel)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_agent_levels.commission_agent_level_rebate')->with([
            'carrierAgentLevel' => $carrierAgentLevel,
            'agentpa' => $agentpa
        ]);
    }

    //抽佣
    public function saveAgentLevelRebate($id, Request $request)
    {
        foreach ($_POST['level'] as $k => $v) {
            $ar[] = array(
                $v,
                $_POST['commission_ratio'][$k],
                $_POST['commission_max'][$k],
            );
        }
        \DB::beginTransaction();
        try{
            foreach ($ar as $key => $value) {
                if ($value[1] > 100) {
                    $error_respon = array(
                        'success' => false,
                        'message' => '抽佣比例 不能大于 100'
                    );
                    return $error_respon;
                }

                $data['level'] = $value[0];
                $data['commission_ratio'] = $value[1];
                $data['commission_max'] = $value[2];
                $data['carrier_id'] = \WinwinAuth::currentWebCarrier()->id;
                $data['agent_level_id'] = $id;
                CarrierAgentLevelCommission::updateOrCreate(['carrier_id'=>\WinwinAuth::currentWebCarrier()->id,'agent_level_id'=>$id,'level'=>$value[0]], $data);
            }
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            \Log::error('多级佣金抽成比例设置失败：',['message' => $e->getMessage()]);
            return self::sendError('多级佣金抽成比例设置失败', 404);
        }
        if ($request->ajax()) {
            return self::sendResponse([], 'ok');
        }
        return $this->sendSuccessResponse(route('carrierAgentLevels.index'));
    }
}
