<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierActivityDataTable;
use App\Models\CarrierActivityAudit;
use App\Repositories\Carrier\CarrierActivityTypeRepository;
use App\Repositories\Carrier\CarrierPlayerLevelRepository;
use App\Repositories\Carrier\CarrierAgentUserRepository;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierActivityRequest;
use App\Http\Requests\Carrier\UpdateCarrierActivityRequest;
use App\Repositories\Carrier\CarrierActivityRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\CarrierActivity;
use App\Models\Activity\CarrierActivityFlowLimitedPlatform;
use App\Models\Activity\CarrierActivityAgentUser;
use App\Models\Activity\CarrierActivityPlayerLevel;
use App\Models\Activity\CarrierActivityAmphotericGamePlat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CarrierActivityController extends AppBaseController
{
    /** @var  CarrierActivityRepository */
    private $carrierActivityRepository;

    public function __construct(CarrierActivityRepository $carrierActivityRepo)
    {
        $this->carrierActivityRepository = $carrierActivityRepo;
    }

    /**
     * Display a listing of the CarrierActivity.
     *
     * @param CarrierActivityDataTable $carrierActivityDataTable
     * @return Response
     */
    public function index(CarrierActivityDataTable $carrierActivityDataTable)
    {
        return $carrierActivityDataTable->render('Carrier.carrier_activities.index');
    }

    /**
     * Show the form for creating a new CarrierActivity.
     *
     * @return Response
     */
    public function create(CarrierActivityTypeRepository $repository,CarrierPlayerLevelRepository $perlevel,CarrierAgentUserRepository $repoagentuser, CarrierActivityRepository $repactlist)
    {
        $carrierid = \Auth::user()->carrier_id;
        $carrierActivityType = $repository->allActivityType($carrierid);//获取活动类型数据
        $carrierPlayerLevel = $perlevel->allPlayerLevels($carrierid);//获取会员等级数据
        $carrierAgentUser = $repoagentuser->allAgentUser($carrierid);//获取代理用户数据
        $carrierActivitylist = $repactlist->allActivitylist($carrierid);//获取活动数据数据
        $carrierGamePlatList = \App\Models\Map\CarrierGamePlat::where(['carrier_id'=>$carrierid])->with('gamePlat')->get();//获取游戏平台ID
        
        $data['carrier_id'] = $carrierid;
        $data['image_category'] = 4;

        return view('Carrier.carrier_activities.create',[
            'carrierActivityType'=>$carrierActivityType,
            'carrierPlayerLevel'=>$carrierPlayerLevel,
            'carrierAgentUser'=>$carrierAgentUser,
            'carrierActivitylist'=>$carrierActivitylist,
            'carrierGamePlatList'=>$carrierGamePlatList,
        ]);
    }

    /**
     * Store a newly created CarrierActivity in storage.
     *
     * @param CreateCarrierActivityRequest $request
     *self::BONUSER_TYPE_PERCENTAGE => '存送百分比',
        self::BONUSER_TYPE_FIXED_AMOUNT => '存送固定红利',
        self::BONUSER_TYPE_POSITVE => '昨日正负盈利百分比',
        self::BONUSER_TYPE_BETTING => '昨日投注额固定红利',
        self::BONUSER_TYPE_MEMBER_LEVEL => '会员等级存送百分比',
     * @return Response
     */
    public function store(CreateCarrierActivityRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
        $input['ip_times'] = $request->get('ip_times',0);
        $input['sort'] = $request->get('sort',0);

        switch ($request->get('bonuses_type')){
            case CarrierActivity::BONUSER_TYPE_PERCENTAGE:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('precentage_json');break;
            case CarrierActivity::BONUSER_TYPE_FIXED_AMOUNT:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('fixed_json');break;
            case CarrierActivity::BONUSER_TYPE_POSITVE:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('positive_json');break;
            case CarrierActivity::BONUSER_TYPE_BETTING:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('betting_json');break;
            case CarrierActivity::BONUSER_TYPE_MEMBER_LEVEL:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('member_level_json');break;
            default:
                $input['rebate_financial_bonuses_step_rate_json'] = null;
        }
        if($input['rebate_financial_bonuses_step_rate_json']){
            $json = json_decode($input['rebate_financial_bonuses_step_rate_json'],true);
            foreach ($json as $item){
                if(!$item){
                    return $this->sendErrorResponse('红利条件不完整');
                }
                if(is_array($item)){
                    foreach ($item as $mItem){
                        if(!$mItem){
                            return $this->sendErrorResponse('红利条件不完整');
                        }
                    }
                }
            }
        }
        
        //以下是需要保存为文件name对应的table中的字段
        $file_fields = [
            'content_file_path' => 'content_file_path'
        ];
        $update_type = $request->get('update_type');
        //如果是文件字段,则需要将数据保存到文件中
        if(in_array($update_type,array_keys($file_fields)) && $fileContent = $request->get($update_type)){
            $fileName = \WinwinAuth::carrierUser()->carrier_id.'/activity/'.md5('Carrier'.\WinwinAuth::carrierUser()->carrier_id.strlen($fileContent).time());
            \Storage::disk('carrier')->put($fileName,$fileContent);
            $input[$file_fields[$update_type]] = $fileName;
        }
        $carrierAct = $this->carrierActivityRepository->create($input);
        if(!empty($carrierAct))
        {
            //活动流水限平台
            $data['name'] = $request->get('name');
            $data['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
            $act_id = CarrierActivity::where($data)->first();
            
            if($mainGameplatjsonData = $request->get('main_game_plat_json')){
                $mainGameplatArray = json_decode($mainGameplatjsonData,true);
                $mainGameplatArray = array_filter($mainGameplatArray,function ($element){
                    return $element['selectedGamePages'] && is_array($element['selectedGamePages']);
                });
                foreach ($mainGameplatArray as $mainGameplat){
                    foreach ($mainGameplat['selectedGamePages'] as $key => $value) {
                        $carrierPlat = new CarrierActivityFlowLimitedPlatform();
                        $carrierPlat->act_id = $act_id['id'];
                        $carrierPlat->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $carrierPlat->carrier_game_plat_id = $value;
                        $carrierPlat->save();
                    }
                }
            }
            
            if(!empty($request->get('bonuses_type') == CarrierActivity::BONUSER_TYPE_POSITVE ))
            {
                //正负盈利产生的平台
                if($amphotericGamePlatJsonData = $request->get('amphoteric_game_plat_json')){
                    $amphotericGamePlatArray = json_decode($amphotericGamePlatJsonData,true);
                    $amphotericGamePlatArray = array_filter($amphotericGamePlatArray,function ($element){
                        return $element['selectedGames'] && is_array($element['selectedGames']);
                    });
                    foreach ($amphotericGamePlatArray as $amphotericGamePlat){
                        foreach ($amphotericGamePlat['selectedGames'] as $key => $value) {
                            $carrierAmphotericGamePlat = new CarrierActivityAmphotericGamePlat();
                            $carrierAmphotericGamePlat->act_id = $act_id['id'];
                            $carrierAmphotericGamePlat->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                            $carrierAmphotericGamePlat->carrier_game_plat_id = $value;
                            $carrierAmphotericGamePlat->save();
                        }
                    }
                }
            }
            
            //活动代理用户
            if($agentUserIdJsonData = $request->get('agent_user_id_json')){
                $agentUserArray = json_decode($agentUserIdJsonData,true);
                $agentUserArray = array_filter($agentUserArray,function ($element){
                    return $element['selectedAgentPages'] && is_array($element['selectedAgentPages']);
                });
                foreach ($agentUserArray as $agentUser){
                    foreach ($agentUser['selectedAgentPages'] as $key => $value) {
                        $carrierAgentUser = new CarrierActivityAgentUser();
                        $carrierAgentUser->act_id = $act_id['id'];
                        $carrierAgentUser->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $carrierAgentUser->agent_user_id = $value;
                        $carrierAgentUser->save();
                    }
                }
            }
            //活动会员等级
            if($playerleveljsonData = $request->get('player_level_json')){
                $playerlevelArray = explode(",", $playerleveljsonData); 
                foreach ($playerlevelArray as $v){
                    $carrierPlayerlevel = new CarrierActivityPlayerLevel();
                    $carrierPlayerlevel->act_id = $act_id['id'];
                    $carrierPlayerlevel->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $carrierPlayerlevel->player_level_id = $v;
                    $carrierPlayerlevel->save();
                }
            }
        }
        
        return $this->sendSuccessResponse( route('carrierActivities.index'));
    }

    /**
     * Display the specified CarrierActivity.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierActivity = $this->carrierActivityRepository->findWithoutFail($id);

        if (empty($carrierActivity)) {
            Flash::error('Carrier Activity not found');

            return redirect(route('carrierActivities.index'));
        }

        return view('carrier_activities.show')->with('carrierActivity', $carrierActivity);
    }

    /**
     * Show the form for editing the specified CarrierActivity.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id,CarrierActivityTypeRepository $repository,CarrierPlayerLevelRepository $perlevel,CarrierAgentUserRepository $repoagentuser, CarrierActivityRepository $repactlist)
    {
        $carrierid = \WinwinAuth::carrierUser()->carrier_id;
        $carrierActivityType = $repository->allActivityType($carrierid);//获取活动类型数据
        $carrierPlayerLevel = $perlevel->allPlayerLevels($carrierid);//获取活动类型数据
        $carrierAgentUser = $repoagentuser->allAgentUser($carrierid);//获取代理用户数据
        $carrierActivitylist = $repactlist->allActivitylist($carrierid);//获取活动数据数据
        $carrierGamePlatList = \App\Models\Map\CarrierGamePlat::where(['carrier_id'=>$carrierid])->with('gamePlat')->get();//获取游戏平台ID
        $carrierActivity = $this->carrierActivityRepository->findWithoutFail($id);
        if (empty($carrierActivity)) {
            return redirect(route('carrierActivities.index'));
        }
        
        $data_info['act_id'] =  $carrierActivity['id'];
        $data_info['carrier_id'] =  \WinwinAuth::carrierUser()->carrier_id;
        //活动代理用户
        $agentUser = CarrierActivityAgentUser::where($data_info)->get();
        foreach ($agentUser as $key => $value) {
            $agentUserGroup['selectedAgentPages'][] = "".$value['agent_user_id']."";
        }
        if(empty($agentUserGroup))
        {
            $agentUserGroup = null;
        }else{
            $agentUserGroup = "[".json_encode($agentUserGroup,true)."]";
        }
        //活动流水限平台
        $flowLimitedPlatform = CarrierActivityFlowLimitedPlatform::where($data_info)->get();
        foreach ($flowLimitedPlatform as $key => $value) {
            $flowLimitedPlatformGroup['selectedGamePages'][] = "".$value['carrier_game_plat_id']."";
        }
        if(empty($flowLimitedPlatformGroup))
        {
            $flowLimitedPlatformGroup = null;
        }else{
            $flowLimitedPlatformGroup = "[".json_encode($flowLimitedPlatformGroup,true)."]";
        }
        
        //活动会员等级
        $playerLevel = CarrierActivityPlayerLevel::where($data_info)->get();
        foreach ($playerLevel as $key => $value) {
            $playerLevelGroup[] = $value['player_level_id'];
        }
        if(empty($playerLevelGroup))
        {
            $playerLevelGroup = null;
        }else{
            $playerLevelGroup = json_encode($playerLevelGroup,true);
        }
        //正负盈利产生的平台
        $amphotericGamePlat = CarrierActivityAmphotericGamePlat::where($data_info)->get();
        foreach ($amphotericGamePlat as $key => $value) {
            $amphotericGamePlatGroup['selectedGames'][] = "".$value['carrier_game_plat_id']."";
        }
        if(empty($amphotericGamePlatGroup))
        {
            $amphotericGamePlatGroup = null;
        }else{
            $amphotericGamePlatGroup = "[".json_encode($amphotericGamePlatGroup,true)."]";
        }
        
        $data['carrier_id'] = $carrierid;
        $data['image_category'] = 4;
        return view('Carrier.carrier_activities.edit',[
            'carrierActivityType'=>$carrierActivityType,
            'carrierPlayerLevel'=>$carrierPlayerLevel,
            'carrierAgentUser'=>$carrierAgentUser,
            'carrierActivitylist'=>$carrierActivitylist,
            'carrierGamePlatList'=>$carrierGamePlatList,
            'carrierActivity'=>$carrierActivity,
            'agentUserGroup'=>$agentUserGroup,
            'flowLimitedPlatformGroup'=>$flowLimitedPlatformGroup,
            'playerLevelGroup'=>$playerLevelGroup,
            'amphotericGamePlatGroup'=>$amphotericGamePlatGroup,
        ]);
    }


    /**
     * Update the specified CarrierActivity in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierActivityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierActivityRequest $request)
    {
        $input = $request->all();
        $carrierActivity = $this->carrierActivityRepository->findWithoutFail($id);
        
        if (empty($carrierActivity)) {
            return $this->sendNotFoundResponse();
        }
        switch ($request->get('bonuses_type')){
            case CarrierActivity::BONUSER_TYPE_PERCENTAGE:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('precentage_json');break;
            case CarrierActivity::BONUSER_TYPE_FIXED_AMOUNT:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('fixed_json');break;
            case CarrierActivity::BONUSER_TYPE_POSITVE:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('positive_json');break;
            case CarrierActivity::BONUSER_TYPE_BETTING:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('betting_json');break;
            case CarrierActivity::BONUSER_TYPE_MEMBER_LEVEL:
                $input['rebate_financial_bonuses_step_rate_json'] = $request->get('member_level_json');break;
            default:
                $input['rebate_financial_bonuses_step_rate_json'] = null;
        }
        if($input['rebate_financial_bonuses_step_rate_json']){
            $json = json_decode($input['rebate_financial_bonuses_step_rate_json'],true);
            foreach ($json as $item){
                if(!$item){
                    return $this->sendErrorResponse('红利条件不完整');
                }
                if(is_array($item)){
                    foreach ($item as $mItem){
                        if(!$mItem){
                            return $this->sendErrorResponse('红利条件不完整');
                        }
                    }
                }
            }
        }

        //以下是需要保存为文件name对应的table中的字段
        $file_fields = [
            'content_file_path' => 'content_file_path'
        ];
        $update_type = $request->get('update_type');
        //如果是文件字段,则需要将数据保存到文件中
        if(in_array($update_type,array_keys($file_fields)) && $fileContent = $request->get($update_type)){
            $filedName = $file_fields[$update_type];
            if($carrierActivity->$filedName){
                $fileName = $carrierActivity->$filedName;
            }else{
                $fileName = \WinwinAuth::carrierUser()->carrier_id.'/webConf/'.md5('Carrier'.\WinwinAuth::carrierUser()->carrier_id.strlen($fileContent).time());
            }
            \Storage::disk('carrier')->put($fileName,$fileContent);
            $input[$file_fields[$update_type]] = $fileName;
        }
        if(empty($input[$file_fields[$update_type]]))
        {
            $input[$file_fields[$update_type]] = $carrierActivity['content_file_path'];
        }
        try{
            \DB::transaction(function () use ($input,$carrierActivity,$request,$update_type,$id){
                $this->carrierActivityRepository->update($input, $carrierActivity->id);
                //活动流水限平台
                $data['name'] = $request->get('name');
                $data['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
                $act_id = CarrierActivity::where($data)->first();
                CarrierActivityFlowLimitedPlatform::where('act_id',$id)->delete();
                if($mainGameplatjsonData = $request->get('main_game_plat_json')){
                    $mainGameplatArray = json_decode($mainGameplatjsonData,true);
                    $mainGameplatArray = array_filter($mainGameplatArray,function ($element){
                        return $element['selectedGamePages'] && is_array($element['selectedGamePages']);
                    });
                    foreach ($mainGameplatArray as $mainGameplat){
                        foreach ($mainGameplat['selectedGamePages'] as $key => $value) {
                            $carrierPlat = new CarrierActivityFlowLimitedPlatform();
                            $carrierPlat->act_id = $act_id['id'];
                            $carrierPlat->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                            $carrierPlat->carrier_game_plat_id = $value;
                            $carrierPlat->save();
                        }
                    }
                }

                if($request->get('bonuses_type') == CarrierActivity::BONUSER_TYPE_POSITVE )
                {
                    //正负盈利产生的平台
                    CarrierActivityAmphotericGamePlat::where('act_id',$id)->delete();
                    if($amphotericGamePlatJsonData = $request->get('amphoteric_game_plat_json')){
                        $amphotericGamePlatArray = json_decode($amphotericGamePlatJsonData,true);
                        $amphotericGamePlatArray = array_filter($amphotericGamePlatArray,function ($element){
                            return $element['selectedGames'] && is_array($element['selectedGames']);
                        });
                        foreach ($amphotericGamePlatArray as $amphotericGamePlat){
                            foreach ($amphotericGamePlat['selectedGames'] as $key => $value) {
                                $carrierAmphotericGamePlat = new CarrierActivityAmphotericGamePlat();
                                $carrierAmphotericGamePlat->act_id = $act_id['id'];
                                $carrierAmphotericGamePlat->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                                $carrierAmphotericGamePlat->carrier_game_plat_id = $value;
                                $carrierAmphotericGamePlat->save();
                            }
                        }
                    }
                }

                //活动代理用户
                CarrierActivityAgentUser::where('act_id',$id)->delete();
                if($agentUserIdJsonData = $request->get('agent_user_id_json')){
                    $agentUserArray = json_decode($agentUserIdJsonData,true);
                    $agentUserArray = array_filter($agentUserArray,function ($element){
                        return $element['selectedAgentPages'] && is_array($element['selectedAgentPages']);
                    });
                    foreach ($agentUserArray as $agentUser){
                        foreach ($agentUser['selectedAgentPages'] as $key => $value) {
                            $carrierAgentUser = new CarrierActivityAgentUser();
                            $carrierAgentUser->act_id = $act_id['id'];
                            $carrierAgentUser->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                            $carrierAgentUser->agent_user_id = $value;
                            $carrierAgentUser->save();
                        }
                    }
                }
                //活动会员等级
                CarrierActivityPlayerLevel::where('act_id',$id)->delete();
                if($playerleveljsonData = $request->get('player_level_json')){
                    $playerlevelArray = explode(",", $playerleveljsonData); 
                    foreach ($playerlevelArray as $v){
                        $carrierPlayerlevel = new CarrierActivityPlayerLevel();
                        $carrierPlayerlevel->act_id = $act_id['id'];
                        $carrierPlayerlevel->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $carrierPlayerlevel->player_level_id = $v;
                        $carrierPlayerlevel->save();
                    }
                }
                
            });
            return $this->sendSuccessResponse(route('carrierActivities.index'));
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified CarrierActivity from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $activityUser = CarrierActivityAudit::where('act_id', $id)->first();

        if (!empty($activityUser)) {

            return $this->sendErrorResponse('该活动下有用户参与，不能直接删除，可以关闭', 403);
        }
        $carrierActivity = $this->carrierActivityRepository->findWithoutFail($id);
        if (empty($carrierActivity)) {
            return $this->sendNotFoundResponse();
        }
        try{
            $this->carrierActivityRepository->delete($id);
            CarrierActivityPlayerLevel::where('act_id',$id)->delete();
            CarrierActivityAgentUser::where('act_id',$id)->delete();
            CarrierActivityAmphotericGamePlat::where('act_id',$id)->delete();
            CarrierActivityFlowLimitedPlatform::where('act_id',$id)->delete();
        }catch (\Exception $e){
            \Log::error('删除优惠活动失败',[$e->getMessage()]);
            return $this->sendErrorResponse('操作失败，请重试', 403);
        }
        return $this->sendSuccessResponse(route('carrierActivities.index'));
    }
    
    /**
     * 上架下架
     * @param type $id
     * @param Request $request
     * @return type
     */
    public function saveStatus($id,Request $request)
    {
        $data['status'] = $request->get('status');
        $carrierActivity = $this->carrierActivityRepository->update($data, $id);
        if (empty($carrierActivity)) {
            return $this->sendNotFoundResponse();
        }
        return $this->sendSuccessResponse( route('carrierActivities.index'));
    }
}
