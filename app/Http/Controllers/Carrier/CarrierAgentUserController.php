<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierAgentUserDataTable;
use App\Helpers\IP\RealIpHelper;
use App\Http\Requests\Carrier;
use Illuminate\Http\Request;
use App\Repositories\Carrier\CarrierAgentLevelRepository;
use App\Http\Requests\Carrier\CreateCarrierAgentUserRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentUserRequest;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Route;
use App\Models\Log\AgentAccountLog;
use App\Models\CarrierAgentUser;
use App\Models\CarrierTemplate;
use Carbon\Carbon;

class CarrierAgentUserController extends AppBaseController
{

    /** @var  CarrierAgentUserRepository */
    private $carrierAgentUserRepository;

    public static function routeLists()
    {
        Route::resource('carrierAgentUsers', 'CarrierAgentUserController'); // 代理管理
        Route::get('carrierAgentUsers.showAgentUserInfoEditModal/{id}', 'CarrierAgentUserController@showAgentUserInfoEditModal')->name('carrierAgentUsers.showAgentUserInfoEditModal'); // 代理编辑模态框
        Route::get('carrierAgentUsers.agentSubPlayer/{id}', 'CarrierAgentUserController@agentSubPlayer')->name('carrierAgentUsers.agentSubPlayer'); // 代理下线会员
        Route::get('carrierAgentUsers.subAgent/{id}', 'CarrierAgentUserController@subAgent')->name('carrierAgentUsers.subAgent'); // 代理旗下的代理用户
        Route::patch('carrierAgentUsers.saveStatus/{id}', 'CarrierAgentUserController@saveStatus')->name('carrierAgentUsers.saveStatus'); // 代理管理启用禁用
        Route::post('carrierAgentUsers.dataAgentLevel', 'CarrierAgentUserController@dataAgentLevel')->name('carrierAgentUsers.dataAgentLevel'); // 代理管理(代理类型二级联动)
        Route::patch('carrierAgentLevels.saveStatus/{id}', 'CarrierAgentLevelController@saveStatus')->name('carrierAgentLevels.saveStatus'); // 代理类型启用禁用
        Route::get('carrierAgentUsers.editPassword/{id}', 'CarrierAgentUserController@editPassword')->name('carrierAgentUsers.editPassword'); // 代理列表修改密码
        Route::get('carrierAgentUsers.editTemplate/{id}', 'CarrierAgentUserController@editTemplate')->name('carrierAgentUsers.editTemplate'); // 代理列表模板修改
        Route::patch('carrierAgentUsers.savePassword/{id}', 'CarrierAgentUserController@savePassword')->name('carrierAgentUsers.savePassword'); // 代理列表保存修改密码
        Route::patch('carrierAgentUsers.saveTemplate/{id}', 'CarrierAgentUserController@saveTemplate')->name('carrierAgentUsers.saveTemplate'); // 代理列表保存修改密码
        Route::get('carrierAgentUsers.agentAmount', 'CarrierAgentUserController@agentAmount')->name('carrierAgentUsers.agentAmount'); // 修改余额
        Route::get('carrierAgentUsers.experienceAmount', 'CarrierAgentUserController@experienceAmount')->name('carrierAgentUsers.experienceAmount'); // 修改代理体验额度
        Route::patch('carrierAgentUsers.saveExperienceAmount/{id}', 'CarrierAgentUserController@saveExperienceAmount')->name('carrierAgentUsers.saveExperienceAmount'); // 保存代理体验额度
        Route::patch('carrierAgentUsers.updateTelephone/{id}', 'CarrierAgentUserController@updateTelephone')->name('carrierAgentUsers.updateTelephone'); // 修改手机号
        Route::patch('carrierAgentUsers.updateEmail/{id}', 'CarrierAgentUserController@updateEmail')->name('carrierAgentUsers.updateEmail'); // 修改邮箱
        Route::patch('carrierAgentUsers.updateRealName/{id}', 'CarrierAgentUserController@updateRealName')->name('carrierAgentUsers.updateRealName'); // 修改真实姓名
        Route::get('carrierAgentUsers.editAgentType', 'CarrierAgentUserController@editAgentType')->name('carrierAgentUsers.editAgentType'); // 修改代理类型
        Route::patch('carrierAgentUsers.saveAgentType/{id}', 'CarrierAgentUserController@saveAgentType')->name('carrierAgentUsers.saveAgentType'); // 点击保存代理类型
    }

    public function __construct(CarrierAgentUserRepository $carrierAgentUserRepo)
    {
        $this->carrierAgentUserRepository = $carrierAgentUserRepo;
    }

    /**
     * Display a listing of the CarrierAgentUser.
     *
     * @param CarrierAgentUserDataTable $carrierAgentUserDataTable
     * @return Response
     */
    public function index(CarrierAgentUserDataTable $carrierAgentUserDataTable)
    {
        return $carrierAgentUserDataTable->render('Carrier.carrier_agent_users.index');
    }

    /**
     * Show the form for creating a new CarrierAgentUser.
     *
     * @return Response
     */
    public function create(CarrierAgentLevelRepository $repository)
    {
        $carrierid = \Auth::user()->carrier_id;
        // $carrierAgentLevelType = $repository->allAgentLevels($carrierid);//获取代理类型数据
        $carrierAgentLevel = \App\Models\CarrierAgentLevel::where('type', 1)->get();
        $templates =CarrierTemplate::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->with('templates')->whereHas('templates', function ($query) {
            $query->where('type', 4);
        })->get();
        return view('Carrier.carrier_agent_users.create')->with('carrierAgentLevel', $carrierAgentLevel)->with('templates', $templates);
    }

    /**
     * Store a newly created CarrierAgentUser in storage.
     *
     * @param CreateCarrierAgentUserRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierAgentUserRequest $request)
    {
        $input = $request->all();
        
        $input['carrier_id'] = \Auth::user()->carrier_id;
        $input['register_ip'] = RealIpHelper::getIp(); // 获取IP
        $input['promotion_code'] = CarrierAgentUser::generateReferralCode();
        $input['pay_password'] = bcrypt('000000');
        $input['password'] = bcrypt($input['password']);
        
        if (! empty($input['tgcode'])) {
            $pId = CarrierAgentUser::where('promotion_code', $input['tgcode'])->first();
            if (empty($pId)) {
                return $this->sendErrorResponse([
                    'field' => 'promotion_code',
                    'message' => '推荐代理商不存在，请重新输入'
                ], 403);
            }
            if (! $pId->agentLevel->is_multi_agent || ! $pId->carrier->is_multi_agent) {
                return $this->sendErrorResponse([
                    'field' => 'agent_level',
                    'message' => '该推荐代理商不支持多级推广，请联系该代理'
                ], 403);
            }
            $input['parent_id'] = $pId->id;
        } else {
            $defaultAgent = CarrierAgentUser::where('carrier_id', \WinwinAuth::currentWebCarrier()->id)->where('is_default', 1)->first();
            $input['parent_id'] = empty($defaultAgent) ? 0 : $defaultAgent->id;
        }
        
        $input['audit_status'] = $input['status'] = 1;
        $input['customer_remark'] = "客服后台创建";
        $input['customer_time'] = Carbon::now();
        
        $carrierAgentUser = $this->carrierAgentUserRepository->create($input);
        
        if ($request->ajax()) {
            
            return self::sendResponse([], 'ok');
        }
        
        Flash::success('Carrier Agent User saved successfully.');
        
        return redirect(route('carrierAgentUsers.index'));
    }

    /**
     * Display the specified CarrierAgentUser.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.carrier_agent_users.show')->with([
            'carrierAgentUser' => $carrierAgentUser
        ]);
    }

    /**
     * 显示代理用户编辑模态框
     *
     * @param
     *            $id
     * @return mixed
     */
    public function showAgentUserInfoEditModal($id)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        if ($carrierAgentUser['agent_level_id']) {
            $carrierAgentLevel = \App\Models\CarrierAgentLevel::where([
                'id' => $carrierAgentUser['agent_level_id']
            ])->first();
            $carrierAgentLevelType = \App\Models\CarrierAgentLevel::typeMeta()[$carrierAgentLevel->type];
        } else {
            $carrierAgentLevel = null;
            $carrierAgentLevelType = null;
        }
        $carrierAgentDomain = \App\Models\CarrierAgentDomain::where([
            'agent_id' => $carrierAgentUser['id']
        ])->get();
        return view('Carrier.carrier_agent_users.agent_user_info_edit_modal')->with([
            'carrierAgentDomain' => $carrierAgentDomain,
            'carrierAgentUser' => $carrierAgentUser,
            'carrierAgentLevel' => $carrierAgentLevel,
            'carrierAgentLevelType' => $carrierAgentLevelType
        ]);
    }

    /**
     * 代理下线会员
     */
    public function agentSubPlayer($id)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        $player = \App\Models\Player::where([
            'agent_id' => $carrierAgentUser['id']
        ])->get();
        return view('Carrier.carrier_agent_users.agent_sub_player', [
            'player' => $player
        ]);
    }

    /**
     * 代理下线代理用户
     */
    public function subAgent($id)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        $carrierSubAgentUser = \App\Models\CarrierAgentUser::where('parent_id', $carrierAgentUser['id'])->get();
        return view('Carrier.carrier_agent_users.sub_agent', [
            'carrierSubAgentUser' => $carrierSubAgentUser
        ]);
    }

    /**
     * 修改代理类型
     *
     * @param type $id
     * @return type
     */
    public function editAgentType(Request $request)
    {
        $carrierAgentUser = \App\Models\CarrierAgentUser::where('id', $request->get('id'))->first();
        
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        $type = \App\Models\CarrierAgentLevel::where('id', $carrierAgentUser['agent_level_id'])->first(); // 获取代理名称数据
        $carrierAgentLevelName = \App\Models\CarrierAgentLevel::where('type', $type['type'])->get();
        return view('Carrier.carrier_agent_users.agent_type_edit', [
            'carrierAgentUser' => $carrierAgentUser,
            'type' => $type,
            'carrierAgentLevelName' => $carrierAgentLevelName
        ]);
    }

    /**
     * 保存修改代理类型
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveAgentType($id, Request $request)
    {
        if (! empty($request->get('agent_level_id'))) {
            if ($request->get('agent_level_id')) {
                $data['agent_level_id'] = $request->get('agent_level_id');
            } else {
                $error_respon = array(
                    'success' => false,
                    'message' => '代理名称不能为空'
                );
                return $error_respon;
            }
            $data['updated_at'] = \Carbon\Carbon::now();
            $carrierAgentUser = $this->carrierAgentUserRepository->update($data, $id);
            if (empty($carrierAgentUser)) {
                return $this->sendNotFoundResponse();
            }
            return $this->sendSuccessResponse(route('carrierAgentUsers.index'));
        }
    }

    /**
     * 调整余额
     *
     * @param type $id
     * @return type
     */
    public function agentAmount(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ], [], [
            'id' => '用户'
        ]);
        $carrierAgentUser = $this->carrierAgentUserRepository->with([
            'carrier.carrierPayChannels' => function ($query) {
                $query->available();
            },
            'carrier.carrierPayChannels.payChannel.payChannelType'
        ])
            ->findWithoutFail($request->get('id'));
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.carrier_account_adjusts_log.agent_amount', [
            'carrierAgentUser' => $carrierAgentUser
        ]);
    }

    /**
     * 会员礼金
     *
     * @param type $id
     * @return type
     */
    public function experienceAmount(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ], [], [
            'id' => '用户'
        ]);
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($request->get('id'));
        if (empty($carrierAgentUser)) {
            return $this->renderNotFoundPage();
        }
        return view('Carrier.carrier_agent_users.experience_amount', [
            'carrierAgentUser' => $carrierAgentUser
        ]);
    }

    /**
     * 保存代理会员礼金
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveExperienceAmount($id, Request $request)
    {
        
        // 调整会员余额
        // 5, 更新会员礼金
        if ($request->get('amount') == 0) {
            return $this->sendErrorResponse("请设置调整会员礼金");
        }
        try {
            $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
            $agentAccountLog = new AgentAccountLog();
            
            $agentAccountLog->fund_type = $request->get('adjust_is_plus') == 1;
            $agentAccountLog->fund_source = '客服调整会员礼金';
            // 调整代理会员礼金
            $input['experience_amount'] = $request->get('amount') * ($request->get('adjust_is_plus') == 1 ? 1 : - 1);
            
            // 新增账户记录
            $agentAccountLog->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
            $agentAccountLog->agent_id = $id;
            $agentAccountLog->amount = abs($input['experience_amount']);
            $agentAccountLog->operator_reviewer_id = \WinwinAuth::carrierUser()->id;
            // 更新代理会员礼金
            $oldMainAmount = $carrierAgentUser->experience_amount;
            $carrierAgentUser->experience_amount += $input['experience_amount'];
            $agentAccountLog->remark = '代理会员礼金原余额： ' . $oldMainAmount . ', 现余额： ' . $carrierAgentUser->experience_amount;
            $agentAccountLog->save();
            $carrierAgentUser->update();
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified CarrierAgentUser.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id, CarrierAgentLevelRepository $repository)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        $carrierid = \Auth::user()->carrier_id;
        $carrierAgentLevels = $repository->allAgentLevels($carrierid); // 获取代理类型数据
        if (! empty($carrierAgentLevels)) {
            $type = $carrierAgentUser['type']; // 代理类型ID
            $carrierAgentLevelName = $repository->allAgentLevels($carrierid, $type); // 获取代理名称数据
        }
        return view('Carrier.carrier_agent_users.edit', [
            'carrierAgentUser' => $carrierAgentUser,
            'carrierAgentLevels' => $carrierAgentLevels,
            'carrierAgentLevelName' => $carrierAgentLevelName
        ]);
    }

    /**
     * Update the specified CarrierAgentUser in storage.
     *
     * @param int $id
     * @param UpdateCarrierAgentUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierAgentUserRequest $request)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        
        $carrierAgentUser = $this->carrierAgentUserRepository->update($request->all(), $id);
        
        if ($request->ajax()) {
            
            return self::sendResponse([], 'ok');
        }
        
        Flash::success('更新成功.');
        
        return redirect(route('carrierAgentUsers.index'));
    }

    /**
     * Remove the specified CarrierAgentUser from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            if ($request->ajax()) {
                return self::sendError('无法找到数据', 404);
            }
            Flash::error('无法找到该数据');
            return redirect(route('carrierAgentUsers.index'));
        }
        $this->carrierAgentUserRepository->delete($id);
        if ($request->ajax()) {
            return self::sendResponse([], 'success');
        }
        Flash::success('删除成功.');
        return redirect(route('carrierAgentUsers.index'));
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
        $data['status'] = $request->get('status');
        $carrierAgentUser = $this->carrierAgentUserRepository->update($data, $id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse(route('carrierAgentUsers.index'));
    }

    /**
     * 代理类型二级联动数据
     */
    public function dataAgentLevel(Request $request)
    {
        $data['type'] = $request->get('type');
        $data['carrier_id'] = \Auth::user()->carrier_id;
        
        $classes = \App\Models\CarrierAgentLevel::where($data)->get();
        echo json_encode($classes);
    }

    /**
     * 修改密码
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function editPassword($id, Request $request)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_agent_users.edit_password', [
            'carrierAgentUser' => $carrierAgentUser
        ]);
    }

    public function savePassword($id, Request $request)
    {
        if (! empty($request->get('password')) && ! empty($request->get('confirm_password'))) {
            $confirm_password = $request->get('confirm_password');
            if ($request->get('password') === $confirm_password) {
                $data['password'] = bcrypt($request->get('password'));
                $carrierAgentUser = $this->carrierAgentUserRepository->update($data, $id);
                if (empty($carrierAgentUser)) {
                    return $this->sendNotFoundResponse();
                }
                return $this->sendSuccessResponse(route('carrierAgentUsers.index'));
            } else {
                $error_respon = array(
                    'success' => false,
                    'message' => '二次密码不一致。'
                );
                return $error_respon;
            }
        } else {
            $error_respon = array(
                'success' => false,
                'message' => '密码不能为空。'
            );
            return $error_respon;
        }
    }

    /**
     * 修改模板
     *
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function editTemplate($id, Request $request)
    {
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        $templates =CarrierTemplate::where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->with('templates')->whereHas('templates', function ($query) {
            $query->where('type', 4);
        })->get();
        return view('Carrier.carrier_agent_users.edit_template', [
            'carrierAgentUser' => $carrierAgentUser,
            'templates' => $templates,
        ]);
    }
    public function saveTemplate($id, Request $request)
    {
        if (! empty($request->get('template_agent_admin'))) {
            $data['template_agent_admin'] = $request->get('template_agent_admin');
            $carrierAgentUser = $this->carrierAgentUserRepository->update($data, $id);
            if (empty($carrierAgentUser)) {
                return $this->sendNotFoundResponse();
            }
            return $this->sendSuccessResponse(route('carrierAgentUsers.index'));

        } else {
            $error_respon = array(
                'success' => false,
                'message' => '模板错误，请重试。'
            );
            return $error_respon;
        }
    }

    /**
     * 更新代理用户真实姓名
     *
     * @param Request $request
     */
    public function updateRealName($id, Request $request)
    {
        $this->validate($request, [
            'realname' => 'required|max:10'
        ], [], [
            'realname' => '姓名'
        ]);
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        $this->carrierAgentUserRepository->update([
            'realname' => $request->get('realname')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     * 修改手机号码
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateTelephone($id, Request $request)
    {
        $this->validate($request, [
            'mobile' => [
                'required',
                'unique:inf_agent,mobile,' . $id . ',id',
                'regex:/^1[3-9]\d{9}$/'
            ]
        ], [], [
            'mobile' => '手机号码'
        ]);
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        $this->carrierAgentUserRepository->update([
            'mobile' => $request->get('mobile')
        ], $id);
        return $this->sendSuccessResponse();
    }

    /**
     * 修改邮箱
     *
     * @param
     *            $id
     * @param Request $request
     * @return Response
     */
    public function updateEmail($id, Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:inf_agent,email,' . $id . ',id|email'
        ], [], [
            'email' => '电子邮件'
        ]);
        $carrierAgentUser = $this->carrierAgentUserRepository->findWithoutFail($id);
        if (empty($carrierAgentUser)) {
            return $this->sendNotFoundResponse();
        }
        $this->carrierAgentUserRepository->update([
            'email' => $request->get('email')
        ], $id);
        return $this->sendSuccessResponse();
    }
}
